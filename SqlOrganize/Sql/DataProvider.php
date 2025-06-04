<?php

namespace SqlOrganize\Sql;

use PDO;

use Exception;

class DataProvider {

    protected Db $db;

    public function __construct(Db $db) {
        $this->db = $db;
    }


    public function fetchEntitiesByIds(string $entityName, ...$ids): array {
        $data = $this->fetchTreeByIds($entityName, ...$ids);
        $entityMetadata = $this->db->getEntityMetadata($entityName);

        $className = $this->db->config->namespace."\\".$entityMetadata->getClassName();

        $response = [];
        foreach($data as $d) {
            $obj = new $className;
            $obj->ssetFromTree($d);
            $response[] = $obj;
        }

        return $response;


    }

    /**
     * Devuelve entidades a partir de ids
     */
    public function fetchTreeByIds(string $entityName, ...$ids): array {
        $rawEntities = $this->fetchDataByIds($entityName, ...$ids);

        // Inicializar relaciones en null
        $fieldNamesRel = $this->db->fieldNamesRel($entityName);

        $response = [];

        // Reorganizar en forma de árbol
        foreach ($rawEntities as &$row) {
            $response[] = $this->valuesTree($entityName, $row);
        }

        return $response; // Ya es array asociativo, no se necesita deserializar
    }

    /**
     * Ejecuta SQL y devuelve array asociativo
     */
    public function fetchDataBySql(string $sql, ?array $params = null): array {
        $stmt = $this->db->connection()->prepare($sql);
        $stmt->execute($params ?? []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Consulta por IDs
     */
    public function fetchDataByIds(string $entityName, ...$ids): array {
        $ids = array_unique($ids);
        if (empty($ids)) return [];

        $placeholders = [];
        $params = [];
        foreach ($ids as $index => $id) {
            $param = ":id$index";
            $placeholders[] = $param;
            $params[$param] = $id;
        }

        $entityMetadata = $this->db->GetEntityMetadata($entityName);
        $sql = $this->db->createSelectQueries()->selectAll($entityName);
        $sql .= " WHERE " . $entityMetadata->Pt() . "." . $this->db->config->idName . " IN (" . implode(", ", $placeholders) . ")";

        $stmt = $this->db->connection()->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) !== count($ids)) {
            throw new Exception("La consulta no devolvió todos los registros. ¿Están correctos los IDs?");
        }

        return $rows;
    }

    /**
     * Armar árbol de valores
     */
    protected function valuesTree(string $entityName, array $values): array {
        $response = [];

        foreach ($this->db->fieldNames($entityName) as $fieldName) {
            if (array_key_exists($fieldName, $values)) {
                $response[$fieldName] = $values[$fieldName];
            }
        }

        $this->valuesTreeRecursive($values, $this->db->getEntityMetadata($entityName)->tree, $response);

        return $response;
    }

    protected function valuesTreeRecursive(array $values, array $tree, array &$response): void {
        foreach ($tree as $fieldId => $et) {

            $fieldName = $et->fieldName;
            if (isset($response[$fieldName]) && $response[$fieldName] !== null) {
                $response[$fieldName . '_'] = [];

                foreach ($this->db->fieldNames($et->refEntityName) as $refFieldName) {
                    $compositeKey = $fieldId . $this->db->config->separator . $refFieldName;
                    $response[$fieldName . '_'][$refFieldName] = (isset($values[$compositeKey])) ? $values[$compositeKey] : null;
                }

                if (!empty($et->children)) {
                    $this->valuesTreeRecursive($values, $et->children, $response[$fieldName . '_']);
                }
            }
        }
    }
}
