
using System.Reflection;
using System.Text.RegularExpressions;
using SqlOrganize.ValueTypesUtils;
using SqlOrganize.DateTimeUtils;
using SqlOrganize.Sql.Exceptions;
using System;
using SqlOrganize.CollectionUtils;


namespace SqlOrganize.Sql
{
    /// <summary>
    /// Valores de la entidad. Se definen los siguientes métodos básicos de administración<br/>
    /// -sset: Seteo con cast y formateo<br/>
    /// -set: Seteo directo<br/>
    /// -check: Validar valor<br/>
    /// -default: Asignar valor por defecto<br/>
    /// -get: Retorno directo<br/>
    /// -json: Transformar a json<br/>
    /// -sql: Transformar a sql<br/>
    /// </summary>
    /// <remarks>
    /// Los valores son almacenados en una colección. La ventaja es que se puede utilizar el estado "NO DEFINIDO" (no existe en la colección).</br>
    /// Es necesario acceder a la base de datos para consultar la estructura y puede ser necesario para definir el valor por defecto de algunos elementos
    /// </remarks>
    public class EntityValues : EntityFieldId
    {
        /// <summary>Se mantiene una lista independiente de fieldNames por si se necesitan definir fieldNames adicionales a los de la db para procesamiento o comparacion</summary>
        protected List<string> fieldNames;

        protected Logging logging = new Logging();

        protected IDictionary<string, object?> values = new Dictionary<string, object?>();

        public EntityValues(Db _db, string _entityName, string? _fieldId = null) : base(_db, _entityName, _fieldId)
        {
            fieldNames = new List<string>(db.FieldNames(entityName));
        }

        public Logging Logging { get { return logging; } }

        public IDictionary<string, object?> Values()
        {
            return values;
        }

        public virtual EntityValues SetValues(IDictionary<string, object?> row)
        {
            values.Merge(row);
            return this;
        }

        public EntityValues SetValues(Data obj)
        {
            values = obj.Dict() ?? new Dictionary<string, object?>();
            return SetValues(obj.Dict());
        }

        /// <summary>Existe valor de field</summary>
        public bool ContainsKey(string fieldId)
        {
            return values.ContainsKey(fieldId);
        }

        public EntityValues Clear()
        {
            values.Clear();
            return this;
        }

        public virtual EntityValues Set(Data o)
        {
            var d = o.Dict();
            return Set(d);
        }

        public virtual EntityValues Sset(IDictionary<string, object?> row)
        {
            foreach (var fieldName in fieldNames)
                if (row.ContainsKey(Pf() + fieldName))
                    Sset(fieldName, row[Pf() + fieldName]);

            return this;
        }

        public virtual EntityValues Set(IDictionary<string, object?> row)
        {
            foreach (var fieldName in fieldNames)
                if (row.ContainsKey(Pf() + fieldName))
                    Set(fieldName, row[Pf() + fieldName]);

            return this;
        }

        public virtual EntityValues Set(string fieldName, object? value)
        {
            string fn = fieldName;
            if (!Pf().IsNoE() && fieldName.Contains(Pf()))
                fn = fieldName.Replace(Pf(), "");
            values[fn] = value;
            return this;
        }

        public EntityValues Remove(string fieldName)
        {
            values.Remove(fieldName);
            return this;
        }

        public string GetStr(string nullStr = "", string separator = " ", params string[] fieldNames)
        {
            string ret = "";
            foreach (var field in fieldNames)
            {
                ret += GetStr(field, nullStr) + separator;
            }
            return ret.RemoveLastString(separator);
        }

        public string GetStr(string fieldName, string nullStr = "")
        {
            return GetOrNull(fieldName)?.ToString() ?? nullStr;
        }

        public virtual object Get(string fieldName)
        {
            return values[fieldName]!;
        }

        public object? GetOrNull(string fieldName)
        {
            return values.ContainsKey(fieldName) ? values[fieldName] : null;

        }

