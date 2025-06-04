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
require_once MAIN_PATH . 'SqlOrganize/Model/Config.php';
require_once MAIN_PATH . 'SqlOrganize/Utils/ValueTypesUtils.php';


use SqlOrganize\Utils\ValueTypesUtils;



abstract class BuildSchema
{
    public Config $config;
    public array $tables = [];
    public array $entities = [];
    public array $fields = [];

 
    /**
     * Definir datos del esquema y arbol de relaciones
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        // Definicion inicial de tables y columns
        $tableAlias = array_merge([], $config->reservedAlias);

        foreach ($this->getTableNames() as $tableName) {
            if (in_array($tableName, $config->reservedEntities)) {
                continue;
            }

            $table = new Table();
            $table->name = $tableName;
            $table->alias = $this->getAlias($tableName, $tableAlias, 4);
            $tableAlias[] = $table->alias;
            $table->columns = $this->getColumns($table->name);

            $fieldsAliases = array_merge([], $config->reservedAlias);
            foreach ($table->columns as $col) {
                if ($col->IS_FOREIGN_KEY == 1 && 
                    !in_array($col->REFERENCED_TABLE_NAME, $config->reservedEntities)) {
                    $idSource = ($config->idName == "field_name") ? 
                        $col->COLUMN_NAME : $col->REFERENCED_TABLE_NAME;
                    $col->alias = $this->getAlias($idSource, $fieldsAliases, 3);
                    $fieldsAliases[] = $col->alias;
                }
                $table->columnNames[] = $col->COLUMN_NAME;

                if ($col->IS_FOREIGN_KEY == 1 && 
                    !in_array($col->REFERENCED_TABLE_NAME, $config->reservedEntities) &&
                    !in_array($col->REFERENCED_TABLE_NAME, $config->dontTreatAsFk)) {
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
            if (in_array($t->name, $this->config->reservedEntities)) {
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
            if (in_array($t->name, $this->config->reservedEntities)) {
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

            $bet = new BuildEntityTree($this->config, $this->entities, $this->fields, $e->name);
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
        
        switch (strtolower($c->DATA_TYPE)) {
             case "tinyint":
                if ($f->maxLength == 1) {
                    $f->type = "bool";
                } else {
                    $f->type = "int";
                }
                break;

            case "smallint":
            case "mediumint":
            case "int":
            case "integer":
            case "bigint":
                $f->type = "int";
                break;
           
            case "float":
            case "double":
            case "decimal":
                $f->type = "float";
                break;
            
            case "char":
            case "varchar":
            case "text":
            case "enum":
            case "set":
            case "blob":
            case "tinyblob":
                $f->type = "string";
                break;

            case "date":
            case "datetime":
            case "timestamp":
            case "time":
            case "year":
                $f->type = "DateTime";
                break;

            case "json":
                $f->type = "array";
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

public function createSchema(): void    
{
    if (!is_dir($this->config->schemaClassPath)) {
        mkdir($this->config->schemaClassPath, 0755, true);
    }

    $schemaFileName = "Schema.php";
    $schemaSourcePath = $this->config->schemaClassPath . DIRECTORY_SEPARATOR . $schemaFileName;
    $sw = fopen($schemaSourcePath, 'w');

    fwrite($sw, "<?php\n\n");
    fwrite($sw, "namespace SqlOrganize\\Sql\\" . $this->config->namespace . ";\n\n");
    fwrite($sw, "require_once MAIN_PATH . 'SqlOrganize/Sql/ISchema.php';\n");
    fwrite($sw, "require_once MAIN_PATH . 'SqlOrganize/Sql/EntityMetadata.php';\n");
    fwrite($sw, "require_once MAIN_PATH . 'SqlOrganize/Sql/Field.php';\n");
    fwrite($sw, "require_once MAIN_PATH . 'SqlOrganize/Sql/EntityTree.php';\n");
    fwrite($sw, "require_once MAIN_PATH . 'SqlOrganize/Sql/EntityRelation.php';\n");
    fwrite($sw, "require_once MAIN_PATH . 'SqlOrganize/Sql/EntityRef.php';\n\n");

    fwrite($sw, "use SqlOrganize\\Sql\\ISchema;\n");
    fwrite($sw, "use SqlOrganize\\Sql\\EntityMetadata;\n");
    fwrite($sw, "use SqlOrganize\\Sql\\Field;\n\n");
    fwrite($sw, "use SqlOrganize\\Sql\\EntityTree;\n");
    fwrite($sw, "use SqlOrganize\\Sql\\EntityRelation;\n");
    fwrite($sw, "use SqlOrganize\\Sql\\EntityRef;\n\n");

    fwrite($sw, "/**\n");
    fwrite($sw, " * Esquema de la base de datos\n");
    fwrite($sw, " * Esta clase fue generada por una herramienta, no debe ser modificada.\n");
    fwrite($sw, " */\n");
    fwrite($sw, "class Schema extends ISchema\n");
    fwrite($sw, "{\n");
    fwrite($sw, "    public function __construct()\n");
    fwrite($sw, "    {\n");

