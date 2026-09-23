<?php

namespace IlBronza\Operators\Http\Controllers\Contracttypes;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

class ContracttypeIndexController extends ContracttypeCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];
    public $rowSelectCheckboxes = true;

    public function addPostFieldsToTable()
    {
        $this->addCondenseButton();

        if (! $this->getModelClass()::make()->userCanArchive())
            return;

        $this->getTable()->createPostButtonSamePage([
            'href' => app('operators')->route('contracttypes.merge.preview'),
            'translatedText' => 'Accorpa mansioni selezionate',
            'icon' => 'compress',
        ]);
    }

    public function getIndexFieldsArray()
    {
        return config('operators.models.contracttype.fieldsGroupsFiles.index')::getTracedFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('operators.models.contracttype.fieldsGroupsFiles.related')::getTracedFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->getModelClass()::withCount('operators')
            ->orderBy('sorting_index')
            ->orderBy('name')
            ->get();
    }

    public function beforeRenderIndex() : void
    {
        $this->getTable()->setDragAndDropColumnIntestation('sorting_index');
        $this->getTable()->setDragAndDropStoringReorderUrl(
            $this->getModelClass()::make()->getStoreMassReorderUrl()
        );
    }

}
