<?php

namespace IlBronza\Operators\Http\Controllers\Employments;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

class EmploymentShowController extends EmploymentCRUD
{
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['show'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.employment.parametersFiles.create');
    }

    public function getRelationshipsManagerClass()
    {
        return config("operators.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $employment)
    {
        $employment = $this->findModel($employment);

        return $this->_show($employment);
    }
}
