<?php

namespace IlBronza\Operator\Http\Controllers;

use IlBronza\CRUD\BelongsToCRUDController;
use IlBronza\CRUD\Traits\CRUDBelongsToManyTrait;
use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDDeleteTrait;
use IlBronza\CRUD\Traits\CRUDDestroyTrait;
use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;
use IlBronza\Operator\Http\Controllers\CRUDTraits\CRUDOperatorChildrenParametersTrait;
use IlBronza\Operator\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CrudOperatorChildrenController extends BelongsToCRUDController
{
    use CRUDOperatorChildrenParametersTrait;

    use CRUDShowTrait;
    use CRUDIndexTrait;
    use CRUDEditUpdateTrait;
    use CRUDCreateStoreTrait;

    use CRUDDeleteTrait;
    use CRUDDestroyTrait;

    use CRUDRelationshipTrait;
    use CRUDBelongsToManyTrait;

    public $parentModelClass = Operator::class;
    public $modelClass = Operator::class;

    public $allowedMethods = [
        'index',
        'create',
        'store',
        'delete'
    ];

    public $routeBaseNamePieces = ['operators', 'children'];
    public $parentModelKey = 'parent_id';


    //this is needed to override routename ex contacts.buildings.store => buildings.store
    // public $routeBaseNamePieces = ['buildings'];


    // public $guardedEditDBFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    // public $guardedCreateDBFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    public $guardedShowDBFields = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function index(Request $request, Operator $operator)
    {
        return $this->_index($request);
    }

    public function getIndexElements()
    {
        return $this->parentModel->children()->get();
    }

    public function show(Operator $parent, Operator $operator)
    {
        return $this->_show($operator);
    }

    public function store(Request $request, Operator $operator)
    {
        return $this->_store($request);
    }

    public function delete(Operator $parent, Operator $operator)
    {
        return $this->_delete($operator);
    }
}