        /// <summary>Todos los valores de fieldName definidos con prefijo (si existe)</summary>
        public IDictionary<string, object?> Get()
        {
            Dictionary<string, object?> response = new();
            foreach (var fieldName in fieldNames)
                if (values.ContainsKey(fieldName))
                    response[Pf() + fieldName] = values[fieldName]!;

            return response;
        }

        /// <summary>Formato SQL</summary>
        /// <remarks>La conversion de formato es realizada directamente por la libreria SQL, pero para ciertos casos puede ser necesario <br/></remarks>
        public object Sql(string fieldName)
        {
            if (!values.ContainsKey(fieldName))
                throw new Exception("Se esta intentando obtener valor de un campo no definido");

            var value = values[fieldName];

            if (value == null)
                return "null";

            Field field = db.Field(entityName, fieldName);

            switch (field.dataType) //solo funciona para tipos especificos, para mapear correctamente deberia almacenarse en field, el tipo original sql.
            {
                case "varchar":
                    return "'" + (string)value + "'";

                case "datetime": //puede que no funcione correctamente, es necesario almacenar el tipo original sql
                    return "'" + ((DateTime)value).ToString("u");

                default:
                    return value;

            }

        }

        public virtual object? ValueField(string fieldName, object? value)
        {
            Field field = db.Field(entityName, fieldName);
            if (value == null)
                return null;

            switch (field.type)
            {
                case "string":
                    try
                    {
                        return (string)value;
                    }
                    catch (Exception e)
                    {
                        return value.ToString();
                    }

                case "decimal":
                    if (value is decimal)
                        return value;
                    else
                    {
                        var v = value.ToString().Replace('.', ',')!;
                        return (v == "") ? null : decimal.Parse(v);
                    }

                case "int":
                case "Int32":
                    if (value is Int32)
                        return (Int32)value;
                    else
                    {
                        var v = value.ToString()!;
                        return (v == "") ? null : Int32.Parse(v);
                    }

                case "short":
                    if (value is short)
                        return value;
                    else
                    {
                        var v = value.ToString()!;
                        return (v == "") ? null : short.Parse(v);
                    }

                case "ushort":
                    if (value is ushort)
                        return value;
                    else
                    {
                        var v = value.ToString()!;
                        return (v == "") ? null : ushort.Parse(v);
                    }

                case "byte":
                    if (value is byte)
                        return value;
                    else
                    {
                        var v = value.ToString()!;
                        return (v == "") ? null : byte.Parse(v);
                    }

                case "bool":
                    if (value is bool)
                        return (bool)value;
                    else
                        return (value as string)!.ToBool();

                case "DateTime":
                    if (value is DateTime)
                        return (DateTime)value;
                    else
                        return DateTime.Parse(value.ToString()!);

                default:
                    return value;
            }
        }

        /// <summary>Seteo "lento", con verificacion y convercion de tipo de datos</summary>
        public virtual EntityValues Sset(string _fieldName, object? value)
        {
            string fieldName = CleanPf(_fieldName);

            if (fieldName.Contains("-"))
            {
                var (fid, fn, ren) = db.KeyDeconstruction(entityName, fieldName, "-");
                values[fieldName] = db.Values(ren).ValueField(fn, value);
            }
            else
            {

                values[fieldName] = ValueField(fieldName, value);
            }
            return this;
        }

        /// <summary>Resetear valores definidos</summary>
        /// <returns></returns>
        public virtual EntityValues Reset()
        {
            List<string> fieldNames = new List<string>(this.fieldNames);
            fieldNames.Remove(db.config.id); //id debe dejarse para el final porque puede depender de otros valores

            foreach (var fieldName in fieldNames)
                if (values.ContainsKey(fieldName))
                    Reset(fieldName);

            if (values.ContainsKey(db.config.id))
                Reset(db.config.id);

            return this;
        }

