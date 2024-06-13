<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class OperatorDestroyController extends OperatorCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($operator)
    {
        $operator = $this->findModel($operator);

        return $this->_destroy($operator);
    }
}