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
    
    public string $tableName;
    public string $columnName;
    public mixed $columnDefault = null;
    public int $isNullable = 0;
    public ?string $dataTypeDb = null;
    
    public mixed $characterMaximumLength = null;
    public mixed $maxLength = null;
    public mixed $numericPrecision = null;
    public mixed $numericScale = null;
    public ?string $referencedTableName = null;
    public ?string $referencedColumnName = null;
    public int $isPrimaryKey = 0;
    public int $isUniqueKey = 0;
    public int $constraintName = 0;
    public int $isForeignKey = 0;
    public int $isUnsigned = 0;
    
    public string $columnType;
}