        /// <summary>Reasigna fieldName</summary>
        /// <remarks>fieldName debe estar definido obligatoriamente</remarks>
        public virtual EntityValues Reset(string fieldName)
        {
            Field field = db.Field(entityName, fieldName);

            foreach (var (resetKey, resetValue) in field.resets)
            {
                var rk = resetKey.ToLower();
                switch (rk)
                {
                    case "trim":
                        if (!values[fieldName].IsNoE())
                            values[fieldName] = values[fieldName]!.ToString()!.Trim(((string)resetValue).ToChar());
                        break;

                    case "removemultiplespaces":
                        if (!values[fieldName].IsNoE())
                            values[fieldName] = Regex.Replace(values[fieldName]!.ToString()!, @"\s+", " ");
                        break;

                    case "nullifempty":
                        if (values[fieldName].IsNoE())
                            values[fieldName] = null;
                        break;

                    case "defaultifnull":
                        if (values[fieldName].IsNoE())
                            values[fieldName] = GetDefault(fieldName);
                        break;

                    case "setdefault":
                        values[fieldName] = GetDefault(fieldName);
                        break;

                    case "cleandigits":
                        if (!values[fieldName].IsNoE())
                        {
                            values[fieldName] = values[fieldName]!.ToString().CleanStringOfDigits();
                        }
                        break;

                    case "cleannondigits":
                        if (!values[fieldName].IsNoE())
                        {
                            values[fieldName] = values[fieldName]!.ToString().CleanStringOfNonDigits();
                        }
                        break;
                }
            }

            return this;
        }

        /// <summary>Asignar valor por defecto para aquellos valores no definidos</summary>
        /// <returns></returns>
        public EntityValues Default()
        {
            foreach (var fieldName in fieldNames)
                Default(fieldName); //Default chequea la existencia del campo fieldName en Values

            return this;
        }

        /// <summary>
        /// Fuerza la asignacion de valor por defecto
        /// </summary>
        /// <param name="fieldName"></param>
        /// <returns></returns>
        public EntityValues SetDefault(string fieldName)
        {
            if (values.ContainsKey(fieldName))
                Remove(fieldName);
            return Default(fieldName);
        }

        /// <summary>
        /// Reset _Id
        /// </summary>
        /// <remarks>_Id depende de otros valores de la misma entidad, se reasigna luego de definir el resto de los valores</remarks>
        /// <example>db.Values("entityName").Set(source).Set("_Id", null).Reset("_Id"); //inicializa y reasigna _Id individualmente //<br/>
        /// db.Values("entityName").Set(source).Default().Reset() //inicializa y reasigna _Id conjuntamente</example>
        /// <returns></returns>
        public EntityValues Reset__Id()
        {
            List<string> fieldsId = db.Entity(entityName).id;
            foreach (string fieldName in fieldsId)
                if (!values.ContainsKey(fieldName) || values[fieldName].IsNoE())
                    return this; //no se reasigna si no esta definido o si es distinto de null

            if (fieldsId.Count == 1)
            {
                values["_Id"] = values[fieldsId[0]].ToString();
                return this;
            }

            List<string> valuesId = new();
            foreach (string fieldName in fieldsId)
                valuesId.Add(values[fieldName].ToString()!);

            values["_Id"] = String.Join(db.config.concatString, valuesId);
            return this;
        }

        /// <summary>Definir valor por defecto del field si no esta definido</summary>
        public EntityValues Default(string fieldName)
        {
            if (values.ContainsKey(fieldName))
                return this;

            values[fieldName] = GetDefault(fieldName);
            return this;
        }

        /// <summary>Verificar campos definidos</summary>
        /// <returns>true si la verificacion es correcta, false caso contrario</returns>
        /// <remarks>Para obtener los errores utilizar logging.ToString()</remarks>
        public bool Check()
        {
            logging.Clear();
            foreach (var fieldName in fieldNames)
                if (values.ContainsKey(fieldName))
                    Check(fieldName);

            return !logging.HasErrors();
        }

