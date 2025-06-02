<?php

namespace SqlOrganize\Model;

require_once MAIN_PATH . 'SqlOrganize/Model/BuildSchema.php';
require_once MAIN_PATH . 'SqlOrganize/Model/BuildEntityTree.php';
require_once MAIN_PATH . 'SqlOrganize/Model/Table.php';
require_once MAIN_PATH . 'SqlOrganize/Model/Column.php';
require_once MAIN_PATH . 'SqlOrganize/Model/EntityMetadata.php';
require_once MAIN_PATH . 'SqlOrganize/Model/Field.php';
require_once MAIN_PATH . 'SqlOrganize/Model/EntityTree.php';
require_once MAIN_PATH . 'SqlOrganize/Model/EntityRelation.php';
require_once MAIN_PATH . 'SqlOrganize/Model/EntityRef.php';
require_once MAIN_PATH . 'SqlOrganize/Utils/ValueTypesUtils.php';

use SqlOrganize\Utils\ValueTypesUtils;



abstract class BuildSchema
{
    public array $tables = [];
    public array $entities = [];
    public array $fields = [];

 
    /**
     * Definir datos del esquema y arbol de relaciones
     */
    public function __construct()
    {

        $reserved_alias = array_filter(array_map('trim', explode(',', RESERVED_ALIAS)));
        $reserved_entities = array_filter(array_map('trim', explode(',', RESERVED_ENTITIES)));
        $dont_treat_as_fk  = array_filter(array_map('trim', explode(',', DONT_TREAT_AS_FK)));

        // Definicion inicial de tables y columns
        $tableAlias = array_merge([], $reserved_alias);

        foreach ($this->getTableNames() as $tableName) {
            if (in_array($tableName, $reserved_entities)) {
                continue;
            }

            $table = new Table();
            $table->name = $tableName;
            $table->alias = $this->getAlias($tableName, $tableAlias, 4);
            $tableAlias[] = $table->alias;
            $table->columns = $this->getColumns($table->name);

            $fieldsAliases = array_merge([], $reserved_alias);
            foreach ($table->columns as $col) {
                if ($col->IS_FOREIGN_KEY == 1 && 
                    !in_array($col->REFERENCED_TABLE_NAME, $reserved_entities)) {
                    $idSource = (ID_SOURCE == "field_name") ? 
                        $col->COLUMN_NAME : $col->REFERENCED_TABLE_NAME;
                    $col->alias = $this->getAlias($idSource, $fieldsAliases, 3);
                    $fieldsAliases[] = $col->alias;
                }
                $table->columnNames[] = $col->COLUMN_NAME;

                if ($col->IS_FOREIGN_KEY == 1 && 
                    !in_array($col->REFERENCED_TABLE_NAME, $reserved_entities) &&
                    !in_array($col->REFERENCED_TABLE_NAME, $dont_treat_as_fk)) {
                    $table->fk[] = $col->COLUMN_NAME;
                }
                if ($col->IS_PRIMARY_KEY == 1) {
                    $table->pk[] = $col->COLUMN_NAME;
                }
                if ($col->IS_NULLABLE == 0) {
                    $table->notNull[] = $col->COLUMN_NAME;
                }
            }

            $this->tables[] = $table;
        }

        // Definicion de campos unicos de tables
        foreach ($this->tables as $table) {
            $infoUnique = $this->getInfoUnique($table->name);

            foreach ($infoUnique as $constraintName => $columnNames) {
                if (count($columnNames) > 1) {
                    $table->uniqueMultiple[] = $columnNames;
                } else {
                    $table->unique[] = $columnNames[0];
                }
            }
        }

        // Definicion de entities
        foreach ($this->tables as $t) {
            if (in_array($t->name, $reserved_entities)) {
                continue;
            }

            $e = new EntityMetadata();
            $e->name = $t->name;
            $e->alias = $t->alias;
            $e->fields = $t->columnNames;
            $e->pk = $t->pk;
            $e->fk = $t->fk;
            $e->unique = $t->unique;
            $e->uniqueMultiple = $t->uniqueMultiple;
            $e->notNull = $t->notNull;
            $this->entities[$e->name] = $e;
        }

        // Definir id de entities
        foreach ($this->entities as $name => $e) {
            $e->id = $this->defineId($e);
        }


        // Definicion de fields
        foreach ($this->tables as $t) {
            if (in_array($t->name, $reserved_entities)) {
                continue;
            }

            foreach ($t->columns as $c) {
                if (!in_array($c->COLUMN_NAME, $this->entities[$t->name]->fields)) {
                    continue;
                }

                $f = new Field();
                $f->entityName = $t->name;
                $f->name = $c->COLUMN_NAME;
                $f->alias = $c->alias;

                $this->defineField($c, $f);

                if (!isset($this->fields[$t->name])) {
                    $this->fields[$t->name] = [];
                }

                $this->fields[$t->name][$f->name] = $f;
            }
        }

        // Definicion de tree y relations de entities
        foreach ($this->entities as $name => $e) {

            $bet = new BuildEntityTree($this->entities, $this->fields, $e->name);
            $e->tree = $bet->build();
            $this->relationsRecursive($e->relations, $e->tree);
        }


        // Definicion de refs
        foreach ($this->entities as $name => $e) {
            foreach ($this->entities as $name_ => $e_) {
                foreach ($e_->tree as $fieldId => $tree) {
                    if ($tree->refEntityName === $name) {
                        $fn = ($tree->fieldName === $tree->refEntityName) ? "" : $tree->fieldName . "_";

                        $rref = new EntityRef();
                        $rref->entityName = $tree->entityName;
                        $rref->fieldName = $tree->fieldName;

                        if (in_array($tree->fieldName, $e_->unique)) {
                            $e->oo[ValueTypesUtils::toCamelCase($tree->entityName) . "_" . $fn] = $rref;
                        } else {
                            $e->om[ValueTypesUtils::toCamelCase($tree->entityName) . "_" . $fn] = $rref;
                        }
                    }
                }
            }
        }
        
    }

