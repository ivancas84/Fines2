<?php

namespace SqlOrganize\Model;

class Table
{
    public ?string $name = null;
    public ?string $alias = null;
    public array $columns = [];
    public array $pk = [];
    public array $columnNames = [];
    public array $fk = [];
    public array $unique = [];
    public array $uniqueMultiple = [];
    public array $notNull = [];

    public function __construct()
    {
        $this->columns = [];
        $this->pk = [];
        $this->columnNames = [];
        $this->fk = [];
        $this->unique = [];
        $this->uniqueMultiple = [];
        $this->notNull = [];
    }

    /**
     * Retorna columnas que son foreign keys pero no están referenciadas en las tablas especificadas
     * @param array $referencedTableNames Array de nombres de tablas referenciadas
     * @return array Array de objetos Column
     */
    public function columnsFkNotReferenced(array $referencedTableNames): array
    {
        $columns = [];
        
        foreach ($this->columns as $column) {
            if (($column->isForeignKey == 1) && 
                (!in_array($column->referencedTableName, $referencedTableNames))) {
                $columns[] = $column;
            }
        }

        return $columns;
    }
}