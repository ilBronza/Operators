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
    }
}



        // $operatorsButton = $menu->createButton([
        //     'name' => 'operators.index',
        //     'icon' => 'users',
        //     'text' => 'clients::operators.list',
        //     'href' => IbRouter::route($this, 'operators.index')
        // ]);