<?php

namespace IlBronza\Operators\Http\Controllers\Timelines;

use IlBronza\Operators\Models\Sellables\OperatorOrderrow;
use IlBronza\Timeline\Http\Controllers\BaseTimelineRowModalController;

class ContracttypeTimelineRowModalController extends BaseTimelineRowModalController
{
	public function getRowType() : string
	{
		return OperatorOrderrow::gpc();
	}
}
