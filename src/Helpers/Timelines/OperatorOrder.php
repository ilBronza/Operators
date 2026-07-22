<?php

namespace IlBronza\Operators\Helpers\Timelines;

use IlBronza\Operators\Models\Operator;
use IlBronza\Products\Models\Order;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;

/**
 * Gruppo timeline composito operatore + ordine.
 * Lo stesso ordine puo' comparire sotto piu' operatori, quindi
 * l'id del gruppo unisce le due chiavi: un Order da solo non basta.
 * Tutto il resto e' delegato all'ordine, dato che l'operatore
 * e' gia' espresso dal gruppo padre.
 */
class OperatorOrder implements TimelineGroupInterface
{
	public function __construct(public Operator $operator, public Order $order)
	{
	}

	static function create(Operator $operator, Order $order) : static
	{
		return new static($operator, $order);
	}

	public function getOperator() : Operator
	{
		return $this->operator;
	}

	public function getOrder() : Order
	{
		return $this->order;
	}

	public function getTimelineGroupId() : string
	{
		return $this->operator->getKey() . ':' . $this->order->getKey();
	}

	//getKey e getMorphClass servono a TimelineGroupCreatorHelper,
	//che oggi assume un model eloquent
	public function getKey() : string
	{
		return $this->getTimelineGroupId();
	}

	public function getMorphClass() : string
	{
		return $this->order->getMorphClass();
	}

	public function getTimelineGroupName() : string
	{
		return $this->order->getTimelineGroupName();
	}

	public function getTimelineGroupContent() : string
	{
		return $this->order->getTimelineGroupContent();
	}

	public function getTimelineGroupCssStyles() : array
	{
		return $this->order->getTimelineGroupCssStyles();
	}

	public function getTimelineGroupHtmlClasses() : array
	{
		return $this->order->getTimelineGroupHtmlClasses();
	}

	public function getTimelineGroupActions() : array
	{
		return $this->order->getTimelineGroupActions();
	}

	public function getTimelineBindingDataArray() : array
	{
		return $this->order->getTimelineBindingDataArray();
	}

	public function getTimelineGroupGanttUrl() : string
	{
		return $this->order->getTimelineGroupGanttUrl();
	}

	public function getTimelineGroupModalUrl() : ? string
	{
		return $this->order->getTimelineGroupModalUrl();
	}
}
