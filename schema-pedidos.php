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
        $entities['agents'] = EntityMetadata::getInstance('agents', 'agen');
        $entities['agents']->pk = ['id'];
        $entities['agents']->fk = ['customer'];
        $entities['agents']->notNull = ['id', 'is_active', 'is_agentgroup', 'name', 'role'];

        $entities['agents']->tree = [];
        $entities['agents']->tree['customer'] = EntityTree::getInstance('customer', 'customers', 'id');

        $entities['agents']->relations = [];
        $entities['agents']->relations['customer'] = EntityRelation::getInstance('customer', 'customers', 'id');

        $entities['agents']->fields['customer'] = Field::getInstance('agents', 'customer', 'bigint', 'int');
        $entities['agents']->fields['customer']->defaultValue = '0';
        $entities['agents']->fields['customer']->alias = 'cus';
        $entities['agents']->fields['customer']->refEntityName = 'customers';
        $entities['agents']->fields['customer']->refFieldName = 'id';
        $entities['agents']->fields['customer']->checks = [
            'type' => 'int',
        ];
        $entities['agents']->fields['id'] = Field::getInstance('agents', 'id', 'bigint', 'int');
        $entities['agents']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['agents']->fields['is_active'] = Field::getInstance('agents', 'is_active', 'int', 'int');
        $entities['agents']->fields['is_active']->defaultValue = '0';
        $entities['agents']->fields['is_active']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['agents']->fields['is_agentgroup'] = Field::getInstance('agents', 'is_agentgroup', 'int', 'int');
        $entities['agents']->fields['is_agentgroup']->defaultValue = '0';
        $entities['agents']->fields['is_agentgroup']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['agents']->fields['name'] = Field::getInstance('agents', 'name', 'varchar', 'string');
        $entities['agents']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['agents']->fields['name']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['agents']->fields['role'] = Field::getInstance('agents', 'role', 'int', 'int');
        $entities['agents']->fields['role']->defaultValue = '0';
        $entities['agents']->fields['role']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['agents']->fields['unresolved_count'] = Field::getInstance('agents', 'unresolved_count', 'int', 'int');
        $entities['agents']->fields['unresolved_count']->checks = [
            'type' => 'int',
        ];
        $entities['agents']->fields['user'] = Field::getInstance('agents', 'user', 'bigint', 'int');
        $entities['agents']->fields['user']->defaultValue = '0';
        $entities['agents']->fields['user']->checks = [
            'type' => 'int',
        ];
        $entities['agents']->fields['workload'] = Field::getInstance('agents', 'workload', 'int', 'int');
        $entities['agents']->fields['workload']->checks = [
            'type' => 'int',
        ];
        $entities['attachments'] = EntityMetadata::getInstance('attachments', 'atta');
        $entities['attachments']->pk = ['id'];
        $entities['attachments']->fk = ['ticket_id'];
        $entities['attachments']->notNull = ['customer_id', 'date_created', 'file_path', 'id', 'is_active', 'is_image', 'is_uploaded', 'name', 'source', 'source_id', 'ticket_id'];

        $entities['attachments']->tree = [];
        $entities['attachments']->tree['ticket_id'] = EntityTree::getInstance('ticket_id', 'tickets', 'id');
        $entities['attachments']->tree['ticket_id']->children = [];
        $entities['attachments']->tree['ticket_id']->children['category'] = EntityTree::getInstance('category', 'categories', 'id');

        $entities['attachments']->tree['ticket_id']->children['customer'] = EntityTree::getInstance('customer', 'customers', 'id');

        $entities['attachments']->tree['ticket_id']->children['priority'] = EntityTree::getInstance('priority', 'priorities', 'id');

        $entities['attachments']->tree['ticket_id']->children['status'] = EntityTree::getInstance('status', 'statuses', 'id');


        $entities['attachments']->relations = [];
        $entities['attachments']->relations['ticket_id'] = EntityRelation::getInstance('ticket_id', 'tickets', 'id');

        $entities['attachments']->relations['category'] = EntityRelation::getInstance('category', 'categories', 'id');
        $entities['attachments']->relations['category']->parentId = 'ticket_id';

        $entities['attachments']->relations['customer'] = EntityRelation::getInstance('customer', 'customers', 'id');
        $entities['attachments']->relations['customer']->parentId = 'ticket_id';

        $entities['attachments']->relations['priority'] = EntityRelation::getInstance('priority', 'priorities', 'id');
        $entities['attachments']->relations['priority']->parentId = 'ticket_id';

        $entities['attachments']->relations['status'] = EntityRelation::getInstance('status', 'statuses', 'id');
        $entities['attachments']->relations['status']->parentId = 'ticket_id';

        $entities['attachments']->fields['customer_id'] = Field::getInstance('attachments', 'customer_id', 'bigint', 'int');
        $entities['attachments']->fields['customer_id']->defaultValue = '0';
        $entities['attachments']->fields['customer_id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['attachments']->fields['date_created'] = Field::getInstance('attachments', 'date_created', 'datetime', 'DateTime');
        $entities['attachments']->fields['date_created']->defaultValue = 'current_timestamp()';
        $entities['attachments']->fields['date_created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['attachments']->fields['file_path'] = Field::getInstance('attachments', 'file_path', 'text', 'string');
        $entities['attachments']->fields['file_path']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['attachments']->fields['file_path']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['attachments']->fields['id'] = Field::getInstance('attachments', 'id', 'bigint', 'int');
        $entities['attachments']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['attachments']->fields['is_active'] = Field::getInstance('attachments', 'is_active', 'int', 'int');
        $entities['attachments']->fields['is_active']->defaultValue = '1';
        $entities['attachments']->fields['is_active']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['attachments']->fields['is_image'] = Field::getInstance('attachments', 'is_image', 'int', 'int');
        $entities['attachments']->fields['is_image']->defaultValue = '0';
        $entities['attachments']->fields['is_image']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['attachments']->fields['is_uploaded'] = Field::getInstance('attachments', 'is_uploaded', 'int', 'int');
        $entities['attachments']->fields['is_uploaded']->defaultValue = '0';
        $entities['attachments']->fields['is_uploaded']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['attachments']->fields['name'] = Field::getInstance('attachments', 'name', 'varchar', 'string');
        $entities['attachments']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['attachments']->fields['name']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['attachments']->fields['source'] = Field::getInstance('attachments', 'source', 'varchar', 'string');
        $entities['attachments']->fields['source']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['attachments']->fields['source']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['attachments']->fields['source_id'] = Field::getInstance('attachments', 'source_id', 'bigint', 'int');
        $entities['attachments']->fields['source_id']->defaultValue = '0';
        $entities['attachments']->fields['source_id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['attachments']->fields['ticket_id'] = Field::getInstance('attachments', 'ticket_id', 'bigint', 'int');
        $entities['attachments']->fields['ticket_id']->defaultValue = '0';
        $entities['attachments']->fields['ticket_id']->alias = 'tic';
        $entities['attachments']->fields['ticket_id']->refEntityName = 'tickets';
        $entities['attachments']->fields['ticket_id']->refFieldName = 'id';
        $entities['attachments']->fields['ticket_id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['categories'] = EntityMetadata::getInstance('categories', 'cate');
        $entities['categories']->pk = ['id'];
        $entities['categories']->notNull = ['id', 'load_order', 'name'];

        $entities['categories']->om = [];
        $entities['categories']->om['Tickets_category_'] = EntityRef::getInstance('category', 'tickets');
        $entities['categories']->fields['id'] = Field::getInstance('categories', 'id', 'int', 'int');
        $entities['categories']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['categories']->fields['load_order'] = Field::getInstance('categories', 'load_order', 'int', 'int');
        $entities['categories']->fields['load_order']->defaultValue = '1';
        $entities['categories']->fields['load_order']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['categories']->fields['name'] = Field::getInstance('categories', 'name', 'varchar', 'string');
        $entities['categories']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['categories']->fields['name']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['customers'] = EntityMetadata::getInstance('customers', 'cust');
        $entities['customers']->pk = ['id'];
        $entities['customers']->notNull = ['email', 'id', 'name', 'ticket_count', 'user'];

        $entities['customers']->om = [];
        $entities['customers']->om['Agents_customer_'] = EntityRef::getInstance('customer', 'agents');
        $entities['customers']->om['Logs_modified_by_'] = EntityRef::getInstance('modified_by', 'logs');
        $entities['customers']->om['Threads_customer_'] = EntityRef::getInstance('customer', 'threads');
        $entities['customers']->om['Tickets_customer_'] = EntityRef::getInstance('customer', 'tickets');
        $entities['customers']->fields['email'] = Field::getInstance('customers', 'email', 'varchar', 'string');
        $entities['customers']->fields['email']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['customers']->fields['email']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['customers']->fields['id'] = Field::getInstance('customers', 'id', 'bigint', 'int');
        $entities['customers']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['customers']->fields['name'] = Field::getInstance('customers', 'name', 'varchar', 'string');
        $entities['customers']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['customers']->fields['name']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['customers']->fields['ticket_count'] = Field::getInstance('customers', 'ticket_count', 'int', 'int');
        $entities['customers']->fields['ticket_count']->defaultValue = '0';
        $entities['customers']->fields['ticket_count']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['customers']->fields['user'] = Field::getInstance('customers', 'user', 'bigint', 'int');
        $entities['customers']->fields['user']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['custom_fields'] = EntityMetadata::getInstance('custom_fields', 'cus1');
        $entities['custom_fields']->pk = ['id'];
        $entities['custom_fields']->notNull = ['id', 'is_personal_info', 'load_order', 'name', 'tl_width'];

        $entities['custom_fields']->fields['allow_my_profile'] = Field::getInstance('custom_fields', 'allow_my_profile', 'int', 'int');
        $entities['custom_fields']->fields['allow_my_profile']->defaultValue = '1';
        $entities['custom_fields']->fields['allow_my_profile']->checks = [
            'type' => 'int',
        ];
        $entities['custom_fields']->fields['allow_ticket_form'] = Field::getInstance('custom_fields', 'allow_ticket_form', 'int', 'int');
        $entities['custom_fields']->fields['allow_ticket_form']->defaultValue = '1';
        $entities['custom_fields']->fields['allow_ticket_form']->checks = [
            'type' => 'int',
        ];
        $entities['custom_fields']->fields['char_limit'] = Field::getInstance('custom_fields', 'char_limit', 'int', 'int');
        $entities['custom_fields']->fields['char_limit']->checks = [
            'type' => 'int',
        ];
        $entities['custom_fields']->fields['date_display_as'] = Field::getInstance('custom_fields', 'date_display_as', 'varchar', 'string');
        $entities['custom_fields']->fields['date_display_as']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['date_display_as']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['custom_fields']->fields['date_format'] = Field::getInstance('custom_fields', 'date_format', 'varchar', 'string');
        $entities['custom_fields']->fields['date_format']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['date_format']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['custom_fields']->fields['date_range'] = Field::getInstance('custom_fields', 'date_range', 'varchar', 'string');
        $entities['custom_fields']->fields['date_range']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['date_range']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['custom_fields']->fields['default_value'] = Field::getInstance('custom_fields', 'default_value', 'text', 'string');
        $entities['custom_fields']->fields['default_value']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['default_value']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['custom_fields']->fields['end_range'] = Field::getInstance('custom_fields', 'end_range', 'datetime', 'DateTime');
        $entities['custom_fields']->fields['end_range']->checks = [
            'type' => 'DateTime',
        ];
        $entities['custom_fields']->fields['extra_info'] = Field::getInstance('custom_fields', 'extra_info', 'text', 'string');
        $entities['custom_fields']->fields['extra_info']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['extra_info']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['custom_fields']->fields['field'] = Field::getInstance('custom_fields', 'field', 'varchar', 'string');
        $entities['custom_fields']->fields['field']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['field']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['custom_fields']->fields['id'] = Field::getInstance('custom_fields', 'id', 'int', 'int');
        $entities['custom_fields']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['custom_fields']->fields['is_auto_fill'] = Field::getInstance('custom_fields', 'is_auto_fill', 'int', 'int');
        $entities['custom_fields']->fields['is_auto_fill']->checks = [
            'type' => 'int',
        ];
        $entities['custom_fields']->fields['is_personal_info'] = Field::getInstance('custom_fields', 'is_personal_info', 'int', 'int');
        $entities['custom_fields']->fields['is_personal_info']->defaultValue = '0';
        $entities['custom_fields']->fields['is_personal_info']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['custom_fields']->fields['load_order'] = Field::getInstance('custom_fields', 'load_order', 'int', 'int');
        $entities['custom_fields']->fields['load_order']->defaultValue = '1';
        $entities['custom_fields']->fields['load_order']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['custom_fields']->fields['name'] = Field::getInstance('custom_fields', 'name', 'varchar', 'string');
        $entities['custom_fields']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['custom_fields']->fields['name']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['custom_fields']->fields['number_type'] = Field::getInstance('custom_fields', 'number_type', 'varchar', 'string');
        $entities['custom_fields']->fields['number_type']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['number_type']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['custom_fields']->fields['placeholder_text'] = Field::getInstance('custom_fields', 'placeholder_text', 'text', 'string');
        $entities['custom_fields']->fields['placeholder_text']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['placeholder_text']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['custom_fields']->fields['slug'] = Field::getInstance('custom_fields', 'slug', 'varchar', 'string');
        $entities['custom_fields']->fields['slug']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['slug']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['custom_fields']->fields['start_range'] = Field::getInstance('custom_fields', 'start_range', 'datetime', 'DateTime');
        $entities['custom_fields']->fields['start_range']->checks = [
            'type' => 'DateTime',
        ];
        $entities['custom_fields']->fields['time_format'] = Field::getInstance('custom_fields', 'time_format', 'int', 'int');
        $entities['custom_fields']->fields['time_format']->checks = [
            'type' => 'int',
        ];
        $entities['custom_fields']->fields['tl_width'] = Field::getInstance('custom_fields', 'tl_width', 'int', 'int');
        $entities['custom_fields']->fields['tl_width']->defaultValue = '100';
        $entities['custom_fields']->fields['tl_width']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['custom_fields']->fields['type'] = Field::getInstance('custom_fields', 'type', 'varchar', 'string');
        $entities['custom_fields']->fields['type']->checks = [
            'type' => 'string',
        ];
        $entities['custom_fields']->fields['type']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['logs'] = EntityMetadata::getInstance('logs', 'logs');
        $entities['logs']->pk = ['id'];
        $entities['logs']->fk = ['modified_by'];
        $entities['logs']->notNull = ['body', 'date_created', 'id', 'modified_by', 'ref_id', 'type'];

        $entities['logs']->tree = [];
        $entities['logs']->tree['modified_by'] = EntityTree::getInstance('modified_by', 'customers', 'id');

        $entities['logs']->relations = [];
        $entities['logs']->relations['modified_by'] = EntityRelation::getInstance('modified_by', 'customers', 'id');

        $entities['logs']->fields['body'] = Field::getInstance('logs', 'body', 'longtext', 'string');
        $entities['logs']->fields['body']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['logs']->fields['date_created'] = Field::getInstance('logs', 'date_created', 'datetime', 'DateTime');
        $entities['logs']->fields['date_created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['logs']->fields['id'] = Field::getInstance('logs', 'id', 'bigint', 'int');
        $entities['logs']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['logs']->fields['modified_by'] = Field::getInstance('logs', 'modified_by', 'bigint', 'int');
        $entities['logs']->fields['modified_by']->alias = 'mod';
        $entities['logs']->fields['modified_by']->refEntityName = 'customers';
        $entities['logs']->fields['modified_by']->refFieldName = 'id';
        $entities['logs']->fields['modified_by']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['logs']->fields['ref_id'] = Field::getInstance('logs', 'ref_id', 'bigint', 'int');
        $entities['logs']->fields['ref_id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['logs']->fields['type'] = Field::getInstance('logs', 'type', 'varchar', 'string');
        $entities['logs']->fields['type']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['logs']->fields['type']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['priorities'] = EntityMetadata::getInstance('priorities', 'prio');
        $entities['priorities']->pk = ['id'];
        $entities['priorities']->notNull = ['bg_color', 'color', 'id', 'load_order', 'name'];

        $entities['priorities']->om = [];
        $entities['priorities']->om['Tickets_priority_'] = EntityRef::getInstance('priority', 'tickets');
        $entities['priorities']->fields['bg_color'] = Field::getInstance('priorities', 'bg_color', 'varchar', 'string');
        $entities['priorities']->fields['bg_color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['priorities']->fields['bg_color']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['priorities']->fields['color'] = Field::getInstance('priorities', 'color', 'varchar', 'string');
        $entities['priorities']->fields['color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['priorities']->fields['color']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['priorities']->fields['id'] = Field::getInstance('priorities', 'id', 'int', 'int');
        $entities['priorities']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['priorities']->fields['load_order'] = Field::getInstance('priorities', 'load_order', 'int', 'int');
        $entities['priorities']->fields['load_order']->defaultValue = '1';
        $entities['priorities']->fields['load_order']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['priorities']->fields['name'] = Field::getInstance('priorities', 'name', 'varchar', 'string');
        $entities['priorities']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['priorities']->fields['name']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['statuses'] = EntityMetadata::getInstance('statuses', 'stat');
        $entities['statuses']->pk = ['id'];
        $entities['statuses']->notNull = ['bg_color', 'color', 'id', 'load_order', 'name'];

        $entities['statuses']->om = [];
        $entities['statuses']->om['Tickets_status_'] = EntityRef::getInstance('status', 'tickets');
        $entities['statuses']->fields['bg_color'] = Field::getInstance('statuses', 'bg_color', 'varchar', 'string');
        $entities['statuses']->fields['bg_color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['statuses']->fields['bg_color']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['statuses']->fields['color'] = Field::getInstance('statuses', 'color', 'varchar', 'string');
        $entities['statuses']->fields['color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['statuses']->fields['color']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['statuses']->fields['id'] = Field::getInstance('statuses', 'id', 'int', 'int');
        $entities['statuses']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['statuses']->fields['load_order'] = Field::getInstance('statuses', 'load_order', 'int', 'int');
        $entities['statuses']->fields['load_order']->defaultValue = '1';
        $entities['statuses']->fields['load_order']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['statuses']->fields['name'] = Field::getInstance('statuses', 'name', 'varchar', 'string');
        $entities['statuses']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['statuses']->fields['name']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['threads'] = EntityMetadata::getInstance('threads', 'thre');
        $entities['threads']->pk = ['id'];
        $entities['threads']->fk = ['customer', 'ticket'];
        $entities['threads']->notNull = ['body', 'date_created', 'date_updated', 'id', 'is_active', 'ticket', 'type'];

        $entities['threads']->tree = [];
        $entities['threads']->tree['customer'] = EntityTree::getInstance('customer', 'customers', 'id');

        $entities['threads']->tree['ticket'] = EntityTree::getInstance('ticket', 'tickets', 'id');
        $entities['threads']->tree['ticket']->children = [];
        $entities['threads']->tree['ticket']->children['category'] = EntityTree::getInstance('category', 'categories', 'id');

        $entities['threads']->tree['ticket']->children['customer_tic'] = EntityTree::getInstance('customer', 'customers', 'id');

        $entities['threads']->tree['ticket']->children['priority'] = EntityTree::getInstance('priority', 'priorities', 'id');

        $entities['threads']->tree['ticket']->children['status'] = EntityTree::getInstance('status', 'statuses', 'id');


        $entities['threads']->relations = [];
        $entities['threads']->relations['customer'] = EntityRelation::getInstance('customer', 'customers', 'id');

        $entities['threads']->relations['ticket'] = EntityRelation::getInstance('ticket', 'tickets', 'id');

        $entities['threads']->relations['category'] = EntityRelation::getInstance('category', 'categories', 'id');
        $entities['threads']->relations['category']->parentId = 'ticket';

        $entities['threads']->relations['customer_tic'] = EntityRelation::getInstance('customer', 'customers', 'id');
        $entities['threads']->relations['customer_tic']->parentId = 'ticket';

        $entities['threads']->relations['priority'] = EntityRelation::getInstance('priority', 'priorities', 'id');
        $entities['threads']->relations['priority']->parentId = 'ticket';

        $entities['threads']->relations['status'] = EntityRelation::getInstance('status', 'statuses', 'id');
        $entities['threads']->relations['status']->parentId = 'ticket';

        $entities['threads']->fields['attachments'] = Field::getInstance('threads', 'attachments', 'text', 'string');
        $entities['threads']->fields['attachments']->checks = [
            'type' => 'string',
        ];
        $entities['threads']->fields['attachments']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['threads']->fields['body'] = Field::getInstance('threads', 'body', 'longtext', 'string');
        $entities['threads']->fields['body']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['threads']->fields['browser'] = Field::getInstance('threads', 'browser', 'varchar', 'string');
        $entities['threads']->fields['browser']->checks = [
            'type' => 'string',
        ];
        $entities['threads']->fields['browser']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['threads']->fields['customer'] = Field::getInstance('threads', 'customer', 'bigint', 'int');
        $entities['threads']->fields['customer']->alias = 'cus';
        $entities['threads']->fields['customer']->refEntityName = 'customers';
        $entities['threads']->fields['customer']->refFieldName = 'id';
        $entities['threads']->fields['customer']->checks = [
            'type' => 'int',
        ];
        $entities['threads']->fields['date_created'] = Field::getInstance('threads', 'date_created', 'datetime', 'DateTime');
        $entities['threads']->fields['date_created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['threads']->fields['date_updated'] = Field::getInstance('threads', 'date_updated', 'datetime', 'DateTime');
        $entities['threads']->fields['date_updated']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['threads']->fields['id'] = Field::getInstance('threads', 'id', 'bigint', 'int');
        $entities['threads']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['threads']->fields['ip_address'] = Field::getInstance('threads', 'ip_address', 'varchar', 'string');
        $entities['threads']->fields['ip_address']->checks = [
            'type' => 'string',
        ];
        $entities['threads']->fields['ip_address']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['threads']->fields['is_active'] = Field::getInstance('threads', 'is_active', 'int', 'int');
        $entities['threads']->fields['is_active']->defaultValue = '1';
        $entities['threads']->fields['is_active']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['threads']->fields['os'] = Field::getInstance('threads', 'os', 'varchar', 'string');
        $entities['threads']->fields['os']->checks = [
            'type' => 'string',
        ];
        $entities['threads']->fields['os']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['threads']->fields['seen'] = Field::getInstance('threads', 'seen', 'datetime', 'DateTime');
        $entities['threads']->fields['seen']->checks = [
            'type' => 'DateTime',
        ];
        $entities['threads']->fields['source'] = Field::getInstance('threads', 'source', 'varchar', 'string');
        $entities['threads']->fields['source']->checks = [
            'type' => 'string',
        ];
        $entities['threads']->fields['source']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['threads']->fields['ticket'] = Field::getInstance('threads', 'ticket', 'bigint', 'int');
        $entities['threads']->fields['ticket']->alias = 'tic';
        $entities['threads']->fields['ticket']->refEntityName = 'tickets';
        $entities['threads']->fields['ticket']->refFieldName = 'id';
        $entities['threads']->fields['ticket']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['threads']->fields['type'] = Field::getInstance('threads', 'type', 'varchar', 'string');
        $entities['threads']->fields['type']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['threads']->fields['type']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['tickets'] = EntityMetadata::getInstance('tickets', 'tick');
        $entities['tickets']->pk = ['id'];
        $entities['tickets']->fk = ['category', 'customer', 'priority', 'status'];
        $entities['tickets']->notNull = ['category', 'customer', 'date_created', 'date_updated', 'id', 'is_active', 'last_reply_by', 'priority', 'status', 'subject', 'user_type'];

        $entities['tickets']->tree = [];
        $entities['tickets']->tree['category'] = EntityTree::getInstance('category', 'categories', 'id');

        $entities['tickets']->tree['customer'] = EntityTree::getInstance('customer', 'customers', 'id');

        $entities['tickets']->tree['priority'] = EntityTree::getInstance('priority', 'priorities', 'id');

        $entities['tickets']->tree['status'] = EntityTree::getInstance('status', 'statuses', 'id');

        $entities['tickets']->relations = [];
        $entities['tickets']->relations['category'] = EntityRelation::getInstance('category', 'categories', 'id');

        $entities['tickets']->relations['customer'] = EntityRelation::getInstance('customer', 'customers', 'id');

        $entities['tickets']->relations['priority'] = EntityRelation::getInstance('priority', 'priorities', 'id');

        $entities['tickets']->relations['status'] = EntityRelation::getInstance('status', 'statuses', 'id');

        $entities['tickets']->om = [];
        $entities['tickets']->om['Attachments_ticket_id_'] = EntityRef::getInstance('ticket_id', 'attachments');
        $entities['tickets']->om['Threads_ticket_'] = EntityRef::getInstance('ticket', 'threads');
        $entities['tickets']->fields['add_recipients'] = Field::getInstance('tickets', 'add_recipients', 'text', 'string');
        $entities['tickets']->fields['add_recipients']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['add_recipients']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tickets']->fields['agent_created'] = Field::getInstance('tickets', 'agent_created', 'int', 'int');
        $entities['tickets']->fields['agent_created']->checks = [
            'type' => 'int',
        ];
        $entities['tickets']->fields['assigned_agent'] = Field::getInstance('tickets', 'assigned_agent', 'text', 'string');
        $entities['tickets']->fields['assigned_agent']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['assigned_agent']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tickets']->fields['auth_code'] = Field::getInstance('tickets', 'auth_code', 'varchar', 'string');
        $entities['tickets']->fields['auth_code']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['auth_code']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tickets']->fields['browser'] = Field::getInstance('tickets', 'browser', 'varchar', 'string');
        $entities['tickets']->fields['browser']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['browser']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tickets']->fields['category'] = Field::getInstance('tickets', 'category', 'int', 'int');
        $entities['tickets']->fields['category']->alias = 'cat';
        $entities['tickets']->fields['category']->refEntityName = 'categories';
        $entities['tickets']->fields['category']->refFieldName = 'id';
        $entities['tickets']->fields['category']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['tickets']->fields['customer'] = Field::getInstance('tickets', 'customer', 'bigint', 'int');
        $entities['tickets']->fields['customer']->alias = 'cus';
        $entities['tickets']->fields['customer']->refEntityName = 'customers';
        $entities['tickets']->fields['customer']->refFieldName = 'id';
        $entities['tickets']->fields['customer']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['tickets']->fields['cust_24'] = Field::getInstance('tickets', 'cust_24', 'tinytext', 'string');
        $entities['tickets']->fields['cust_24']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['cust_25'] = Field::getInstance('tickets', 'cust_25', 'tinytext', 'string');
        $entities['tickets']->fields['cust_25']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['cust_26'] = Field::getInstance('tickets', 'cust_26', 'tinytext', 'string');
        $entities['tickets']->fields['cust_26']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['cust_27'] = Field::getInstance('tickets', 'cust_27', 'tinytext', 'string');
        $entities['tickets']->fields['cust_27']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['cust_28'] = Field::getInstance('tickets', 'cust_28', 'tinytext', 'string');
        $entities['tickets']->fields['cust_28']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['date_closed'] = Field::getInstance('tickets', 'date_closed', 'datetime', 'DateTime');
        $entities['tickets']->fields['date_closed']->checks = [
            'type' => 'DateTime',
        ];
        $entities['tickets']->fields['date_created'] = Field::getInstance('tickets', 'date_created', 'datetime', 'DateTime');
        $entities['tickets']->fields['date_created']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['tickets']->fields['date_updated'] = Field::getInstance('tickets', 'date_updated', 'datetime', 'DateTime');
        $entities['tickets']->fields['date_updated']->checks = [
            'type' => 'DateTime',
            'required' => '1',
        ];
        $entities['tickets']->fields['id'] = Field::getInstance('tickets', 'id', 'bigint', 'int');
        $entities['tickets']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['tickets']->fields['ip_address'] = Field::getInstance('tickets', 'ip_address', 'varchar', 'string');
        $entities['tickets']->fields['ip_address']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['ip_address']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tickets']->fields['is_active'] = Field::getInstance('tickets', 'is_active', 'int', 'int');
        $entities['tickets']->fields['is_active']->defaultValue = '1';
        $entities['tickets']->fields['is_active']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['tickets']->fields['last_reply_by'] = Field::getInstance('tickets', 'last_reply_by', 'bigint', 'int');
        $entities['tickets']->fields['last_reply_by']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['tickets']->fields['last_reply_on'] = Field::getInstance('tickets', 'last_reply_on', 'datetime', 'DateTime');
        $entities['tickets']->fields['last_reply_on']->checks = [
            'type' => 'DateTime',
        ];
        $entities['tickets']->fields['last_reply_source'] = Field::getInstance('tickets', 'last_reply_source', 'varchar', 'string');
        $entities['tickets']->fields['last_reply_source']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['last_reply_source']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tickets']->fields['live_agents'] = Field::getInstance('tickets', 'live_agents', 'tinytext', 'string');
        $entities['tickets']->fields['live_agents']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['misc'] = Field::getInstance('tickets', 'misc', 'longtext', 'string');
        $entities['tickets']->fields['misc']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['os'] = Field::getInstance('tickets', 'os', 'varchar', 'string');
        $entities['tickets']->fields['os']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['os']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tickets']->fields['prev_assignee'] = Field::getInstance('tickets', 'prev_assignee', 'text', 'string');
        $entities['tickets']->fields['prev_assignee']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['prev_assignee']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tickets']->fields['priority'] = Field::getInstance('tickets', 'priority', 'int', 'int');
        $entities['tickets']->fields['priority']->alias = 'pri';
        $entities['tickets']->fields['priority']->refEntityName = 'priorities';
        $entities['tickets']->fields['priority']->refFieldName = 'id';
        $entities['tickets']->fields['priority']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['tickets']->fields['source'] = Field::getInstance('tickets', 'source', 'varchar', 'string');
        $entities['tickets']->fields['source']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['source']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
            'nullIfEmpty' => true,
        ];
        $entities['tickets']->fields['status'] = Field::getInstance('tickets', 'status', 'int', 'int');
        $entities['tickets']->fields['status']->alias = 'sta';
        $entities['tickets']->fields['status']->refEntityName = 'statuses';
        $entities['tickets']->fields['status']->refFieldName = 'id';
        $entities['tickets']->fields['status']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['tickets']->fields['subject'] = Field::getInstance('tickets', 'subject', 'text', 'string');
        $entities['tickets']->fields['subject']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['tickets']->fields['subject']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['tickets']->fields['tags'] = Field::getInstance('tickets', 'tags', 'tinytext', 'string');
        $entities['tickets']->fields['tags']->checks = [
            'type' => 'string',
        ];
        $entities['tickets']->fields['user_type'] = Field::getInstance('tickets', 'user_type', 'varchar', 'string');
        $entities['tickets']->fields['user_type']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['tickets']->fields['user_type']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['ticket_tags'] = EntityMetadata::getInstance('ticket_tags', 'tic1');
        $entities['ticket_tags']->pk = ['id'];
        $entities['ticket_tags']->notNull = ['bg_color', 'color', 'description', 'id', 'name'];

        $entities['ticket_tags']->fields['bg_color'] = Field::getInstance('ticket_tags', 'bg_color', 'varchar', 'string');
        $entities['ticket_tags']->fields['bg_color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['ticket_tags']->fields['bg_color']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['ticket_tags']->fields['color'] = Field::getInstance('ticket_tags', 'color', 'varchar', 'string');
        $entities['ticket_tags']->fields['color']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['ticket_tags']->fields['color']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        $entities['ticket_tags']->fields['description'] = Field::getInstance('ticket_tags', 'description', 'tinytext', 'string');
        $entities['ticket_tags']->fields['description']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['ticket_tags']->fields['id'] = Field::getInstance('ticket_tags', 'id', 'int', 'int');
        $entities['ticket_tags']->fields['id']->checks = [
            'type' => 'int',
            'required' => '1',
        ];
        $entities['ticket_tags']->fields['name'] = Field::getInstance('ticket_tags', 'name', 'varchar', 'string');
        $entities['ticket_tags']->fields['name']->checks = [
            'type' => 'string',
            'required' => '1',
        ];
        $entities['ticket_tags']->fields['name']->resets = [
            'trim' => ' ',
            'normalizeSpaces' => true,
        ];
        return $entities;
    }
}
