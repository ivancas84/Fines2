﻿using Microsoft.Extensions.Caching.Memory;
using Newtonsoft.Json.Linq;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql.Exceptions;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql
{

    /// <summary> Almacenamiento de datos en Cache </summary>
    /// <remarks> Dependiendo de la implementación de Cache, puede utilizar formato json. <br/>
    /// Se recomienda utilizar algún método de transformación basado en json como "Obj"</remarks>
    public class EntityCache
    {
        Db Db;
        EntitySql Sql;

        /// <summary>
        /// Constructor para EntitySelect
        /// </summary>
        /// <param name="_db">Contenedor principal del proyecto</param>
        public EntityCache(Db db, EntitySql sql)
        {
            Db = db;
            Sql = sql;
        }

        /// <summary>Metodo de busqueda rapida en cache</summary>
        /// <remarks>Solo analiza el atributo fields. A diferencia de _Ids(), SI devuelve relaciones!!!</remarks>
        public IEnumerable<Dictionary<string, object?>> Ids(params object[] ids)
        {
            if (Sql.fields.IsNoE())
                Sql.Fields();

            List<string> _fields = Sql.fields!.Replace("$", "").Split(',').ToList().Select(s => s.Trim()).ToList();

            return BuildDicts(_fields, ids);
        }

        /// <summary>Idem Ids pero para un solo Id</summary>
        public IDictionary<string, object?>? Id(object id)
        {
            var list = Ids(id);
            if (list.IsNoE())
                return null;

            return list.ElementAt(0);
        }

        /// <summary>Similar a _Ids pero para un solo id</summary>
        public IDictionary<string, object>? _Id(object id)
        {
            var list = _Ids(id);
            if (list.IsNoE())
                return null;

            return list.ElementAt(0);
        }

        /// <summary>Obtener campos de una entidad (sin relaciones)<br/>
        /// Si no encuentra valores en el Cache, realiza una consulta a la base de datos y lo almacena en Db.Cache.</summary>
        /// <remarks>A diferencia de Ids(), NO devuelve relaciones!!!</remarks>
        public List<IDictionary<string, object?>> _Ids(params object[] ids)
        {
            ids.Distinct();

            List<IDictionary<string, object?>> response = new(ids.Count()); //respuesta que sera devuelta

            List<string> stringIds = new(); //Transformar object a string para evitar problemas de tipos cuando se utiliza FileCache
            List<object> searchIds = new(); //ids que no se encuentran en cache y deben ser buscados

            for (var i = 0; i < ids.Count(); i++)
            {
                stringIds.Add(ids[i].ToString());
                object? data;
                if (Db.cache!.TryGetValue(Sql.entityName + stringIds.ElementAt(i), out data))
                {
                    if (data is JObject)
                        data = (data as JObject).ToObject<IDictionary<string, object?>>();

                    response.Insert(i, (IDictionary<string, object>)data!);
                }
                else
                {
                    response.Insert(i, null);
                    searchIds.Add(stringIds.ElementAt(i));
                }
            }

            if (searchIds.Count == 0)
                return response;

            #region acceso a la base de datos (faltan datos en cache)
            IEnumerable<Dictionary<string, object?>> rows = Db.Sql(Sql.entityName).
                Size(0).
                Where("$" + Db.config.id + " IN (@searchIds)").
                Param("@searchIds", searchIds).
                Dicts();
            #endregion 

            if (rows.Count() != searchIds.Count())
                throw new Exception("La consulta a traves de ids existentes no arrojo ningun resultado. Se estan usando ids correspondientes a otra entidad? Existe el id utilizado en la base de datos?");
            
            foreach (Dictionary<string, object?> row in rows)
            {
                int index = Array.IndexOf(stringIds.ToArray(), row[Db.config.id].ToString());
                response[index] = Set(Sql.entityName, row);
            }

            return response;
        }

        public ObservableCollection<T> Entities<T>() where T : Entity, new()
        {
            var source = Dicts();
            ObservableCollection < T > oc = new();

            for (var i = 0; i < source.Count(); i++)
            {
                T obj = Entity.CreateFromDict<T>(source.ElementAt(i));
                obj.Index = i;
                oc.Add(obj);
            }

            return oc;
        }

        public void AddEntityToOC<T>(ObservableCollection<T> oc) where T : Entity, new()
        {
            var source = Dicts();
            Db.AddEntityToOC(source, oc);
        }


        public void AddEntityToClearOC<T>(ObservableCollection<T> oc) where T : Entity, new()
        {
            var source = Dicts();
            Db.AddEntityToClearOC(source, oc);
        }

        public void AddColumnToClearOC<T>(string columnName, ObservableCollection<T> oc)
        {
            var source = Dicts();
            var data = source.ColOfVal<T>(columnName);
            oc.Clear();
            oc.AddRange(data);
        }

        /// <summary>
        /// Consulta de datos (uso de cache para consulta y resultados)<br/>
        /// </summary>
        /// <param name="query">Consulta</param>
        public IEnumerable<Dictionary<string, object?>> Dicts()
        {
            if (!Sql.select.IsNoE() || !Sql.group.IsNoE() || !Sql.join.IsNoE())
                return DictsQuery();

            if (Sql.fields.IsNoE())
                Sql.Fields();

            List<string> _fields = Sql.fields!.Replace("$", "").Split(',').ToList().Select(s => s.Trim()).ToList();

            //si no se encuentra el Db.config.id, no se realiza cache.
            //Si por ejemplo se consultan solo campos de relacoines, no se aplicaria correctamente el distinct
            if (!_fields.Contains(Db.config.id))
                return DictsQuery();

            EntitySql sqlAux = Sql.Clone();
            sqlAux.fields = Db.config.id;

            IEnumerable<object> ids = Db.Cache(sqlAux).DictsQuery().ColOfVal<object>(Db.config.id);

            return BuildDicts(_fields, ids.ToArray());
        }

        public object? Value(int column = 0)
        {
            var dict = Dict();

            List<string> keys = new List<string>(dict.Keys);

            if (column >= 0 && column < keys.Count)
            {
                string keyAtIndex = keys[column];
                return dict[keyAtIndex];
            }

            return null;

        }

        public T? Value<T>(int column = 0)
        {
            var response = Value(column);
            return (response.IsNoE()) ? (T)response! : default;
        }

        public object? Value(string fieldName)
        {
            var dict = Dict();
            return (!dict.IsNoE()) ? dict[fieldName] : null;
        }

        public T? Value<T>(string fieldName)
        {
            var dict = Dict();
            return (!dict.IsNoE()) ? (T)dict[fieldName] : default(T);
        }

        public IEnumerable<T> Column<T>(int column = 0)
        {
            var data = Dicts();
            if (data.Any())
            {
                List<string> keys = new List<string>(data.ElementAt(0).Keys);

                if (column >= 0 && column < keys.Count)
                {
                    string keyAtIndex = keys[column];
                    return data.ColOfVal<T>(keyAtIndex);
                }
            }
            return new List<T>();

        }

        public IEnumerable<object> Column(int column = 0)
        {
            return Column<object>(column);
        }

        public IEnumerable<object> Column(string fieldName)
        {
            return Dicts().ColOfVal<object>(fieldName);
        }

        public IEnumerable<T> Column<T>(string fieldName)
        {
            return Dicts().ColOfVal<T>(fieldName);
        }

        /// <summary>Efectua una consulta a la base de datos, la almacena en cache.<br/>
        /// Dependiendo del tipo de consulta almacena la fila de resultado en cache.</summary>
        /// <remarks>Cuando se esta seguro de que se desea consultar una sola fila. Utilizar este metodo para evitar que se tenga que procesar un tamaño grande de resultado</remarks>
        public IDictionary<string, object?>? Dict()
        {
            if (!Sql.select.IsNoE() || !Sql.group.IsNoE())
                return DictQuery();

            if (Sql.fields.IsNoE())
                Sql.Fields();

            EntitySql esqlAux = Sql.Clone();
            esqlAux.fields = Db.config.id;

            object? id = esqlAux.Value<object>();

            if (id.IsNoE())
                return null;

            List<string> fields = Sql.fields!.Replace("$", "").Split(',').ToList().Select(s => s.Trim()).ToList();

            IEnumerable<Dictionary<string, object?>> response = BuildDicts(fields, id);

            return response.ElementAt(0);
        }

        /// <summary>Efectua una consulta a la base de datos, la almacena en cache.<br/>
        /// Dependiendo del tipo de consulta almacena la fila de resultado en cache.</summary>
        /// <remarks>Cuando se esta seguro de que se desea consultar una sola fila. Utilizar este metodo para evitar que se tenga que procesar un tamaño grande de resultado</remarks>
        public T? ToEntity<T>() where T : Entity, new()
        {

            IDictionary<string, object?>? dict = Dict();
            return (dict.IsNoE()) ? null : Entity.CreateFromDict<T>(dict!);
        }

        public T SetEntity<T>(T entity) where T : Entity, new()
        {
            IDictionary<string, object?>? dict = Dict();
            entity.Set(dict!);
            return entity;

        }

        /// <summary>Organiza los elementos a consultar y efectua la consulta a la base de datos.</summary>
        protected IEnumerable<Dictionary<string, object?>> BuildDicts(List<string> fields, params object[] ids)
        {
            FieldsOrganize fo = new(Db, Sql.entityName, fields);

            List<IDictionary<string, object?>> data = _Ids(ids);

            List<Dictionary<string, object?>> response = new();

            for (var i = 0; i < data.Count; i++)
            {
                response.Add(new());
                for (var j = 0; j < fo.Fields.Count; j++)
                    response[i][fo.Fields[j]] = null;
                for (var j = 0; j < fo.FieldsMain.Count; j++)
                    response[i][fo.FieldsMain[j]] = data[i][fo.FieldsMain[j]];
            }

            return DictsRecursive(fo, response, 0);
        }

        /// <summary>Analiza la respuesta de una consulta y re organiza los elementos para armar el resultado </summary>
        protected IEnumerable<Dictionary<string, object?>> DictsRecursive(FieldsOrganize fo, IEnumerable<Dictionary<string, object?>> response, int index)
        {
            if (index >= fo.FieldsIdOrder.Count) return response;
            {
                if (response.Count() == 0) return response;

                string fieldId = fo.FieldsIdOrder[index];
                string refEntityName = Db.Entity(fo.EntityName).relations[fieldId].refEntityName;
                string? parentId = Db.Entity(fo.EntityName).relations[fieldId].parentId;
                string fieldName = Db.Entity(fo.EntityName).relations[fieldId].fieldName;
                string refFieldName = Db.Entity(fo.EntityName).relations[fieldId].refFieldName;
                string fkName = (!parentId.IsNoE()) ? parentId + Db.config.separator + fieldName : fieldName;

                List<object> ids = response.ColOfVal<object>(fkName).Distinct().ToList();
                ids.RemoveAll(item => item.IsNoE());
                IEnumerable<IDictionary<string, object?>> data;
                if (ids.Count() == 1 && ids.ElementAt(0).IsNoE())
                    return Enumerable.Empty<Dictionary<string, object?>>();
                else
                {
                    //Si las fk estan asociadas a una unica pk, debe indicarse para mayor eficiencia
                    if (Db.config.fkId)
                    {
                        data = Db.Sql(refEntityName).Cache()._Ids(ids.ToArray());
                    }
                    else
                    {
                        //data = Db.Query(refEntityName).Where("$"+Db.config.id+" IN (@0)").Parameters(ids).DictsCacheQuery();
                        data = Db.Sql(refEntityName).Cache().Ids(ids.ToArray());
                    }
                }

                for (var i = 0; i < response.Count(); i++)
                {
                    if (response.ElementAt(i)[fkName].IsNoE())
                        continue;

                    for (var j = 0; j < data.Count(); j++)
                    {
                        if (response.ElementAt(i)[fkName]!.ToString()!.Equals(data.ElementAt(j)[refFieldName]!.ToString()))
                        {
                            for (var k = 0; k < fo.FieldsRel[fieldId].Count; k++)
                            {
                                var n = fo.FieldsRel[fieldId][k];
                                response.ElementAt(i)[fieldId + Db.config.separator + n] = data.ElementAt(j)[n];
                            }
                        }
                    }
                }

                return (++index < fo.FieldsIdOrder.Count) ? DictsRecursive(fo, response, index) : response;
            }
        }

        /// <summary> Analiza una fila de resultado y la almacena en cache.</summary>
        /// <returns>Resultado filtrado solo para la entidad principal</returns>
        protected Dictionary<string, object?> Set(string entityName, Dictionary<string, object?> row)
        {
            if (!Db.Entity(entityName).relations.IsNoE())
                SetRecursive(Db.Entity(entityName).relations!, row);

            Db.cache!.Set(entityName + row[Db.config.id]!.ToString(), row);
            return row;
        }

        /// <summary> Analiza una fila de resultado y la almacena en cache considerando cada entidad de las relaciones. </summary>
        protected void SetRecursive(Dictionary<string, EntityRelation> relations, Dictionary<string, object?> row)
        {
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
                if (rowAux.Count > 0)
                    Db.cache!.Set(entityName + rowAux[Db.config.id]!.ToString(), rowAux);
            }
        }

        /// <summary>Verifica cache para ver si existe consulta, si no existe realiza la consulta y la almacena en Cache</summary>
        protected IEnumerable<Dictionary<string, object?>> DictsQuery()
        {
            List<string> queries;
            object _queries;

            //Obtener o definir queries de la cache. queries se utiliza para almacenar las consultas realizadas y poder eliminarlas facilmente si se requiere.
            bool res = Db.cache!.TryGetValue("queries", out _queries);
            if (res)
            {
                if (_queries is JArray)  //si se utiliza cache en archivo
                    queries = (_queries as JArray).ToObject<List<string>>();
                else // si se utiliza cache en memoria
                    queries = (List<string>)_queries;
            }
            else
            {
                queries = new();
            }

            IEnumerable<Dictionary<string, object?>> result;
            object _result;            
            string queryKey = Sql!.ToString();
            res = Db.cache!.TryGetValue(queryKey, out _result);

            if (res)
            {
                //si se utiliza cache en archivo
                if (_result is JArray) 
                    result = (_result as JArray).ToObject<IEnumerable<Dictionary<string, object?>>>();

                //si se utiliza cache en memoria
                else
                    result = (IEnumerable<Dictionary<string, object?>>)_result;
            }
            else
            {
                #region acceso a la base de datos (no se encontro en cache)
                result = Sql.Dicts();
                Db.cache!.Set(queryKey, result);
                queries!.Add(queryKey);
                Db.cache!.Set("queries", queries);
                #endregion
            }
            return result!;
        }

        /// <summary>Ejecuta consulta de datos (con relaciones).<br/>
        /// Verifica la cache para obtener el resultado de la consulta, si no existe en cache accede a la base de datos.</summary>
        /// <remarks> La diferencia con Dict es que no guarda datos de entidades en cache, solo guarda la consulta </remarks>
        protected IDictionary<string, object?>? DictQuery()
        {
            var res = DictsQuery();
            if (res.Any()) 
                return res.First();
            else 
                return null;
        }

        /// <summary>Organizacion de Campos de la entidad para armar relaciones</summary>
        protected class FieldsOrganize
        {
            Db Db;

            public string EntityName;

            /*
            Campos a consultar de relaciones, 
            Se pueden agregar fk adicionales necesarias para comparar
            */
            public List<string> Fields;

            /*
            Campos de relaciones
            Para facilitar el filtro de campos de relaciones se agregan agrupadas por fieldId
            */
            public Dictionary<string, List<string>> FieldsRel = new();

            /*
            FieldsId que deben ser consultados en el orden correspondiente
            */
            public List<string> FieldsIdOrder = new();

            /*
            Campos a consultar de la entidad principal "entityName", 
            */
            public List<string> FieldsMain = new();

            public FieldsOrganize(Db db, string entityName, List<string> fields)
            {
                Db = db;
                EntityName = entityName;
                Fields = fields;
                this.OrganizeRelations(0);
                this.OrganizeOrder(Db.Entity(entityName).tree);
            }
            /*
            Organizar fields 
            Se agregan los campos necesarios para consultar y comparar el arbol de fields
            */
            protected void OrganizeRelations(int index)
            {
                if (Fields[index].Contains(Db.config.separator))
                {
                    var f = Fields[index].Split(Db.config.separator);
                    EntityRelation r = Db.Entity(EntityName).relations[f[0]];
                    string fkName = (!r.parentId.IsNoE()) ? r.parentId + Db.config.separator + r.fieldName : r.fieldName;

                    if (!FieldsRel.ContainsKey(f[0]))
                        FieldsRel.Add(f[0], new List<string>());
                    if (!FieldsRel[f[0]].Contains(f[1]))
                        FieldsRel[f[0]].Add(f[1]);
                    if (!Fields.Contains(fkName))
                        Fields.Add(fkName);
                }
                else
                    FieldsMain.Add(Fields[index]);

                if (++index < Fields.Count)
                    OrganizeRelations(index);
            }


            protected void OrganizeOrder(Dictionary<string, EntityTree> tree)
            {
                foreach (var (fieldId, et) in tree)
                {
                    bool recorrerChildren = false;
                    for (var j = 0; j < Fields.Count; j++)
                    {
                        if (Fields[j].Contains(Db.config.separator))
                        {
                            var f = Fields[j].Split(Db.config.separator);
                            if (f[0] == fieldId && !FieldsIdOrder.Contains(fieldId))
                            {
                                FieldsIdOrder.Add(fieldId);
                                recorrerChildren = true;
                            }

                        }
                    }
                    if (recorrerChildren && !et.children.IsNoE())
                        OrganizeOrder(et.children);
                }
            }
        }

    }


}
