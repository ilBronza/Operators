<?php

namespace IlBronza\Operators\Http\Controllers\Employments;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class EmploymentDestroyController extends EmploymentCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($employment)
    {
        $employment = $this->findModel($employment);

        return $this->_destroy($employment);
    }
}