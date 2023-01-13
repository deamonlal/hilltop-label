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
        $entityId = $request->entityId;
        LabelService::checkModelExists($entity, $entityId);
        return ['labels' => $entityLabelRelation::all()];
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
        LabelService::checkLabelsExists($labels);
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
        LabelService::checkLabelsExists($labels);
        LabelService::deleteLabelRelations($entityLabelRelation, $entityId, $entityForeignIdString);
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
        LabelService::checkLabelsExists($labels);
        LabelService::deleteLabelRelations($entityLabelRelation, $entityId, $entityForeignIdString);
        return [
            'code' => 200,
            'message' => "Labels was deleted"
        ];
    }
}
