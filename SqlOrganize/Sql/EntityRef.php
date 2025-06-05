<?php
namespace SqlOrganize\Sql;

class EntityRef
{
    public string $entityName;
    public string $fieldName;

    public static function getInstance($fieldName, $entityName): EntityRef {
        $er = new EntityRef();
        $er->fieldName = $fieldName;
        $er->entityName = $entityName;
        return $er;
    }
}