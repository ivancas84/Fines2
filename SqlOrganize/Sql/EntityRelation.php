<?php

namespace SqlOrganize\Sql;

class EntityRelation
{
    public string $fieldName;
    public string $refEntityName;
    public string $refFieldName = 'id';
    public ?string $parentId = null;


}
