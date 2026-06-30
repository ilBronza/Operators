<?php

namespace IlBronza\Operators\Http\Controllers\Timelines;

use IlBronza\Operators\Models\Sellables\OperatorOrderrow;
use IlBronza\Timeline\Http\Controllers\BaseTimelineRowModalController;
use Illuminate\Http\Request;

class OperatorTimelineRowModalController extends BaseTimelineRowModalController
{
	public function getRowType() : string
	{
		return OperatorOrderrow::gpc();
	}
}
