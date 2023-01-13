<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use App\Models\Site;
use App\Models\UserLabelsRelation;
use App\Models\CompanyLabelsRelation;
use App\Models\SiteLabelsRelation;
use Exception;

class LabelService {

    static public function identifyEntity(string $entity) {
        $answer = match ($entity) {
            'user' => new User(),
            'company' => new Company(),
            'site' => new Site()
        };
        return $answer;
    }

    static public function identifyEntityRelation(string $entity) {
        $answer = match ($entity) {
            'user' => new UserLabelsRelation(),
            'company' => new CompanyLabelsRelation(),
            'site' => new SiteLabelsRelation()
        };
        return $answer;
    }

    static public function addLabelRelations(array $labels, $entityLabelRelation, $entityId, $entityForeignIdString) {
        foreach ($labels as $label) {
            $labelId = Label::where('name', '=', $label)->first()->id;
            $entityLabelRelation->label_id = $labelId;
            $entityLabelRelation->$entityForeignIdString = $entityId;
            $entityLabelRelation->save();
        }
    }

    static public function deleteLabelRelations($entityLabelRelation, $entityId, $entityForeignIdString) {
        $entityLabelRelation::where($entityForeignIdString, '=', $entityId)->destroy();
    }
    
    static public function checkEmptyLabelArray($labels) {
        if(empty($labels)) {
            throw new Exception('Label array is empty');
        }
    }
    
    static public function checkModelExists($entity, $entityId) {
        if(empty($entity::all()->where('id', '=', $entityId)->toArray())) {
                throw new Exception("Entity was not found");
        }
    }
    
    static public function checkLabelsExists(array $labels) {
        if(Label::select('name')->whereIn('name', $labels)->get()) {
                throw new Exception("Some label was not found");
        }
    }
}
