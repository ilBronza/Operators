<?php

namespace IlBronza\Operators\Http\Controllers\OperatorContracttypes;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class OperatorContracttypeDestroyController extends OperatorContracttypeCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($operatorContracttype)
    {
        $operatorContracttype = $this->findModel($operatorContracttype);

        return $this->_destroy($operatorContracttype);
    }
}