    foreach ($this->entities as $entityName => $entity) {
        fwrite($sw, "        \$this->entities['{$entityName}'] = EntityMetadata::getInstance('{$entityName}', '{$entity->alias}');\n");

        if (!empty($entity->schema)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->schema = '{$entity->schema}';\n");
        }
        if (!empty($entity->pk)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->pk = ['" . implode("', '", $entity->pk) . "'];\n");
        }
        if (!empty($entity->fk)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->fk = ['" . implode("', '", $entity->fk) . "'];\n");
        }
        if (!empty($entity->noAdmin)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->noAdmin = ['" . implode("', '", $entity->noAdmin) . "'];\n");
        }
        if (!empty($entity->unique)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->unique = ['" . implode("', '", $entity->unique) . "'];\n");
        }
        if (!empty($entity->uniqueMultiple)) {
            $ums = array_map(fn($g) => "['" . implode("', '", $g) . "']", $entity->uniqueMultiple);
            fwrite($sw, "        \$this->entities['{$entityName}']->uniqueMultiple = [" . implode(", ", $ums) . "];\n");
        }
        if (!empty($entity->notNull)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->notNull = ['" . implode("', '", $entity->notNull) . "'];\n");
        }

        fwrite($sw, "\n");

        // Tree relationships
        if (!empty($entity->tree)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->tree = [];\n");
            foreach ($entity->tree as $fieldId => $tree) {
                fwrite($sw, "        \$this->entities['{$entityName}']->tree['{$fieldId}'] = EntityTree::getInstance('{$tree->fieldName}', '{$tree->refEntityName}', '{$tree->refFieldName}');\n");
                
                if (!empty($tree->children)) {
                    $this->writeTreeChildren($sw, "        \$this->entities['{$entityName}']->tree['{$fieldId}']", $tree->children);
                }
                fwrite($sw, "\n");
            }
        }

        // Relations
        if (!empty($entity->relations)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->relations = [];\n");
            foreach ($entity->relations as $fieldId => $relation) {
                fwrite($sw, "        \$this->entities['{$entityName}']->relations['{$fieldId}'] = EntityRelation::getInstance('{$relation->fieldName}', '{$relation->refEntityName}', '{$relation->refFieldName}');\n");
                if (!empty($relation->parentId)) {
                    fwrite($sw, "        \$this->entities['{$entityName}']->relations['{$fieldId}']->parentId = '{$relation->parentId}';\n");
                }
                fwrite($sw, "\n");

            }
        }

        // One-to-One relationships (oo)
        if (!empty($entity->oo)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->oo = [];\n");
            foreach ($entity->oo as $id => $rref) {
                fwrite($sw, "        \$this->entities['{$entityName}']->oo['{$id}']  = EntityRef::getInstance('{$rref->fieldName}', '{$rref->entityName}');\n\n");
            }
        }

        // One-to-Many relationships (om)
        if (!empty($entity->om)) {
            fwrite($sw, "        \$this->entities['{$entityName}']->om = [];\n");
            foreach ($entity->om as $id => $rref) {
                fwrite($sw, "        \$this->entities['{$entityName}']->om['{$id}'] = EntityRef::getInstance('{$rref->fieldName}', '{$rref->entityName}');\n");
            }
        }

        // Fields
        if (isset($this->fields[$entityName])) {
            foreach ($this->fields[$entityName] as $fieldName => $field) {
                fwrite($sw, "        \$this->entities['{$entityName}']->fields['{$fieldName}'] = Field::getInstance('{$entityName}', '{$fieldName}', '{$field->dataType}', '{$field->type}');\n");

                if (!empty($field->defaultValue)) {
                    fwrite($sw, "        \$this->entities['{$entityName}']->fields['{$fieldName}']->defaultValue = '{$field->defaultValue}';\n");
                }
                if (!empty($field->alias)) {
                    fwrite($sw, "        \$this->entities['{$entityName}']->fields['{$fieldName}']->alias = '{$field->alias}';\n");
                }
                if (!empty($field->refEntityName)) {
                    fwrite($sw, "        \$this->entities['{$entityName}']->fields['{$fieldName}']->refEntityName = '{$field->refEntityName}';\n");
                }
                if (!empty($field->refFieldName)) {
                    fwrite($sw, "        \$this->entities['{$entityName}']->fields['{$fieldName}']->refFieldName = '{$field->refFieldName}';\n");
                }
                if (!empty($field->checks)) {
                    fwrite($sw, "        \$this->entities['{$entityName}']->fields['{$fieldName}']->checks = [\n");
                    foreach ($field->checks as $k => $v) {
                        fwrite($sw, "            '{$k}' => '{$v}',\n");
                    }
                    fwrite($sw, "        ];\n");
                }
                if (!empty($field->resets)) {
                    fwrite($sw, "        \$this->entities['{$entityName}']->fields['{$fieldName}']->resets = [\n");
                    foreach ($field->resets as $k => $v) {
                        $vStr = is_bool($v) ? ($v ? 'true' : 'false') : "'{$v}'";
                        fwrite($sw, "            '{$k}' => {$vStr},\n");
                    }
                    fwrite($sw, "        ];\n");
                }

            }
        }

    }

    fwrite($sw, "    }\n");
    fwrite($sw, "}\n");

    fclose($sw);

    if (!is_dir($this->config->schemaBuilderClassPath)) {
        mkdir($this->config->schemaBuilderClassPath, 0755, true);
    }

    $schemaDestinationPath = $this->config->schemaBuilderClassPath . DIRECTORY_SEPARATOR . $schemaFileName;
    copy($schemaSourcePath, $schemaDestinationPath);
}

public function writeTreeChildren($sw, $source, $children)
{
    fwrite($sw, $source . "->children = [];\n");
    foreach ($children as $fieldId => $tree) {
        fwrite($sw, $source . "->children['{$fieldId}'] = EntityTree::getInstance('{$tree->fieldName}', '{$tree->refEntityName}', '{$tree->refFieldName}');\n");
        if (!empty($tree->children)) {
            $this->writeTreeChildren($sw, $source . "->children['{$fieldId}']", $tree->children);
        }
        fwrite($sw, "\n");
    }
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