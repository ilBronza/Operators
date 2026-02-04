<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ContracttypeRelatedFieldsGroupParametersFile extends ContracttypeFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
        $result = parent::getTracedFieldsGroup();

        unset($result['fields']['operators']);

		return $result;
	}
}