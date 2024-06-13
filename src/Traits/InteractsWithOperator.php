<?php

namespace IlBronza\Operators\Traits;

use IlBronza\Operators\Models\Operator;

trait InteractsWithOperator
{
    public function operator()
    {
        return $this->hasOne(Operator::getProjectClassname());
    }

    public function getOperator() : ? Operator
    {
        return $this->operator;
    }

    public function getOrCreateOperator() : Operator
    {
        if($operator = $this->getOperator())
            return $operator;

        $operator = Operator::getProjectClassname()::make();

        $this->operator()->save($operator);

        return $operator;
    }
}