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
					'href' => $this->route('operators.index'),
					'children' => [
						[
							'name' => 'operators.archive',
							'icon' => 'address-book',
							'text' => 'operators::operators.archive',
							'href' => $this->route('operators.archive'),
						]
					]
				],
				[
					'name' => 'operatorContracttypes.index',
					'icon' => 'id-badge',
					'text' => 'operators::operatorContracttypes.list',
					'href' => $this->route('operatorContracttypes.index')
				],
				[
					'name' => 'operatorBadges.index',
					'icon' => 'id-card',
					'text' => 'operators::operatorBadges.list',
					'href' => $this->route('operatorBadges.index')
				],
				[
					'name' => 'contracttypes.index',
					'icon' => 'helmet-safety',
					'text' => 'operators::contracttypes.index',
					'href' => $this->route('contracttypes.index')
				],
				[
					'name' => 'accessGates.index',
					'icon' => 'door-open',
					'text' => 'operators::accessGates.index',
					'href' => $this->route('accessGates.index')
				],
				[
					'name' => 'employments.index',
					'icon' => 'helmet-safety',
					'text' => 'operators::employments.index',
					'roles' => ['asd'],
					'href' => $this->route('employments.index')
				]
            ]
        ]);

        $settingsButton->addChild($operatorsButton);
    }
}

