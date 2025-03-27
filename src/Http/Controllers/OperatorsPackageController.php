<?php

namespace IlBronza\Operators\Http\Controllers;

use IlBronza\CRUD\Http\Controllers\BasePackageController;

use function dd;

class OperatorsPackageController extends BasePackageController
{
	static $packageConfigPrefix = 'operators';

    public function getRouteBaseNamePrefix() : ? string
    {
        return config('operators.routePrefix');
    }

    public function setModelClass()
    {
	    $this->modelClass = config("operators.models.{$this->configModelClassName}.class");
    }
}
