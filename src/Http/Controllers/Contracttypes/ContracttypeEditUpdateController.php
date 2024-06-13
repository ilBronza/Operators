<?php

namespace IlBronza\Operators\Http\Controllers\Contracttypes;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class ContracttypeEditUpdateController extends ContracttypeCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.contracttype.parametersFiles.create');
    }

    public function edit(string $contracttype)
    {
        $contracttype = $this->findModel($contracttype);

        return $this->_edit($contracttype);
    }

    public function update(Request $request, $contracttype)
    {
        $contracttype = $this->findModel($contracttype);

        return $this->_update($request, $contracttype);
    }
}
