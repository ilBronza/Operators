<?php

namespace IlBronza\Operators\Http\Controllers\Timelines;

use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\OperatorContracttype;
use IlBronza\Operators\Models\Sellables\OperatorOrderrow;
use IlBronza\Timeline\Helpers\TimelineGroupCreatorHelper;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use Illuminate\Support\Collection;

class OperatorsByContracttypesTimelineController extends OperatorGlobalTimelineController
{
	public string $option = 'subgroups';

	public function container(string $option = 'subgroups')
	{
		return parent::container($option);
	}

	public function timeline(string $option = 'subgroups')
	{
		return parent::timeline($option);
	}

	public function getEndpoint() : string
	{
		return app('operators')->route('operators.byContracttypesTimeline', [
			'option' => $this->option ?? 'subgroups',
		]);
	}

	public function getSubgroupsTimelineData()
	{
		$subgroups = $this->getOperatorContracttypeTimelineGroups();
		$subgroupIdsByOperator = $subgroups
			->groupBy(fn (OperatorContracttype $subgroup) => $subgroup->getOperator()->getKey())
			->map(fn (Collection $operatorSubgroups) => $operatorSubgroups
				->map(fn (TimelineGroupInterface $subgroup) => $subgroup->getTimelineGroupId())
				->values()
				->all()
			);

		foreach($this->getOperatorTimelineGroups() as $operator)
		{
			$group = TimelineGroupCreatorHelper::createGroupByModel($operator);
			$nestedGroups = $subgroupIdsByOperator->get($operator->getKey(), []);

			if($nestedGroups)
			{
				$group = get_object_vars($group);
				$group['nestedGroups'] = $nestedGroups;
				$group['showNested'] = true;
			}

			$this->groups[] = $group;
		}

		$this->createGroupsByCollection($subgroups);
		$this->createItemsByCollectionAndGetter(
			$this->getSubgroupsTimelineItems(),
			'getSubgroupTimelineGroup'
		);

		return $this->sendResponse();
	}

	protected function getOperatorTimelineGroups() : Collection
	{
		return Operator::gpc()::with('user')->get();
	}

	protected function getOperatorContracttypeTimelineGroups() : Collection
	{
		$query = OperatorContracttype::gpc()::query()->with([
			'operator.user',
			'contracttype',
		]);

		if(method_exists($query->getModel(), 'supplier'))
			$query->with('supplier');

		return $query->get()
			->filter(fn (OperatorContracttype $subgroup) => $subgroup->getOperator() && $subgroup->getContracttype())
			->values();
	}

	protected function getSubgroupsTimelineItems() : Collection
	{
		return OperatorOrderrow::query()
			->with([
				'order',
				'sellable.target',
				'sellableSupplier.supplier.target',
			])
			->get()
			->filter(fn (OperatorOrderrow $row) => $row->getSubgroupTimelineGroup() instanceof TimelineGroupInterface)
			->values();
	}
}
