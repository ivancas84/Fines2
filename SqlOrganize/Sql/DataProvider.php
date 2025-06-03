<?php

namespace SqlOrganize\Sql;

use PDO;

use Exception;

class DataProvider {

    protected Db $db;

    public function __construct(Db $db) {
        $this->db = $db;
    }

    /**
     * Devuelve entidades a partir de ids
     */
    public function fetchTreeByIds(string $entityName, ...$ids): array {
        $rawEntities = $this->fetchDataByIds($entityName, $ids);
        // Inicializar relaciones en null
        $fieldNamesRel = $this->db->fieldNamesRel($entityName);
        foreach ($rawEntities as &$entity) {
            foreach ($fieldNamesRel as $fieldName) {
                $entity[$fieldName] = null;
            }
        }

        // Reorganizar en forma de árbol
        foreach ($rawEntities as &$entity) {
            $entity = $this->valuesTree($entityName, $entity);
        }

        return $rawEntities; // Ya es array asociativo, no se necesita deserializar
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
    public function fetchDataByIds(string $entityName, array $ids): array {
        $ids = array_unique($ids);
        if (empty($ids)) return [];

        $placeholders = [];
        $params = [];
        foreach ($ids as $index => $id) {
            $param = ":id$index";
            $placeholders[] = $param;
            $params[$param] = $id;
        }

        $sql = $this->db->createSelectQueries()->selectAll($entityName);
        $sql .= " WHERE id IN (" . implode(", ", $placeholders) . ")";

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

        if (isset($values['Label'])) {
            $response['Label'] = $values['Label'];
        }

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

                $labelKey = $fieldId . $this->db->config->separator . 'Label';
                if (isset($values[$labelKey])) {
                    $response[$fieldName . '_']['Label'] = $values[$labelKey];
                }

                foreach ($this->db->fieldNames($et->refEntityName) as $refFieldName) {
                    $compositeKey = $fieldId . $this->db->config->separator . $refFieldName;
                    if (isset($values[$compositeKey])) {
                        $response[$fieldName . '_'][$refFieldName] = $values[$compositeKey];
                    }
                }

                if (!empty($et->children)) {
                    $this->valuesTreeRecursive($values, $et->children, $response[$fieldName . '_']);
                }
            }
        }
    }
}