        /// <summary>Validar valor del field</summary>
        /// <param name="fieldName">Nombre del field a validar</param>
        /// <returns>Resultado de la validacion</returns>
        /// <remarks>El field debe estar definido obligatoriamente</remarks>
        public bool Check(string fieldName)
        {
            logging.ClearByKey(fieldName);

            Field field = db.Field(entityName, fieldName);
            Validation v = new(Get(fieldName));
            v.Clear();
            foreach (var (checkMethod, param) in field.checks)
            {
                switch (checkMethod)
                {
                    case "type":
                        v.Type((string)param);
                        break;
                    case "required":
                        if ((bool)param)
                            v.Required();
                        break;

                }
            }

            foreach (var error in v.errors)
                logging.AddErrorLog(key: fieldName, type: error.type, msg: error.msg);

            return !v.HasErrors();
        }

        public EntityValues SsetNotNull(IDictionary<string, object?> row)
        {
            foreach (var fieldName in fieldNames)
                if (row.ContainsKey(Pf() + fieldName))
                    if (!row[Pf() + fieldName].IsNoE())
                        Sset(fieldName, row[Pf() + fieldName]);

            return this;
        }

        public EntityValues SetNotNull(IDictionary<string, object?> row)
        {
            foreach (var fieldName in fieldNames)
                if (row.ContainsKey(Pf() + fieldName))
                    if (!row[Pf() + fieldName].IsNoE())
                        Set(fieldName, row[Pf() + fieldName]);

            return this;
        }

        /// <summary>Crear instancia de EntityValues de una relacion a partir de los valores definidos en la instancia</summary>
        public EntityValues GetValues(string fieldId)
        {
            EntityRelation rel = db.Entity(entityName).relations[fieldId];
            return db.Values(rel.refEntityName, fieldId).Set(Values());
        }

        /// <summary>Crear instancia de EntityValues obteniendo del cache o consulta los valores de la relacion</summary>
        public EntityValues? GetValuesCache(string fieldId)
        {
            EntityRelation rel = db.Entity(entityName).relations[fieldId];
            if (rel.parentId == null)
            {
                object? val = GetOrNull(rel.fieldName);
                if (!val.IsNoE())
                {
                    var data = db.Sql(rel.refEntityName).Cache()._Id(val!);
                    return db.Values(rel.refEntityName).Set(data!);
                }
            }
            else
            {
                EntityValues? values = GetValuesCache(rel.parentId);
                if (!values.IsNoE())
                    return values!.GetValuesCache(rel.fieldName);
            }
            return null;
        }

        /// <summary>Concatena strings indicados en el parametro</summary>
        public string ToStringFields(params string[] fields)
        {
            string s = "";
            foreach (string field in fields)
            {
                s += GetOrNull(field)?.ToString() ?? "?";
                s += ", ";
            }
            return s;
        }

        public string ToStringExcept(params string[] fields)
        {
            foreach (string field in fields)
                fieldNames.Remove(field);

            var label = "";
            foreach (string fieldName in fieldNames)
            {
                label += GetOrNull(fieldName)?.ToString() ?? " ";
                label += ", ";
            }

            return label.RemoveMultipleSpaces().Trim();
        }

        public override string ToString()
        {
            List<string> fieldNames = MainKeys();

            var label = "";
            foreach (string fieldName in fieldNames)
            {
                label += GetOrNull(fieldName)?.ToString() ?? " ";
                label += ", ";
            }

            label = label.RemoveLastChar(',').Trim();

            return label.RemoveMultipleSpaces().Trim();
        }

        /// <summary>Retorna una lista de los fields principales</summary>
        /// <remarks>Utilizados principalmente para Label</remarks>
        protected List<string> MainKeys()
        {
            var entity = db.Entity(entityName);
            List<string> fields = new();
            foreach (string f in entity.unique)
                if (entity.notNull.Contains(f))
                    fields.Add(f);

            bool uniqueMultipleFlag = true;
            foreach (List<string> um in entity.uniqueMultiple)
            {
                foreach (string f in um)
                    if (!entity.notNull.Contains(f))
                    {
                        uniqueMultipleFlag = false;
                        break;
                    }

                if (uniqueMultipleFlag)
                    foreach (var f in um)
                        fields.Add(f);

                uniqueMultipleFlag = true;
            }

            if (fields.IsNoE())
                fields = entity.notNull;

            if (fields.IsNoE())
                fields = entity.fields;

            if (fields.Count() > 1 && fields.Contains(db.config.id))
                fields.Remove(db.config.id);

            return fields;
        }

