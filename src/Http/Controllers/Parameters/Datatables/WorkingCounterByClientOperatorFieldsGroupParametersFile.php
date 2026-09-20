<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

class WorkingCounterByClientOperatorFieldsGroupParametersFile extends WorkingCounterFieldsGroupParametersFile
{
	public static function getFieldsGroup() : array
	{
		$result = parent::getFieldsGroup();

		unset($result['fields']['client_operator_label']);

		return $result;
	}
}
