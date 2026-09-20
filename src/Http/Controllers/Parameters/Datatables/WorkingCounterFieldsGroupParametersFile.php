<?php

namespace IlBronza\Operators\Http\Controllers\Parameters\Datatables;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class WorkingCounterFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	public static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'operators::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',
				'operator' => 'relations.belongsTo',
				'client_operator_label' => 'flat',
				'type' => 'flat',
				'amount' => 'numbers.number2',
				'reset_date' => 'dates.date',
				'expired_at' => 'dates.date',
				'mySelfDelete' => 'links.delete',
			],
		];
	}
}
