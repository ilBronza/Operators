<?php

use IlBronza\Clients\Http\Controllers\Operators\CrudOperatorsController;
use IlBronza\Clients\Http\ParametersFile\Operators\CrudOperatorsParametersFile;
use IlBronza\Clients\Models\Operators;

return [
    'routePrefix' => 'operators',

    'models' => [
        'operators' => [
            'class' => Operators::class,
            'table' => 'clients__operators',
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