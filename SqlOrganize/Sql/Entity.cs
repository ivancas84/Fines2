using Dapper;
using Newtonsoft.Json.Linq;
using SqlOrganize.DateTimeUtils;
using SqlOrganize.ValueTypesUtils;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Data;
using System.Reflection;
using System.Reflection.Metadata;
using System.Text;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Comportamiento general para las clases de datos
    /// </summary>
    public class Entity : INotifyPropertyChanged, IDataErrorInfo
    {
        public EntityMetadata Metadata { get; set; }

        #region entityName
        protected string _entityName;
        public virtual string entityName { get { return _entityName; } }
        #endregion

        #region Logging
        protected Logging _Logging = new Logging();
        public Logging Logging => _Logging;
        #endregion

        #region db
        protected Db _db;
        public Db db => _db;
        #endregion

        #region Errores WPF
        public bool ValidateEnabled { get; set; } = false;

        public string this[string columnName] => ValidateEnabled ? ValidateField(columnName) : string.Empty;


        /// <summary>
        /// Verificar error en propiedades
        /// Notificar cambio en propiedad sin hay un error (para que visualice el error en el formulario)
        /// </summary>
        /// <remarks>
        /// Devuelve un string con la concatenacion de todos los errores.
        /// </remarks>
        public string Error => GetErrorMessages();

        private string GetErrorMessages()
        {
            var properties = GetType().GetProperties();
            List<string> errors = new();

            foreach (var property in properties)
            {
                string error = this[property.Name];
                if (!string.IsNullOrEmpty(error))
                {
                    NotifyPropertyChanged(property.Name);
                    errors.Add(error);
                }
            }

            return string.Join(" - ", errors);
        }
        #endregion

        #region Label
        protected string _Label = "";

        public virtual string Label
        {
            get { return _Label; }
            set => SetProperty(ref _Label, value, nameof(Label));
        }
        #endregion

        #region Status (propiedad opcional para indicar estado)
        protected object? _Status;
        public virtual object? Status
        {
            get { return _Status; }
            set => SetProperty(ref _Status, value, nameof(Status));
        }
        #endregion


        /// <summary>Indice dentro de una coleccíón</summary>
        /// <remarks>Facilita la impresion del número de fila, por ejemplo/remarks>
        protected int index = 0;

        public int Index
        {
            get { return index; }
            set => SetProperty(ref index, value, nameof(Index));
        }

        /// <summary> Crear instancia de T utilizando serializacion a partir del id </summary>

        public static T CreateFromId<T>(object id) where T : Entity, new()
        {
            T _obj = new T(); //crear objeto vacio para obtener el entityName

            using (var connection = _obj.db.Connection().Open())
            {
                string sql = _obj.db.Sql().ById(_obj.entityName);

                return connection.QueryFirst<T>(
                    sql, 
                    new { Id = id }
                );
            }
        }

        /// <summary> Crear instancia de T utilizando serializacion a partir de key > value unicos </summary>

        public static T? CreateFromUnique<T>(string key, object value) where T : Entity, new()
        {
            T _obj = new T(); //crear objeto vacio para obtener el entityName

            using (var connection = _obj.db.Connection().Open())
            {
                string sql = _obj.db.Sql().ByKey(_obj.entityName, key);

                return connection.QueryFirst<T>(
                    sql,
                    new { Key = value }
                );
            }
        }

        /// <summary> Asignar propiedades sin utilizar serializacion </summary>
        public static T CreateFromObj<T>(object source) where T : Entity, new()
        {
            T obj = new T(); 

            // Get properties of both source and destination
            var sourceProperties = source.GetType().GetProperties();
            var destinationProperties = typeof(T).GetProperties();


            foreach (var destProp in destinationProperties)
            {
                // Find the matching source property by name and type
                var sourceProp = sourceProperties.FirstOrDefault(sp => sp.Name == destProp.Name);

                if (sourceProp != null && sourceProp.CanRead && destProp.CanWrite)
                {
                    // Copy the value from the source to the destination
                    destProp.SetValue(obj, sourceProp.GetValue(source));
                }
            }

            return obj;
        }
        
        public static T CreateEmpty<T>(string fieldName = "Label") where T : Entity, new()
        {
            T obj = new();
            obj.Set("id", null);
            obj.Set(fieldName, "-Seleccione " + obj.entityName.ToTitleCase() + "-");
            return obj;
        }

        #region set or get properties particulares
        public virtual object? Get(string fieldName)
        {
            PropertyInfo? property = GetType().GetProperty(fieldName);
            if(property == null) throw new Exception($"Propiedad {entityName}.{fieldName} no encontrada.");
            return property!.GetValue(this) ?? null;
        }

        //se mantiene para compatibilidad
        public virtual object? GetOrNull(string fieldName)
        {
            return Get(fieldName);
        }

        public virtual void Set(string fieldName, object? value)
        {
            PropertyInfo? propertyInfo = GetType().GetProperty(fieldName);
            if (propertyInfo == null || !propertyInfo.CanWrite)
                throw new Exception(entityName + "." + fieldName + " no existe o no tiene permisos de escritura");

            propertyInfo.SetValue(this, value);

        }

        public virtual void Sset(string fieldName, object? value)
        {
            PropertyInfo? propertyInfo = GetType().GetProperty(fieldName);
            if (propertyInfo == null || !propertyInfo.CanWrite)
                throw new Exception(entityName + "." + fieldName + " no existe o no tiene permisos de esctritura");


            if ((value == null) || (value.ToString() == ""))
            {
                propertyInfo.SetValue(this, null);
                return;
            }


            Field field = db.Field(entityName, fieldName);

            switch (field.type)
            {
                default:
                    propertyInfo.SetValue(this, Convert.ChangeType(value, propertyInfo.PropertyType));
                    return;

                case "int":
                    if (value is int)
                    {
                        propertyInfo.SetValue(this, value);
                        return;
                    }
                    propertyInfo.SetValue(this, Convert.ToInt32(value));
                    return;

                case "decimal":
                    if (value is decimal)
                    {
                        propertyInfo.SetValue(this, value);
                        return;
                    }
                    propertyInfo.SetValue(this, Convert.ToDecimal(value.ToString()!.Replace('.', ',')!));
                    return;


                case "bool":
                    if (value is decimal)
                    {
                        propertyInfo.SetValue(this, value);
                        return;
                    }

                    propertyInfo.SetValue(this, value.ToString().ToBool());
                    return;

                case "DateTime":
                    if (value is DateTime)
                    {
                        propertyInfo.SetValue(this, value);
                        return;
                    }

                    propertyInfo.SetValue(this, DateTime.Parse(value.ToString()!));
                    return;
            }
        }

        /// <summary> Asignar valor por defecto a propiedades simples </summary>
        public virtual void Default()
        {
            foreach (var fieldName in db.FieldNames(entityName))
            {
                var def = GetDefault(fieldName);
                Set(fieldName, def);
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
        /// <param name="dict">Diccionario de valores</param>
        /// <param name="prefix">Opcional, valor de prefijo</param>
        public virtual void Set(IDictionary<string, object?> dict, string prefix = "")
        {
            foreach (var fieldName in db.FieldNames(entityName))
                if(dict.ContainsKey(prefix+fieldName))
                    this.Set(fieldName, dict[prefix+fieldName]);
        }

        /// <summary> Seteo solo de valores no nulos </summary>
        public void SetNotNull(IDictionary<string, object?> dict, string prefix = "")
        {
            foreach (var fieldName in db.FieldNames(entityName))
                if (dict.ContainsKey(prefix + fieldName) && !dict[fieldName].IsNoE())
                    Set(fieldName, dict[fieldName]);
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

        public virtual void SetDefault(string fieldName)
        {
            this.Set(fieldName, GetDefault(fieldName));
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

                case "bool":
                    return field.defaultValue.ToString()!.ToBool();

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
                    var dv = field.defaultValue.ToString()!.ToLower();
                    if (dv.Contains("new") || dv.Contains("guid"))
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

        public T ShallowCopy<T>()
        {
            return (T)MemberwiseClone();
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

        /// <summary> Convertir propiedades simples a diccionario </summary>
        public virtual IDictionary<string, object?> ToDict()
        {
            Dictionary<string, object?> response = new();
            foreach (var fieldName in db.FieldNames(entityName))
                response[fieldName] = Get(fieldName);
            return response;
        }

        /// <summary> Resetear un determinado field </summary>
        public virtual void Reset(string fieldName)
        {
            Field field = db.Field(entityName, fieldName);

            foreach (var (resetKey, resetValue) in field.resets)
            {
                var rk = resetKey.ToLower();
                var val = Get(fieldName);
                switch (rk)
                {
                    case "trim":
                        if (!val.IsNoE())
                            this.Set(fieldName, val.ToString()!.Trim(((string)resetValue).ToChar()));
                        break;

                    case "removemultiplespaces":
                        if (!val.IsNoE())
                            this.Set(fieldName, Regex.Replace(val!.ToString()!, @"\s+", " "));
                        break;

                    case "nullifempty":
                        if (val.IsNoE())
                            this.Set(fieldName, null);
                        break;

                    case "defaultifnull":
                        if (val.IsNoE())
                            this.Set(fieldName, GetDefault(fieldName));
                        break;

                    case "nextifnull":
                        if (val.IsNoE())
                        {
                            var val_ = db.Sql().GetNextValue(entityName, field.name);
                            Sset(fieldName, val_);
                        }

                        break;

                    case "setdefault":
                        this.Set(fieldName, GetDefault(fieldName));
                        break;

                    case "cleandigits":
                        if (!val.IsNoE())
                            this.Set(fieldName, val.ToString().CleanStringOfDigits());
                        break;

                    case "cleannondigits":
                        if (val.IsNoE())
                            this.Set(fieldName, val.ToString().CleanStringOfNonDigits());
                        break;
                }
            }
        }

        /// <summary> Obtener valores por defecto para campos enteros </summary>
        protected object? GetDefaultInt(Field field)
        {
            if (field.defaultValue.ToString()!.ToLower().Contains("next"))
                return db.Sql().GetNextValue(field.entityName, field.name);
            
            if (field.defaultValue.ToString()!.ToLower().Contains("max"))
            {
                using (var connection = db.Connection().Open())
                {
                    string sql = db.Sql().MaxValue(entityName, field.name);

                    return connection.ExecuteScalar<long>(sql) + 1;
                }
            }

            return field.defaultValue.ToString().CleanStringOfNonDigits();            
        }

        /// <summary> Validacion de campos para WPF </summary>
        protected virtual string ValidateField(string fieldName)
        {
            Field field = db.Field(entityName, fieldName);
            if (field.IsRequired())
                if(IsNullOrEmpty(fieldName))
                    return "Debe completar valor.";

            if (field.IsUnique())
                if (IsNullOrEmpty(fieldName))
                {
                    using (var connection = db.Connection().Open())
                    {
                        string sql = db.Sql().IdKey(entityName, fieldName);
                        var value = Get(fieldName);
                        var id = connection.QuerySingleOrDefault<object>(
                            sql,
                            new { Key = value }
                        );

                        if (!id.IsNoE() && !Get(db.config.id)!.Equals(id))
                            return "Valor existente.";
                    }

                    
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
            Validation v = new(this.Get(fieldName));
            v.Clear();
            foreach (var (checkMethod, param) in field.checks)
            {
                switch (checkMethod)
                {
                    case "type":
                        v.Type((string)param);
                        break;
                    case "required":
                        if (param.ToBool())
                            v.Required();
                        break;

                }
            }

            foreach (var error in v.errors)
                Logging.AddErrorLog(key: fieldName, type: error.type, msg: error.msg);

            return !v.HasErrors();
        }



        /// <summary> Recargar valores particulares </summary>
        public void Reload<T>() where T : Entity, new()
        {
            var id = Get("id");

            if (!id.IsNoE())
            {
                T entity = CreateFromId<T>(id);
                Set(entity);
            }
        }

        public bool IsNullOrEmpty(params string[] fieldNames)
        {
            foreach (var fieldName in fieldNames)
                if (this.Get(fieldName).IsNoE())
                    return true;

            return false;
        }

        #endregion


        #region SQL
        public string InsertSql()
        {
            return db.PersistSql().Insert(this);
        }

        public string UpdateSql()
        {
            return db.PersistSql().Update(this);

        }

        /// <summary> Crea contexto de persistencia y actualiza campo </summary>
        public string UpdateKeyIdSql(string fieldName)
        {
            return db.PersistSql().UpdateKeyId(this, fieldName);
        }

        public string DeleteIdSql()
        {
            return db.PersistSql().DeleteId(this);
        }

        public string DeleteIdsSql()
        {
            return db.PersistSql().DeleteIds(this);
        }

        /// <summary> Crear contexto de persistencia y ejecutar persistencia de entidad </summary>
        /// <returns> Identificador del objeto persistido </returns>
        public string PersistSql<T>() where T: Entity
        {
            return db.PersistSql().Persist((T)this);
        }
      
        /// <summary> Persistencia rapida en base a Id </summary>
        public object PersistIdSql()
        {
            Reset();
            if (!Check())
                throw new Exception("Error al verificar " + Logging.ToString());

            if (this.Get(db.config.id).IsNoE())
                return InsertSql();
            else
                return UpdateSql();
        }
        #endregion

        #region OC
        public T AddToOC<T>(ObservableCollection<T> oc) where T : Entity
        {
            oc.Add((T)this);
            return (T)this;
        }

        public T InsertFirstToOC<T>(ObservableCollection<T> oc) where T : Entity
        {
            oc.Insert(0, (T)this);
            return (T)this;
        }
        #endregion

        #region INotifyPropertyChanged
        protected void SetProperty<T>(ref T field, T value, string propertyName)
        {
            if (!Equals(field, value))
            {
                field = value;
                NotifyPropertyChanged(propertyName);
            }
        }


        public event PropertyChangedEventHandler? PropertyChanged;

        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
        #endregion

        #region Compare
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

        #region dapper
        public object Persist<T>() where T : Entity
        {
            using (var connection = db.Connection().Open())
            {
                var sql = PersistSql<T>();
                connection.Execute(sql, this);
                return Get(db.config.id);
            }
        }

        public T? Unique<T>() where T : Entity
        {

            string sql = this.db.Sql().Unique(this);

            using (var connection = db.Connection().Open())
            {
                // Pass the Customer instance as the parameters
                return connection.QuerySingleOrDefault<T>(sql, this);
            }

        }
        
        public dynamic? Unique()
        {
            string sql = this.db.Sql().Unique(this);

            using (var connection = db.Connection().Open())
            {
                // Pass the Customer instance as the parameters
                return connection.QuerySingleOrDefault(sql, this);
            }

        }
        #endregion

   

    }

}
