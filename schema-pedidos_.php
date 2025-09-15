<?php

namespace Pedidos;

use Pedidos\SchemaPedidos;

/**
 * Esquema de la base de datos
 * Esta clase fue generada por una herramienta, no debe ser modificada.
 */
class SchemaPedidos_
{
    public static function getEntities()
    {
        $entities = SchemaPedidos::getEntities();
        $entities["tickets"]->fields["is_active"]->defaultValue = 1;
        $entities["tickets"]->fields["customer"]->defaultValue = 450; //corresponde a "sistemas"
        $entities["tickets"]->fields["status"]->defaultValue = 1; //corresponde a "nueva"
        $entities["tickets"]->fields["priority"]->defaultValue = 1; //corresponde a "baja"
        $entities["tickets"]->fields["category"]->defaultValue = 1; //corresponde a "General"
        $entities["tickets"]->fields["assigned_agent"]->defaultValue = ""; //cadena vacia
        $entities["tickets"]->fields["date_created"]->defaultValue = 'current_timestamp()'; 
        $entities["tickets"]->fields["date_updated"]->defaultValue = 'current_timestamp()'; 
        $entities["tickets"]->fields["agent_created"]->defaultValue = 0; 
        $entities["tickets"]->fields["ip_address"]->defaultValue = ""; 
        $entities["tickets"]->fields["source"]->defaultValue = ""; 
        $entities["tickets"]->fields["browser"]->defaultValue = ""; 
        $entities["tickets"]->fields["os"]->defaultValue = ""; 
        $entities["tickets"]->fields["user_type"]->defaultValue = ""; 
        $entities["tickets"]->fields["last_reply_by"]->defaultValue = "450"; 
        $entities["tickets"]->fields["cust_25"]->defaultValue = ""; 
        $entities["tickets"]->fields["cust_26"]->defaultValue = ""; 
        $entities["tickets"]->fields["cust_27"]->defaultValue = ""; 
        $entities["tickets"]->fields["tags"]->defaultValue = ""; 
        $entities["tickets"]->fields["last_reply_source"]->defaultValue = ""; 
        $entities["tickets"]->fields["misc"]->defaultValue = ""; 
        $entities["tickets"]->fields["auth_code"]->defaultValue = "prop"; //definido a traves de propiedad de configuracion 

        $entities["threads"]->fields["is_active"]->defaultValue = 1; 
        $entities["threads"]->fields["customer"]->defaultValue = 1; 
        $entities["threads"]->fields["type"]->defaultValue = "report"; 
        $entities["threads"]->fields["ip_address"]->defaultValue = ""; 
        $entities["threads"]->fields["source"]->defaultValue = ""; 
        $entities["threads"]->fields["os"]->defaultValue = ""; 
        $entities["threads"]->fields["browser"]->defaultValue = ""; 
        $entities["threads"]->fields["date_created"]->defaultValue = "current_timestamp()"; 
        $entities["threads"]->fields["date_updated"]->defaultValue = "current_timestamp()"; 


        foreach ($entities as $entity)
            $entity->fields["id"]->defaultValue = "max";
        
        return $entities;
    }
}
