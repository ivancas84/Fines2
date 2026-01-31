<?php

namespace SqlOrganize\Model;

class EntityRelation
{
    public string $fieldName;
    public string $refEntityName;
    public string $refFieldName;
    public ?string $parentId = null;
}