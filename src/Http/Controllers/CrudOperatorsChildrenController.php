<?php

namespace IlBronza\Operators\Http\Controllers;

use IlBronza\CRUD\BelongsToCRUDController;
use IlBronza\CRUD\Traits\CRUDBelongsToManyTrait;
use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDDeleteTrait;
use IlBronza\CRUD\Traits\CRUDDestroyTrait;
use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;
use IlBronza\Operators\Http\Controllers\CRUDTraits\CRUDOperatorsChildrenParametersTrait;
use IlBronza\Operators\Models\Operators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CrudOperatorsChildrenController extends BelongsToCRUDController
{
    use CRUDOperatorsChildrenParametersTrait;

    use CRUDShowTrait;
    use CRUDIndexTrait;
    use CRUDEditUpdateTrait;
    use CRUDCreateStoreTrait;

    use CRUDDeleteTrait;
    use CRUDDestroyTrait;

    use CRUDRelationshipTrait;
    use CRUDBelongsToManyTrait;

    public $parentModelClass = Operators::class;
    public $modelClass = Operators::class;

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

    public function index(Request $request, Operators $operators)
    {
        return $this->_index($request);
    }

    public function getIndexElements()
    {
        return $this->parentModel->children()->get();
    }

    public function show(Operators $parent, Operators $operators)
    {
        return $this->_show($operators);
    }

    public function store(Request $request, Operators $operators)
    {
        return $this->_store($request);
    }

    public function delete(Operators $parent, Operators $operators)
    {
        return $this->_delete($operators);
    }
}
