<?php

namespace IlBronza\Operators\Models;

use Carbon\Carbon;
use IlBronza\Clients\Models\Client;
use IlBronza\CRUD\Models\BasePivotModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

use function dd;

class ClientOperator extends BasePivotModel
{
	use CRUDUseUuidTrait;

	static $packageConfigPrefix = 'operators';
	static $modelConfigPrefix = 'clientOperator';
	protected $keyType = 'string';
	protected $casts = [
		'started_at' => 'date',
		'ended_at' => 'date'
	];

	static $deletingRelationships = [];

	use PackagedModelsTrait;

	public function client()
	{
		return $this->belongsTo(
			config('clients.models.client.class')
		);
	}

	public function getClientId() : ?string
	{
		return $this->client_id;
	}

	public function operator()
	{
		return $this->belongsTo(
			config('operators.models.operator.class')
		);
	}

	public function getOperator() : ?Operator
	{
		return $this->operator;
	}

	public function employment()
	{
		return $this->belongsTo(Employment::getProjectClassName());
	}

	public function contracttype()
	{
		return $this->belongsTo(Contracttype::getProjectClassName());
	}

	public function remuneration()
	{
		return $this->price()->where('collection_id', 'remuneration');
	}

	public function getContracttypeName() : ?string
	{
		return $this->getContracttype()?->getName();
	}

	public function getContracttype() : ?Contracttype
	{
		return $this->contracttype;
	}

	public function getEmploymentString() : ?string
	{
		return $this->getEmployment()->getName();
	}

	public function getEmployment() : ?Employment
	{
		return $this->employment;
	}

	public function getStartedAt() : ?Carbon
	{
		return $this->started_at;
	}

	public function isValid() : bool
	{
		if ($endedAt = $this->getEndedAt())
			return $endedAt->endOfDay() > Carbon::now();

		if ($this->hasExternalCompany())
			return true;

		if ($this->hasPermanentJob())
			return true;

		return false;
	}

	public function getEndedAt() : ?Carbon
	{
		return $this->ended_at;
	}

	public function hasExternalCompany() : bool
	{
		return $this->client_id != Client::gpc()::getOwnerCompany()->getKey();
	}

	public function hasPermanentJob() : ?bool
	{
		return $this->getEmployment()?->isPermanent() ?? false;
	}
	public function scopeByEmployments($query, array|Collection $employments)
	{
		if($employments instanceof Collection)
			$employments = $employments->pluck('id');

		$query->whereIn('employment_id', $employments);
	}



	protected static function boot()
	{
		parent::boot();

		static::saving(function ($clientOperator)
		{
			if($clientOperator->isDirty('contracttype_id'))
			{
				if($contracttype = $clientOperator->contracttype)
				{
					$clientOperator->operator?->contracttypes()->syncWithoutDetaching([$contracttype->getKey()]);
				}

			}
		});
	}
}