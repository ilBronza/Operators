<?php

namespace IlBronza\Operators\Traits;

use IlBronza\Operators\Models\Operator;

trait InteractsWithOperator
{
	public function operator()
	{
		return $this->hasOne(Operator::getProjectClassName());
	}

	public function getOperator() : ?Operator
	{
		return $this->operator;
	}

	public function createOperator() : Operator
	{
		$operator = Operator::gpc()::make();

		$this->operator()->save($operator);

		return $operator;
	}

	public function getOrCreateOperator() : Operator
	{
		if ($operator = $this->getOperator())
			return $operator;

		return $this->createOperator();
	}
}