    protected function defineField(Column $c, Field $f): void
    {
        if (!empty($c->CHARACTER_MAXIMUM_LENGTH) && 
            !empty($c->CHARACTER_MAXIMUM_LENGTH) && 
            intval($c->CHARACTER_MAXIMUM_LENGTH) > 0) {
            $f->maxLength = intval($c->CHARACTER_MAXIMUM_LENGTH);
        } elseif (!empty($c->MAX_LENGTH) && !empty($c->MAX_LENGTH)) {
            $f->maxLength = intval($c->MAX_LENGTH);
        }

        $f->dataType = $c->DATA_TYPE;
        
        switch ($c->DATA_TYPE) {
            case "varbinary":
            case "binary":
                if ($f->maxLength == 1) {
                    $f->type = "byte";
                } else {
                    $f->type = "Byte[]";
                }
                break;

            case "image":
            case "rowversion":
                $f->type = "Byte[]";
                break;

            case "money":
            case "smallmoney":
            case "numeric":
            case "decimal":
                $f->type = "decimal";
                break;

            case "varchar":
            case "char":
            case "nchar":
            case "nvarchar":
            case "text":
            case "mediumtext":
            case "tinytext":
            case "longtext":
                $f->type = "string";
                break;

            case "real":
                $f->type = "Single";
                break;

            case "float":
                $f->type = "Double";
                break;

            case "bit":
                $f->type = "bool";
                break;

            case "datetime":
            case "datetime2":
            case "smalldatetime":
            case "timestamp":
            case "date":
            case "time":
                $f->type = "DateTime";
                break;

            case "smallint":
            case "year":
                $f->type = ($c->IS_UNSIGNED == 1) ? "ushort" : "short";
                break;

            case "int":
            case "mediumint":
                $f->type = ($c->IS_UNSIGNED == 1) ? "uint" : "int";
                break;

            case "tinyint":
                if ($f->maxLength == 1) {
                    $f->type = "bool";
                } else {
                    $f->type = "byte";
                }
                break;

            case "bigint":
                $f->type = ($c->IS_UNSIGNED == 1) ? "ulong" : "long";
                break;

            case "uniqueidentifier":
                $f->type = "Guid";
                break;

            case "sql_variant":
            case "table":
            case "cursor":
            case "xml":
                $f->type = "object";
                break;

            default:
                $f->type = $c->DATA_TYPE;
                break;
        }

        if (!empty($c->COLUMN_DEFAULT) && 
            !empty($c->COLUMN_DEFAULT) && 
            $c->COLUMN_DEFAULT !== "NULL") {
            $f->defaultValue = $c->COLUMN_DEFAULT;
        }

        if (!empty($c->REFERENCED_TABLE_NAME)) {
            $f->refEntityName = $c->REFERENCED_TABLE_NAME;
        }

        if (!empty($c->REFERENCED_COLUMN_NAME)) {
            $f->refFieldName = $c->REFERENCED_COLUMN_NAME;
        }

        $f->notNull = ($c->IS_NULLABLE == 1) ? false : true;

        $f->checks = [
            "type" => $f->type
        ];

        if ($f->notNull) {
            $f->checks["required"] = true;
        }

        if ($f->type == "string") {
            $f->resets = [
                "trim" => ' ',
                "removeMultipleSpaces" => true
            ];
            if (!$f->notNull) {
                $f->resets["nullIfEmpty"] = true;
            }
        }

        if ($f->type == "bool" && $f->defaultValue !== null) {
            $f->defaultValue = ValueTypesUtils::toBool($f->defaultValue);
        }

        if ($f->type == "string" && $f->defaultValue !== null) {
            $f->defaultValue = trim($f->defaultValue, "'");
        }
    }


