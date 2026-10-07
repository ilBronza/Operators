<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorArchiveFieldsGroupParametersFile;
use function config;

class OperatorArchiveController extends OperatorIndexController
{
    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
        $fieldsGroupClass = config('operators.models.operator.fieldsGroupsFiles.archive');

        // Published configurations may still contain the unqualified class name.
        if ($fieldsGroupClass === 'OperatorArchiveFieldsGroupParametersFile')
            $fieldsGroupClass = OperatorArchiveFieldsGroupParametersFile::class;

        return $fieldsGroupClass::getTracedFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->getModelClass()::with(
			'user.extraFields',
			'user.address',
			'address',
            'contracttypes',
            'employments'
        )
        ->get();
    }

}
