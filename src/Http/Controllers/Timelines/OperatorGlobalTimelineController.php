<?php

namespace IlBronza\Operators\Http\Controllers\Timelines;

use IlBronza\Buttons\Button;
use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
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

	public function getButtons() : Collection
	{
		return collect([
			Button::create([
				'href' => app('operators')->route('operators.timelineContainer'),
				'text' => 'operators::timeline.operators',
			]),
			Button::create([
				'href' => app('operators')->route('operators.byContracttypesTimelineContainer', [
					'option' => 'subgroups',
				]),
				'text' => 'operators::timeline.operatorsByContracttypes',
			]),
			Button::create([
				'href' => app('operators')->route('operators.byOrdersTimelineContainer', [
					'option' => 'subgroups',
				]),
				'text' => 'operators::timeline.operatorsByOrders',
			]),
		]);
	}

	public function getRows() : Collection
	{
		$ids = Orderrow::gpc()::select('id')->pluck('id');

		return RowsFinderHelper::getCompositeRowCollectionByIds($ids);

		// return Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();
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
}