        public (string? fieldId, string fieldName, string entityName, object? value) ParentVariables(string mainEntityName)
        {
            object? value;
            string fieldName;
            string entityName = mainEntityName;
            string? newFieldId = null;

            string? parentId = db.Entity(mainEntityName).relations[fieldId!].parentId;
            if (parentId != null)
            {
                //sea por ejemplo alumnoT.personaF (con fieldId alumno) = personaT.id (con fieldId = persona), entones:
                //parentFieldName = personaF
                //value = personaValues.values["id"]
                //fieldId = alumno
                //fieldName = personaF
                //entityName = alumnoT
                string parentFieldName = db.Entity(mainEntityName).relations[fieldId!].fieldName;
                value = values[db.Entity(mainEntityName).relations[fieldId!].refFieldName];
                newFieldId = parentId;
                fieldName = parentFieldName;
                entityName = db.Entity(mainEntityName).relations[parentId].refEntityName;

            }
            else
            {
                fieldName = db.Entity(mainEntityName).relations[fieldId!].fieldName;
                value = values[db.Entity(mainEntityName).relations[fieldId!].refFieldName];
            }

            return (newFieldId, fieldName, entityName, value);
        }

        /// <summary>Devolver valor por defecto de field</summary>
        /// <remarks>El valor especial "?" indica que se define valor por defecto fuera del modelo<br/>
        /// El valor especial "max" indica que se define valor maximo posible<br/>
        /// El valor especial "next" indica que se define valor siguiente de la secuencia</remarks>
        public virtual object? GetDefault(string fieldName)
        {
            var field = db.Field(entityName, fieldName);

            if (field.defaultValue is null || field.defaultValue.ToString()!.StartsWith("?"))
                return null;

            switch (field.type)
            {
                case "string":
                    if (field.defaultValue.ToString()!.ToLower().Contains("guid"))
                        return (Guid.NewGuid()).ToString();

                    //generate random strings
                    else if (field.defaultValue.ToString()!.ToLower().Contains("random"))
                    {
                        string param = field.defaultValue.ToString()!.SubstringBetween("(", ")");
                        return ValueTypesUtils.Utils.RandomString(Int32.Parse(param));
                    }
                    else
                        return field.defaultValue;
                case "DateTime":
                    if (field.defaultValue.ToString()!.ToLower().Contains("cur") ||
                        field.defaultValue.ToString()!.ToLower().Contains("getdate")
                    )
                        return DateTime.Now;
                    else
                        return field.defaultValue;

                case "sbyte":
                    return Convert.ToSByte(GetDefaultInt(field));

                case "byte":
                    return Convert.ToByte(GetDefaultInt(field));

                case "long":
                    return Convert.ToInt64(GetDefaultInt(field));

                case "ulong":
                    return Convert.ToUInt64(GetDefaultInt(field));

                case "int":
                case "nint":
                    return Convert.ToInt32(GetDefaultInt(field));

                case "uint":
                case "nuint":
                    return Convert.ToUInt32(GetDefaultInt(field));

                case "short":
                    //el tipo YEAR de mysql es mapeado a short
                    if (field.defaultValue.ToString()!.ToLower().Contains("current_year"))
                        return Convert.ToInt16(DateTime.Now.Year);

                    if (field.defaultValue.ToString()!.ToLower().Contains("current_semester"))
                        return DateTime.Now.ToSemester();

                    return Convert.ToInt16(GetDefaultInt(field));

                case "ushort":
                    return Convert.ToUInt16(GetDefaultInt(field));

                case "Guid":
                    if (field.defaultValue.ToString()!.ToLower().Contains("new"))
                        return Guid.NewGuid();
                    else
                    {
                        var guidString = Regex.Replace(field.defaultValue.ToString()!, @"[^a-zA-Z0-9-]", string.Empty);
                        return Guid.Parse(guidString);
                    }


                default:
                    return field.defaultValue;
            }
        }

