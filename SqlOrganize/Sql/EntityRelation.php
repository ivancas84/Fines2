<?php

namespace SqlOrganize\Sql;

class EntityRelation
{
    public string $fieldName;
    public string $refEntityName;
    public string $refFieldName = 'id';
    public ?string $parentId = null;

    public static function getInstance($fieldName, $refEntityName, $refFieldName = "id"): EntityRelation {
        $et = new EntityRelation();
        $et->fieldName = $fieldName;
        $et->refEntityName = $refEntityName;
        $et->refFieldName = $refFieldName;
        return $et;
    }
}