    /**
     * Definir alias para tablas y campos
     */
    protected function getAlias(string $name, array $reserved, int $length = 3, string $separator = "_"): string
    {
        $n = trim($name, '_');
        $words = explode($separator, $n);

        $nameAux = "";

        if (strlen($n) < $length) {
            $length = strlen($n);
        }

        if (count($words) > 1) {
            foreach ($words as $word) {
                $nameAux .= $word[0];
            }
        }

        $aliasAux = substr($name, 0, $length);

        $c = 0;

        while (in_array($aliasAux, $reserved)) {
            $c++;
            $aliasAux = substr($aliasAux, 0, $length - 1);
            $aliasAux .= strval($c);
        }

        return $aliasAux;
    }

    protected abstract function getTableNames(): array;

    protected abstract function getColumns(string $tableName): array;

    protected abstract function getInfoUnique(string $tableName): array;

    protected function relationsRecursive(array &$rel, array $tree, ?string $parentId = null): void
    {
        foreach ($tree as $fieldId => $t) {
            $r = new EntityRelation();
            $r->fieldName = $t->fieldName;
            $r->refEntityName = $t->refEntityName;
            $r->refFieldName = $t->refFieldName;
            $r->parentId = $parentId;
            $rel[$fieldId] = $r;
            $this->relationsRecursive($rel, $t->children, $fieldId);
        }
    }

