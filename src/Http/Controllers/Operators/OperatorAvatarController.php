<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\CRUD\Http\Controllers\Traits\ControllerLogoTrait;

class OperatorAvatarController extends OperatorCRUD
{
	use ControllerLogoTrait;

    public $allowedMethods = ['avatarFetcher'];

    public function avatarFetcher(string $operator)
    {
        $this->modelInstance = $this->findModel($operator);

		return $this->returnLogoImage();
	}
}
