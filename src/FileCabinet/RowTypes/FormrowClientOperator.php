<?php

namespace IlBronza\Operators\FileCabinet\RowTypes;

use IlBronza\Clients\Models\Client;
use IlBronza\FileCabinet\Providers\RowTypes\BaseModelReferenceRow;
use IlBronza\Operators\Models\ClientOperator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FormrowClientOperator extends BaseModelReferenceRow
{
    public static function getTranslatedName() : string
    {
        return trans('operators::filecabinet.clientOperator');
    }

    protected function referenceQuery(bool $historical = false) : Builder
    {
        $query = ClientOperator::getProjectClassName()::query();
        if ($historical)
            $query->withoutGlobalScopes();

        $owner = $this->getDossierrow()?->getDossierable();
        if (! $owner instanceof Client || $owner->getKey() === null)
            return $query->whereRaw('1 = 0');

        $query->where($query->getModel()->qualifyColumn('client_id'), $owner->getKey());
        if (! $historical)
            $query->current();

        return $query->with(['operator' => function ($operator) use ($historical) {
            if ($historical)
                $operator->withoutGlobalScopes();
        }]);
    }

    protected function referenceLabel(Model $model) : string
    {
        return $model->getOperator()?->getName() ?: (string) $model->getKey();
    }
}
