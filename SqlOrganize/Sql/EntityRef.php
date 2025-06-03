<?php
namespace SqlOrganize\Sql;

class EntityRef
{
    public string $entityName;
    public string $fieldName;

    public static function getInstance($fieldName, $entityName): EntityRelation {
        $er = new EntityRelation();
        $er->fieldName = $fieldName;
        $er->entityName = $entityName;
        return $er;
    }
}