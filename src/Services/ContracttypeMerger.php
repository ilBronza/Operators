<?php

namespace IlBronza\Operators\Services;

use IlBronza\Operators\Models\Contracttype;
use IlBronza\Operators\Models\OperatorContracttype;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ContracttypeMerger
{
	public function selection(array $ids, bool $lock = false) : Collection
	{
		$query = Contracttype::gpc()::query()->whereKey($ids)->orderBy('id');

		if ($lock)
			$query->lockForUpdate();

		$contracttypes = $query->get();

		if ($contracttypes->isEmpty() || $contracttypes->count() !== count(array_unique($ids)))
			throw ValidationException::withMessages([
				'ids' => 'Seleziona mansioni esistenti e non archiviate. La selezione potrebbe essere cambiata: riparti dalla lista mansioni.',
			]);

		foreach ($contracttypes as $contracttype)
			abort_unless($contracttype->userCanArchive(), 403);

		return $contracttypes;
	}

	public function preview(array $ids) : array
	{
		$selected = $this->selection($ids)->sortBy('name')->values();
		$candidates = Contracttype::gpc()::query()->orderBy('name')->get()
			->filter(fn ($contracttype) => $contracttype->userCanUpdate())->values();
		$operatorIds = $this->operatorIdsByContracttype($selected->pluck('id')->merge($candidates->pluck('id'))->unique()->all());
		$selectedOperatorIds = $this->uniqueOperatorIds($operatorIds, $selected->pluck('id')->all());
		$summaries = [];

		foreach ($candidates as $master)
		{
			$slaveIds = $selected->pluck('id')->reject(fn ($id) => $id === $master->getKey())->values();
			$slaveOperators = $this->uniqueOperatorIds($operatorIds, $slaveIds->all());
			$masterOperators = $operatorIds->get($master->getKey(), collect());
			$new = $slaveOperators->diff($masterOperators)->count();
			$summaries[$master->getKey()] = [
				'slave_count' => $slaveIds->count(),
				'slave_operators' => $slaveOperators->count(),
				'master_operators' => $masterOperators->count(),
				'already_associated' => $slaveOperators->intersect($masterOperators)->count(),
				'new_associations' => $new,
				'final_operators' => $masterOperators->count() + $new,
			];
		}

		return [
			'selected' => $selected,
			'candidates' => $candidates,
			'duplicateNames' => $candidates->countBy('name')->filter(fn ($count) => $count > 1)->keys()->all(),
			'operatorCounts' => $operatorIds->map->count(),
			'selectedOperatorCount' => $selectedOperatorIds->count(),
			'summaries' => $summaries,
		];
	}

	public function merge(array $ids, string $masterId) : array
	{
		return Contracttype::gpc()::make()->getConnection()->transaction(function () use ($ids, $masterId) : array
		{
			// Lock in a stable order, including an eventual master outside the selection.
			$contracttypes = $this->selection(array_values(array_unique([...$ids, $masterId])), true);
			$master = $contracttypes->firstWhere('id', $masterId);
			abort_unless($master->userCanUpdate(), 403);
			$slaves = $contracttypes->reject(fn ($contracttype) => $contracttype->is($master));

			if ($slaves->isEmpty())
				throw ValidationException::withMessages(['master_id' => 'Scegli un master diverso oppure seleziona almeno una mansione da archiviare.']);

			$operatorIds = $this->operatorIdsByContracttype($contracttypes->pluck('id')->all());
			$slaveOperators = $this->uniqueOperatorIds($operatorIds, $slaves->pluck('id')->all());
			$masterOperators = $operatorIds->get($masterId, collect());
			$created = 0;

			foreach ($slaveOperators->diff($masterOperators) as $operatorId)
			{
				// Use the project model so its supplier/sellable and audit events run normally.
				$association = OperatorContracttype::gpc()::firstOrCreate([
					'contracttype_id' => $masterId,
					'operator_id' => $operatorId,
				]);
				$created += (int) $association->wasRecentlyCreated;
			}

			foreach ($slaves as $slave)
				$slave->archive();

			return [
				'master_name' => $master->getName(),
				'archived' => $slaves->count(),
				'created' => $created,
				'already_associated' => $slaveOperators->count() - $created,
			];
		});
	}

	private function operatorIdsByContracttype(array $ids) : Collection
	{
		// Query the pivot model directly: belongsToMany does not filter soft-deleted pivots.
		return OperatorContracttype::gpc()::query()->whereIn('contracttype_id', $ids)
			->whereHas('operator')->get(['contracttype_id', 'operator_id'])
			->groupBy('contracttype_id')
			->map(fn ($associations) => $associations->pluck('operator_id')->unique()->values());
	}

	private function uniqueOperatorIds(Collection $operatorIds, array $contracttypeIds) : Collection
	{
		return collect($contracttypeIds)->flatMap(fn ($id) => $operatorIds->get($id, collect()))->unique()->values();
	}
}
