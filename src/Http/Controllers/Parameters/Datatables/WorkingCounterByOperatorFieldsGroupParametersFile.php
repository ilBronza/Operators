<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

class WorkingCounterByOperatorFieldsGroupParametersFile extends WorkingCounterFieldsGroupParametersFile
{
	public static function getFieldsGroup() : array
	{
		$result = parent::getFieldsGroup();

		unset($result['fields']['operator']);

		return $result;
	}
}
