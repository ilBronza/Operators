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

	//senza questo il titolo pagina cerca routes.xxx invece di operators::routes.xxx
	public function getPackageConfigName()
	{
		return 'operators';
	}

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

	public function getContainerRouteName() : string
	{
		return 'operators.timelineContainer';
	}

	public function getTimelineButtonsParameters() : array
	{
		return [
			'operators.timelineContainer' => [
				'text' => 'operators::timeline.operators',
				'parameters' => [],
			],
			'operators.byContracttypesTimelineContainer' => [
				'text' => 'operators::timeline.operatorsByContracttypes',
				'parameters' => ['option' => 'subgroups'],
			],
			'operators.byOrdersTimelineContainer' => [
				'text' => 'operators::timeline.operatorsByOrders',
				'parameters' => ['option' => 'subgroups'],
			],
		];
	}

	//il bottone della timeline che si sta guardando resta visibile ma disabilitato
	public function getButtons() : Collection
	{
		$activeRouteName = $this->getContainerRouteName();

		return collect($this->getTimelineButtonsParameters())
			->map(fn(array $button, string $routeName) => $this->getTimelineButton(
				$routeName, $button, $routeName == $activeRouteName
			))
			->values();
	}

	public function getTimelineButton(string $routeName, array $parameters, bool $active) : Button
	{
		$button = Button::create([
			'href' => app('operators')->route($routeName, $parameters['parameters']),
			'text' => $parameters['text'],
		]);

		$button->setSecondary();
		$button->setSmall();

		if(! $active)
			return $button;

		//disabled da solo non blocca un <a>, serve la classe uikit
		$button->setDisabled();
		$button->setHtmlClass('uk-disabled');

		return $button;
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
