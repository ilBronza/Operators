<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\AccountManager\Http\Controllers\Userdata\EditUserDataAvatarController;
use IlBronza\AccountManager\Models\User;
use IlBronza\CRUD\Http\Controllers\Traits\ControllerLogoTrait;
use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\Operators\Models\Operator;

use Illuminate\Http\Request;

use function app;
use function dd;

class OperatorAvatarController extends EditUserDataAvatarController
{
	public $returnBack = true;

	use ControllerLogoTrait;

    public $allowedMethods = ['avatarFetcher', 'logoUploadForm', 'logoUpdate'];

    public function avatarFetcher(string $operator)
    {
        $this->modelInstance = Operator::gpc()::find($operator);

		return $this->returnLogoImage();
	}

	public function getUpdateModelAction()
	{
		return app('operators')->route('operators.logoUpdate', ['operator' => $this->modelInstance]);
	}

	public function logoUploadForm(string $operator)
	{
		$operator = Operator::gpc()::find($operator);

		return $this->userEdit($operator->user_id);
	}

	public function getAfterUpdatedRedirectUrl()
	{
		return app('operators')->route('operators.edit', ['operator' => $this->operator]);
	}

	public function logoUpdate(Request $request, string $user)
	{
		$this->user = User::gpc()::find($user);

		$this->operator =  $this->user->getOperator();

		return $this->userUpdate($request, $user);
	}
}
