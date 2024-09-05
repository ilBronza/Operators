<?php

namespace IlBronza\Operators\Http\Controllers\Employments;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class EmploymentEditUpdateController extends EmploymentCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.employment.parametersFiles.create');
    }

    public function edit(string $employment)
    {
        $employment = $this->findModel($employment);

        return $this->_edit($employment);
    }

    public function update(Request $request, $employment)
    {
        $employment = $this->findModel($employment);

        return $this->_update($request, $employment);
    }
}
