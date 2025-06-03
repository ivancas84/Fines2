<?php

namespace SqlOrganize\Sql;

class EntityTree
{
    public string $fieldName;
    public string $refEntityName;
    public string $refFieldName = 'id';
    
    /** @var array<string, EntityTree> */
    public array $children = [];

    
}
