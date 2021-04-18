<?php

namespace IlBronza\Operator\Http\Controllers\CRUDTraits;

trait CRUDOperatorChildrenParametersTrait
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
                'parent.user.name' => 'flat',
                'children' => [
                    'type' => 'relations.hasMany',
                    'routeBasename' => 'operators'
                ]
            ]
        ]
    ];

    static $formFields = [
        'common' => [
            'default' => [
                'user' => [
                    'type' => 'select',
                    'multiple' => false,
                    'rules' => 'integer|nullable|exists:users,id',
                    'relation' => 'user'
                ]
            ]
        ]
    ];    
}