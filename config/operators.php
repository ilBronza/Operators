<?php

use IlBronza\Operators\Http\Controllers\Parameters\Datatables\WorkingCounterByOperatorFieldsGroupParametersFile;

use IlBronza\Operators\Http\Controllers\Parameters\Datatables\WorkingCounterByClientOperatorFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\WorkingCounterFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\WorkingCounterCreateStoreFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\WorkingCounterEditUpdateFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\WorkingCounters\WorkingCounterCreateStoreController;
use IlBronza\Operators\Http\Controllers\WorkingCounters\WorkingCounterDestroyController;
use IlBronza\Operators\Http\Controllers\WorkingCounters\WorkingCounterEditUpdateController;
use IlBronza\Operators\Http\Controllers\WorkingCounters\WorkingCounterIndexController;
use IlBronza\Operators\Http\Controllers\WorkingCounters\WorkingCounterShowController;
use IlBronza\Operators\Http\Controllers\WorkingCounters\WorkingCountersByOperatorEditUpdateController;
use IlBronza\Operators\Models\WorkingCounter;

use IlBronza\Operators\Helpers\OperatorOrderrows\OperatorRowAssociatorHelper;
use IlBronza\Operators\Helpers\OperatorPricesCreatorHelper;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorCreateStoreController;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorDestroyController;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorEditUpdateController;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorHistoryController;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorIndexController;
use IlBronza\Operators\Http\Controllers\ClientOperators\ClientOperatorShowController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeCreateStoreController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeDestroyController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeEditUpdateController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeIndexController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeReorderController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeShowController;
use IlBronza\Operators\Http\Controllers\Contracttypes\ContracttypeSupplierTimelineController;
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
use IlBronza\Operators\Http\Controllers\Operators\OperatorArchiveController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorAvatarController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorBulkDeleteController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorByRoleIndexController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorCreateStoreController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorDestroyController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorDocumentsController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorEditUpdateController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorIndexController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorReorderController;
use IlBronza\Operators\Http\Controllers\Operators\OperatorShowController;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ClientOperatorByOperatorFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ClientOperatorFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ClientOperatorRelatedFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ContracttypeFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\ContracttypeRelatedFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\EmploymentFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\EmploymentRelatedFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorArchiveFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorByEmploymentFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorContracttypeByContracttypeFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorContracttypeFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorContracttypeRelatedFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorOrderrowsFieldsGroupParametersFile;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorQuotationrowsFieldsGroupParametersFile;
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
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorOrderrowEditUpdateFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorQuotationrowEditUpdateFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorTimelineCreateBySellableRowFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorTimelineCreateBySupplierRowFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorTimelineCreateGenericRowFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\Fieldsets\OperatorTimelineCreateRowFieldsetsParameters;
use IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers\ContracttypeRelationManager;
use IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers\EmploymentRelationManager;
use IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers\OperatorContracttypeRelationManager;
use IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers\OperatorRelationManager;
use IlBronza\Operators\Http\Controllers\Parameters\RelationshipsManagers\ClientOperatorRelationManager;
use IlBronza\Operators\Http\Controllers\Timelines\OperatorGlobalTimelineController;
use IlBronza\Operators\Http\Controllers\Timelines\OperatorTimelineCreateRowController;
use IlBronza\Operators\Http\Controllers\Timelines\OperatorsByContracttypesTimelineController;
use IlBronza\Operators\Http\Controllers\Timelines\OperatorsByOrdersTimelineController;
use IlBronza\Operators\Http\Controllers\WorkingDays\WorkingDayCalendarConsolidateController;
use IlBronza\Operators\Http\Controllers\WorkingDays\WorkingDayCalendarController;
use IlBronza\Operators\Http\Controllers\WorkingDays\WorkingDayPrintCalendarController;
use IlBronza\Operators\Http\Controllers\WorkingDays\WorkingDayUpdateEditController;
use IlBronza\Operators\Models\ClientOperator;
use IlBronza\Operators\Models\Contracttype;
use IlBronza\Operators\Models\Employment;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\OperatorContracttype;
use IlBronza\Operators\Models\Sellables\Helpers\ContracttypeOperatorSellableSupplierPricesHelper;
use IlBronza\Operators\Models\WorkingDay;

