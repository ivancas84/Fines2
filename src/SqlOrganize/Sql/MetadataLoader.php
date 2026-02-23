<?php

use SqlOrganize\Sql\EntityMetadata;
use SqlOrganize\Sql\Field;
use SqlOrganize\Sql\EntityTree;
use SqlOrganize\Sql\EntityRelation;
use SqlOrganize\Sql\EntityRef;
use SqlOrganize\Sql\Db;

class MetadataLoader
{
    public static function load(array $schema, Db $db): array
    {
        $entities = [];

        foreach ($schema as $entityName => $meta) {

            $em = EntityMetadata::getInstance(
                $entityName,
                $meta['alias']
            );

            $em->db = $db;

            $em->pk       = $meta['pk'] ?? [];
            $em->fk       = $meta['fk'] ?? [];
            $em->unique   = $meta['unique'] ?? [];
            $em->notNull  = $meta['notNull'] ?? [];

            // -------------------------
            // FIELDS
            // -------------------------
            foreach (($meta['fields'] ?? []) as $fieldName => $f) {

                $field = Field::getInstance(
                    $entityName,
                    $fieldName,
                    $f['dataType'] ?? 'varchar',
                    $f['type'] ?? 'string'
                );

                $field->db            = $db;
                $field->fieldType     = $f['fieldType'] ?? 'nf';
                $field->refEntityName = $f['refEntityName'] ?? null;
                $field->refFieldName  = $f['refFieldName'] ?? 'id';

                $em->fields[$fieldName] = $field;
            }

            // -------------------------
            // TREE
            // -------------------------
            foreach (($meta['tree'] ?? []) as $k => $t) {

                $tree = EntityTree::getInstance(
                    $t['fieldName'],
                    $t['refEntityName'],
                    $t['refFieldName'] ?? 'id'
                );

                $em->tree[$k] = $tree;
            }

            // -------------------------
            // RELATIONS
            // -------------------------
            foreach (($meta['relations'] ?? []) as $k => $r) {

                $rel = EntityRelation::getInstance(
                    $r['fieldName'],
                    $r['refEntityName'],
                    $r['refFieldName'] ?? 'id'
                );

                $rel->parentId = $r['parentId'] ?? null;

                $em->relations[$k] = $rel;
            }

            // -------------------------
            // OO
            // -------------------------
            foreach (($meta['oo'] ?? []) as $k => $r) {
                $em->oo[$k] = EntityRef::getInstance(
                    $r['fieldName'],
                    $r['entityName']
                );
            }

            // -------------------------
            // OM
            // -------------------------
            foreach (($meta['om'] ?? []) as $k => $r) {
                $em->om[$k] = EntityRef::getInstance(
                    $r['fieldName'],
                    $r['entityName']
                );
            }

            $entities[$entityName] = $em;
        }

        return $entities;
    }
}