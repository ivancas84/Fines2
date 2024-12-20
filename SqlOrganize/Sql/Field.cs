﻿namespace SqlOrganize.Sql
{
    public class Field
    {
        public Db db { get; set; }
        public string name { get; set; }

        public string? alias { get; set; }

        /* 
        nombre de la entidad 
        */
        public string entityName { get; set; }

        /* 
        si es clave foranea: Nombre de la entidad referenciada por la clave foranea 
        */
        public string? refEntityName { get; set; }

        /* 
        si es clave foranea: Nombre del field al que hace referencia de la entidad referenciada
        */
        public string? refFieldName { get; set; } = "id";

        /// <summary>
        /// Tipo de datos del motor
        /// </summary>
        public string dataType { get; set; } = "varchar";

        /// <summary>
        /// Tipo de datos del lenguaje
        /// </summary>
        public string type { get; set; } = "string";

        /* 
        string con el tipo de field
            "pk": Clave primaria
            "nf": Field normal
            "mo": Clave foranea muchos a uno
            "oo": Clave foranea uno a uno
        */

        /// <summary> Valor por defecto </summary>
        public object defaultValue { get; set; }


        /* longitud maxima permitida */
        //protected int? _length;  

        /* valor maximo permitido */
        //protected object? _max;  

        /* valor minimo permitido */
        //protected object? _min;  

        /* lista de valores permitidos */
        //List<object> _values;

        public Dictionary<string, object> checks = new();

        public Dictionary<string, object> resets = new();

        public EntityMetadata Entity() => this.db.Entity(entityName);

        public EntityMetadata RefEntity() => this.db.Entity(refEntityName!);

        public bool IsRequired()
        {
            var entity = db.Entity(entityName);
            return (entity.notNull.Contains(this.name));
        }

        /// <summary>
        /// Permite el Field identificar univocamente a la entidad por sí solo?
        /// </summary>
        /// <returns>true si el field permite identificar univocamente a la entidad por sí solo</returns>
        public bool IsUnique()
        {
            var entity = this.db.Entity(entityName);
            if (entity.unique.Contains(this.name)) return true;
            if (entity.pk.Contains(this.name) && entity.pk.Count == 1) return true;
            return false;
        }


    }
}
