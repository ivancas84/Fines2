<?php

namespace SqlOrganize\Model;

use SqlOrganize\Model\Config;
use SqlOrganize\Sql\ISchema;
use SqlOrganize\Sql\EntityMetadata;

class BuildClasses
{
    /**
     * Definir datos del esquema y arbol de relaciones
     * 
     * @param Config $config
     * @param array of EntityMetadata $entities
     */
    public static function Build(Config $config, array $entities)
    {
        if (!is_dir($config->dataClassesPath)) {
            mkdir($config->dataClassesPath, 0777, true);
        }

        foreach ($entities as $entityName => $entityMetadata) {

            $filePath = $config->dataClassesPath . $entityMetadata->getClassName() . ".php";
            $file = fopen($filePath, 'w');
            
            fwrite($file, "<?php\n");
            fwrite($file, "\n");
            fwrite($file, "namespace " . $config->namespace . ";\n");
            fwrite($file, "\n");
            fwrite($file, "use SqlOrganize\\Sql\\Entity;\n");
            fwrite($file, "use Exception;\n");
            fwrite($file, "use DateTime;\n\n");
            fwrite($file, "class " . $entityMetadata->getClassName() . " extends Entity\n");
            fwrite($file, "{\n\n");
            // Constructor
            fwrite($file, "    public function __construct()\n");
            fwrite($file, "    {\n");
            fwrite($file, "        \$this->_entityName = \"" . $entityName . "\";\n");
            fwrite($file, "        \$this->_db = " . $config->dbClass . "::getInstance();\n");
            fwrite($file, "        \$this->setDefault();\n");
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

                if (in_array($relation->fieldName, $entityMetadata->unique)) {
                    // Relación one-to-one
                    fwrite($file, "    /** @var " . $entities[$relation->refEntityName]->getClassName() . "|null (fk " . $entityName . "." . $relation->fieldName . " _o:o " . $relation->refEntityName . ".id) */\n");
                    fwrite($file, "    public ?\\" .  $config->namespace . "\\" . $entities[$relation->refEntityName]->getClassName() . "_ \$" . $relation->fieldName . "_ = null;\n");
                    fwrite($file, "\n");
                } else {
                    // Relación many-to-one
                    fwrite($file, "    /** @var " . $entities[$relation->refEntityName]->getClassName() . "|null (fk " . $entityName . "." . $relation->fieldName . " _m:o " . $relation->refEntityName . ".id) */\n");
                    fwrite($file, "    public ?\\" . $config->namespace . "\\" . $entities[$relation->refEntityName]->getClassName() . "_ \$" . $relation->fieldName . "_ = null;\n");
                    fwrite($file, "\n");   
                
                }
            }

            // Relaciones one-to-one inversas
            foreach ($entityMetadata->oo as $id => $rref) {
                fwrite($file, "    /** @var " . $entities[$rref->entityName]->getClassName() . "|null (ref " . $rref->entityName . "." . $rref->fieldName . " _o:o " . $entityName . ".id) */\n");
                fwrite($file, "    public ?\\" .  $config->namespace . "\\" . $entities[$rref->entityName]->getClassName() . "_ \$" . $id . " = null;\n");
                fwrite($file, "\n");
            }

            // Relaciones one-to-many
            foreach ($entityMetadata->om as $id => $rref) {
                fwrite($file, "    /** @var int|null */\n");
                fwrite($file, "    public ?int \$" . $id . "Count = null;\n");
                fwrite($file, "\n");
                fwrite($file, "    /** @var " . $entities[$rref->entityName]->getClassName() . "[] (ref " . $rref->entityName . "." . $rref->fieldName . " _m:o " . $entityName . ".id) */\n");
                fwrite($file, "    public array \$" . $id . " = [];\n");
                fwrite($file, "\n");
            }

            fwrite($file, "}\n");
            fclose($file);

            
            $filePath = $config->dataClassesPath . $entityMetadata->getClassName() . "_.php";
            if (file_exists($filePath)) continue;
            $file = fopen($filePath, 'w');
            fwrite($file, "<?php\n");
            fwrite($file, "\n");
            fwrite($file, "namespace " . $config->namespace . ";\n");           
            fwrite($file, "\n");
            fwrite($file, "use \\" . $config->namespace . "\\" . $entityMetadata->getClassName() . ";\n");           
            fwrite($file, "\n");
            fwrite($file, "use SqlOrganize\\Sql\\Entity;\n");
            fwrite($file, "use Exception;\n");
            fwrite($file, "use DateTime;\n\n");
            fwrite($file, "class " . $entityMetadata->getClassName() . "_ extends " . $entityMetadata->getClassName() . "\n");
            fwrite($file, "{\n\n");
            fwrite($file, "}\n\n");
        }
    }

  
}