<?php

namespace SqlOrganize\Sql;

/**
 * Validacion basica
 */
class Validation
{
    public $value; // valor a validar
    public array $errors = []; // log de errores

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function required(): self
    {
        if (empty($this->value)) {
            $this->errors[] = ["msg" => "Valor nulo o vacío", "type" => "required"];
        }
        return $this;
    }

    public function type(string $type): self
    {
        switch ($type) {
            case "string":
                if (!empty($this->value) && !is_string($this->value)) {
                    $this->errors[] = ["msg" => "Valor no texto", "type" => "type"];
                }
                break;

            case "integer":
            case "int":
                if (!empty($this->value) && !is_int($this->value)) {
                    $this->errors[] = ["msg" => "Valor no entero", "type" => "type"];
                }
                break;
        }
        return $this;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function clear(): self
    {
        $this->errors = [];
        return $this;
    }

}

?>