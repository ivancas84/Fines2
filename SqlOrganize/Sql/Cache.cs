using Dapper;
using Microsoft.Extensions.Caching.Memory;
using Newtonsoft.Json.Linq;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Model;
using static System.Runtime.InteropServices.JavaScript.JSType;
using System.Data;
using System.ComponentModel;

namespace SqlOrganize.Sql

{
    public class Cache
    {
        public Db Db { get; }

        public Cache(Db db)
        {
            Db = db;
        }

        /// <summary>
        /// Consulta de datos (uso de cache para consulta y resultados)<br/>
        /// </summary>
        /// <param name="sql">Consulta (solo se obtendra columna id)</param>
        public IEnumerable<T> QueryIds<T>(string entityName, string sql, object parameters) where T : Entity, new()
        {
            IEnumerable<object> ids = Query(sql, parameters).ColOfVal<object>(Db.config.id);

            List<Dictionary<string, object?>> response = Ids(entityName, ids.ToArray());

            for (var i = 0; i < response.Count; i++)
            {
                foreach (string fieldName in Db.FieldNamesRel(entityName))
                    response[i][fieldName] = null;
            }

            TreeRecursive(entityName, Db.Entity(entityName).tree, response);

            for (var j = 0; j < response.Count(); j++)
            {
                response[j] = ValuesTree(entityName, response[j]);
            }

            string json = JsonConvert.SerializeObject(response);
            return JsonConvert.DeserializeObject<IEnumerable<T>>(json);




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

        public IEnumerable<Dictionary<string, object?>> Query(string sql, object parameters)
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

        /// <summary>Analiza la respuesta de una consulta y re organiza los elementos para armar el resultado </summary>
        protected IEnumerable<Dictionary<string, object?>> TreeRecursive(string entityName, Dictionary<string, EntityTree> tree, IEnumerable<Dictionary<string, object?>> response)
        {

            if (response.Count() == 0) return response; 


            foreach (var (fieldId, et) in tree)
            {
                string refEntityName = Db.Entity(entityName).relations[fieldId].refEntityName;
                string? parentId = Db.Entity(entityName).relations[fieldId].parentId;
                string fieldName = Db.Entity(entityName).relations[fieldId].fieldName;
                string refFieldName = Db.Entity(entityName).relations[fieldId].refFieldName;
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
        /// Si no encuentra valores en el Cache, realiza una consulta a la base de datos y lo almacena en Db.Cache.</summary>
        /// <remarks>A diferencia de Ids(), NO devuelve relaciones!!!</remarks>
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
