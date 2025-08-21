<?php

namespace IlBronza\Operators\Http\Controllers\ClientOperators;

use IlBronza\AccountManager\Models\User;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Operators\Http\Controllers\Operators\VehicleCRUD;
use Illuminate\Support\Str;

class ClientOperatorHistoryController extends ClientOperatorCRUD
{
    public $allowedMethods = ['history'];

    public function setActivities()
    {
        $this->activities = collect($this->model->activities);

        $extraFieldsModels = $this->model->getExtraFieldsRelatedModels();

        foreach($extraFieldsModels as $extraFieldsModel)
        {
            $this->activities = $this->activities->merge($extraFieldsModel->activities);
        }
    }

    public function setFields()
    {
        $this->fields = [];

        foreach($this->activities as $activity)
        {
            foreach($activity->properties['attributes'] as $key => $value)
            {
                if($activity->subject_id == $this->model->getKey())
                    $this->fields[$key] = true;

                else
                    $this->fields[$key] = true;
            }
        }

        $this->fieldsToRemove = $this->fields;
    }

    public function history($element)
    {
        $this->model = $this->findModel($element);

        $directAttributeFields = array_keys($this->model->getAttributes());
        $attributeExtraFields = array_keys($this->model->getExtraFieldsCasts());


        $this->setActivities();
        $this->setFields();

        $casts = $this->model->getCasts();

        $totalAttributes = array_merge($directAttributeFields, $attributeExtraFields);



        $this->activitiesResult = [];

        foreach($this->activities as $activity)
        {
            $item = [
                'activity_id' => $activity->id,
                'activity_created_at' => $activity->created_at->format('Y-m-d H:i:s'),
                'activity_description' => $activity->description,
            ];

            if($activity->causer_type == 'User')
                $item['activity_causer'] = User::gpc()::findCached($activity->causer_id)?->getName();

            unset($this->fields['id']);
            unset($this->fields['priceable_id']);
            unset($this->fields['created_by_id']);
            unset($this->fields['previous_id']);
            unset($this->fields['next_id']);
            unset($this->fields['measurement_unit_id']);
            unset($this->fields['collection_id']);
            // unset($this->fields['broadside_row_parameters']);
            // unset($this->fields['parameters']);



            foreach($this->fields as $field => $true)
            {
                if(str_ends_with($field, '_id'))
                {
                    $pieces = explode('_', $field);

                    $relationName = Str::camel($pieces[0]);

                    try
                    {
                        if($this->model->$relationName()->exists())
                        {
                            $relation = $this->model->$relationName();

                            $relatedClass = $relation->getRelated();

                            if($activity->properties['attributes'][$field] ?? null)
                                if($relatedModel = $relatedClass::gpc()::find($activity->properties['attributes'][$field]))
                                {
                                    $item[$field] = $relatedModel->getName() ?? $relatedModel->getKey();
                                    unset($this->fieldsToRemove[$field]);
                                }                            
                        }

                    }
                    catch(\Throwable $e)
                    {
                        $item[$field] = $e->getMessage();
                        unset($this->fieldsToRemove[$field]);
                    }
                }
                else
                {
                    if($value = $activity->properties['attributes'][$field] ?? null)
                    {
                        unset($this->fieldsToRemove[$field]);

                        if(($casts[$field] ?? null) == 'date')
                        {
                            if($value instanceof \DateTime)
                                $item[$field] = $value->format('Y-m-d');

                            elseif($value instanceof Carbon\Carbon)
                                $item[$field] = $value->format('Y-m-d');

                            else
                            {
                                $carbonValue = \Carbon\Carbon::parse($value);
                                $item[$field] = $carbonValue->format('Y-m-d');

                            }
                        }
                        else
                            $item[$field] = $activity->properties['attributes'][$field] ?? null;

                    }
                }
            }

            $this->activitiesResult[] = $item;
        }


        $this->filteredFields = array_diff_key($this->fields, $this->fieldsToRemove);

        //cost_gross_day

        return view('crud::utilities.history.history', [
            'activitiesResult' => $this->activitiesResult,
            'model' => $this->model,
            'fields' => $this->filteredFields,
            'totalAttributes' => $totalAttributes,
            'activities' => $this->activities->sortByDesc('created_at'),
        ]);
    }
}
