<?php

namespace IlBronza\Operators\Http\Controllers;

use IlBronza\CRUD\CRUD;

class OperatorsPackageController extends CRUD
{
    public function getRouteBaseNamePrefix() : ? string
    {
        return config('operators.routePrefix');
    }

    public function setModelClass()
    {
        $this->modelClass = config("operators.models.{$this->configModelClassName}.class");
    }
}
