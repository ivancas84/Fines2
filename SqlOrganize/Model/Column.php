<?php

namespace SqlOrganize\Model;

/**
 * Varios campos que podrían ser considerados como int, se definen como mixed 
 * porque los distintos motores de base de datos asignan tipos diferentes.
 * Ejemplos: CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE
 */
class Column
{
    public string $alias = "";
    public string $dataType;
    
    public string $TABLE_NAME;
    public string $COLUMN_NAME;
    public mixed $COLUMN_DEFAULT = null;
    public int $IS_NULLABLE = 0;
    public ?string $DATA_TYPE = null;
    
    public mixed $CHARACTER_MAXIMUM_LENGTH = null;
    public mixed $MAX_LENGTH = null;
    public mixed $NUMERIC_PRECISION = null;
    public mixed $NUMERIC_SCALE = null;
    public ?string $REFERENCED_TABLE_NAME = null;
    public ?string $REFERENCED_COLUMN_NAME = null;
    public int $IS_PRIMARY_KEY = 0;
    public int $IS_UNIQUE_KEY = 0;
    public int $CONSTRAINT_NAME = 0;
    public int $IS_FOREIGN_KEY = 0;
    public int $IS_BOOLEAN = 0;
    public int $IS_UNSIGNED = 0;
    
    public string $COLUMN_TYPE;
}