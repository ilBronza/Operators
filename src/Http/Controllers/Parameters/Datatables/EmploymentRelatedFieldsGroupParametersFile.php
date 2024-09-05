<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class EmploymentRelatedFieldsGroupParametersFile extends EmploymentFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
        $result = parent::getFieldsGroup();

        unset($result['fields']['operators']);

		return $result;
	}
}