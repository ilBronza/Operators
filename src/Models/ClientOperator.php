<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Operators\Models\Employment;

class ClientOperator extends BaseModel
{
    use CRUDUseUuidTrait;

    static $packageConfigPrefix = 'operators';
    static $modelConfigPrefix = 'clientOperator';

    protected $dates = [
        'started_at',
        'ended_at'
    ];

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

    public function employments()
    {
        return $this->belongsTo(Employment::getProjectClassName());
    }
}