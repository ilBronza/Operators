<?php

namespace IlBronza\Operators\Http\Controllers\OperatorContracttypes;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class OperatorContracttypeEditUpdateController extends OperatorContracttypeCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.operatorContracttype.parametersFiles.create');
    }

    public function edit(string $operatorContracttype)
    {
        $operatorContracttype = $this->findModel($operatorContracttype);

        return $this->_edit($operatorContracttype);
    }

    public function update(Request $request, $operatorContracttype)
    {
        $operatorContracttype = $this->findModel($operatorContracttype);

        return $this->_update($request, $operatorContracttype);
    }
}