        protected object? GetDefaultInt(Field field)
        {
            if (field.defaultValue.ToString()!.ToLower().Contains("next"))
            {
                ulong next = (ulong)db.Query().GetNextValue(field.entityName);
                return next;
            }
            else if (field.defaultValue.ToString()!.ToLower().Contains("max"))
            {
                object max_ = db.Query().GetMaxValue(field.entityName, field.name);
                long max = Convert.ToInt64(max_);
                return max + 1;
            }
            else
            {
                return field.defaultValue;
            }
        }

        public bool IsNullOrEmpty(string fieldName)
        {
            return GetOrNull(fieldName).IsNoE();
        }

        public bool IsNullOrEmpty(params string[] fieldNames)
        {
            foreach (string fieldName in fieldNames)
            {
                if (IsNullOrEmpty(fieldName))
                    return true;
            }

            return false;
        }

        public EntityValues ResetLabels()
        {

            foreach (var (fieldId, rel) in this.db.Entity(entityName).relations)
            {

                var values = GetValuesCache(fieldId);
                if (values != null)
                    Set(fieldId + "-Label", values.ToString());
                else
                    Set(fieldId + "-Label", null);
            }

            return this;
        }
        public virtual T GetData<T>() where T : Data, new()
        {
            ResetLabels();
            var obj = db.Data<T>(Values());
            if (Logging.HasLogs())
                obj.Msg += Logging.ToString();

            return obj;
        }

        /// <summary>
        /// Comparar valores con los indicados en parametro
        /// </summary>
        /// <param name="val">Valores externos a persistir<</param>
        /// <param name="ignoreFields">Campos que seran ignorados en la comparacion<</param>
        /// <param name="ignoreNull">Si el campo del parametro es nulo, sera ignorado en la comparacion<</param>
        /// <param name="ignoreNonExistent">Si el campo no esta definido localmente, sera ignorado en la comparacion</param>

        /// <returns>Valores del parametro que son diferentes o que no estan definidos localmente</returns>
        /// <remarks>Solo compara fieldNames</remarks>
        public virtual IDictionary<string, object?> Compare(CompareParams cp)
        {
            Dictionary<string, object?> dict1_ = new(values);
            Dictionary<string, object?> dict2_ = new(cp.val.Values());
            Dictionary<string, object?> response = new();


            if (!cp.ignoreFields.IsNoE())
                foreach (var key in cp.ignoreFields!)
                {
                    dict1_.Remove(key);
                    dict2_.Remove(key);
                }

            if (!cp.fieldsToCompare.IsNoE())
            {
                foreach (var fieldName in fieldNames)
                {
                    if (!cp.fieldsToCompare.Contains(fieldName))
                    {
                        dict1_.Remove(fieldName);
                        dict2_.Remove(fieldName);
                    }
                }
            }

            foreach (var fieldName in fieldNames)
            {
                if (cp.ignoreNonExistent && (!dict1_.ContainsKey(fieldName) || !dict2_.ContainsKey(fieldName)))
                    continue;

                if (cp.ignoreNull && (!dict2_.ContainsKey(fieldName) || dict2_[fieldName].IsNoE()))
                    continue;

                if (!dict1_.ContainsKey(fieldName) && dict2_.ContainsKey(fieldName))
                {
                    response[fieldName] = dict2_[fieldName];
                    continue;
                }

                if (dict1_.ContainsKey(fieldName) && !dict2_.ContainsKey(fieldName))
                {
                    response[fieldName] = "UNDEFINED";
                    continue;
                }

                if (dict1_[fieldName].IsNoE() && dict2_[fieldName].IsNoE())
                    continue;

                if (dict1_[fieldName].IsNoE() && !dict2_[fieldName].IsNoE())
                {
                    response[fieldName] = dict2_[fieldName];
                    continue;
                }

                if (!dict1_[fieldName].IsNoE() && dict2_[fieldName].IsNoE())
                {
                    response[fieldName] = dict2_[fieldName];
                    continue;
                }

                if (!dict1_[fieldName]!.ToString()!.ToLower().Trim()!.Equals(dict2_[fieldName]!.ToString()!.ToLower().Trim()!))
                {
                    response[fieldName] = dict2_[fieldName];
                    continue;
                }
            }
            return response;
        }

