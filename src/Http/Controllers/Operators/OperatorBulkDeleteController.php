<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Traits\CRUDBulkDeleteTrait;
use IlBronza\Operators\Http\Controllers\Operators\OperatorCRUD;

class OperatorBulkDeleteController extends OperatorCRUD
{
    use CRUDBulkDeleteTrait;

    public $allowedMethods = ['bulkDelete'];
}
