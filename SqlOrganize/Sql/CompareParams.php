<?php

namespace SqlOrganize\Sql;

class CompareParams
{
    /**
     * Campos a ignorar en la comparación
     */
    public ?array $ignoreFields = null;

    /**
     * Ignorar valores nulos
     */
    public bool $ignoreNull = false;

    /**
     * Ignorar campos no existentes
     */
    public bool $ignoreNonExistent = false;

    /**
     * Campos específicos a comparar (si se especifica, solo se comparan estos)
     */
    public ?array $fieldsToCompare = null;

    public function __construct(
        ?array $ignoreFields = null,
        bool $ignoreNull = false,
        bool $ignoreNonExistent = false,
        ?array $fieldsToCompare = null
    ) {
        $this->ignoreFields = $ignoreFields;
        $this->ignoreNull = $ignoreNull;
        $this->ignoreNonExistent = $ignoreNonExistent;
        $this->fieldsToCompare = $fieldsToCompare;
    }
}

?>