<?php

namespace App\Services;

use App\Models\Label;
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
            $labelCollection = Label::where('name', '=', $label)->first();
			if(is_null($labelCollection)) {
				$newLabel = new Label();
				$newLabel->name = $label;
				$newLabel->save();
				$labelId = $newLabel->id;
			}
			else {
				$labelId = $labelCollection->id;
			}
			$relation = new $entityLabelRelation();
            $relation->label_id = $labelId;
            $relation->$entityForeignIdString = $entityId;
            $relation->save();
        }
    }

    static public function deleteLabelRelations($entityLabelRelation, $entityId, $entityForeignIdString, array $labels) {
		foreach (Label::select('id')->whereIn('name', $labels)->get() as $ids) {
			$labelIds [] = $ids['id'];
		}
        $entityLabelRelation::whereIn('label_id', $labelIds)->where($entityForeignIdString, '=', $entityId)->delete();
    }
	
	static public function deleteAllLabelRelations($entityLabelRelation, $entityId, $entityForeignIdString, array $labels) {
        $entityLabelRelation::where($entityForeignIdString, '=', $entityId)->delete();
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
    
    static public function checkLabelsExists($entityLabelRelation, $entityForeignIdString, $entityId, array $labels) {
	    foreach (Label::select('id')->whereIn('name', $labels)->get() as $ids) {
			$labelIds [] = $ids['id'];
		}
        if(count($entityLabelRelation::select('label_id')->distinct('label_id')->whereIn('label_id', $labelIds)->where($entityForeignIdString, '=', $entityId)->distinct()->get()) != count($labelIds)) {
                throw new Exception("Some label was not found");
        }
    }
}
