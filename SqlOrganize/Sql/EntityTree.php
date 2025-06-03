<?php

namespace SqlOrganize\Sql;

class EntityTree
{
    public string $fieldName;
    public string $refEntityName;
    public string $refFieldName = 'id';
    
    /** @var array<string, EntityTree> */
    public array $children = [];

    public static function getInstance($fieldName, $refEntityName, $refFieldName = "id"): EntityTree {
        $et = new EntityTree();
        $et->fieldName = $fieldName;
        $et->refEntityName = $refEntityName;
        $et->refFieldName = $refFieldName;
        return $et;
    }
    
}
