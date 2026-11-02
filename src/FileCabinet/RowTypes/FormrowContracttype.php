<?php

namespace IlBronza\Operators\FileCabinet\RowTypes;

use IlBronza\CRUD\Scopes\ArchivingScope;
use IlBronza\FileCabinet\Providers\RowTypes\BaseModelReferenceRow;
use IlBronza\Operators\Models\Contracttype;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormrowContracttype extends BaseModelReferenceRow
{
    public static function getTranslatedName() : string
    {
        return trans('operators::filecabinet.contracttype');
    }

    protected function referenceQuery(bool $historical = false) : Builder
    {
        // Contract types are a shared catalog; keep the host application's scopes.
        $query = Contracttype::getProjectClassName()::query();
        if ($historical)
            $query->withoutGlobalScopes([SoftDeletingScope::class, ArchivingScope::class]);

        return $query;
    }

    protected function referenceLabel(Model $model) : string
    {
        return $model->getName() ?: (string) $model->getKey();
    }
}
