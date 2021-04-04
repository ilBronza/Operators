<?php

namespace ilBronza\Operator\Http\Controllers;

use Illuminate\Http\Request;
use ilBronza\CRUD\CRUD;
use ilBronza\CRUD\Traits\CRUDBelongsToManyTrait;
use ilBronza\CRUD\Traits\CRUDCreateStoreTrait;
use ilBronza\CRUD\Traits\CRUDDeleteTrait;
use ilBronza\CRUD\Traits\CRUDDestroyTrait;
use ilBronza\CRUD\Traits\CRUDEditUpdateTrait;
use ilBronza\CRUD\Traits\CRUDIndexTrait;
use ilBronza\CRUD\Traits\CRUDPlainIndexTrait;
use ilBronza\CRUD\Traits\CRUDRelationshipTrait;
use ilBronza\CRUD\Traits\CRUDShowTrait;
use ilBronza\CRUD\Traits\CRUDUpdateEditorTrait;
use ilBronza\Operator\Http\Controllers\CRUDTraits\CRUDOperatorParametersTrait;
use ilBronza\Operator\Models\Operator;

class CrudOperatorController extends CRUD
{
    use CRUDOperatorParametersTrait;

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
    public $modelClass = 'ilBronza\Operator\Models\Operator';

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
        'destroy'
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
    public $showMethodRelationships = ['children', 'user'];

    protected $relationshipsControllers = [
        'children' => '\IlBronza\Operator\Http\Controllers\CrudOperatorController'
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
        return Operator::with('parent.user')->get();
    }

    /**
     * START base methods declared in extended controller to correctly perform dependency injection
     *
     * these methods are compulsorily needed to execute CRUD base functions
     **/
    public function show(Operator $operator)
    {
        //$this->addExtraView('top', 'folder.subFolder.viewName', ['some' => $thing]);

        return $this->_show($operator);
    }

    public function edit(Operator $operator)
    {
        return $this->_edit($operator);
    }

    public function update(Request $request, Operator $operator)
    {
        return $this->_update($request, $operator);
    }

    public function destroy(Operator $operator)
    {
        return $this->_destroy($operator);
    }

    /**
     * END base methods
     **/




     /**
      * START CREATE PARAMETERS AND METHODS
      **/

    // public function beforeRenderCreate()
    // {
    //     $this->modelInstance->agent_id = session('agent')->getKey();
    // }

     /**
      * STOP CREATE PARAMETERS AND METHODS
      **/

}

