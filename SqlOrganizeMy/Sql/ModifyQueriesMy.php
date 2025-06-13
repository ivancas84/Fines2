<?php
namespace SqlOrganize\Sql;
use SqlOrganize\Utils\ValueTypesUtils;


class ModifyQueriesMy extends ModifyQueries
{
    public function __construct(Db $db)
    {
        parent::__construct($db);
    }

    /**
     * Genera SQL para UPDATE específico de SQL Server
     */
    protected function generateUpdateSql(string $entityName, array $row, string $prefix): string
    {
        $e = $this->db->getEntityMetadata($entityName);

        $sql = "UPDATE {$e->getSchemaNameAlias()} SET\n";
        
        $fieldNames = $this->db->fieldNamesAdmin($entityName);

        foreach ($fieldNames as $fieldName) {
            if (array_key_exists($fieldName, $row)) {
                $sql .= "{$fieldName} = :{$prefix}{$fieldName}, ";
                $this->parameters[$prefix . $fieldName] = $row[$fieldName];
            }
        }
        
        $sql = ValueTypesUtils::removeLastChar($sql, ',');
        $sql .= "\n";
        
        return $sql;
    }
}