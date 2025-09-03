<?php

namespace Pedidos;

use SqlOrganize\Sql\EntityMetadata;
use SqlOrganize\Sql\Field;

use SqlOrganize\Sql\EntityTree;
use SqlOrganize\Sql\EntityRelation;
use SqlOrganize\Sql\EntityRef;

/**
 * Esquema de la base de datos
 * Esta clase fue generada por una herramienta, no debe ser modificada.
 */
class SchemaPedidos
{
    public static function getEntities()
    {
        $entities = [];
        $entities['wpwt_psmsc_agents'] = EntityMetadata::getInstance('wpwt_psmsc_agents', 'wpwt');
        $entities['wpwt_psmsc_agents']->pk = ['id'];
        $entities['wpwt_psmsc_agents']->fk = ['customer'];
        $entities['wpwt_psmsc_agents']->notNull = ['id', 'is_active', 'is_agentgroup', 'name', 'role'];

        $entities['wpwt_psmsc_agents']->tree = [];
        $entities['wpwt_psmsc_agents']->tree['customer'] = EntityTree::getInstance('customer', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_agents']->relations = [];
        $entities['wpwt_psmsc_agents']->relations['customer'] = EntityRelation::getInstance('customer', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_agents']->fields['customer'] = Field::getInstance('wpwt_psmsc_agents', 'customer', 'bigint', 'int');
        $entities['wpwt_psmsc_agents']->fields['customer']->defaultValue = '0';
        $entities['wpwt_psmsc_agents']->fields['customer']->alias = 'wpw';
        $entities['wpwt_psmsc_agents']->fields['customer']->refEntityName = 'wpwt_psmsc_customers';
        $entities['wpwt_psmsc_agents']->fields['customer']->refFieldName = 'id';
        $entities['wpwt_psmsc_agents']->fields['customer']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_agents']->fields['id'] = Field::getInstance('wpwt_psmsc_agents', 'id', 'bigint', 'int');
        $entities['wpwt_psmsc_agents']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_agents']->fields['is_active'] = Field::getInstance('wpwt_psmsc_agents', 'is_active', 'int', 'int');
        $entities['wpwt_psmsc_agents']->fields['is_active']->defaultValue = '0';
        $entities['wpwt_psmsc_agents']->fields['is_active']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_agents']->fields['is_agentgroup'] = Field::getInstance('wpwt_psmsc_agents', 'is_agentgroup', 'int', 'int');
        $entities['wpwt_psmsc_agents']->fields['is_agentgroup']->defaultValue = '0';
        $entities['wpwt_psmsc_agents']->fields['is_agentgroup']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_agents']->fields['name'] = Field::getInstance('wpwt_psmsc_agents', 'name', 'varchar', 'string');
        $entities['wpwt_psmsc_agents']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_agents']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_agents']->fields['role'] = Field::getInstance('wpwt_psmsc_agents', 'role', 'int', 'int');
        $entities['wpwt_psmsc_agents']->fields['role']->defaultValue = '0';
        $entities['wpwt_psmsc_agents']->fields['role']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_agents']->fields['unresolved_count'] = Field::getInstance('wpwt_psmsc_agents', 'unresolved_count', 'int', 'int');
        $entities['wpwt_psmsc_agents']->fields['unresolved_count']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_agents']->fields['user'] = Field::getInstance('wpwt_psmsc_agents', 'user', 'bigint', 'int');
        $entities['wpwt_psmsc_agents']->fields['user']->defaultValue = '0';
        $entities['wpwt_psmsc_agents']->fields['user']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_agents']->fields['workload'] = Field::getInstance('wpwt_psmsc_agents', 'workload', 'int', 'int');
        $entities['wpwt_psmsc_agents']->fields['workload']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_attachments'] = EntityMetadata::getInstance('wpwt_psmsc_attachments', 'wpw1');
        $entities['wpwt_psmsc_attachments']->pk = ['id'];
        $entities['wpwt_psmsc_attachments']->fk = ['ticket_id'];
        $entities['wpwt_psmsc_attachments']->notNull = ['customer_id', 'date_created', 'file_path', 'id', 'is_active', 'is_image', 'is_uploaded', 'name', 'source', 'source_id', 'ticket_id'];

        $entities['wpwt_psmsc_attachments']->tree = [];
        $entities['wpwt_psmsc_attachments']->tree['ticket_id'] = EntityTree::getInstance('ticket_id', 'wpwt_psmsc_tickets', 'id');
        $entities['wpwt_psmsc_attachments']->tree['ticket_id']->children = [];
        $entities['wpwt_psmsc_attachments']->tree['ticket_id']->children['category'] = EntityTree::getInstance('category', 'wpwt_psmsc_categories', 'id');

        $entities['wpwt_psmsc_attachments']->tree['ticket_id']->children['customer'] = EntityTree::getInstance('customer', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_attachments']->tree['ticket_id']->children['priority'] = EntityTree::getInstance('priority', 'wpwt_psmsc_priorities', 'id');

        $entities['wpwt_psmsc_attachments']->tree['ticket_id']->children['status'] = EntityTree::getInstance('status', 'wpwt_psmsc_statuses', 'id');


        $entities['wpwt_psmsc_attachments']->relations = [];
        $entities['wpwt_psmsc_attachments']->relations['ticket_id'] = EntityRelation::getInstance('ticket_id', 'wpwt_psmsc_tickets', 'id');

        $entities['wpwt_psmsc_attachments']->relations['category'] = EntityRelation::getInstance('category', 'wpwt_psmsc_categories', 'id');
        $entities['wpwt_psmsc_attachments']->relations['category']->parentId = 'ticket_id';

        $entities['wpwt_psmsc_attachments']->relations['customer'] = EntityRelation::getInstance('customer', 'wpwt_psmsc_customers', 'id');
        $entities['wpwt_psmsc_attachments']->relations['customer']->parentId = 'ticket_id';

        $entities['wpwt_psmsc_attachments']->relations['priority'] = EntityRelation::getInstance('priority', 'wpwt_psmsc_priorities', 'id');
        $entities['wpwt_psmsc_attachments']->relations['priority']->parentId = 'ticket_id';

        $entities['wpwt_psmsc_attachments']->relations['status'] = EntityRelation::getInstance('status', 'wpwt_psmsc_statuses', 'id');
        $entities['wpwt_psmsc_attachments']->relations['status']->parentId = 'ticket_id';

        $entities['wpwt_psmsc_attachments']->fields['customer_id'] = Field::getInstance('wpwt_psmsc_attachments', 'customer_id', 'bigint', 'int');
        $entities['wpwt_psmsc_attachments']->fields['customer_id']->defaultValue = '0';
        $entities['wpwt_psmsc_attachments']->fields['customer_id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['date_created'] = Field::getInstance('wpwt_psmsc_attachments', 'date_created', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_attachments']->fields['date_created']->defaultValue = 'current_timestamp()';
        $entities['wpwt_psmsc_attachments']->fields['date_created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['file_path'] = Field::getInstance('wpwt_psmsc_attachments', 'file_path', 'text', 'string');
        $entities['wpwt_psmsc_attachments']->fields['file_path']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['file_path']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_attachments']->fields['id'] = Field::getInstance('wpwt_psmsc_attachments', 'id', 'bigint', 'int');
        $entities['wpwt_psmsc_attachments']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['is_active'] = Field::getInstance('wpwt_psmsc_attachments', 'is_active', 'int', 'int');
        $entities['wpwt_psmsc_attachments']->fields['is_active']->defaultValue = '1';
        $entities['wpwt_psmsc_attachments']->fields['is_active']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['is_image'] = Field::getInstance('wpwt_psmsc_attachments', 'is_image', 'int', 'int');
        $entities['wpwt_psmsc_attachments']->fields['is_image']->defaultValue = '0';
        $entities['wpwt_psmsc_attachments']->fields['is_image']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['is_uploaded'] = Field::getInstance('wpwt_psmsc_attachments', 'is_uploaded', 'int', 'int');
        $entities['wpwt_psmsc_attachments']->fields['is_uploaded']->defaultValue = '0';
        $entities['wpwt_psmsc_attachments']->fields['is_uploaded']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['name'] = Field::getInstance('wpwt_psmsc_attachments', 'name', 'varchar', 'string');
        $entities['wpwt_psmsc_attachments']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_attachments']->fields['source'] = Field::getInstance('wpwt_psmsc_attachments', 'source', 'varchar', 'string');
        $entities['wpwt_psmsc_attachments']->fields['source']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['source']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_attachments']->fields['source_id'] = Field::getInstance('wpwt_psmsc_attachments', 'source_id', 'bigint', 'int');
        $entities['wpwt_psmsc_attachments']->fields['source_id']->defaultValue = '0';
        $entities['wpwt_psmsc_attachments']->fields['source_id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_attachments']->fields['ticket_id'] = Field::getInstance('wpwt_psmsc_attachments', 'ticket_id', 'bigint', 'int');
        $entities['wpwt_psmsc_attachments']->fields['ticket_id']->defaultValue = '0';
        $entities['wpwt_psmsc_attachments']->fields['ticket_id']->alias = 'wpw';
        $entities['wpwt_psmsc_attachments']->fields['ticket_id']->refEntityName = 'wpwt_psmsc_tickets';
        $entities['wpwt_psmsc_attachments']->fields['ticket_id']->refFieldName = 'id';
        $entities['wpwt_psmsc_attachments']->fields['ticket_id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_categories'] = EntityMetadata::getInstance('wpwt_psmsc_categories', 'wpw2');
        $entities['wpwt_psmsc_categories']->pk = ['id'];
        $entities['wpwt_psmsc_categories']->notNull = ['id', 'load_order', 'name'];

        $entities['wpwt_psmsc_categories']->om = [];
        $entities['wpwt_psmsc_categories']->om['WpwtPsmscTickets_category_'] = EntityRef::getInstance('category', 'wpwt_psmsc_tickets');
        $entities['wpwt_psmsc_categories']->fields['id'] = Field::getInstance('wpwt_psmsc_categories', 'id', 'int', 'int');
        $entities['wpwt_psmsc_categories']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_categories']->fields['load_order'] = Field::getInstance('wpwt_psmsc_categories', 'load_order', 'int', 'int');
        $entities['wpwt_psmsc_categories']->fields['load_order']->defaultValue = '1';
        $entities['wpwt_psmsc_categories']->fields['load_order']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_categories']->fields['name'] = Field::getInstance('wpwt_psmsc_categories', 'name', 'varchar', 'string');
        $entities['wpwt_psmsc_categories']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_categories']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_customers'] = EntityMetadata::getInstance('wpwt_psmsc_customers', 'wpw3');
        $entities['wpwt_psmsc_customers']->pk = ['id'];
        $entities['wpwt_psmsc_customers']->notNull = ['email', 'id', 'name', 'ticket_count', 'user'];

        $entities['wpwt_psmsc_customers']->om = [];
        $entities['wpwt_psmsc_customers']->om['WpwtPsmscAgents_customer_'] = EntityRef::getInstance('customer', 'wpwt_psmsc_agents');
        $entities['wpwt_psmsc_customers']->om['WpwtPsmscLogs_modified_by_'] = EntityRef::getInstance('modified_by', 'wpwt_psmsc_logs');
        $entities['wpwt_psmsc_customers']->om['WpwtPsmscThreads_customer_'] = EntityRef::getInstance('customer', 'wpwt_psmsc_threads');
        $entities['wpwt_psmsc_customers']->om['WpwtPsmscTickets_customer_'] = EntityRef::getInstance('customer', 'wpwt_psmsc_tickets');
        $entities['wpwt_psmsc_customers']->fields['email'] = Field::getInstance('wpwt_psmsc_customers', 'email', 'varchar', 'string');
        $entities['wpwt_psmsc_customers']->fields['email']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_customers']->fields['email']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_customers']->fields['id'] = Field::getInstance('wpwt_psmsc_customers', 'id', 'bigint', 'int');
        $entities['wpwt_psmsc_customers']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_customers']->fields['name'] = Field::getInstance('wpwt_psmsc_customers', 'name', 'varchar', 'string');
        $entities['wpwt_psmsc_customers']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_customers']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_customers']->fields['ticket_count'] = Field::getInstance('wpwt_psmsc_customers', 'ticket_count', 'int', 'int');
        $entities['wpwt_psmsc_customers']->fields['ticket_count']->defaultValue = '0';
        $entities['wpwt_psmsc_customers']->fields['ticket_count']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_customers']->fields['user'] = Field::getInstance('wpwt_psmsc_customers', 'user', 'bigint', 'int');
        $entities['wpwt_psmsc_customers']->fields['user']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_custom_fields'] = EntityMetadata::getInstance('wpwt_psmsc_custom_fields', 'wpw4');
        $entities['wpwt_psmsc_custom_fields']->pk = ['id'];
        $entities['wpwt_psmsc_custom_fields']->notNull = ['id', 'is_personal_info', 'load_order', 'name', 'tl_width'];

        $entities['wpwt_psmsc_custom_fields']->fields['allow_my_profile'] = Field::getInstance('wpwt_psmsc_custom_fields', 'allow_my_profile', 'int', 'int');
        $entities['wpwt_psmsc_custom_fields']->fields['allow_my_profile']->defaultValue = '1';
        $entities['wpwt_psmsc_custom_fields']->fields['allow_my_profile']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['allow_ticket_form'] = Field::getInstance('wpwt_psmsc_custom_fields', 'allow_ticket_form', 'int', 'int');
        $entities['wpwt_psmsc_custom_fields']->fields['allow_ticket_form']->defaultValue = '1';
        $entities['wpwt_psmsc_custom_fields']->fields['allow_ticket_form']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['char_limit'] = Field::getInstance('wpwt_psmsc_custom_fields', 'char_limit', 'int', 'int');
        $entities['wpwt_psmsc_custom_fields']->fields['char_limit']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['date_display_as'] = Field::getInstance('wpwt_psmsc_custom_fields', 'date_display_as', 'varchar', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['date_display_as']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['date_display_as']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['date_format'] = Field::getInstance('wpwt_psmsc_custom_fields', 'date_format', 'varchar', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['date_format']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['date_format']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['date_range'] = Field::getInstance('wpwt_psmsc_custom_fields', 'date_range', 'varchar', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['date_range']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['date_range']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['default_value'] = Field::getInstance('wpwt_psmsc_custom_fields', 'default_value', 'text', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['default_value']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['default_value']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['end_range'] = Field::getInstance('wpwt_psmsc_custom_fields', 'end_range', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_custom_fields']->fields['end_range']->checks = [
            'type' => 'DateTime',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['extra_info'] = Field::getInstance('wpwt_psmsc_custom_fields', 'extra_info', 'text', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['extra_info']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['extra_info']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['field'] = Field::getInstance('wpwt_psmsc_custom_fields', 'field', 'varchar', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['field']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['field']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['id'] = Field::getInstance('wpwt_psmsc_custom_fields', 'id', 'int', 'int');
        $entities['wpwt_psmsc_custom_fields']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['is_auto_fill'] = Field::getInstance('wpwt_psmsc_custom_fields', 'is_auto_fill', 'int', 'int');
        $entities['wpwt_psmsc_custom_fields']->fields['is_auto_fill']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['is_personal_info'] = Field::getInstance('wpwt_psmsc_custom_fields', 'is_personal_info', 'int', 'int');
        $entities['wpwt_psmsc_custom_fields']->fields['is_personal_info']->defaultValue = '0';
        $entities['wpwt_psmsc_custom_fields']->fields['is_personal_info']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['load_order'] = Field::getInstance('wpwt_psmsc_custom_fields', 'load_order', 'int', 'int');
        $entities['wpwt_psmsc_custom_fields']->fields['load_order']->defaultValue = '1';
        $entities['wpwt_psmsc_custom_fields']->fields['load_order']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['name'] = Field::getInstance('wpwt_psmsc_custom_fields', 'name', 'varchar', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['number_type'] = Field::getInstance('wpwt_psmsc_custom_fields', 'number_type', 'varchar', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['number_type']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['number_type']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['placeholder_text'] = Field::getInstance('wpwt_psmsc_custom_fields', 'placeholder_text', 'text', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['placeholder_text']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['placeholder_text']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['slug'] = Field::getInstance('wpwt_psmsc_custom_fields', 'slug', 'varchar', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['slug']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['slug']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['start_range'] = Field::getInstance('wpwt_psmsc_custom_fields', 'start_range', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_custom_fields']->fields['start_range']->checks = [
            'type' => 'DateTime',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['time_format'] = Field::getInstance('wpwt_psmsc_custom_fields', 'time_format', 'int', 'int');
        $entities['wpwt_psmsc_custom_fields']->fields['time_format']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['tl_width'] = Field::getInstance('wpwt_psmsc_custom_fields', 'tl_width', 'int', 'int');
        $entities['wpwt_psmsc_custom_fields']->fields['tl_width']->defaultValue = '100';
        $entities['wpwt_psmsc_custom_fields']->fields['tl_width']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['type'] = Field::getInstance('wpwt_psmsc_custom_fields', 'type', 'varchar', 'string');
        $entities['wpwt_psmsc_custom_fields']->fields['type']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_custom_fields']->fields['type']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_logs'] = EntityMetadata::getInstance('wpwt_psmsc_logs', 'wpw5');
        $entities['wpwt_psmsc_logs']->pk = ['id'];
        $entities['wpwt_psmsc_logs']->fk = ['modified_by'];
        $entities['wpwt_psmsc_logs']->notNull = ['body', 'date_created', 'id', 'modified_by', 'ref_id', 'type'];

        $entities['wpwt_psmsc_logs']->tree = [];
        $entities['wpwt_psmsc_logs']->tree['modified_by'] = EntityTree::getInstance('modified_by', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_logs']->relations = [];
        $entities['wpwt_psmsc_logs']->relations['modified_by'] = EntityRelation::getInstance('modified_by', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_logs']->fields['body'] = Field::getInstance('wpwt_psmsc_logs', 'body', 'longtext', 'longtext');
        $entities['wpwt_psmsc_logs']->fields['body']->checks = [
            'type' => 'longtext',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_logs']->fields['date_created'] = Field::getInstance('wpwt_psmsc_logs', 'date_created', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_logs']->fields['date_created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_logs']->fields['id'] = Field::getInstance('wpwt_psmsc_logs', 'id', 'bigint', 'int');
        $entities['wpwt_psmsc_logs']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_logs']->fields['modified_by'] = Field::getInstance('wpwt_psmsc_logs', 'modified_by', 'bigint', 'int');
        $entities['wpwt_psmsc_logs']->fields['modified_by']->alias = 'wpw';
        $entities['wpwt_psmsc_logs']->fields['modified_by']->refEntityName = 'wpwt_psmsc_customers';
        $entities['wpwt_psmsc_logs']->fields['modified_by']->refFieldName = 'id';
        $entities['wpwt_psmsc_logs']->fields['modified_by']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_logs']->fields['ref_id'] = Field::getInstance('wpwt_psmsc_logs', 'ref_id', 'bigint', 'int');
        $entities['wpwt_psmsc_logs']->fields['ref_id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_logs']->fields['type'] = Field::getInstance('wpwt_psmsc_logs', 'type', 'varchar', 'string');
        $entities['wpwt_psmsc_logs']->fields['type']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_logs']->fields['type']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_priorities'] = EntityMetadata::getInstance('wpwt_psmsc_priorities', 'wpw6');
        $entities['wpwt_psmsc_priorities']->pk = ['id'];
        $entities['wpwt_psmsc_priorities']->notNull = ['bg_color', 'color', 'id', 'load_order', 'name'];

        $entities['wpwt_psmsc_priorities']->om = [];
        $entities['wpwt_psmsc_priorities']->om['WpwtPsmscTickets_priority_'] = EntityRef::getInstance('priority', 'wpwt_psmsc_tickets');
        $entities['wpwt_psmsc_priorities']->fields['bg_color'] = Field::getInstance('wpwt_psmsc_priorities', 'bg_color', 'varchar', 'string');
        $entities['wpwt_psmsc_priorities']->fields['bg_color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_priorities']->fields['bg_color']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_priorities']->fields['color'] = Field::getInstance('wpwt_psmsc_priorities', 'color', 'varchar', 'string');
        $entities['wpwt_psmsc_priorities']->fields['color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_priorities']->fields['color']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_priorities']->fields['id'] = Field::getInstance('wpwt_psmsc_priorities', 'id', 'int', 'int');
        $entities['wpwt_psmsc_priorities']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_priorities']->fields['load_order'] = Field::getInstance('wpwt_psmsc_priorities', 'load_order', 'int', 'int');
        $entities['wpwt_psmsc_priorities']->fields['load_order']->defaultValue = '1';
        $entities['wpwt_psmsc_priorities']->fields['load_order']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_priorities']->fields['name'] = Field::getInstance('wpwt_psmsc_priorities', 'name', 'varchar', 'string');
        $entities['wpwt_psmsc_priorities']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_priorities']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_statuses'] = EntityMetadata::getInstance('wpwt_psmsc_statuses', 'wpw7');
        $entities['wpwt_psmsc_statuses']->pk = ['id'];
        $entities['wpwt_psmsc_statuses']->notNull = ['bg_color', 'color', 'id', 'load_order', 'name'];

        $entities['wpwt_psmsc_statuses']->om = [];
        $entities['wpwt_psmsc_statuses']->om['WpwtPsmscTickets_status_'] = EntityRef::getInstance('status', 'wpwt_psmsc_tickets');
        $entities['wpwt_psmsc_statuses']->fields['bg_color'] = Field::getInstance('wpwt_psmsc_statuses', 'bg_color', 'varchar', 'string');
        $entities['wpwt_psmsc_statuses']->fields['bg_color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_statuses']->fields['bg_color']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_statuses']->fields['color'] = Field::getInstance('wpwt_psmsc_statuses', 'color', 'varchar', 'string');
        $entities['wpwt_psmsc_statuses']->fields['color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_statuses']->fields['color']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_statuses']->fields['id'] = Field::getInstance('wpwt_psmsc_statuses', 'id', 'int', 'int');
        $entities['wpwt_psmsc_statuses']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_statuses']->fields['load_order'] = Field::getInstance('wpwt_psmsc_statuses', 'load_order', 'int', 'int');
        $entities['wpwt_psmsc_statuses']->fields['load_order']->defaultValue = '1';
        $entities['wpwt_psmsc_statuses']->fields['load_order']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_statuses']->fields['name'] = Field::getInstance('wpwt_psmsc_statuses', 'name', 'varchar', 'string');
        $entities['wpwt_psmsc_statuses']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_statuses']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_threads'] = EntityMetadata::getInstance('wpwt_psmsc_threads', 'wpw8');
        $entities['wpwt_psmsc_threads']->pk = ['id'];
        $entities['wpwt_psmsc_threads']->fk = ['customer', 'ticket'];
        $entities['wpwt_psmsc_threads']->notNull = ['body', 'date_created', 'date_updated', 'id', 'is_active', 'ticket', 'type'];

        $entities['wpwt_psmsc_threads']->tree = [];
        $entities['wpwt_psmsc_threads']->tree['customer'] = EntityTree::getInstance('customer', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_threads']->tree['ticket'] = EntityTree::getInstance('ticket', 'wpwt_psmsc_tickets', 'id');
        $entities['wpwt_psmsc_threads']->tree['ticket']->children = [];
        $entities['wpwt_psmsc_threads']->tree['ticket']->children['category'] = EntityTree::getInstance('category', 'wpwt_psmsc_categories', 'id');

        $entities['wpwt_psmsc_threads']->tree['ticket']->children['customer_tic'] = EntityTree::getInstance('customer', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_threads']->tree['ticket']->children['priority'] = EntityTree::getInstance('priority', 'wpwt_psmsc_priorities', 'id');

        $entities['wpwt_psmsc_threads']->tree['ticket']->children['status'] = EntityTree::getInstance('status', 'wpwt_psmsc_statuses', 'id');


        $entities['wpwt_psmsc_threads']->relations = [];
        $entities['wpwt_psmsc_threads']->relations['customer'] = EntityRelation::getInstance('customer', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_threads']->relations['ticket'] = EntityRelation::getInstance('ticket', 'wpwt_psmsc_tickets', 'id');

        $entities['wpwt_psmsc_threads']->relations['category'] = EntityRelation::getInstance('category', 'wpwt_psmsc_categories', 'id');
        $entities['wpwt_psmsc_threads']->relations['category']->parentId = 'ticket';

        $entities['wpwt_psmsc_threads']->relations['customer_tic'] = EntityRelation::getInstance('customer', 'wpwt_psmsc_customers', 'id');
        $entities['wpwt_psmsc_threads']->relations['customer_tic']->parentId = 'ticket';

        $entities['wpwt_psmsc_threads']->relations['priority'] = EntityRelation::getInstance('priority', 'wpwt_psmsc_priorities', 'id');
        $entities['wpwt_psmsc_threads']->relations['priority']->parentId = 'ticket';

        $entities['wpwt_psmsc_threads']->relations['status'] = EntityRelation::getInstance('status', 'wpwt_psmsc_statuses', 'id');
        $entities['wpwt_psmsc_threads']->relations['status']->parentId = 'ticket';

        $entities['wpwt_psmsc_threads']->fields['attachments'] = Field::getInstance('wpwt_psmsc_threads', 'attachments', 'text', 'string');
        $entities['wpwt_psmsc_threads']->fields['attachments']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_threads']->fields['attachments']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_threads']->fields['body'] = Field::getInstance('wpwt_psmsc_threads', 'body', 'longtext', 'longtext');
        $entities['wpwt_psmsc_threads']->fields['body']->checks = [
            'type' => 'longtext',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_threads']->fields['browser'] = Field::getInstance('wpwt_psmsc_threads', 'browser', 'varchar', 'string');
        $entities['wpwt_psmsc_threads']->fields['browser']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_threads']->fields['browser']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_threads']->fields['customer'] = Field::getInstance('wpwt_psmsc_threads', 'customer', 'bigint', 'int');
        $entities['wpwt_psmsc_threads']->fields['customer']->alias = 'wpw';
        $entities['wpwt_psmsc_threads']->fields['customer']->refEntityName = 'wpwt_psmsc_customers';
        $entities['wpwt_psmsc_threads']->fields['customer']->refFieldName = 'id';
        $entities['wpwt_psmsc_threads']->fields['customer']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_threads']->fields['date_created'] = Field::getInstance('wpwt_psmsc_threads', 'date_created', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_threads']->fields['date_created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_threads']->fields['date_updated'] = Field::getInstance('wpwt_psmsc_threads', 'date_updated', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_threads']->fields['date_updated']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_threads']->fields['id'] = Field::getInstance('wpwt_psmsc_threads', 'id', 'bigint', 'int');
        $entities['wpwt_psmsc_threads']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_threads']->fields['ip_address'] = Field::getInstance('wpwt_psmsc_threads', 'ip_address', 'varchar', 'string');
        $entities['wpwt_psmsc_threads']->fields['ip_address']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_threads']->fields['ip_address']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_threads']->fields['is_active'] = Field::getInstance('wpwt_psmsc_threads', 'is_active', 'int', 'int');
        $entities['wpwt_psmsc_threads']->fields['is_active']->defaultValue = '1';
        $entities['wpwt_psmsc_threads']->fields['is_active']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_threads']->fields['os'] = Field::getInstance('wpwt_psmsc_threads', 'os', 'varchar', 'string');
        $entities['wpwt_psmsc_threads']->fields['os']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_threads']->fields['os']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_threads']->fields['seen'] = Field::getInstance('wpwt_psmsc_threads', 'seen', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_threads']->fields['seen']->checks = [
            'type' => 'DateTime',
        ];
        $entities['wpwt_psmsc_threads']->fields['source'] = Field::getInstance('wpwt_psmsc_threads', 'source', 'varchar', 'string');
        $entities['wpwt_psmsc_threads']->fields['source']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_threads']->fields['source']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_threads']->fields['ticket'] = Field::getInstance('wpwt_psmsc_threads', 'ticket', 'bigint', 'int');
        $entities['wpwt_psmsc_threads']->fields['ticket']->alias = 'wp1';
        $entities['wpwt_psmsc_threads']->fields['ticket']->refEntityName = 'wpwt_psmsc_tickets';
        $entities['wpwt_psmsc_threads']->fields['ticket']->refFieldName = 'id';
        $entities['wpwt_psmsc_threads']->fields['ticket']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_threads']->fields['type'] = Field::getInstance('wpwt_psmsc_threads', 'type', 'varchar', 'string');
        $entities['wpwt_psmsc_threads']->fields['type']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_threads']->fields['type']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_tickets'] = EntityMetadata::getInstance('wpwt_psmsc_tickets', 'wpw9');
        $entities['wpwt_psmsc_tickets']->pk = ['id'];
        $entities['wpwt_psmsc_tickets']->fk = ['category', 'customer', 'priority', 'status'];
        $entities['wpwt_psmsc_tickets']->notNull = ['category', 'customer', 'date_created', 'date_updated', 'id', 'is_active', 'last_reply_by', 'priority', 'status', 'subject', 'user_type'];

        $entities['wpwt_psmsc_tickets']->tree = [];
        $entities['wpwt_psmsc_tickets']->tree['category'] = EntityTree::getInstance('category', 'wpwt_psmsc_categories', 'id');

        $entities['wpwt_psmsc_tickets']->tree['customer'] = EntityTree::getInstance('customer', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_tickets']->tree['priority'] = EntityTree::getInstance('priority', 'wpwt_psmsc_priorities', 'id');

        $entities['wpwt_psmsc_tickets']->tree['status'] = EntityTree::getInstance('status', 'wpwt_psmsc_statuses', 'id');

        $entities['wpwt_psmsc_tickets']->relations = [];
        $entities['wpwt_psmsc_tickets']->relations['category'] = EntityRelation::getInstance('category', 'wpwt_psmsc_categories', 'id');

        $entities['wpwt_psmsc_tickets']->relations['customer'] = EntityRelation::getInstance('customer', 'wpwt_psmsc_customers', 'id');

        $entities['wpwt_psmsc_tickets']->relations['priority'] = EntityRelation::getInstance('priority', 'wpwt_psmsc_priorities', 'id');

        $entities['wpwt_psmsc_tickets']->relations['status'] = EntityRelation::getInstance('status', 'wpwt_psmsc_statuses', 'id');

        $entities['wpwt_psmsc_tickets']->om = [];
        $entities['wpwt_psmsc_tickets']->om['WpwtPsmscAttachments_ticket_id_'] = EntityRef::getInstance('ticket_id', 'wpwt_psmsc_attachments');
        $entities['wpwt_psmsc_tickets']->om['WpwtPsmscThreads_ticket_'] = EntityRef::getInstance('ticket', 'wpwt_psmsc_threads');
        $entities['wpwt_psmsc_tickets']->fields['add_recipients'] = Field::getInstance('wpwt_psmsc_tickets', 'add_recipients', 'text', 'string');
        $entities['wpwt_psmsc_tickets']->fields['add_recipients']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_tickets']->fields['add_recipients']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['agent_created'] = Field::getInstance('wpwt_psmsc_tickets', 'agent_created', 'int', 'int');
        $entities['wpwt_psmsc_tickets']->fields['agent_created']->checks = [
            'type' => 'int',
        ];
        $entities['wpwt_psmsc_tickets']->fields['assigned_agent'] = Field::getInstance('wpwt_psmsc_tickets', 'assigned_agent', 'text', 'string');
        $entities['wpwt_psmsc_tickets']->fields['assigned_agent']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_tickets']->fields['assigned_agent']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['auth_code'] = Field::getInstance('wpwt_psmsc_tickets', 'auth_code', 'varchar', 'string');
        $entities['wpwt_psmsc_tickets']->fields['auth_code']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_tickets']->fields['auth_code']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['browser'] = Field::getInstance('wpwt_psmsc_tickets', 'browser', 'varchar', 'string');
        $entities['wpwt_psmsc_tickets']->fields['browser']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_tickets']->fields['browser']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['category'] = Field::getInstance('wpwt_psmsc_tickets', 'category', 'int', 'int');
        $entities['wpwt_psmsc_tickets']->fields['category']->alias = 'wpw';
        $entities['wpwt_psmsc_tickets']->fields['category']->refEntityName = 'wpwt_psmsc_categories';
        $entities['wpwt_psmsc_tickets']->fields['category']->refFieldName = 'id';
        $entities['wpwt_psmsc_tickets']->fields['category']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['customer'] = Field::getInstance('wpwt_psmsc_tickets', 'customer', 'bigint', 'int');
        $entities['wpwt_psmsc_tickets']->fields['customer']->alias = 'wp1';
        $entities['wpwt_psmsc_tickets']->fields['customer']->refEntityName = 'wpwt_psmsc_customers';
        $entities['wpwt_psmsc_tickets']->fields['customer']->refFieldName = 'id';
        $entities['wpwt_psmsc_tickets']->fields['customer']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['cust_24'] = Field::getInstance('wpwt_psmsc_tickets', 'cust_24', 'tinytext', 'tinytext');
        $entities['wpwt_psmsc_tickets']->fields['cust_24']->checks = [
            'type' => 'tinytext',
        ];
        $entities['wpwt_psmsc_tickets']->fields['cust_25'] = Field::getInstance('wpwt_psmsc_tickets', 'cust_25', 'tinytext', 'tinytext');
        $entities['wpwt_psmsc_tickets']->fields['cust_25']->checks = [
            'type' => 'tinytext',
        ];
        $entities['wpwt_psmsc_tickets']->fields['cust_26'] = Field::getInstance('wpwt_psmsc_tickets', 'cust_26', 'tinytext', 'tinytext');
        $entities['wpwt_psmsc_tickets']->fields['cust_26']->checks = [
            'type' => 'tinytext',
        ];
        $entities['wpwt_psmsc_tickets']->fields['cust_27'] = Field::getInstance('wpwt_psmsc_tickets', 'cust_27', 'tinytext', 'tinytext');
        $entities['wpwt_psmsc_tickets']->fields['cust_27']->checks = [
            'type' => 'tinytext',
        ];
        $entities['wpwt_psmsc_tickets']->fields['cust_28'] = Field::getInstance('wpwt_psmsc_tickets', 'cust_28', 'tinytext', 'tinytext');
        $entities['wpwt_psmsc_tickets']->fields['cust_28']->checks = [
            'type' => 'tinytext',
        ];
        $entities['wpwt_psmsc_tickets']->fields['date_closed'] = Field::getInstance('wpwt_psmsc_tickets', 'date_closed', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_tickets']->fields['date_closed']->checks = [
            'type' => 'DateTime',
        ];
        $entities['wpwt_psmsc_tickets']->fields['date_created'] = Field::getInstance('wpwt_psmsc_tickets', 'date_created', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_tickets']->fields['date_created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['date_updated'] = Field::getInstance('wpwt_psmsc_tickets', 'date_updated', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_tickets']->fields['date_updated']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['id'] = Field::getInstance('wpwt_psmsc_tickets', 'id', 'bigint', 'int');
        $entities['wpwt_psmsc_tickets']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['ip_address'] = Field::getInstance('wpwt_psmsc_tickets', 'ip_address', 'varchar', 'string');
        $entities['wpwt_psmsc_tickets']->fields['ip_address']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_tickets']->fields['ip_address']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['is_active'] = Field::getInstance('wpwt_psmsc_tickets', 'is_active', 'int', 'int');
        $entities['wpwt_psmsc_tickets']->fields['is_active']->defaultValue = '1';
        $entities['wpwt_psmsc_tickets']->fields['is_active']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['last_reply_by'] = Field::getInstance('wpwt_psmsc_tickets', 'last_reply_by', 'bigint', 'int');
        $entities['wpwt_psmsc_tickets']->fields['last_reply_by']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['last_reply_on'] = Field::getInstance('wpwt_psmsc_tickets', 'last_reply_on', 'datetime', 'DateTime');
        $entities['wpwt_psmsc_tickets']->fields['last_reply_on']->checks = [
            'type' => 'DateTime',
        ];
        $entities['wpwt_psmsc_tickets']->fields['last_reply_source'] = Field::getInstance('wpwt_psmsc_tickets', 'last_reply_source', 'varchar', 'string');
        $entities['wpwt_psmsc_tickets']->fields['last_reply_source']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_tickets']->fields['last_reply_source']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['live_agents'] = Field::getInstance('wpwt_psmsc_tickets', 'live_agents', 'tinytext', 'tinytext');
        $entities['wpwt_psmsc_tickets']->fields['live_agents']->checks = [
            'type' => 'tinytext',
        ];
        $entities['wpwt_psmsc_tickets']->fields['misc'] = Field::getInstance('wpwt_psmsc_tickets', 'misc', 'longtext', 'longtext');
        $entities['wpwt_psmsc_tickets']->fields['misc']->checks = [
            'type' => 'longtext',
        ];
        $entities['wpwt_psmsc_tickets']->fields['os'] = Field::getInstance('wpwt_psmsc_tickets', 'os', 'varchar', 'string');
        $entities['wpwt_psmsc_tickets']->fields['os']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_tickets']->fields['os']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['prev_assignee'] = Field::getInstance('wpwt_psmsc_tickets', 'prev_assignee', 'text', 'string');
        $entities['wpwt_psmsc_tickets']->fields['prev_assignee']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_tickets']->fields['prev_assignee']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['priority'] = Field::getInstance('wpwt_psmsc_tickets', 'priority', 'int', 'int');
        $entities['wpwt_psmsc_tickets']->fields['priority']->alias = 'wp2';
        $entities['wpwt_psmsc_tickets']->fields['priority']->refEntityName = 'wpwt_psmsc_priorities';
        $entities['wpwt_psmsc_tickets']->fields['priority']->refFieldName = 'id';
        $entities['wpwt_psmsc_tickets']->fields['priority']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['source'] = Field::getInstance('wpwt_psmsc_tickets', 'source', 'varchar', 'string');
        $entities['wpwt_psmsc_tickets']->fields['source']->checks = [
            'type' => 'string',
        ];
        $entities['wpwt_psmsc_tickets']->fields['source']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['status'] = Field::getInstance('wpwt_psmsc_tickets', 'status', 'int', 'int');
        $entities['wpwt_psmsc_tickets']->fields['status']->alias = 'wp3';
        $entities['wpwt_psmsc_tickets']->fields['status']->refEntityName = 'wpwt_psmsc_statuses';
        $entities['wpwt_psmsc_tickets']->fields['status']->refFieldName = 'id';
        $entities['wpwt_psmsc_tickets']->fields['status']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['subject'] = Field::getInstance('wpwt_psmsc_tickets', 'subject', 'text', 'string');
        $entities['wpwt_psmsc_tickets']->fields['subject']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['subject']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_tickets']->fields['tags'] = Field::getInstance('wpwt_psmsc_tickets', 'tags', 'tinytext', 'tinytext');
        $entities['wpwt_psmsc_tickets']->fields['tags']->checks = [
            'type' => 'tinytext',
        ];
        $entities['wpwt_psmsc_tickets']->fields['user_type'] = Field::getInstance('wpwt_psmsc_tickets', 'user_type', 'varchar', 'string');
        $entities['wpwt_psmsc_tickets']->fields['user_type']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_tickets']->fields['user_type']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_ticket_tags'] = EntityMetadata::getInstance('wpwt_psmsc_ticket_tags', 'wpw10');
        $entities['wpwt_psmsc_ticket_tags']->pk = ['id'];
        $entities['wpwt_psmsc_ticket_tags']->notNull = ['bg_color', 'color', 'description', 'id', 'name'];

        $entities['wpwt_psmsc_ticket_tags']->fields['bg_color'] = Field::getInstance('wpwt_psmsc_ticket_tags', 'bg_color', 'varchar', 'string');
        $entities['wpwt_psmsc_ticket_tags']->fields['bg_color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_ticket_tags']->fields['bg_color']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_ticket_tags']->fields['color'] = Field::getInstance('wpwt_psmsc_ticket_tags', 'color', 'varchar', 'string');
        $entities['wpwt_psmsc_ticket_tags']->fields['color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_ticket_tags']->fields['color']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        $entities['wpwt_psmsc_ticket_tags']->fields['description'] = Field::getInstance('wpwt_psmsc_ticket_tags', 'description', 'tinytext', 'tinytext');
        $entities['wpwt_psmsc_ticket_tags']->fields['description']->checks = [
            'type' => 'tinytext',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_ticket_tags']->fields['id'] = Field::getInstance('wpwt_psmsc_ticket_tags', 'id', 'int', 'int');
        $entities['wpwt_psmsc_ticket_tags']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_ticket_tags']->fields['name'] = Field::getInstance('wpwt_psmsc_ticket_tags', 'name', 'varchar', 'string');
        $entities['wpwt_psmsc_ticket_tags']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['wpwt_psmsc_ticket_tags']->fields['name']->resets = [
            'trim' => ' ',
            'removeMultipleSpaces' => true,
        ];
        return $entities;
    }
}
