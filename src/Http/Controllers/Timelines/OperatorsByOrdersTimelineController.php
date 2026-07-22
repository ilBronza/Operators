<?php

namespace IlBronza\Operators\Http\Controllers\Timelines;

use IlBronza\Operators\Helpers\Timelines\OperatorOrder;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\Sellables\OperatorOrderrow;
use IlBronza\Timeline\Helpers\TimelineGroupCreatorHelper;
use Illuminate\Support\Collection;

class OperatorsByOrdersTimelineController extends OperatorGlobalTimelineController
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
		return app('operators')->route('operators.byOrdersTimeline', [
			'option' => $this->option ?? 'subgroups',
		]);
	}

	public function getContainerRouteName() : string
	{
		return 'operators.byOrdersTimelineContainer';
	}

	public function getSubgroupsTimelineData()
	{
		$rows = $this->getSubgroupsTimelineItems();

		//i subgroup nascono dalle righe, non da una query sugli ordini:
		//cosi' gli id usati per il nesting sono gli stessi che poi
		//gli item chiedono al loro gruppo
		$subgroups = $rows
			->map(fn (OperatorOrderrow $row) => $row->getSubgroupByOrderTimelineGroup())
			->unique(fn (OperatorOrder $subgroup) => $subgroup->getTimelineGroupId())
			->values();

		$subgroupIdsByOperator = $subgroups
			->groupBy(fn (OperatorOrder $subgroup) => $subgroup->getOperator()->getKey())
			->map(fn (Collection $operatorSubgroups) => $operatorSubgroups
				->map(fn (OperatorOrder $subgroup) => $subgroup->getTimelineGroupId())
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
		$this->createItemsByCollectionAndGetter($rows, 'getSubgroupByOrderTimelineGroup');

		return $this->sendResponse();
	}

	protected function getOperatorTimelineGroups() : Collection
	{
		return Operator::gpc()::with('user')->get();
	}

	//una riga senza operatore non ha posto in una timeline per operatore
	protected function getSubgroupsTimelineItems() : Collection
	{
		return OperatorOrderrow::query()
			->with([
				'order',
				'sellable.target',
				'sellableSupplier.supplier.target',
			])
			->get()
			->filter(fn (OperatorOrderrow $row) => $row->getOperator())
			->values();
	}
}
