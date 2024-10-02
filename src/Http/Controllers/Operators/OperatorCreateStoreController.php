<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use IlBronza\AccountManager\Helpers\UserCreatorHelper;
use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

use Illuminate\Http\Request;

use function dd;

class OperatorCreateStoreController extends OperatorCRUD
{
    use CRUDCreateStoreTrait;

    public $allowedMethods = ['create', 'store'];

    public function getGenericParametersFile() : ? string
    {
        return config('operators.models.operator.parametersFiles.create');
    }

	public function store(Request $request)
	{
		$fieldsets = (new ($this->getGenericParametersFile()))->getFieldsetsParameters();

		$fields = [];

		foreach($fieldsets as $name => $parameters)
			foreach($parameters['fields'] as $fieldName => $parameters)
				$fields[$fieldName] = $parameters['rules'];

		$parameters = $request->validate($fields);

		$user = UserCreatorHelper::createBySlimParameters(
			$request->new_first_name,
			$request->new_surname,
			$request->new_email . rand(0,12399),
			true
		);

		$operator = $user->createOperator();

		if($request->client)
		{
			$operator->clients()->sync([$request->client => [
				'employment_id' => $request->input('employment'),
			]]);
		}

		return redirect()->to(
			$operator->getEditUrl()
		);
	}
}
