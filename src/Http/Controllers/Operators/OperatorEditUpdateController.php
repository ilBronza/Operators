<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

use function config;

class OperatorEditUpdateController extends OperatorCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.operator.parametersFiles.edit');
    }

	public function getRelationshipsManagerClass()
	{
		return config("operators.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
	}

	public function edit(string $operator)
    {
        $operator = $this->findModel($operator);

        return $this->_edit($operator);
    }

    public function update(Request $request, $operator)
    {
        $operator = $this->findModel($operator);

        return $this->_update($request, $operator);
    }
}
