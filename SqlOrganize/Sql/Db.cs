using Microsoft.Extensions.Caching.Memory;
using System.Collections.ObjectModel;
using System.Data.Common;

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
    /// Todos los elementos necesarios para conectarse, obtener y definir datos de una base, deben pertenecer a Db.
    /// </remarks>
    public abstract class Db
    {
        /// <summary>
        /// Contextos de persistencia
        /// </summary>
        /// <remarks> Los contextos de persistencia son almacenados en la coleccion para ser ejecutados posteriormente </remarks>
        public Collection<PersistContext> PersistQueue { get; set; }

        public virtual Config config { get; }

        //public Dictionary<string, Dictionary<string, EntityTree>> tree { get; set; } = new();

        //public Dictionary<string, Dictionary<string, EntityRel>> relations { get; set; } = new();

        public Dictionary<string, EntityMetadata> entities { get; set; }


        public IMemoryCache? cache { get; set; } = null;

        public Db(Config _config, ISchema schema, IMemoryCache? cache = null)
        {
            config = _config;
            this.cache = cache;
            entities = schema.entities;
            foreach (EntityMetadata e in entities.Values)
            {
                e.db = this;

                foreach (var (key, f) in e.fields)
                    f.db = this;
            }

        }


        public Dictionary<string, Field> FieldsEntity(string entityName)
        {
            if (!entities.ContainsKey(entityName))
                throw new Exception("La entidad " + entityName + " no existe");

            return entities[entityName].fields;
        }

        /// <summary>
        /// Configuracion de field
        /// </summary>
        /// <remarks>
        /// * Si no existe el field consultado se devuelve una configuracion vacia<br/>
        /// * No es obligatorio que exista el field en la configuracion, se cargaran los parametros por defecto.
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
        /// <remarks>Importante, por cada entidad y por cada relacion, debe incluirse el campo derivado db.config.id. Varios metodos definidos asumen que el valor de _Id esta incluido (EntityVal, DbCache, EntitySql, etc)<br/>
        /// Utilizar FieldNamesRel, para devolver los nombres de campos junto el nombre de campos de relaciones</remarks>
        /// <param name="entityName"></param>
        /// <returns>Nombres de campos de la entidad</returns>
        public List<string> FieldNames(string entityName) {
            var l = FieldsEntity(entityName).Keys.ToList();
            if (!l.Contains(config.id))
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
                        fieldNamesR.Add(fieldId + config.separator + fieldName);

            return FieldNames(entityName).Concat(fieldNamesR).ToList();
        }

        public List<string> FieldNamesAdmin(string entityName)
        {
            var e = Entity(entityName);
            return e.fieldNames.Except(e.noAdmin).ToList();
        }

        public EntityMetadata Entity(string entityName)
        {
            if (!entities.ContainsKey(entityName))
                throw new Exception("La entidad " + entityName + " no existe");

            return entities[entityName];
        }

        /// <summary>
        /// Instancia de Query para simplificar la ejecucion de consultas a la base de datos
        /// </summary>
        /// <returns>Instancia de Query</returns>
        public abstract Query Query();

        public abstract EntitySql Sql(string entity_name);

        public EntityCache Cache(EntitySql sql) {
            return new EntityCache(this, sql);
        }

        /// <summary> Crear contexto de persistencia </summary>
        public abstract PersistContext Persist();

        public virtual EntityMapping Mapping(string entityName, string? fieldId = null)
        {
            return new(this, entityName, fieldId);
        }

        /// <summary>
        /// Extrae los elementos de una key
        /// </summary>
        /// <param name="entityName">Nombre de la entidad</param>
        /// <param name="key">fieldId separator fieldName</param>
        /// <returns>Elementos de la relación</returns>
        /// <remarks>Asegurar existencia de caracter de separación.<br/>
        /// Se puede controlar por ej.: if (key.Contains("__")) </remarks>
        public (string fieldId, string fieldName, string refEntityName) KeyDeconstruction(string entityName, string key) {
            int i = key.IndexOf(config.separator);
            string fieldId = key.Substring(0, i);
            string refEntityName = Entity(entityName!).relations[fieldId].refEntityName;
            string fieldName = key.Substring(i + config.separator.Length);
            return (fieldId, fieldName, refEntityName);
        }


        public void ExecuteQueue()
        {
            if (PersistQueue.IsNoE())
                throw new Exception("No existen contextos para procesar.");

            var query = PersistQueue.ElementAt(0).Db.Query();
            using DbConnection connection = query.OpenConnection();
            query.BeginTransaction();
            try
            {
                foreach (PersistContext persist in PersistQueue)
                {
                    if (persist.Sql().IsNoE())
                        continue;
                    persist.Query(query).ExecTransaction();
                }

                query.CommitTransaction();
            }
            catch (Exception ex)
            {
                query.RollbackTransaction();
                throw ex;
            }
        }

        public CreateQueue CreateQueue()
        {
            return new CreateQueue(this);
        }

        public void RemoveCache()
        {
            cache!.RemoveCacheQueries();

            foreach (PersistContext context in PersistQueue)
                foreach (var d in context.detail)
                    context.Db.cache!.Remove(d.entityName + d.id);
        }

        public void ClearQueue()
        {
            PersistQueue?.Clear();
        }

        public void ProcessQueue()
        {
            ExecuteQueue();
            RemoveCache();
            ClearQueue();
        }
    }

    public class CreateQueue : IDisposable
    {
        private readonly Db _db;  // Reference to the Db instance

        public CreateQueue(Db db)
        {
            _db = db;
            _db.PersistQueue = new Collection<PersistContext>();  // Initialize or reset the queue
        }

        // The Dispose method is called automatically when the 'using' block is exited
        public void Dispose()
        {
            // Clear the queue and free resources
            if (_db.PersistQueue != null)
            {
                _db.PersistQueue.Clear();
                _db.PersistQueue = null;  // Set it to null to free up resources
            }
        }
    }

  
}