    public function createModelTree($sw, array $tree, string $prefix = ""): void
    {
        fwrite($sw, $prefix . "                            'children' => [\n");

        foreach ($tree as $fieldId => $tree_) {
            fwrite($sw, $prefix . "                                // " . $fieldId . "\n");
            fwrite($sw, $prefix . "                                '" . $fieldId . "' => [\n");
            fwrite($sw, $prefix . "                                    'fieldName' => '" . $tree_->fieldName . "',\n");
            fwrite($sw, $prefix . "                                    'refFieldName' => '" . $tree_->refFieldName . "',\n");
            fwrite($sw, $prefix . "                                    'refEntityName' => '" . $tree_->refEntityName . "',\n");
            if (!empty($tree_->children)) {
                $this->createModelTree($sw, $tree_->children, $prefix . "        ");
            }
            fwrite($sw, $prefix . "                                ],\n\n");
        }

        fwrite($sw, $prefix . "                            ],\n");
    }
    
public function createSchema(): void
{
    if (!is_dir(SCHEMA_CLASS_PATH)) {
        mkdir(SCHEMA_CLASS_PATH, 0755, true);
    }

    $schemaFileName = "Schema.php";
    $schemaSourcePath = SCHEMA_CLASS_PATH . DIRECTORY_SEPARATOR . $schemaFileName;
    $sw = fopen($schemaSourcePath, 'w');

    fwrite($sw, "<?php\n\n");
    fwrite($sw, "namespace SqlOrganize\\Sql\\" . MODEL_NAMESPACE . ";\n\n");
    fwrite($sw, "use SqlOrganize\\Sql\\ISchema;\n");
    fwrite($sw, "use SqlOrganize\\Sql\\EntityMetadata;\n");
    fwrite($sw, "use SqlOrganize\\Sql\\Field;\n\n");
    fwrite($sw, "/**\n");
    fwrite($sw, " * Esquema de la base de datos\n");
    fwrite($sw, " * Esta clase fue generada por una herramienta, no debe ser modificada.\n");
    fwrite($sw, " */\n");
    fwrite($sw, "class Schema extends ISchema\n");
    fwrite($sw, "{\n");
    fwrite($sw, "    public function __construct()\n");
    fwrite($sw, "    {\n");

    foreach ($this->entities as $entityName => $entity) {
        fwrite($sw, "        \$e = new EntityMetadata();\n");
        fwrite($sw, "        \$e->name = '{$entityName}';\n");
        fwrite($sw, "        \$e->alias = '{$entity->alias}';\n");

        if (!empty($entity->schema)) {
            fwrite($sw, "        \$e->schema = '{$entity->schema}';\n");
        }
        if (!empty($entity->pk)) {
            fwrite($sw, "        \$e->pk = ['" . implode("', '", $entity->pk) . "'];\n");
        }
        if (!empty($entity->fk)) {
            fwrite($sw, "        \$e->fk = ['" . implode("', '", $entity->fk) . "'];\n");
        }
        if (!empty($entity->noAdmin)) {
            fwrite($sw, "        \$e->noAdmin = ['" . implode("', '", $entity->noAdmin) . "'];\n");
        }
        if (!empty($entity->unique)) {
            fwrite($sw, "        \$e->unique = ['" . implode("', '", $entity->unique) . "'];\n");
        }
        if (!empty($entity->uniqueMultiple)) {
            $ums = array_map(fn($g) => "['" . implode("', '", $g) . "']", $entity->uniqueMultiple);
            fwrite($sw, "        \$e->uniqueMultiple = [" . implode(", ", $ums) . "];\n");
        }
        if (!empty($entity->notNull)) {
            fwrite($sw, "        \$e->notNull = ['" . implode("', '", $entity->notNull) . "'];\n");
        }

        // Fields
        if (isset($this->fields[$entityName])) {
            foreach ($this->fields[$entityName] as $fieldName => $field) {
                fwrite($sw, "        \$f = new Field();\n");
                fwrite($sw, "        \$f->entityName = '{$entityName}';\n");
                fwrite($sw, "        \$f->name = '{$fieldName}';\n");
                fwrite($sw, "        \$f->dataType = '{$field->dataType}';\n");
                fwrite($sw, "        \$f->type = '{$field->type}';\n");

                if (!empty($field->defaultValue)) {
                    fwrite($sw, "        \$f->defaultValue = '{$field->defaultValue}';\n");
                }
                if (!empty($field->alias)) {
                    fwrite($sw, "        \$f->alias = '{$field->alias}';\n");
                }
                if (!empty($field->refEntityName)) {
                    fwrite($sw, "        \$f->refEntityName = '{$field->refEntityName}';\n");
                }
                if (!empty($field->refFieldName)) {
                    fwrite($sw, "        \$f->refFieldName = '{$field->refFieldName}';\n");
                }
                if (!empty($field->checks)) {
                    fwrite($sw, "        \$f->checks = [\n");
                    foreach ($field->checks as $k => $v) {
                        fwrite($sw, "            '{$k}' => '{$v}',\n");
                    }
                    fwrite($sw, "        ];\n");
                }
                if (!empty($field->resets)) {
                    fwrite($sw, "        \$f->resets = [\n");
                    foreach ($field->resets as $k => $v) {
                        $vStr = is_bool($v) ? ($v ? 'true' : 'false') : "'{$v}'";
                        fwrite($sw, "            '{$k}' => {$vStr},\n");
                    }
                    fwrite($sw, "        ];\n");
                }

                fwrite($sw, "        \$e->fields['{$fieldName}'] = \$f;\n\n");
            }
        }

        // Relations, tree, oo, om could be added here the same way

        fwrite($sw, "        \$this->entities['{$entityName}'] = \$e;\n\n");
    }

    fwrite($sw, "    }\n");
    fwrite($sw, "}\n");

    fclose($sw);

    if (!is_dir(SCHEMA_BUILDER_CLASS_PATH)) {
        mkdir(SCHEMA_BUILDER_CLASS_PATH, 0755, true);
    }

    $schemaDestinationPath = SCHEMA_BUILDER_CLASS_PATH . DIRECTORY_SEPARATOR . $schemaFileName;
    copy($schemaSourcePath, $schemaDestinationPath);
}


    public function relationsRef(string $entityName): array
    {
        $response = [];

        foreach ($this->entities as $eN => $entity) {
            foreach ($entity->tree as $fid => $et) {
                if ($et->refEntityName === $entityName) {
                    $response[] = $et;
                }
            }
        }

        return $response;
    }

    protected function defineId(EntityMetadata $entity): array
    {
        if (count($entity->pk) == 1) {
            return $entity->pk;
        }

        foreach ($entity->unique as $f) {
            if (in_array($f, $entity->notNull)) {
                return [$f];
            }
        }

        $uniqueMultipleFlag = true;
        foreach ($entity->uniqueMultiple as $um) {
            foreach ($um as $f) {
                if (!in_array($f, $entity->notNull)) {
                    $uniqueMultipleFlag = false;
                    break;
                }
            }
            if ($uniqueMultipleFlag) {
                return $um;
            }
            $uniqueMultipleFlag = true;
        }

        if (count($entity->notNull) > 1) {
            return $entity->notNull;
        }

        return $entity->fields;
    }
}