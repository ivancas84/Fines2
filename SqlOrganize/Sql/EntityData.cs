using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using SqlOrganize.DateTimeUtils;
using SqlOrganize.ValueTypesUtils;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Reflection;
using System.Reflection.Emit;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Comportamiento general para las clases de datos
    /// </summary>
    public abstract class EntityData : INotifyPropertyChanged, IDataErrorInfo
    {
        #region atributos y propiedades generales
        /// <summary>Campos a ignorar para marcar isUpdated</summary>
        protected List<string> _isUpdatedIgnore = new() { nameof(IsUpdated), nameof(Msg), nameof(Error) };

        protected string _entityName;
        public virtual string entityName { get { return _entityName; } }

        protected Logging _Logging = new Logging();
        public Logging Logging { get { return _Logging; } }


        /// <summary>Flag opcional para indicar que debe ejecutarse la validacion</summary>
        public bool _Validate = false;

        /// <summary>
        /// Si se construye una instancia de data con valores por defecto, puede ser necesario acceder a la base de datos para definirlos.
        /// Db no debe definirse como propiedad para evitar errores en la serializacion/deserializacion
        /// </summary>
        protected Db _db;
        public Db db { get { return _db; } }

        public string this[string columnName]
        {
            get
            {
                if (!_Validate)
                    return "";

                // If there's no error, empty string gets returned
                return ValidateField(columnName);
            }
        }


        /// <summary>
        /// Verificar error en propiedades
        /// Notificar cambio en propiedad sin hay un error (para que visualice el error en el formulario)
        /// </summary>
        /// <remarks>
        /// Devuelve un string con la concatenacion de todos los errores.
        /// </remarks>
        public string Error
        {
            get
            {
                PropertyInfo[] properties = this.GetType().GetProperties();

                List<string> errors = new();
                foreach (PropertyInfo property in properties)
                    if (this[property.Name] != "")
                    {
                        NotifyPropertyChanged(property.Name);
                        errors.Add(this[property.Name]);
                    }

                if (errors.Count > 0)
                    return String.Join(" - ", errors.ToArray());

                return "";
            }
        }

        protected string _Msg = "";
        public string Msg
        {
            get { return _Msg; }
            set
            {
                if (_Msg != value)
                {
                    _Msg = value;
                    NotifyPropertyChanged(nameof(Msg));
                }
            }
        }

        /// <summary>Propiedad opcional para indicar que se esta actualizando</summary>
        /// <remarks>Cargar en false al finalizar la inicializacion</remarks>
        protected string _Label = "";

        public virtual string Label
        {
            get { return _Label; }
            set
            {
                if (_Label != value)
                {
                    _Label = value;
                    NotifyPropertyChanged(nameof(Label));
                }
            }
        }

        /// <summary>Propiedad opcional para indicar que se esta actualizando</summary>
        /// <remarks>Cargar en false al finalizar la inicializacion</remarks>
        public bool isUpdated = false;

        public bool IsUpdated
        {
            get { return isUpdated; }
            set
            {
                if (isUpdated != value)
                {
                    isUpdated = value;
                    NotifyPropertyChanged(nameof(IsUpdated));
                }
            }
        }

        /// <summary>Propiedad opcional para indicar que ya existe en la base de datos</summary>
        protected bool isPersisted = false;

        public bool IsPersisted
        {
            get { return isPersisted; }
            set
            {
                if (isPersisted != value)
                {
                    isPersisted = value;
                    NotifyPropertyChanged(nameof(IsPersisted));
                }
            }
        }


        /// <summary>Indice dentro de una coleccíón</summary>
        /// <remarks>Facilita la impresion del número de fila, por ejemplo/remarks>
        protected int index = 0;

        public int Index
        {
            get { return index; }
            set
            {
                index = value; //por el momento no ejecuta NotifyPropertyChanged
            }
        }

        /// <summary>Propiedad opcional para indicar que se esta actualizando</summary>
        /// <remarks>Cargar en false al finalizar la inicializacion</remarks>
        protected bool isError = false;

        public bool IsError
        {
            get { return isError; }
            set
            {
                if (isError != value)
                {
                    isError = value;
                    NotifyPropertyChanged(nameof(IsError));
                }
            }
        }
        #endregion

        #region set or get properties particulares
        /// <summary> Asignar valor por defecto a propiedades simples </summary>
        public virtual void Default()
        {
            foreach (var fieldName in db.FieldNames(entityName))
            {
                var def = GetDefault(fieldName);
                this.SetPropertyValue(fieldName, def);
            }
        }

        /// <summary> Setear objeto a propiedades simples </summary>
        /// <remarks> Para setear el arbol utilizar Db.Data() </remarks>
        public virtual void Set(object obj)
        {
            Set(obj.Dict());
        }

        /// <summary> Setear diccionario a propiedades simples </summary>
        /// <remarks> Para setear el arbol utilizar Db.Data() </remarks>
        public virtual void Set(IDictionary<string, object?> dict)
        {
            foreach (var fieldName in db.FieldNames(entityName))
                this.SetPropertyValue(fieldName, dict[fieldName]);
        }

        /// <summary> Seteo solo de valores no nulos </summary>
        public void SetNotNull(IDictionary<string, object?> dict)
        {
            foreach (var fieldName in db.FieldNames(entityName))
                if (!dict[fieldName].IsNoE())
                    this.SetPropertyValue(fieldName, dict[fieldName]);
        }

        /// <summary> Seteo "lento" de propiedades simples </summary>
        public virtual void Sset(IDictionary<string, object?> dict)
        {
            foreach (var fieldName in db.FieldNames(entityName))
                Sset(fieldName, dict[fieldName]);
        }

        /// <summary> Seteo lento solo de valores no nulos </summary>
        public void SsetNotNull(IDictionary<string, object?> dict)
        {
            foreach (var fieldName in db.FieldNames(entityName))
                if (!dict[fieldName].IsNoE())
                    Sset(fieldName, dict[fieldName]);
        }

        /// <summary>Seteo "lento" de un determinado campo, con verificacion y convercion de tipo de datos</summary>
        public virtual void Sset(string fieldName, object? value)
        {

            this.SetPropertyValue(fieldName, ValueField(fieldName, value));
        }

        public virtual void SetDefault(string fieldName)
        {
            this.SetPropertyValue(fieldName, GetDefault(fieldName));
        }

        /// <summary> Realizar cast y otras operaciones al parametro value, para definir correctamente el valor del field </summary>
        /// <remarks>Utilizado para seteo "lento"</remarks>
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

        /// <summary> Obtener valor por defecto de un field </summary>
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

        /// <summary> Resetear valores </summary>
        /// <remarks> El reseteo tiene lugar por ejemplo para mejorar la sintaxis (eliminar espacios en blanco) o cuando un valor depende de otros </remarks>
        public virtual void Reset()
        {
            List<string> fieldNames = new List<string>(db.FieldNames(entityName));
            fieldNames.Remove(db.config.id); //id debe dejarse para el final porque puede depender de otros valores

            foreach (var fieldName in fieldNames)
                Reset(fieldName);

            Reset(db.config.id);
        }

        /// <summary> Convertir propiedades particulares a diccionario </summary>
        public virtual IDictionary<string, object?> ToDict()
        {
            Dictionary<string, object?> response = new();
            foreach (var fieldName in db.FieldNames(entityName))
                response[fieldName] = this.GetPropertyValue(fieldName);
            return response;
        }

        /// <summary> Resetear un determinado field </summary>
        
        public virtual void Reset(string fieldName)
        {
            Field field = db.Field(entityName, fieldName);

            foreach (var (resetKey, resetValue) in field.resets)
            {
                var rk = resetKey.ToLower();
                var val = this.GetPropertyValue(fieldName);
                switch (rk)
                {
                    case "trim":
                        if (!val.IsNoE())
                            this.SetPropertyValue(fieldName, val.ToString()!.Trim(((string)resetValue).ToChar()));
                        break;

                    case "removemultiplespaces":
                        if (!val.IsNoE())
                            this.SetPropertyValue(fieldName, Regex.Replace(val!.ToString()!, @"\s+", " "));
                        break;

                    case "nullifempty":
                        if (val.IsNoE())
                            this.SetPropertyValue(fieldName, null);
                        break;

                    case "defaultifnull":
                        if (val.IsNoE())
                            this.SetPropertyValue(fieldName, GetDefault(fieldName));
                        break;

                    case "setdefault":
                        this.SetPropertyValue(fieldName, GetDefault(fieldName));
                        break;

                    case "cleandigits":
                        if (!val.IsNoE())
                            this.SetPropertyValue(fieldName, val.ToString().CleanStringOfDigits());
                        break;

                    case "cleannondigits":
                        if (val.IsNoE())
                            this.SetPropertyValue(fieldName, val.ToString().CleanStringOfNonDigits());
                        break;
                }
            }
        }

        /// <summary> Obtener valores por defecto para campos enteros </summary>
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
                return field.defaultValue.ToString().CleanStringOfNonDigits();
            }
        }

        /// <summary> Validacion de campos para WPF </summary>
        protected virtual string ValidateField(string fieldName)
        {
            Field field = db.Field(entityName, fieldName);
            if (field.IsRequired())
                if(this.GetPropertyValue(fieldName).IsNoE())
                    return "Debe completar valor.";

            if (field.IsUnique())
                if (this.GetPropertyValue(fieldName).IsNoE())
                {
                    var row = db.Sql(entityName).Equal("$" + fieldName, this.GetPropertyValue(fieldName)).Cache().Dict();
                    if (!row.IsNoE() && !this.GetPropertyValue(db.config.id).Equals(row[db.config.id]))
                        return "Valor existente.";
                }

            return "";
        }

        public bool Check()
        {
            Logging.Clear();
            foreach (var fieldName in db.FieldNames(entityName))
                Check(fieldName);

            return !Logging.HasErrors();
        }

        /// <summary>Validar valor del field</summary>
        /// <param name="fieldName">Nombre del field a validar</param>
        /// <returns>Resultado de la validacion</returns>
        /// <remarks>El field debe estar definido obligatoriamente</remarks>
        public bool Check(string fieldName)
        {
            Logging.ClearByKey(fieldName);

            Field field = db.Field(entityName, fieldName);
            Validation v = new(this.GetPropertyValue(fieldName));
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
                Logging.AddErrorLog(key: fieldName, type: error.type, msg: error.msg);

            return !v.HasErrors();
        }

       

        /// <summary> Recargar valores particulares </summary>
        public void Reload()
        {
            var id = this.GetPropertyValue("id");

            if (id.IsNoE())
            {
                var data = db.Sql(entityName).Equal("id", id).Dict();
                Set(data);
            }
        }

        public bool IsNullOrEmpty(params string[] fieldNames)
        {
            foreach (var fieldName in fieldNames)
                if (this.GetPropertyValue(fieldName).IsNoE())
                    return true;

            return false;
        }

        #endregion

        #region Sql
        public EntitySql SqlField(string fieldName)
        {
            return db.Sql(entityName).Equal(fieldName, this.GetPropertyValue(fieldName));
        }

        public EntitySql SqlUniqueWithoutIdIfExists()
        {
            return db.Sql(entityName).UniqueWithoutIdIfExists(ToDict());
        }

        public EntitySql SqlUnique()
        {
            return db.Sql(entityName).Unique(ToDict());
        }
        public EntitySql SqlUniqueFieldsOrValues(string fieldName)
        {
            if (db.Field(entityName, fieldName).IsUnique())
                return SqlField(fieldName);
            else
                return SqlUniqueWithoutIdIfExists();
        }

        public EntitySql SqlRef(string entityName, string fkName)
        {
            return db.Sql(entityName).Equal(fkName, this.GetPropertyValue("id"));
        }
        #endregion

        #region Persistencia
        public EntityPersist Insert()
        {
            return db.Persist().Insert(this);
        }

        public EntityPersist Update()
        {
            return db.Persist().Update(this);
        }

        public EntityPersist UpdateField(string fieldName)
        {
            return db.Persist().UpdateField(this, fieldName);
        }

        public EntityData Insert(EntityPersist persist)
        {
            persist.Insert(this);
            return this;
        }

        public EntityData InsertIfNotExists(EntityPersist persist)
        {
            persist.InsertIfNotExists(this);
            return this;
        }

        public EntityData Update(EntityPersist persist)
        {
            persist.Update(this);
            return this;
        }

        public EntityPersist Delete()
        {
            return db!.Persist().DeleteIds(entityName, this.GetPropertyValue("id"));
        }

        public EntityPersist Persist()
        {
            return db!.Persist().Persist(this);
        }

        public EntityData Persist(EntityPersist persist)
        {
            persist.Persist(this);
            return this;
        }

        public EntityPersist PersistCondition(object? condition)
        {
            return db.Persist().PersistCondition(this, condition);

        }

        public EntityPersist PersistId()
        {
            Reset();
            if (!Check())
                throw new Exception("Error al verificar " + Logging.ToString());

            if (this.GetPropertyValue(db.config.id).IsNoE())
                return Insert();
            else
                return Update();
        }

        public EntityData PersistCompare(EntityPersist persist, CompareParams compare)
        {
            persist.PersistCompare(this, compare);
            return this;
        }

        public EntityPersist? PersistCompare(CompareParams compare)
        {
            return db.Persist().PersistCompare(this, compare);
        }
        #endregion

        public T AddToOC<T>(ObservableCollection<T> oc) where T : EntityData
        {
            oc.Add((T)this);
            return (T)this;
        }

        #region INotifyPropertyChanged
        public event PropertyChangedEventHandler? PropertyChanged;

        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            if (!_isUpdatedIgnore.Contains(propertyName))
                IsUpdated = true;

            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
        #endregion

        #region Otros metodos
        /// <summary>
        /// Comparacion de diccionarios
        /// </summary>
        /// <param name="cp"></param>
        /// <returns></returns>
        public virtual IDictionary<string, object?> Compare(CompareParams cp)
        {
            Dictionary<string, object?> dict1_ = new(ToDict());
            Dictionary<string, object?> dict2_ = new(cp.Data);
            Dictionary<string, object?> response = new();

            if (!cp.IgnoreFields.IsNoE())
                foreach (var key in cp.IgnoreFields!)
                {
                    dict1_.Remove(key);
                    dict2_.Remove(key);
                }

            if (!cp.FieldsToCompare.IsNoE())
            {
                foreach (var fieldName in db.FieldNames(entityName))
                {
                    if (!cp.FieldsToCompare!.Contains(fieldName))
                    {
                        dict1_.Remove(fieldName);
                        dict2_.Remove(fieldName);
                    }
                }
            }

            foreach (var fieldName in db.FieldNames(entityName))
            {
                if (cp.IgnoreNonExistent && (!dict1_.ContainsKey(fieldName) || !dict2_.ContainsKey(fieldName)))
                    continue;

                if (cp.IgnoreNull && (!dict2_.ContainsKey(fieldName) || dict2_[fieldName].IsNoE()))
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
        #endregion

    }

}
