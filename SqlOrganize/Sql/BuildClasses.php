<?php

namespace SqlOrganize\Sql;

use SqlOrganize\Model\Config;
use SqlOrganize\Sql\ISchema;
use SqlOrganize\Sql\EntityMetadata;

class BuildClasses
{
    /**
     * Definir datos del esquema y arbol de relaciones
     * 
     * @param Config $config
     * @param ISchema|null $schema
     */
    public static function Build(Config $config, ?ISchema $schema = null)
    {
        if (!is_dir($config->dataClassesPath)) {
            mkdir($config->dataClassesPath, 0777, true);
        }

        foreach ($schema->entities as $entityName => $entityMetadata) {

            $filePath = $config->dataClassesPath . $entityMetadata->getClassName() . ".php";
            $file = fopen($filePath, 'w');
            
            fwrite($file, "<?php\n");
            fwrite($file, "\n");
            fwrite($file, "namespace SqlOrganize\\Sql\\" . $config->namespace . ";\n");
            fwrite($file, "\n");
            fwrite($file, "use SqlOrganize\\Sql\\Entity;\n");
            fwrite($file, "use SqlOrganize\\Sql\\Db;\n");
            fwrite($file, "use Exception;\n");
            fwrite($file, "use DateTime;\n\n");
            fwrite($file, "class " . $entityMetadata->getClassName() . " extends Entity\n");
            fwrite($file, "{\n\n");
            // Constructor
            fwrite($file, "    public function __construct(Db \$db)\n");
            fwrite($file, "    {\n");
            fwrite($file, "        \$this->_entityName = \"" . $entityName . "\";\n");
            fwrite($file, "        \$this->_db = \$db;\n");
            fwrite($file, "        \$this->setDefault();\n");
            
            // Inicializar collections para relaciones one-to-many
            foreach ($entityMetadata->om as $id => $rref) {
                fwrite($file, "        \$this->" . $id . " = [];\n");
            }
            fwrite($file, "    }\n\n");

            fwrite($file, "    public function setFromTree(array \$treeData)\n");
            fwrite($file, "    {\n");
            fwrite($file, "    }\n\n");

            // Propiedades de campos
            foreach ($entityMetadata->fields as $fieldName => $field) {
                fwrite($file, "    /** @var " . $field->type . "|null */\n");
                fwrite($file, "    public ?" . $field->type . " \$" . $fieldName . " = null;\n");
                fwrite($file, "\n");
            }

            // Relaciones Foreign Key
            foreach ($entityMetadata->tree as $fieldId => $relation) {
                if (!empty($relation->parentId) || !in_array($relation->fieldName, $entityMetadata->getFieldNames())) {
                    continue;
                }

                $refFieldName = (strpos($relation->fieldName, $relation->refEntityName) !== false) ? "" : $relation->fieldName . "_";

                if (in_array($relation->fieldName, $entityMetadata->unique)) {
                    // Relación one-to-one
                    fwrite($file, "    /** @var " . $schema->entities[$relation->refEntityName]->getClassName() . "|null (fk " . $entityName . "." . $relation->fieldName . " _o:o " . $relation->refEntityName . ".id) */\n");
                    fwrite($file, "    public ?" . $schema->entities[$relation->refEntityName]->getClassName() . " \$" . $relation->fieldName . "_ = null;\n");
                    fwrite($file, "\n");
                } else {
                    // Relación many-to-one
                    fwrite($file, "    /** @var " . $schema->entities[$relation->refEntityName]->getClassName() . "|null (fk " . $entityName . "." . $relation->fieldName . " _m:o " . $relation->refEntityName . ".id) */\n");
                    fwrite($file, "    public ?" . $schema->entities[$relation->refEntityName]->getClassName() . " \$" . $relation->fieldName . "_ = null;\n");
                    fwrite($file, "\n");
                    
                    // Propiedad auxiliar con doble underscore
                    fwrite($file, "    /** @var " . $entityMetadata->fields[$relation->fieldName]->type . "|null */\n");
                    fwrite($file, "    public ?" . $entityMetadata->fields[$relation->fieldName]->type . " \$" . $relation->fieldName . "__ = null;\n");
                    fwrite($file, "\n");
                }
            }

            // Relaciones one-to-one inversas
            foreach ($entityMetadata->oo as $id => $rref) {
                fwrite($file, "    /** @var " . $schema->entities[$rref->entityName]->getClassName() . "|null (ref " . $rref->entityName . "." . $rref->fieldName . " _o:o " . $entityName . ".id) */\n");
                fwrite($file, "    public ?" . $schema->entities[$rref->entityName]->getClassName() . " \$" . $id . " = null;\n");
                fwrite($file, "\n");
            }

            // Relaciones one-to-many
            foreach ($entityMetadata->om as $id => $rref) {
                fwrite($file, "    /** @var int|null */\n");
                fwrite($file, "    public ?int \$" . $id . "Count = null;\n");
                fwrite($file, "\n");
                fwrite($file, "    /** @var " . $schema->entities[$rref->entityName]->getClassName() . "[] (ref " . $rref->entityName . "." . $rref->fieldName . " _m:o " . $entityName . ".id) */\n");
                fwrite($file, "    public array \$" . $id . " = [];\n");
                fwrite($file, "\n");
            }

            fwrite($file, "}\n");
            fclose($file);
        }
    }

  
}