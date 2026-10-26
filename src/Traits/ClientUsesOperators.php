<?php

namespace IlBronza\Operators\Traits;

use Carbon\Carbon;
use IlBronza\Operators\Models\Operator;

trait ClientUsesOperators
{
	public function getStartedAtAttribute() : ? Carbon
	{
		if($this->relationLoaded('pivot'))
			return $this->pivot?->started_at;

		return null;
	}

	public function getEndedAtAttribute() : ? Carbon
	{
		if($this->relationLoaded('pivot'))
			return $this->pivot?->ended_at;

		return null;
	}
}