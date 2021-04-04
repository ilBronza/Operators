<?php

namespace ilBronza\Operator\Models;

use App\Models\Traits\Relationships\ParentingTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ilBronza\CRUD\Traits\CRUDIndexTrait;
use ilBronza\CRUD\Traits\Model\CRUDModelTrait;
use ilBronza\CRUD\Traits\Model\CRUDRelationshipModelTrait;

class Operator extends Model
{
    use HasFactory;
    use CRUDModelTrait;
    use CRUDRelationshipModelTrait;
    use ParentingTrait;

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function getParentPossibleValuesArray()
    {
    	return cache()->remember(
    		'operatorsPossibleValuesArray',
    		3600,
    		function()
    		{
    	        $operators = Operator::with('user')->get();

    	        $result = [];

    	        foreach($operators as $operator)
    	        	$result[$operator->getKey()] = $operator->user->getName();

    	        return $result;
    		}
    	);
    }

}
