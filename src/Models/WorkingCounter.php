<?php

namespace IlBronza\Operators\Models;

use Carbon\Carbon;
use IlBronza\CRUD\Models\PackagedBaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;

use function config;

class WorkingCounter extends PackagedBaseModel
{
	use CRUDUseUuidTrait;

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'workingCounter';
	static $deletingRelationships = [];

	protected $keyType = 'string';

	protected $casts = [
		'amount' => 'decimal:2',
		'reset_date' => 'date',
		'expired_at' => 'datetime',
	];

	public static function getPossibleTypesArray() : array
	{
		$result = [];

		foreach (config('operators.models.workingCounter.types', []) as $key => $value)
			$result[is_int($key) ? $value : $key] = $value;

		return $result;
	}

	public function scopeByType($query, string $type)
	{
		return $query->where('type', $type);
	}

	public function clientOperator()
	{
		return $this->belongsTo(ClientOperator::getProjectClassName());
	}

	public function operator()
	{
		return $this->belongsTo(Operator::getProjectClassName());
	}

	public function getClientOperator() : ?ClientOperator
	{
		return $this->clientOperator;
	}

	public function getOperator() : ?Operator
	{
		return $this->operator;
	}

	public function getClientOperatorLabelAttribute() : string
	{
		$clientOperator = $this->getClientOperator();

		if (! $clientOperator)
			return '';

		$period = implode(' - ', array_filter([
			$clientOperator->getStartedAt()?->format('d/m/Y'),
			$clientOperator->getEndedAt()?->format('d/m/Y'),
		]));

		return implode(' — ', array_filter([
			$clientOperator->getOperator()?->getName(),
			$period,
		])) ?: $clientOperator->getKey();
	}

	public function getPossibleClientOperatorsValuesArray() : array
	{
		return ClientOperator::with('operator.user.userdata')->get()
			->mapWithKeys(function (ClientOperator $clientOperator)
			{
				$period = implode(' - ', array_filter([
					$clientOperator->getStartedAt()?->format('d/m/Y'),
					$clientOperator->getEndedAt()?->format('d/m/Y'),
				]));

				return [$clientOperator->getKey() => implode(' — ', array_filter([
					$clientOperator->getOperator()?->getName(),
					$period,
				])) ?: $clientOperator->getKey()];
			})
			->toArray();
	}

	public function getResetDate() : Carbon
	{
		return $this->reset_date;
	}

	public function getTypeLabel() : string
	{
		return static::getPossibleTypesArray()[$this->type] ?? (string) $this->type;
	}

	public function getName() : ?string
	{
		return implode(' — ', array_filter([
			$this->getTypeLabel(),
			$this->reset_date?->format('d/m/Y'),
		]));
	}
}
