<?php

namespace IlBronza\Operators\Http\Controllers;

use function array_keys;
use function collect;

trait CalendarOperatorsTrait
{

	public function getOperators() : Collection
	{
		$operators = Operator::gpc()::where(function ($query)
		{
			$query->where(function ($_query)
			{
				$employments = Employment::gpc()::getPermanentEmployment();

				$_query->active();
				$_query->byValidEmployments([$employments->getKey()]);
			});
			$query->orWhere(function ($_query)
			{
				$_query->whereHas('user', function ($__query)
				{
					$__query->role(['gestionePresenze']);
				});
			});
		})
			// ->where('id', '6e3d119d-86c8-47c2-a044-1735d5a46e0e')
			                 ->with([
				'supplier.sellableSuppliers',
				'validClientOperator',
				'clientOperators' => function ($query)
				{
					$query->where('client_id', Client::gpc()::getOwnerCompany()->getKey());
				}
			])->with([
				'workingDays' => function ($query)
				{
					$query->whereDate('date', '>=', $this->getDateStart());
					$query->whereDate('date', '<=', $this->getDateEnd());
				}
			])->distinct()->get()->sortBy(function ($item)
			{
				return $item->getName();
			});

		$this->sellableSuppliersIdsDictionary = [];

		foreach ($operators as $operator)
			foreach ($operator->supplier->sellableSuppliers as $sellableSupplier)
				$this->sellableSuppliersIdsDictionary[$sellableSupplier->getKey()] = [
					'operator' => $operator,
					'orderrows' => collect()
				];

		$ordersIds = Order::gpc()::select('id')->whereHas('extraFields', function ($query)
		{
			$query->whereBetween('starts_at', [$this->getDateStart(), $this->getDateEnd()])->orWhereBetween('ends_at', [$this->getDateStart(), $this->getDateEnd()]);
		})->pluck('id');

		$orderrows = Orderrow::gpc()::whereIn('sellable_supplier_id', array_keys($this->sellableSuppliersIdsDictionary))->where(function ($query) use ($ordersIds)
		{
			$query->whereIn('order_id', $ordersIds);
			$query->orWhere(function ($_query)
			{
				$_query->whereBetween('starts_at', [$this->getDateStart(), $this->getDateEnd()]);
				$_query->orWhereBetween('ends_at', [$this->getDateStart(), $this->getDateEnd()]);
			});
		})->get();

		foreach ($orderrows as $orderrow)
			$this->sellableSuppliersIdsDictionary[$orderrow->sellable_supplier_id]['orderrows']->push($orderrow);

		foreach ($operators as $operator)
		{
			$orderrowDays = collect();

			foreach ($operator->supplier->sellableSuppliers as $sellableSupplier)
				$orderrowDays = $orderrowDays->merge($this->sellableSuppliersIdsDictionary[$sellableSupplier->getKey()]['orderrows']);

			$operator->setRelation('orderrows', $orderrowDays);
		}

		return $operators->filter(function ($item)
		{
			return $item->getName() != 'Gianolli Andrea';
		});
	}
}