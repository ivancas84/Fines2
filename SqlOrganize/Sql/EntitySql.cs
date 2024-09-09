using Newtonsoft.Json;
using SqlOrganize.Sql.Exceptions;
using System.Text.RegularExpressions;
using SqlOrganize.ValueTypesUtils;
using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql
{

    /// <summary>
    /// Selección de datos de una entidad
    /// </summary>
    /// <remarks>
    /// Los fields se traducen con los metodos de mapeo, deben indicarse con el prefijo. Ej "($ingreso = %p1) AND (MAX($persona__nombres) = %p1)"
    /// </remarks>
    public abstract class EntitySql
    {
        /// <summary>
        /// coneccion opcional
        /// </summary>
        public Db Db { get; }

        public string entityName { get; set; }

        public string? where { get; set; } = "";

        public string? having { get; set; }

        public string? fields { get; set; } = "";

        public string? select { get; set; } = "";

        public string? order { get; set; } = "";

        public int size { get; set; } = 100;

        public int page { get; set; } = 1;

        public string group { get; set; } = "";

        public Dictionary<string, object?> _parameters = new();

        public string join { get; set; } = "";

        private List<string> fieldNamesRel;

        public EntitySql(Db db, string entityName)
        {
            Db = db;
            this.entityName = entityName;
        }

        public bool ContainsFieldName(string fieldName)
        {
            return GetFieldNamesRel().Contains(fieldName);
        }

        public List<string> GetFieldNamesRel() {
            if(fieldNamesRel == null)
                fieldNamesRel = Db.FieldNamesRel(entityName);
            return fieldNamesRel;
        }


        public EntitySql And(string w)
        {
            if(where.IsNoE())
                where += w;
            else
                where = "(" + where + ") AND (" + w + ")";
            return this;
        }

        public EntitySql Or(string w)
        {
            if (where.IsNoE())
                where += w;
            else
                where = "(" + where + ") OR (" + w + ")";
            return this;
        }

        public EntitySql Where(string w)
        {
            where += " " + w;
            return this;
        }

        public EntitySql Param(string paramName, object? value)
        {
            this._parameters[paramName] = value;
            return this;
        }

        public EntitySql Params(IDictionary<string, object> parameters)
        {
            _parameters!.Merge(parameters!);
            return this;
        }

        /// <summary>Short form to define Where("fieldName = @fieldName).Param("@fieldName", value);
        /// 
        /// </summary>
        /// <param name="fieldName"></param>
        /// <param name="param"></param>
        /// <returns></returns>
        public EntitySql Equal(string fieldName, object? value)
        {
            string fn = "@"+fieldName.Replace("$", "");
            return Where(fieldName = " = " + fn).Param(fn, value);
        }

        public EntitySql Search(Data data)
        {
            var d = data.Dict();
            return Search(d);
        }

        /// <summary>
        /// Crear condicion de busqueda del diccionario
        /// </summary>
        /// <param name="param">Diccionario fieldName : Valor</param>
        /// <returns>this</returns>
        /// <remarks>Filtra los campos que pertenecen a la entidad</remarks>
        public EntitySql Search(IDictionary<string, object?> param)
        {
            foreach(var (key, value) in param)
                if (Db.FieldNamesRel(entityName).Contains(key) && !value.IsNoE())
                {
                    if (!where.IsNoE())
                        Where(" AND ");
                    Equal(key, value);
                }
            return this;
        }

        public EntitySql Unique(Data obj)
        {
            var d = obj.Dict();
            return Unique(d);
        }

        public EntitySql Unique(EntityValues values)
        {
            return Unique(values.Values());
        }

        public EntitySql Unique(IDictionary<string, object?> row)
        {
            if (row.IsNoE())
                throw new UniqueException("El parametro de condicion unica esta vacio");

            List<string> whereUniqueList = new();
            foreach (string fieldName in Db.Entity(entityName).unique)
            {
                foreach (var (key, value) in row)
                {
                    string k = key.Replace("$", "");

                    if (k == fieldName)
                    {
                        if(value == null)
                        {
                            whereUniqueList.Add("$" + k + " IS NULL");
                            break;
                        }
                        whereUniqueList.Add("$" + k + " = @" + k);
                        Param("@" + k, value);
                        break;
                    }
                }
            }

            string w = "";
            if (whereUniqueList.Count > 0)
                w = "(" + String.Join(") OR (", whereUniqueList) + ")";

            string ww;
            foreach(var um in Db.Entity(entityName).uniqueMultiple)
            {
                ww = UniqueMultiple(um, row);
                if (!ww.IsNoE())
                    w += (w.IsNoE()) ? ww : " OR " + ww;
            }

            ww = UniqueMultiple(Db.Entity(entityName).pk, row);
            if (!ww.IsNoE())
                w += (w.IsNoE()) ? ww : " OR " + ww;

            if (w.IsNoE())
                throw new UniqueException("No se pudo definir condicion de campo unico con el parametro indicado");

            where += (where.IsNoE()) ? w : " AND (" + w + ")";

            return this;
        }

        protected string UniqueMultiple(List<string> fields, IDictionary<string, object?> param)
        {
            if (fields.IsNoE())
                return "";

            bool existsUniqueMultiple = true;
            List<string> whereMultipleList = new();
            foreach(string field in fields)
            {
                if (!existsUniqueMultiple) 
                    break;

                existsUniqueMultiple = false;

                foreach(var (key, value) in param)
                {
                    string k = key.Replace("$", "");
                    if (k == field)
                    {
                        existsUniqueMultiple = true;
                        if (value == null)
                        {
                            whereMultipleList.Add("$" + k + " IS NULL");
                            break;
                        }
                        whereMultipleList.Add("$" + k + " = @" + k);
                        Param("@" + k, value);
                        break;
                    }
                }
                
                
            }
            if(existsUniqueMultiple && whereMultipleList.Count > 0)
                return "(" + String.Join(") AND (", whereMultipleList) + ")";                

            return "";
        }

        public EntitySql UniqueWithoutIdIfExists(IDictionary<string, object?> source)
        {
            Unique(source);

            if (source.ContainsKey(Db.config.id) && !source[Db.config.id]!.IsNoE())
                And("$" + Db.config.id + " != @" + Db.config.id).Param(Db.config.id, source[Db.config.id]!);

            return this;
        }

        public EntitySql Fields()
        {
            fields += string.Join(", ", Db.FieldNamesRel(entityName));
            return this;
        }

        public EntitySql Fields(string f)
        {
            fields += f;
            return this;
        }

        public EntitySql Fields(params string[] f)
        {
            fields += String.Join(", ", f);
            return this;
        }

        public EntitySql Select(string f)
        {
            select += f;
            return this;
        }

        protected string TraduceFields(string _sql)
        {
            if (_sql.IsNoE())
                return "";

            List<string> fields = _sql!.Replace("$", "").Split(',').ToList().Select(s => s.Trim()).ToList();

            #region procesar *
            List<string> fieldNamesToDelete = new();
            for (int i = 0; i < fields.Count; i++)
            {
                if (fields[i].Contains("*"))
                {
                    fieldNamesToDelete.Add(fields[i]);
                    var en = entityName;
                    var fid = "";
                    if (fields[i].Contains(Db.config.separator))
                    {
                        List<string> ff = fields[i].Split(Db.config.separator).ToList();
                        en = Db.Entity(entityName).relations[ff[0]].refEntityName;
                        fid = ff[0] + Db.config.separator;
                    }

                    List<string> fns = (List<string>)Db.FieldNames(en).AddPrefixToEnum(fid);
                    fields.AddRange(fns);
                }
            }

            foreach (var fntd in fieldNamesToDelete)
                fields.Remove(fntd);
            #endregion

            #region definir sql
            string sql = "";

            foreach (var fieldName in fields)
            {
                if (fieldName.Contains(Db.config.separator))
                {
                    List<string> ff = fieldName.Split(Db.config.separator).ToList();
                    sql += Db.Mapping(Db.Entity(entityName).relations[ff[0]].refEntityName, ff[0]).Map(ff[1]) + " AS '" + fieldName + "', ";
                } else
                    sql += Db.Mapping(entityName).Map(fieldName) + " AS '" + fieldName + "', ";
            }
            sql = sql.RemoveLastChar(',');
            #endregion

            return sql;
        }

        protected string Traduce(string _sql, bool fieldAs = false, string sqlToCheck = "")
        {
            string sql = "";
            int field_start = -1;

            for (int i = 0; i < _sql.Length; i++)
            {
                if (_sql[i] == '$')
                {
                    field_start = i;
                    continue;
                }

                if (field_start != -1)
                {
                    if ((_sql[i] != ' ') && (_sql[i] != ')') && (_sql[i] != ',')) continue;
                    string sqlField = Traduce_(_sql, field_start, i - field_start - 1, fieldAs);
                    if (!fieldAs || (fieldAs && !sqlToCheck.Contains(sqlField)))
                        sql += sqlField;
                    field_start = -1;
                }

                sql += _sql[i];

            }

            if (field_start != -1)
                sql += Traduce_(_sql, field_start, _sql.Length - field_start - 1, fieldAs);


            return sql;
        }

        protected string Traduce_(string _sql, int fieldStart, int fieldEnd, bool fieldAs)
        {
            var fieldName = _sql.Substring(fieldStart + 1, fieldEnd);

            string ff = "";
            if (fieldName.Contains(Db.config.separator))
            {
                List<string> fff = fieldName.Split(Db.config.separator).ToList();
                ff += Db.Mapping(Db.Entity(entityName).relations[fff[0]].refEntityName, fff[0]).Map(fff[1]);
            }
            else
                ff += Db.Mapping(entityName).Map(fieldName);

            if (fieldAs) ff += " AS '" + fieldName + "'";
            return ff;
        }

        /// <summary>Cantidad de filas del resultado</summary>
        /// <remarks>100 por defecto, 0 devolver todas</remarks>
        public EntitySql Size(int _size)
        {
            size = _size;
            return this;
        }


        /// <summary>Numero de pagina</summary>
        /// <remarks>Si size es 0, se ignora</remarks>
        public EntitySql Page(int _page)
        {
            page = _page;
            return this;
        }
       
        public EntitySql Order(string _order)
        {
            order = _order;
            return this;
        }

        public EntitySql Having(string h)
        {
            having += h;
            return this;
        }

        public EntitySql Group(string g)
        {
            group += g;
            return this;
        }

        /// <summary>Codigo adicional que se anexa a join</summary>
        /// <example>
        /// var subesql = db.Sql("movimiento_nota").
        ///     Select("$nota, MAX($fecha_inicio) AS fecha_inicio").
        ///     Group("$nota");
        /// esql.Join(@"INNER JOIN(" + subesql.Sql() + @") sub ON(sub.nota = $nota AND sub.fecha_inicio = $fecha_inicio)");
        /// </example>
        public EntitySql Join(string j)
        {
            join += j;
            return this;
        }

        public abstract EntitySql SelectMaxValue(string fieldName);

        protected string SqlJoin()
        {
            string sql = "";
            if (!Db.Entity(entityName).tree.IsNoE())
                sql += SqlJoinFk(Db.Entity(entityName).tree!, "", entityName, true);

            if (!join.IsNoE())
                sql += Traduce(join) + @"
";
            return sql;
        }

        protected string SqlJoinFk(Dictionary<string, EntityTree> tree, string table_id, string entityName, bool checkInner)
        {
            if (table_id.IsNoE())
                table_id = Db.Entity(entityName).alias;

            string sql = "";
            string schema_name;
            foreach (var (field_id, entity_tree) in tree) {
                schema_name = Db.Entity(entity_tree.refEntityName).schemaName;
                Field field = Db.Field(entityName, entity_tree.fieldName);
                string join = "";
                if (field.IsRequired() && checkInner)
                    join = "INNER";
                else
                {
                    join = "LEFT OUTER";
                    checkInner = false;
                }

                //string join = Db.Field(entityName, entity_tree.fieldName).IsRequired() ? "INNER" : "LEFT OUTER";
                sql += join + " JOIN " + schema_name + " AS " + field_id + " ON (" + table_id + "." + entity_tree.fieldName + " = " + field_id + "." + entity_tree.refFieldName + @")
";

                if (!entity_tree.children.IsNoE()) sql += SqlJoinFk(entity_tree.children, field_id, entity_tree.refEntityName, checkInner);
            }
            return sql;
        }

        public string Sql()
        {
            var sql = "SELECT DISTINCT ";
            sql += SqlFields();
            sql += SqlFrom();
            sql += SqlJoin();
            sql += SqlWhere();
            sql += SqlGroup();
            sql += SqlHaving();
            sql += SqlOrder();
            sql += SqlLimit();

            return sql;
        }

        protected string SqlWhere()
        {
            return (where.IsNoE()) ? "" : "WHERE " + Traduce(where!) + @"
";
        }

        /// <summary> Agrupamiento </summary>
        /// <remarks> Incluir obligatoriamente el nombre del campo en la lista de fields </remarks>
        protected string SqlGroup()
        {
            return (group.IsNoE()) ? "" : "GROUP BY " + Traduce(group!) + @"
";
        }

        protected string SqlHaving()
        {
            return (having.IsNoE()) ? "" : "HAVING " + Traduce(having!) + @"
";
        }

        protected abstract string SqlOrder();

        protected string _SqlFieldsInit()
        {
            if (this.fields.IsNoE() && this.select.IsNoE() && this.group.IsNoE())
                this.Fields();

            string f = TraduceFields(this.fields);

            f += Concat(Traduce(this.select, false, f), @",
", "", !f.IsNoE());

            f += Concat(Traduce(this.group, true, f), @",
", "", !f.IsNoE());

            return f + @"
";
        }

        protected abstract string SqlFields();

        protected string SqlFrom()
        {
            return @"FROM " + Db.Entity(entityName).schemaName + " AS " + Db.Entity(entityName).alias + @"
";
        }

        protected abstract string SqlLimit();
       
        protected string Concat(string? value, string connect_true, string connect_false = "", bool connect_cond = true)
        {
            if (value.IsNoE()) return "";

            string connect = "";
            if (connect_cond)
                connect = connect_true;
            else
                connect = connect_false;

            return connect + " " + value;
        }

        public override string ToString()
        {
            return Regex.Replace(entityName + where + having + fields + select + order + size + page + join + JsonConvert.SerializeObject(_parameters), @"\s+", "");
        }

        public abstract EntitySql Clone();

        protected EntitySql _Clone(EntitySql eq)
        {
            eq.entityName = entityName;
            eq.size = size;
            eq.where = where;
            eq.page = page;
            eq._parameters = _parameters;
            eq.group = group;
            eq.having = having;
            eq.fields = fields;
            eq.select = select;
            eq.order = order;
            eq.join = join;
            return eq;
        }

        /// <summary>Asignar parametros a una instancia de Query</summary>
        public Query Query(Query q)
        {
            string sql = Sql();
            q.sql = sql;
            q._parameters = _parameters;
            return q;
        }

        /// <summary>Crear y asignar parametros a una instancia de Query</summary>
        public Query Query()
        {
            return Query(Db.Query());
        }

        public EntityCache Cache()
        {
            return Db.Cache(this);
        }
    }
}
