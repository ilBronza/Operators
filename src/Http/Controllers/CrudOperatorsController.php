<?php

namespace IlBronza\Operators\Http\Controllers;

use IlBronza\CRUD\CRUD;
use IlBronza\CRUD\Traits\CRUDBelongsToManyTrait;
use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDDeleteTrait;
use IlBronza\CRUD\Traits\CRUDDestroyTrait;
use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDNestableTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;
use IlBronza\CRUD\Traits\CRUDUpdateEditorTrait;
use IlBronza\Operators\Http\Controllers\CRUDTraits\CRUDOperatorsParametersTrait;
use IlBronza\Operators\Models\Operators;
use Illuminate\Http\Request;

class CrudOperatorsController extends CRUD
{
    use CRUDOperatorsParametersTrait;

    use CRUDNestableTrait;

    use CRUDShowTrait;
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;
    use CRUDEditUpdateTrait;
    use CRUDUpdateEditorTrait;
    use CRUDCreateStoreTrait;

    use CRUDDeleteTrait;
    use CRUDDestroyTrait;

    use CRUDRelationshipTrait;
    use CRUDBelongsToManyTrait;

    /**
     * subject model class full path
     **/
    public $modelClass = Operators::class;

    /**
     * http methods allowed. remove non existing methods to get a 403 when called by routes
     **/
    public $allowedMethods = [
        'index',
        'show',
        'edit',
        'update',
        'create',
        'store',
        'destroy',
        'reorder',
        'stroreReorder'
    ];

    /**
     * to override show view use full view name
     **/
    //public $showView = 'products.showPartial';

    // public $guardedEditDBFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    // public $guardedCreateDBFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    public $guardedShowDBFields = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * relations called to be automatically shown on 'show' method
     **/
    public $showMethodRelationships = ['children', 'user', 'parent'];

    protected $relationshipsControllers = [
        'children' => '\IlBronza\Operators\Http\Controllers\CrudOperatorsController'
    ];


    /**
     * getter method for 'index' method.
     *
     * is declared here to force the developer to rationally choose which elements to be shown
     *
     * @return Collection
     **/
    public function getIndexElements()
    {
        return Operators::with('parent.user')->get();
    }

    /**
     * START base methods declared in extended controller to correctly perform dependency injection
     *
     * these methods are compulsorily needed to execute CRUD base functions
     **/
    public function show(Operators $operators)
    {
        //$this->addExtraView('top', 'folder.subFolder.viewName', ['some' => $thing]);

        return $this->_show($operators);
    }

    public function edit(Operators $operators)
    {
        return $this->_edit($operators);
    }

    public function update(Request $request, Operators $operators)
    {
        return $this->_update($request, $operators);
    }

    public function destroy(Operators $operators)
    {
        return $this->_destroy($operators);
    }

    public function reorder(Request $request, Operators $operators = null)
    {
        return $this->_reorder($request, $operators);
    }
}

