<?php

namespace IlBronza\Operators\Http\Controllers\CRUDTraits;

trait CRUDOperatorsParametersTrait
{
    public static $tables = [

        'index' => [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => [
                    'type' => 'links.see',
                    'textParameter' => false
                ],
                'user' => [
                    'type' => 'links.see',
                    'textParameter' => 'name'
                ],
                'parent.user.name' => 'flat'
            /**
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => [
                    'type' => 'links.see',
                    'textParameter' => false
                    'order' => [
                        'priority' => 10,
                        'type' => 'desc'
                    ],
                ],
                'rag_soc' => 'flat',
                'destination' => 'flat',
                'somet_text' => 'editor.text',
                'second_color' => 'editor.color',
                'supplier' => 'relations.belongsTo',
                'color' => 'color',
                'manyToManyModels' => [
                    'type' => 'relations.beongsToMany',
                    'pivot' => 'PivotModelBaseName'
                ],
                'relatedModels' => 'relations.hasMany',
                'belongsToModel' => 'relations.belongsTo',
                'zone' => 'flat',
                'mySelfDelete' => 'links.delete'
            **/
            ]
        ],

        'related' => [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => [
                    'type' => 'links.see',
                    'textParameter' => false
                ],
                'user' => [
                    'type' => 'links.see',
                    'textParameter' => 'name'
                ],
                'parent.user.name' => 'flat'
            /**
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => [
                    'type' => 'links.see',
                    'textParameter' => false
                    'order' => [
                        'priority' => 10,
                        'type' => 'desc'
                    ],
                ],
                'rag_soc' => 'flat',
                'destination' => 'flat',
                'somet_text' => 'editor.text',
                'second_color' => 'editor.color',
                'supplier' => 'relations.belongsTo',
                'color' => 'color',
                'manyToManyModels' => [
                    'type' => 'relations.beongsToMany',
                    'pivot' => 'PivotModelBaseName'
                ],
                'relatedModels' => 'relations.hasMany',
                'belongsToModel' => 'relations.belongsTo',
                'zone' => 'flat',
                'mySelfDelete' => 'links.delete'
            **/
            ]
        ]
    ];

    static $formFields = [
        'common' => [
            'default' => [

                'parent' => [
                    'type' => 'select',
                    'multiple' => false,
                    'rules' => 'integer|nullable|exists:operators,id',
                    'relation' => 'parent'
                ],

                'user' => [
                    'type' => 'select',
                    'multiple' => false,
                    'rules' => 'integer|nullable|exists:users,id',
                    'relation' => 'user'
                ],

        /**
                'name' => ['text' => 'string|required|max:255'],
                'age' => ['number' => 'numeric|required'],
                'color' => ['color' => 'numeric|required'],
                'dated_at' => ['date' => 'date|nullable'],
                'time_at' => ['datetime' => 'date|nullable'],
                'permissions' => [
                    'type' => 'select',
                    'multiple' => true,
                    'rules' => 'array|nullable|exists:permissions,id',
                    'relation' => 'permissions'
                ],
                'city' => [
                    'type' => 'select',
                    'multiple' => false,
                    'rules' => 'integer|nullable|exists:cities,id',
                    'relation' => 'city'
                ],
            ]
        **/
        ],
        /**
        'edit' => [
            'default' => [
            ]
        ],
        'onlyEdit' => [
            'default' => [
            ]
        ],
        'create' => [
            'default' => [
            ]
        ],
        'onlyCreate' => [
            'default' => [
            ]
        **/
        ],
    ];    
}