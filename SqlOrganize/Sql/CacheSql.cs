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

namespace SqlOrganize.Sql
{
    public class CacheSql //EN CONSTRUCCION
    {
        public Db Db { get; }

        public CacheSql(Db db)
        {
            Db = db;
        }

        #region Cache 
        /// <summary>
        /// Consulta de datos (uso de cache para consulta y resultados)<br/>
        /// </summary>
        /// <param name="query">Consulta</param>
        public IEnumerable<Dictionary<string, object?>> SelectCache(string entityName, string sufix)
        {

            var sql = Db.Sql().SelectIdDapper(entityName) + sufix;


            IEnumerable<object> ids = SqlCache(sql).ColOfVal<object>(Db.config.id);

            return SqlCache(sql);


        }

        public IEnumerable<Dictionary<string, object?>> SqlCache(string sql)
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
            string _result;
            res = Db.cache!.TryGetValue(sql, out _result);

            if (!res)
            {
                #region acceso a la base de datos (no se encontro en cache)
                using (var connection = this.Db.Connection().Open())
                {
                    var r = connection.Query<dynamic>(sql);
                    string json = JsonConvert.SerializeObject(r, Formatting.Indented);
                    Db.cache!.Set(sql, json);
                    queries!.Add(sql);
                    Db.cache!.Set("queries", queries);
                }
                #endregion
            }

            return JsonConvert.DeserializeObject<IEnumerable<Dictionary<string, object?>>>(_result);


        }

        #endregion 
    }
}
