<?php

namespace SqlOrganize\Model;

class Field
{




    public string $name;
    
    public ?string $alias = null;
    
    /**
     * Nombre de la entidad
     */
    public string $entityName;
    
    /**
     * Si es clave foránea: Nombre de la entidad referenciada por la clave foránea
     */
    public ?string $refEntityName = null;
    
    /**
     * Si es clave foránea: Nombre del field al que hace referencia de la entidad referenciada
     */
    public ?string $refFieldName = null;
    
    /**
     * Tipo de datos del motor
     */
    public string $dataType;
    
    /**
     * Tipo de datos del lenguaje
     */
    public string $type;
    
    /**
     * String con el tipo de field
     * - "pk": Clave primaria
     * - "nf": Field normal  
     * - "mo": Clave foránea muchos a uno
     * - "oo": Clave foránea uno a uno
     */
    public string $fieldType;
    
    /**
     * ¿Es not null?
     */
    public bool $notNull = false;
    
    /**
     * Valor por defecto
     * 
     * Valores especiales:
     * - _Guid: GUID para tipos string o Guid
     * - _Random: Random string o Random int dependiendo del tipo
     * - _Current_year: Año actual para tipo short
     * - _Current_semester: Semestre actual para tipo short
     * - _New: nuevo valor para tipo Guid
     * - _Max: Valor máximo
     * - _Next: Siguiente valor
     */
    public mixed $defaultValue = null;
    
    /**
     * Longitud máxima
     */
    public ?int $maxLength = null;
    
    /**
     * Lista de chequeos
     * 
     * Ejemplo:
     * [
     *     'field_name' => true, // método exclusivo definido por el usuario
     *     'Type' => 'string',
     *     'Required' => true,
     * ]
     */
    public array $checks = [];
    
    /**
     * Lista de reasignaciones
     * 
     * Ejemplo:
     * [
     *     'field_name' => true, // método exclusivo definido por el usuario
     *     'Trim' => ' ',
     *     'Ltrim' => ' ', // no implementado
     *     'Rtrim' => ' ', // no implementado  
     *     'RemoveMultipleSpaces' => null,
     *     'NullIfEmpty' => true, // si es vacío se asigna null
     *     'DefaultIfNull' => true, // si es null se asigna valor por defecto
     *     'SetDefault' => true, // siempre setea valor por defecto, por más que el valor ya exista
     * ]
     */
    public array $resets = [];
    
    public function __construct()
    {
        $this->checks = [];
        $this->resets = [];
    }
}