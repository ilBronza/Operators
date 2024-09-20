<?php

use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorCreateStoreController;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorDestroyController;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorEditUpdateController;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorIndexController;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorShowController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeCreateStoreController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeDestroyController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeEditUpdateController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeIndexController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeShowController;
use IlBronza\Operators\Http\Controllers\Employments\EmploymentCreateStoreController;
use IlBronza\Operators\Http\Controllers\Employments\EmploymentDestroyController;
use IlBronza\Operators\Http\Controllers\Employments\EmploymentEditUpdateController;
use IlBronza\Operators\Http\Controllers\Employments\EmploymentIndexController;
use IlBronza\Operators\Http\Controllers\Employments\EmploymentShowController;
use IlBronza\Operators\Http\Controllers\OperatorContracttypes\OperatorContracttypeCreateStoreController;
use IlBronza\Operators\Http\Controllers\OperatorContracttypes\OperatorContracttypeDestroyController;
use IlBronza\Operators\Http\Controllers\OperatorContracttypes\OperatorContracttypeEditUpdateController;
use IlBronza\Operators\Http\Controllers\OperatorContracttypes\OperatorContracttypeIndexController;
use IlBronza\Operators\Http\Controllers\OperatorContracttypes\OperatorContracttypeShowController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorAvatarController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorCreateStoreController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorDestroyController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorEditUpdateController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorIndexController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorShowController;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ClientOperatorByOperatorFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ClientOperatorFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ClientOperatorRelatedFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ContracttypeFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ContracttypeRelatedFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\EmploymentFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\EmploymentRelatedFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorByEmploymentFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorContracttypeByContracttypeFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorContracttypeFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorContracttypeRelatedFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\WorkingDayFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\ClientOperatorCreateStoreFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\ClientOperatorEditUpdateFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\ContracttypeCreateStoreFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\EmploymentCreateStoreFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\EmploymentEditUpdateFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorContracttypeCreateStoreFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorContracttypeEditUpdateFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorCreateStoreFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorEditUpdateFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers\ContracttypeRelationManager;
use IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers\EmploymentRelationManager;
use IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers\OperatorContracttypeRelationManager;
use IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers\OperatorRelationManager;
use IlBronza\Operators\Http\Controllers\WorkingDays\WorkingDayCalendarController;
use IlBronza\Operators\Http\Controllers\WorkingDays\WorkingDayUpdateEditController;
use IlBronza\Operators\Models\ClientOperator;
use IlBronza\Operators\Models\Contracttype;
use IlBronza\Operators\Models\Employment;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\OperatorContracttype;
use IlBronza\Operators\Models\WorkingDay;

return [
    'routePrefix' => 'operators',
	'missingImageUrl' => '/img/no_user.png',

	'enabled' => true,

	'models' => [
        'skill' => [
            'table' => 'operators__skills'
        ],
		'operatorContracttype' => [
			'table' => 'operators__operator_contracttypes',
			'class' => OperatorContracttype::class,
			'fieldsGroupsFiles' => [
				'index' => OperatorContracttypeFieldsGroupParametersFile::class,
				'related' => OperatorContracttypeRelatedFieldsGroupParametersFile::class,
				'byContracttype' => OperatorContracttypeByContracttypeFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'show' => OperatorContracttypeRelationManager::class
			],
			'parametersFiles' => [
				'create' => OperatorContracttypeCreateStoreFieldsetsParameters::class,
				'edit' => OperatorContracttypeEditUpdateFieldsetsParameters::class
			],
			'controllers' => [
				'index' => OperatorContracttypeIndexController::class,
				'create' => OperatorContracttypeCreateStoreController::class,
				'store' => OperatorContracttypeCreateStoreController::class,
				'show' => OperatorContracttypeShowController::class,
				'edit' => OperatorContracttypeEditUpdateController::class,
				'update' => OperatorContracttypeEditUpdateController::class,
				'destroy' => OperatorContracttypeDestroyController::class,
			]
		],
        'operatorSkill' => [
            'table' => 'operators__operator_skills'
        ],
		'clientOperator' => [
			'class' => ClientOperator::class,
			'table' => 'operators__client_operators',
			'fieldsGroupsFiles' => [
				'index' => ClientOperatorFieldsGroupParametersFile::class,
				'related' => ClientOperatorRelatedFieldsGroupParametersFile::class,
				'byOperator' => ClientOperatorByOperatorFieldsGroupParametersFile::class
			],
			'parametersFiles' => [
				'create' => ClientOperatorCreateStoreFieldsetsParameters::class,
				'edit' => ClientOperatorEditUpdateFieldsetsParameters::class
			],
			'controllers' => [
				'index' => ClientOperatorIndexController::class,
				'create' => ClientOperatorCreateStoreController::class,
				'store' => ClientOperatorCreateStoreController::class,
				'show' => ClientOperatorShowController::class,
				'edit' => ClientOperatorEditUpdateController::class,
				'update' => ClientOperatorEditUpdateController::class,
				'destroy' => ClientOperatorDestroyController::class,
			]
		],
		'contracttype' => [
			'class' => Contracttype::class,
			'table' => 'operators__contracttypes',
			'fieldsGroupsFiles' => [
				'index' => ContracttypeFieldsGroupParametersFile::class,
				'related' => ContracttypeRelatedFieldsGroupParametersFile::class
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
			'table' => 'operators__employments',
			'fieldsGroupsFiles' => [
				'index' => EmploymentFieldsGroupParametersFile::class,
				'related' => EmploymentRelatedFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'show' => EmploymentRelationManager::class
			],
			'parametersFiles' => [
				'create' => EmploymentCreateStoreFieldsetsParameters::class,
				'edit' => EmploymentEditUpdateFieldsetsParameters::class
			],
			'controllers' => [
				'index' => EmploymentIndexController::class,
				'create' => EmploymentCreateStoreController::class,
				'store' => EmploymentCreateStoreController::class,
				'show' => EmploymentShowController::class,
				'edit' => EmploymentEditUpdateController::class,
				'update' => EmploymentEditUpdateController::class,
				'destroy' => EmploymentDestroyController::class,
			]
		],
		'operator' => [
			'class' => Operator::class,
			'table' => 'operators__operators',
			'fieldsGroupsFiles' => [
				'index' => OperatorFieldsGroupParametersFile::class,
				'byEmployment' => OperatorByEmploymentFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'show' => OperatorRelationManager::class
			],
			'parametersFiles' => [
				'create' => OperatorCreateStoreFieldsetsParameters::class,
				'show' => OperatorEditUpdateFieldsetsParameters::class,
				'edit' => OperatorEditUpdateFieldsetsParameters::class
			],
			'controllers' => [
				'avatar' => OperatorAvatarController::class,
				'index' => OperatorIndexController::class,
				'create' => OperatorCreateStoreController::class,
				'store' => OperatorCreateStoreController::class,
				'show' => OperatorShowController::class,
				'edit' => OperatorEditUpdateController::class,
				'update' => OperatorEditUpdateController::class,
				'destroy' => OperatorDestroyController::class,
			]
		],
	    'workingDay' => [
		    'class' => WorkingDay::class,
		    'table' => 'operators__working_days',
		    'controllers' => [
			    'update' => WorkingDayUpdateEditController::class,
			    'calendar' => WorkingDayCalendarController::class,
		    ],
		    'fieldsGroupsFiles' => [
			    'calendar' => WorkingDayFieldsGroupParametersFile::class,
		    ],
	    ],
    ]
];