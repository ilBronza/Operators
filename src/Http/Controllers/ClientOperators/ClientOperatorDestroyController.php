<?php

namespace IlBronza\Operators\Http\Controllers\ClientOperators;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class ClientOperatorDestroyController extends ClientOperatorCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($clientOperator)
    {
        $clientOperator = $this->findModel($clientOperator);

        return $this->_destroy($clientOperator);
    }
}