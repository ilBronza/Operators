<?php

use IlBronza\Operators\Models\ClientOperator;
use IlBronza\Operators\Models\Contracttype;
use IlBronza\Operators\Models\Employment;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\OperatorContracttype;


return [
    'routePrefix' => 'operators',

    'models' => [
        'skill' => [
            'table' => 'operators__skills'
        ],
        'operatorContracttype' => [
            'table' => 'operators__operator_contracttypes',
            'class' => OperatorContracttype::class,
        ],
        'operatorSkill' => [
            'table' => 'operators__operator_skills'
        ],
        'clientOperator' => [
            'class' => ClientOperator::class,
            'table' => 'operators__client_operators'
        ],
        'contracttype' => [
            'class' => Contracttype::class,
            'table' => 'operators__contracttypes',
            'fieldsGroupsFiles' => [
                'index' => ContracttypeFieldsGroupParametersFile::class
            ],
            'relationshipsManagerClasses' => [
                'show' => ContracttypeRelationManager::class
            ],
            'parametersFiles' => [
                'create' => ContracttypeCreateStoreFieldsetsParameters::class
            ],
            'controllers' => [
                'index' => ContracttypeIndexController::class,
                'create' => ContracttypeCreateStoreController::class,
                'store' => ContracttypeCreateStoreController::class,
                'show' => ContracttypeShowController::class,
                'edit' => ContracttypeEditUpdateController::class,
                'update' => ContracttypeEditUpdateController::class,
                'destroy' => ContracttypeDestroyController::class,
            ]
        ],
        'employment' => [
            'class' => Employment::class,
            'table' => 'operators__employments'
        ],
        'operator' => [
            'class' => Operator::class,
            'table' => 'operators__operators',
            'parametersFiles' => [
                'generic' => CrudOperatorsParametersFile::class
            ],
            'controllers' => [
                'index' => CrudOperatorsController::class,
                'create' => CrudOperatorsController::class,
                'store' => CrudOperatorsController::class,
                'show' => CrudOperatorsController::class,
                'edit' => CrudOperatorsController::class,
                'update' => CrudOperatorsController::class,
                'destroy' => CrudOperatorsController::class,
            ]
        ]
    ]
];