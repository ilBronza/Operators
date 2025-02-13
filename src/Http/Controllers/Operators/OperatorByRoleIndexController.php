<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

use Illuminate\Http\Request;

class OperatorByRoleIndexController extends OperatorIndexController
{
    public $allowedMethods = ['index'];

	public function index(Request $request)
	{
		$this->role = $request->role;

		return $this->_index($request);
	}

    public function getIndexElements()
    {
        return $this->getModelClass()::role($this->role)->active()->with(
			'user.extraFields',
			'user.address',
			'address',
            'contracttypes',
			'contacts.contacttype',
	        'clientOperators.client',
	        'clientOperators.extraFields',
			'clients',
            'sellableSuppliers.directPrice',
            'sellableSuppliers.sellable',
            'employments'
        )
        ->withSupplierId()
        ->get();
    }

}