        public EntityPersist Insert()
        {
            return db.Persist().Insert(this);
        }

        public EntityPersist Update()
        {
            return db.Persist().Update(this);
        }

        public EntityValues Insert(EntityPersist persist)
        {
            persist.Insert(this);
            return this;
        }

        public EntityValues Update(EntityPersist persist)
        {
            persist.Update(this);
            return this;
        }

        public EntityPersist PersistId()
        {
            if (Get(db.config.id).IsNoE())
            {
                Default().Reset();
                if (!Check())
                    throw new Exception("Error al insertar " + logging.ToString());
                return Insert();
            }
            else
            {
                Reset();
                if (!Check())
                    throw new Exception("Error al actualizar " + logging.ToString());
                return Update();
            }
        }

        public EntityPersist Persist()
        {
            return db.Persist().Persist(this);

        }

        public EntityValues Persist(EntityPersist persist)
        {
            persist.Persist(this);
            return this;
        }

        public EntityPersist PersistCondition(object? condition)
        {
            return db.Persist().PersistCondition(this, condition);

        }

        public EntityValues PersistCondition(EntityPersist persist, object? condition)
        {
            persist.PersistCondition(this, condition);
            return this;
        }

        public EntityValues PersistCompare(EntityPersist persist, CompareParams compare)
        {
            persist.PersistCompare(this, compare);
            return this;
        }

        public EntityPersist? PersistCompare(CompareParams compare)
        {
            return db.Persist().PersistCompare(this, compare);
        }

        public EntityValues InsertIfNotExists(EntityPersist persist)
        {
            persist.InsertIfNotExists(this);
            return this;
        }

        public EntityPersist InsertIfNotExists()
        {
            return db.Persist().InsertIfNotExists(this);
        }

        public EntitySql SqlField(string fieldName)
        {
            return db.Sql(entityName).Equal(fieldName, Get(fieldName));
        }

        public EntitySql SqlUniqueWithoutIdIfExists()
        {
            return db.Sql(entityName).UniqueWithoutIdIfExists(Values());
        }

        public EntitySql SqlUnique()
        {
            return db.Sql(entityName).Unique(Values());
        }

        public EntitySql SqlUniqueFieldsOrValues(string fieldName)
        {
            if (this.db.Field(entityName, fieldName).IsUnique())
                return SqlField(fieldName);
            else
                return SqlUniqueWithoutIdIfExists();
        }

        public EntityValues ReloadValues()
        {
            if (!IsNullOrEmpty("id"))
            {
                var data = db.Sql(entityName).Equal("id", Get("id")!).Dict();
                return (data.IsNoE()) ? this : SetValues(data!);
            }
            return this;
        }

        public EntityValues ReloadSet()
        {
            if (!IsNullOrEmpty("id"))
            {
                var data = db.Sql(entityName).Equal("id", Get("id")!).Dict();
                return (data.IsNoE()) ? this : Set(data);

            }
            return this;
        }

    }


    public class CompareParams
    {
        public EntityValues val  { get; set; }
        public IEnumerable<string>? ignoreFields { get; set; } = null;
        public bool ignoreNull { get; set; } = true;
        public bool ignoreNonExistent { get; set; } = true;
        public IEnumerable<string>? fieldsToCompare { get; set; } = null;
    }

}
