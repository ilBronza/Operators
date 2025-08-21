<?php

namespace IlBronza\Operators\Helpers\ClientOperators;

use Carbon\Carbon;
use IlBronza\Operators\Models\ClientOperator;
use IlBronza\Operators\Models\Operator;

use function count;

class ClientOperatorProviderHelper
{
	static function getByOperatorDates(Operator $operator, Carbon $dateStart, Carbon $dateEnd)
	{
		$clientOperators = ClientOperator::gpc()::where('operator_id', $operator->getKey())
			->where(function ($query) use ($dateStart, $dateEnd) {
				$query->where('started_at', '<=', $dateEnd);
				$query->where(function($_query) use($dateEnd)
					{
						$_query->where('ended_at', '>=', $dateEnd);
						$_query->orWhereNull('ended_at');
					});
			})
			->orderBy('started_at', 'DESC')
			->get();

		if(count($clientOperators) == 1)
			return $clientOperators->first();

		if(count($clientOperators) > 1)
		{
			if($clientOperators->where('valid')->count() == 1)
				return $clientOperators->where('valid')->first();

			return null;
		}

		if(count($clientOperators) == 0)
		{
			$clientOperators = ClientOperator::gpc()::where('operator_id', $operator->getKey())->get();

			if($clientOperators->count() == 1)
				return $clientOperators->first();
		}

		return null;

		dd("conto " . count($clientOperators));

		dd($operator->getKey());
	}
}