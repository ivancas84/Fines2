﻿using Microsoft.Extensions.Caching.Memory;

namespace SqlOrganize.Sql
{
    
    /// <summary>
    /// Contenedor principal de SqlOrganize
    /// </summary>
    /// <remarks>
    /// Db utiliza y es utilizado como herramienta en varios patrones de diseño: AbstractFactory, AbstractCreator, AbstractBuilder, Singleton.<br/>
    /// Una implementación de Db para un determinado motor de base de datos, sera el ConcreteFactory (Ej DbMy extends Db).<br/>
    /// Una implementación de Db para una determinada App sera el ConcreteCreator (Ej DbApp extends DbMy).<br/>
    /// En una determinada App existira una clase Container que sera el director (Builder) y utilizara clases estaticas de Db (Singleton).
    /// </remarks>
    public abstract class Db
    {
        public Config config { get; }

        //public Dictionary<string, Dictionary<string, EntityTree>> tree { get; set; } = new();

        //public Dictionary<string, Dictionary<string, EntityRel>> relations { get; set; } = new();

        public Dictionary<string, Entity> entities { get; set; }

        public Dictionary<string, Dictionary<string, Field>> fields { get; set; }

        public IMemoryCache? cache { get; set; } = null;

        public Db(Config _config, Schema schema, IMemoryCache? cache = null)
        {
            config = _config;
            this.cache = cache;
            entities = schema.Entities();
            foreach (Entity e in entities.Values)
                e.db = this;

            fields = schema.Fields();
            foreach (Dictionary<string, Field> df in fields.Values)
                foreach (Field f in df.Values)
                    f.db = this;
        }


        public Dictionary<string, Field> FieldsEntity(string entityName)
        {
            if (!fields.ContainsKey(entityName))
                throw new Exception("La entidad " + entityName + " no existe");

            return fields[entityName];
        }

        /// <summary>
        /// Configuracion de field
        /// </summary>
        /// <remarks>
        /// - Si no existe el field consultado se devuelve una configuracion vacia<br/>
        /// - No es obligatorio que exista el field en la configuracion, se cargaran los parametros por defecto.
        /// </remarks>
        public Field Field(string entityName, string fieldName)
        {
            Dictionary<string, Field> fe = FieldsEntity(entityName);
            return (fe.ContainsKey(fieldName)) ? fe[fieldName] : new Field();
        }

        public List<string> EntityNames() => entities.Select(o => o.Key).ToList();

        /// <summary>
        /// Nombres de campos de la entidad
        /// </summary>
        /// <remarks>Importante, por cada entidad y por cada relacion, debe incluirse el campo derivado db.config.id. Varios metodos definidos asumen que el valor de _Id esta incluido (EntityValues, DbCache, EntitySql, etc)<br/>
        /// Utilizar FieldNamesRel, para devolver los nombres de campos junto el nombre de campos de relaciones</remarks>
        /// <param name="entityName"></param>
        /// <returns>Nombres de campos de la entidad</returns>
        public List<string> FieldNames(string entityName) {
            var l = FieldsEntity(entityName).Keys.ToList();
            if(!l.Contains(config.id))
                l.Insert(0, config.id); //Importante!! id debe ser incluido,
            return l;
        }


        /// <summary>
        /// Lista de campos de la entidad y sus relaciones
        /// </summary>
        /// <param name="entityName">Nombre de la entidad de la cual se retornaran el campo principal y sus relaciones</param>
        /// <returns></returns>
        public List<string> FieldNamesRel(string entityName)
        {
            List<string> fieldNamesR = new();

            if (!Entity(entityName).relations.IsNoE())
                foreach ((string fieldId, EntityRelation er) in Entity(entityName).relations)
                    foreach (string fieldName in FieldNames(er.refEntityName))
                        fieldNamesR.Add(fieldId + "-" + fieldName);

            return FieldNames(entityName).Concat(fieldNamesR).ToList();
        }

        public List<string> FieldNamesAdmin(string entityName)
        {
            var e = Entity(entityName);
            return e.fields.Except(e.noAdmin).ToList();
        }

        public Entity Entity(string entityName)
        {
            if(!entities.ContainsKey(entityName))
                throw new Exception("La entidad " + entityName + " no existe");

            return entities[entityName];
        }

        /// <summary>
        /// Instancia de Query para simplificar la ejecucion de consultas a la base de datos
        /// </summary>
        /// <returns>Instancia de Query</returns>
        public abstract Query Query();

        public abstract EntitySql Sql(string entity_name);

        public EntityCache Cache(EntitySql sql){
            return new EntityCache(this, sql);
        }

        public abstract EntityPersist Persist();

        public virtual EntityMapping Mapping(string entityName, string? fieldId = null)
        {
            return new(this, entityName, fieldId);
        }

        public virtual EntityValues Values(string entityName, string? fieldId = null)
        {
            return new(this, entityName, fieldId);
        }

        /// <summary>
        /// Extrae los elementos de una key
        /// </summary>
        /// <param name="entityName">Nombre de la entidad</param>
        /// <param name="key">fieldId-fieldName</param>
        /// <returns>Elementos de la relación</returns>
        /// <remarks>Asegurar existencia de caracter de separación.<br/>
        /// Se puede controlar por ej.: if (key.Contains("__")) </remarks>
        public (string fieldId, string fieldName, string refEntityName) KeyDeconstruction(string entityName, string key) {
            int i = key.IndexOf("__");
            string fieldId = key.Substring(0, i);
            string refEntityName = Entity(entityName!).relations[fieldId].refEntityName;
            string fieldName = key.Substring(i + 2); //se suman 2 porque es la longitud de "__" (el string de separacion)
            return (fieldId, fieldName, refEntityName);
        }

    }

}
