<?php

use IlBronza\Operators\Models\ClientOperator;
use IlBronza\Operators\Models\Contracttype;
use IlBronza\Operators\Models\Operator;


return [
    'routePrefix' => 'operators',

    'models' => [
        'skill' => [
            'table' => 'operators__skills'
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
            'table' => 'operators__contracttypes'
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