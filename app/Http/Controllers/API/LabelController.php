<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\LabelService;

class LabelController extends \App\Http\Controllers\Controller
{
    /**
     * Вовзращает список лейблов определённой сущности.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $entityForeignIdString = $request->entity . '_id';
        $entity = LabelService::identifyEntity($request->entity);
        $entityLabelRelation = LabelService::identifyEntityRelation($request->entity);
		$entityLabelRelationString = $request->entity . '_labels_relations';
        $entityId = $request->entityId;
        LabelService::checkModelExists($entity, $entityId);
        return ['labels' => $entityLabelRelation::select('labels.name')
			->join('labels', "$entityLabelRelationString.label_id", '=', 'labels.id')
			->where("$entityLabelRelationString.$entityForeignIdString", '=', $entityId)
			->get()
			];
    }

    /**
     * Добавить лейбл сущности
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $entityForeignIdString = $request->entity . '_id';
        $entity = LabelService::identifyEntity($request->entity);
        $entityLabelRelation = LabelService::identifyEntityRelation($request->entity);
        $entityId = $request->entityId;
        $labels = $request->labels;
        LabelService::checkEmptyLabelArray($labels);
        LabelService::checkModelExists($entity, $entityId);
        LabelService::addLabelRelations($labels, $entityLabelRelation, $entityId, $entityForeignIdString);
        return [
            'code' => 200,
            'message' => "Labels was added"
        ];
    }

    /**
     * Перезаписать лейблы сущности.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $entityForeignIdString = $request->entity . '_id';
        $entity = LabelService::identifyEntity($request->entity);
        $entityLabelRelation = LabelService::identifyEntityRelation($request->entity);
        $entityId = $request->entityId;
        $labels = $request->labels;
        LabelService::checkModelExists($entity, $entityId);
        LabelService::checkLabelsExists($entityLabelRelation, $entityForeignIdString, $entityId, $labels);
        LabelService::deleteAllLabelRelations($entityLabelRelation, $entityId, $entityForeignIdString, $labels);
        LabelService::addLabelRelations($labels, $entityLabelRelation, $entityId, $entityForeignIdString);
        return [
            'code' => 200,
            'message' => "Labels was updated"
        ];
    }

    /**
     * Удалить лейблы сущности.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $entityForeignIdString = $request->entity . '_id';
        $entity = LabelService::identifyEntity($request->entity);
        $entityLabelRelation = LabelService::identifyEntityRelation($request->entity);
        $entityId = $request->entityId;
        $labels = $request->labels;
		
        LabelService::checkEmptyLabelArray($labels);
		
        LabelService::checkModelExists($entity, $entityId);
        LabelService::checkLabelsExists($entityLabelRelation, $entityForeignIdString, $entityId, $labels);
        LabelService::deleteLabelRelations($entityLabelRelation, $entityId, $entityForeignIdString, $labels);
        return [
            'code' => 200,
            'message' => "Labels was deleted"
        ];
    }
}
