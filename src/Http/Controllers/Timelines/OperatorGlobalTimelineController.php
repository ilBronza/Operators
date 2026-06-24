<?php

namespace IlBronza\Operators\Http\Controllers\Timelines;

use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use IlBronza\Timeline\Traits\GlobalTimelineTrait;
use Illuminate\Support\Collection;

class OperatorGlobalTimelineController extends BaseTimelineController
{
	use GlobalTimelineTrait;

	public $allowedMethods = [
		'timeline',
		'container',
	];

	public function getEndpoint() : string
	{
		return app('operators')->route('operators.timeline');
	}

	public function getTimelineCreateRowFormEndpoint() : ?string
	{
		return app('operators')->route('operators.timeline.createRowForm', [
			'iframed' => true,
		]);
	}

	public function returnGanttContainer()
	{
		$apiEndpoint = $this->getEndpoint();
		$timelineUpdateRoute = $this->getTimelineUpdateRoute();
		$timelineCreateRowFormEndpoint = $this->getTimelineCreateRowFormEndpoint();
		$modelInstance = $this->getModel();
		$buttons = $this->getButtons();
		$zoom = $this->zoom ?? config('timeline.zoom', 14);

		return view('operators::timelines.operators.timeline', compact(
			'apiEndpoint',
			'timelineUpdateRoute',
			'timelineCreateRowFormEndpoint',
			'modelInstance',
			'buttons',
			'zoom'
		));
	}

	public function getRows() : Collection
	{
		return Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();
	}

	public function getGroupModel($row) : ?TimelineGroupInterface
	{
		return $row->getSupplierTimelineGroup();
	}

	public function getGroupItems() : Collection
	{
		return Operator::gpc()::with('user')
			->get()
			->filter()
			->unique(fn(TimelineGroupInterface $group) => get_class($group) . ':' . $group->getTimelineGroupId())
			->values();
	}

	public function getMainTimelineData()
	{
		$orderrows = $this->getRows();
		$groupItems = $this->getGroupItems();

		$this->createGroupsByCollection($groupItems);
		$this->createItemsByCollectionAndGetter($orderrows, 'getSupplierTimelineGroup');

		return $this->sendResponse();
	}
}
