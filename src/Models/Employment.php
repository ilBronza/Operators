<?php

namespace IlBronza\Operators\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDGetOrCreateTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;

class Employment extends BaseModel
{
    use CRUDGetOrCreateTrait;
    use PackagedModelsTrait;

    use CRUDSluggableTrait;

    static $packageConfigPrefix = 'operators';
    static $modelConfigPrefix = 'employment';

    public $deletingRelationships = [];

}