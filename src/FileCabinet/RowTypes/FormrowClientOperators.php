<?php

namespace IlBronza\Operators\FileCabinet\RowTypes;

use IlBronza\FileCabinet\Providers\RowTypes\MultipleModelReferencesTrait;

class FormrowClientOperators extends FormrowClientOperator
{
    use MultipleModelReferencesTrait;

    static $databaseField = 'text';
    public ?bool $multiple = true;

    public static function getTranslatedName() : string
    {
        return trans('operators::filecabinet.clientOperators');
    }
}
