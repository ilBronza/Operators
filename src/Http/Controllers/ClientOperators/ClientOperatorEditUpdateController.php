<?php

namespace IlBronza\Operators\Http\Controllers\ClientOperators;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class ClientOperatorEditUpdateController extends ClientOperatorCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.clientOperator.parametersFiles.create');
    }

    public function edit(string $clientOperator)
    {
        $clientOperator = $this->findModel($clientOperator);

        return $this->_edit($clientOperator);
    }

    public function update(Request $request, $clientOperator)
    {
        $clientOperator = $this->findModel($clientOperator);

        return $this->_update($request, $clientOperator);
    }
}
