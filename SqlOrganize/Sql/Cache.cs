using Dapper;
using Microsoft.Extensions.Caching.Memory;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql

{
    public class Cache
    {
        public Db Db { get; }

        public Cache(Db db)
        {
            Db = db ?? throw new ArgumentNullException(nameof(db));
        }

        /// <summary>
        /// Queries entity IDs and resolves them into entities using caching and relationships.
        /// </summary>
        public IEnumerable<T> QueryIds<T>(string entityName, string sql, object? parameters = null) where T : Entity, new()
        {
            var ids = Query(sql, parameters).ColOfVal<object>(Db.config.id).ToArray();
            return ByIds<T>(entityName, ids);
        }

        public IEnumerable<T> ByIds<T>(string entityName, params object[] ids)
        {
            List<Dictionary<string, object?>> rawEntities = Ids(entityName, ids);

            foreach (var entity in rawEntities)
                ClearRelationships(entityName, entity);

            TreeRecursive(entityName, Db.Entity(entityName).tree, rawEntities);

            for (var j = 0; j < rawEntities.Count(); j++)
            {
                rawEntities[j] = ValuesTree(entityName, rawEntities[j]);
            }

            string json = JsonConvert.SerializeObject(rawEntities);
            return JsonConvert.DeserializeObject<IEnumerable<T>>(json);
        }

        /// <summary>
        /// Clears related fields for relationships in an entity.
        /// </summary>
        private void ClearRelationships(string entityName, Dictionary<string, object?> entity)
        {
            foreach (var fieldName in Db.FieldNamesRel(entityName))
                entity[fieldName] = null;
        }


        /// <summary> Armar arbol de valores a partir del resultado de una consulta </summary>
        /// <remarks> Los campos deben estar organizados de la forma fieldId__fieldName para poder identificar las ramas </remarks>
        protected Dictionary<string, object?> ValuesTree(string entityName, Dictionary<string, object?> values)
        {
            Dictionary<string, object?> response = new();

            if (values.ContainsKey("Label"))
                response["Label"] = values["Label"];

            foreach (string fieldName in Db.FieldNames(entityName))
            {
                if (values.ContainsKey(fieldName))
                    response[fieldName] = values[fieldName];

            }

            ValuesTreeRecursive(values, Db.Entity(entityName).tree, response);

            return response;
        }

        /// <summary> Metodo recursivo para armar arbol de valores a partir del resultado de una consulta </summary>
        /// <remarks> Los campos deben estar organizados de la forma fieldId__fieldName para poder identificar las ramas </remarks>
        protected void ValuesTreeRecursive(IDictionary<string, object?> values, IDictionary<string, EntityTree> tree, IDictionary<string, object?> response)
        {
            foreach (var (fieldId, et) in tree)
            {
                if (response.ContainsKey(et.fieldName) && !response[et.fieldName].IsNoE())
                {
                    response[et.fieldName + "_"] = new Dictionary<string, object?>();

                    if (values.ContainsKey(fieldId + Db.config.separator + "Label"))
                        (response[et.fieldName + "_"] as Dictionary<string, object?>)!["Label"] = values[fieldId + Db.config.separator + "Label"];

                    foreach (string fieldName in Db.FieldNames(et.refEntityName))
                    {
                        if (values.ContainsKey(fieldId + Db.config.separator + fieldName))
                            (response[et.fieldName + "_"] as Dictionary<string, object?>)![fieldName] = values[fieldId + Db.config.separator + fieldName];
                    }

                    if (!et.children.IsNoE())
                        ValuesTreeRecursive(values, et.children, (response[et.fieldName + "_"] as Dictionary<string, object?>)!);
                }
            }
        }


        /// <summary>
        /// Verifica si el resultado de la consulta no esta en cache, si esta la devuelve, si no esta consulta y almacena en cache
        /// </summary>
        /// <param name="sql"> Cualquier tipo de consulta </param>
        /// <param name="parameters"> Parametros opcionales </param>
        /// <returns></returns>
        public IEnumerable<Dictionary<string, object?>> Query(string sql, object? parameters = null)
        {
            List<string> queries;
            string _queries;

            //Obtener o definir queries de la cache. queries se utiliza para almacenar las consultas realizadas y poder eliminarlas facilmente si se requiere.
            bool res = Db.Cache!.TryGetValue("queries", out _queries);
            if (res)
            {
                queries = JsonConvert.DeserializeObject < List<string> >(_queries);
            }
            else
            {
                queries = new();
            }

            string sql_ = sql + JsonConvert.SerializeObject(parameters);

            IEnumerable<Dictionary<string, object?>> result;
            string _result;
            res = Db.Cache!.TryGetValue(sql_, out _result);

            if (!res)
            {
                #region acceso a la base de datos (no se encontro en cache)
                using (var connection = this.Db.Connection().Open())
                {
                    var resu = connection.Query<dynamic>(sql, parameters);
                    _result = JsonConvert.SerializeObject(resu);
                    Db.Cache!.Set(sql, _result);
                    queries!.Add(sql);
                    Db.Cache!.Set("queries", JsonConvert.SerializeObject(queries));
                }
                #endregion
            }

            return JsonConvert.DeserializeObject<IEnumerable<Dictionary<string, object?>>>(_result);
        }

        /// <summary>
        /// Analyzes the query response and reorganizes the elements to build the result.
        /// </summary>
        protected IEnumerable<Dictionary<string, object?>> TreeRecursive(string entityName, Dictionary<string, EntityTree> tree, IEnumerable<Dictionary<string, object?>> response)
        {

            if (!response.Any())
                return response;

            var entity = Db.Entity(entityName);
            var relations = entity.relations;

            foreach (var (fieldId, et) in tree)
            {
                if (!relations.TryGetValue(fieldId, out var relation))
                    continue;

                string refEntityName = relation.refEntityName;
                string? parentId = relation.parentId;
                string fieldName = relation.fieldName;
                string refFieldName = relation.refFieldName;
                string fkName = (!parentId.IsNoE()) ? parentId + Db.config.separator + fieldName : fieldName;

                List<object> ids = response.ColOfVal<object>(fkName).Distinct().ToList();
                ids.RemoveAll(item => item.IsNoE());
                IEnumerable<IDictionary<string, object?>> data;
                if (ids.Count() == 1 && ids.ElementAt(0).IsNoE())
                    return Enumerable.Empty<Dictionary<string, object?>>();
                else
                {
                    
                    data = Ids(refEntityName, ids.ToArray());
                }

                for (var i = 0; i < response.Count(); i++)
                {
                    if (response.ElementAt(i)[fkName].IsNoE())
                        continue;

                    for (var j = 0; j < data.Count(); j++)
                    {
                        if (response.ElementAt(i)[fkName]!.ToString()!.Equals(data.ElementAt(j)[refFieldName]!.ToString()))
                        {
                            foreach(string fn in Db.FieldNames(refEntityName))
                                response.ElementAt(i)[fieldId + Db.config.separator + fn] = data.ElementAt(j)[fn];
                        }
                    }
                }

                if (!et.children.IsNoE())
                    TreeRecursive(entityName, et.children, response);
            }

            return response;
        }

        /// <summary> Consulta datos de una entidad los almacena en cache y los devuelve sin relaciones <br/>
        /// Si no encuentra valores en el Cache, realiza una consulta a la base de datos y lo almacena en cache.</summary>
        public List<Dictionary<string, object?>> Ids(string entityName, params object[] ids)
        {
            
            ids.Distinct();

            List<Dictionary<string, object?>> response = new(ids.Count()); //respuesta que sera devuelta

            List<object> stringIds = new(); 
            List<object> searchIds = new(); 

            for (var i = 0; i < ids.Count(); i++)
            {
                stringIds.Add(ids[i].ToString());
                string? data;
                if (Db.Cache!.TryGetValue(entityName + stringIds.ElementAt(i).ToString(), out data))
                {
                    response.Insert(i, JsonConvert.DeserializeObject<Dictionary<string, object?>>(data!)!);
                }
                else
                {
                    response.Insert(i, null);
                    searchIds.Add(ids[i].ToString()) ;
                }
            }

            if (searchIds.Count == 0)
                return response;

            IEnumerable<dynamic> rows;

            #region acceso a la base de datos (faltan datos en cache)
            using(var connection = Db.Connection().Open())
            {
                string sql = Db.Sql().ByIdsAll(entityName);
                rows = connection.Query<dynamic>(sql, new { Ids = searchIds });
            }
            #endregion 

            if (rows.Count() != searchIds.Count())
                throw new Exception("La consulta a traves de ids existentes no arrojo ningun resultado. Se estan usando ids correspondientes a otra entidad? Existe el id utilizado en la base de datos?");

            foreach (dynamic row in rows)
            {
                string json = JsonConvert.SerializeObject(row);
                Dictionary<string, object?> data = JsonConvert.DeserializeObject<Dictionary<string, object?>>(json);
                var id = data[Db.config.id].ToString();
                int index = Array.IndexOf(stringIds.ToArray(), id);
                response[index] = Set(entityName, data);
            }

            return response;
        }

        /// <summary> Analiza una fila de resultado y la almacena en cache.</summary>
        /// <returns>Resultado filtrado solo para la entidad principal</returns>
        protected Dictionary<string, object?> Set(string entityName,Dictionary<string, object?> row)
        {
            if (!Db.Entity(entityName).relations.IsNoE())
                SetRecursive(Db.Entity(entityName).relations!, row);

            var id = row[Db.config.id].ToString();
            string json = JsonConvert.SerializeObject(row);
            Db.Cache!.Set(entityName + id, json);
            return row;
        }

        /// <summary> Analiza una fila de resultado y la almacena en cache considerando cada entidad de las relaciones. </summary>
        protected void SetRecursive(Dictionary<string, EntityRelation> relations, Dictionary<string, object?> row)
        {
            //Para cada relacion se obtiene un row auxiliar para almacenar en cache que es removido posteriormente del row original
            foreach (var (fieldId, rel) in relations)
            {
                var entityName = rel.refEntityName;
                Dictionary<string, object?> rowAux = new();
                string f = fieldId + Db.config.separator;
                foreach (var (column, value) in row)
                {
                    if (column.Contains(f))
                    {
                        string ff = column.Substring(f.Length); 
                        rowAux[ff] = value;
                        row.Remove(column);
                    }
                }
                if (!rowAux[Db.config.id].IsNoE())
                {
                    string json = JsonConvert.SerializeObject(rowAux);
                    Db.Cache!.Set(entityName + rowAux[Db.config.id]!.ToString(), json);
                }
            }
        }



    }


    
}