return [
    'routePrefix' => 'operators',
	'missingImageUrl' => '/img/no_user.png',

	'enabled' => false,

	'manageCosts' => true,
	'manageDaysCalendar' => true,

	'roles' => ['administrator'],

    'sellableSupplierPricesHelper' => [
        'contracttype_operatorContracttype' => ContracttypeOperatorSellableSupplierPricesHelper::class
    ],

	'models' => [
		'operatorRow' => [
			'helpers' => [
				'operatorRowAssociatorHelper' => OperatorRowAssociatorHelper::class
			]
		],
        'orderrow' => [
            'fieldsGroupsFiles' => [
                'operatorOrderrow' => OperatorOrderrowsFieldsGroupParametersFile::class,
            ],
            'relatedButtonsMethods' => [
                'getAddSellableSupplierButton' => true,
                'getAddRowButton' => true,
                'getAddRowTableButton' => true
            ],
            'parametersFiles' => [
                'edit' => OperatorOrderrowEditUpdateFieldsetsParameters::class,
                'timelineCreate' => OperatorTimelineCreateRowFieldsetsParameters::class,
                'timelineCreateBySellable' => OperatorTimelineCreateBySellableRowFieldsetsParameters::class,
                'timelineCreateBySupplier' => OperatorTimelineCreateBySupplierRowFieldsetsParameters::class,

                'TEST_REPLACE_TimelineCreateStandAloneUniversal' => OperatorTimelineCreateGenericRowFieldsetsParameters::class
            ]
        ],
        'quotationrow' => [
            'fieldsGroupsFiles' => [
            	'operatorQuotationrow' => OperatorQuotationrowsFieldsGroupParametersFile::class,
                'index' => OperatorQuotationrowsFieldsGroupParametersFile::class,
            ],
            'relatedButtonsMethods' => [
                'getAddSellableSupplierButton' => true,
                'getAddRowButton' => true,
                'getAddRowTableButton' => true
            ],
            'parametersFiles' => [
                'edit' => OperatorQuotationrowEditUpdateFieldsetsParameters::class
            ]
        ],
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
				'edit' => OperatorContracttypeEditUpdateFieldsetsParameters::class,
				'show' => OperatorContracttypeEditUpdateFieldsetsParameters::class
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
			'relationshipsManagerClasses' => [
				'show' => ClientOperatorRelationManager::class
			],
			'parametersFiles' => [
				'create' => ClientOperatorCreateStoreFieldsetsParameters::class,
				'show' => ClientOperatorEditUpdateFieldsetsParameters::class,
				'edit' => ClientOperatorEditUpdateFieldsetsParameters::class
			],
			'controllers' => [
				'history' => ClientOperatorHistoryController::class,
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
				'show' => ContracttypeRelationManager::class,
				'edit' => ContracttypeRelationManager::class,
			],
			'parametersFiles' => [
				'create' => ContracttypeCreateStoreFieldsetsParameters::class,
				'show' => ContracttypeCreateStoreFieldsetsParameters::class,
			],
			'controllers' => [
				'reorder' => ContracttypeReorderController::class,
				'index' => ContracttypeIndexController::class,
				'create' => ContracttypeCreateStoreController::class,
				'store' => ContracttypeCreateStoreController::class,
				'show' => ContracttypeShowController::class,
				'edit' => ContracttypeEditUpdateController::class,
				'update' => ContracttypeEditUpdateController::class,
				'destroy' => ContracttypeDestroyController::class,
				'supplierTimeline' => ContracttypeSupplierTimelineController::class,
			],
			'helpers' => [
				'sellableSupplierPricesCreator' => OperatorPricesCreatorHelper::class
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
				'archive' => OperatorArchiveFieldsGroupParametersFile::class,
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
				'bulkDelete' => OperatorBulkDeleteController::class,
				'reorder' => OperatorReorderController::class,
				'documents' => OperatorDocumentsController::class,
				'avatar' => OperatorAvatarController::class,
				'globalTimeline' => OperatorGlobalTimelineController::class,
				'byContracttypesTimeline' => OperatorsByContracttypesTimelineController::class,
				'byOrdersTimeline' => OperatorsByOrdersTimelineController::class,
				'timelineCreateRow' => OperatorTimelineCreateRowController::class,
				'index' => OperatorIndexController::class,
				'byRole' => OperatorByRoleIndexController::class,
				'archive' => OperatorArchiveController::class,
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
			    'consolidate' => WorkingDayCalendarConsolidateController::class,
			    'calendar' => WorkingDayCalendarController::class,
			    'printCalendar' => WorkingDayPrintCalendarController::class,
		    ],
		    'fieldsGroupsFiles' => [
			    'calendar' => WorkingDayFieldsGroupParametersFile::class,
		    ],
	    ],
		'workingCounter' => [
			'class' => WorkingCounter::class,
			'table' => 'operators__working_counters',
			'types' => [
				'flex',
				'rol',
				'holidays',
			],
			'fieldsGroupsFiles' => [
				'index' => WorkingCounterFieldsGroupParametersFile::class,
				'byOperator' => WorkingCounterByOperatorFieldsGroupParametersFile::class,
				'byClientOperator' => WorkingCounterByClientOperatorFieldsGroupParametersFile::class,
			],
			'parametersFiles' => [
				'create' => WorkingCounterCreateStoreFieldsetsParameters::class,
				'show' => WorkingCounterEditUpdateFieldsetsParameters::class,
				'edit' => WorkingCounterEditUpdateFieldsetsParameters::class,
			],
			'controllers' => [
				'index' => WorkingCounterIndexController::class,
				'create' => WorkingCounterCreateStoreController::class,
				'store' => WorkingCounterCreateStoreController::class,
				'show' => WorkingCounterShowController::class,
				'edit' => WorkingCounterEditUpdateController::class,
				'update' => WorkingCounterEditUpdateController::class,
				'editByOperator' => WorkingCountersByOperatorEditUpdateController::class,
				'updateByOperator' => WorkingCountersByOperatorEditUpdateController::class,
				'destroy' => WorkingCounterDestroyController::class,
			],
		],
    ]
];
