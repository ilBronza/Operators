<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OperatorByEmploymentFieldsGroupParametersFile extends OperatorFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		$parameters = parent::getFieldsGroup();

		unset($parameters['fields']['employments']);

		return $parameters;
	}
}