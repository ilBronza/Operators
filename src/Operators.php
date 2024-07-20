<?php

namespace IlBronza\Operators;

use IlBronza\CRUD\Providers\RouterProvider\RoutedObjectInterface;
use IlBronza\CRUD\Traits\IlBronzaPackages\IlBronzaPackagesTrait;

class Operators implements RoutedObjectInterface
{
    use IlBronzaPackagesTrait;

    static $packageConfigPrefix = 'operators';


    public function manageMenuButtons()
    {
        if(! $menu = app('menu'))
            return;

        $settingsButton = $menu->provideSettingsButton();


        $operatorsButton = $menu->createButton([
            'name' => 'operatorsManager',
            'icon' => 'users',
            'text' => 'operators::operators.manager',
            'children' => [
                [
                    'name' => 'operators.index',
                    'icon' => 'address-book',
                    'text' => 'operators::operators.list',
                    'href' => $this->route('operators.index')
                ],
                [
                    'name' => 'contracttypes.index',
                    'icon' => 'helmet-safety',
                    'text' => 'operators::contracttypes.index',
                    'href' => $this->route('contracttypes.index')
                ]
            ]
        ]);

        $settingsButton->addChild($operatorsButton);
    }
}



