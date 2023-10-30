<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;

class ClientOperator extends BaseModel
{
    use CRUDUseUuidTrait;

    static $packageConfigPrefix = 'operators';
    static $modelConfigPrefix = 'clientOperator';

    use PackagedModelsTrait;

    public function client()
    {
        return $this->hasOne(
            config('clients.models.client.class')
        );
    }

    public function operator()
    {
        return $this->hasOne(
            config('clients.models.operator.class')
        );
    }

}