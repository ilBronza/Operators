<?php

namespace IlBronza\Operators\Http\Controllers\Contracttypes;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class ContracttypeDestroyController extends ContracttypeCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($contracttype)
    {
        $contracttype = $this->findModel($contracttype);

        return $this->_destroy($contracttype);
    }
}