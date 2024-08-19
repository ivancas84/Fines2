using Newtonsoft.Json;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Reflection;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Comportamiento general para las clases de datos
    /// </summary>
    public class Data : INotifyPropertyChanged, IDataErrorInfo
    {
        public virtual string entityName { get; }

        private EntityValues? values;

        /// <summary>Campos a ignorar para marcar isUpdated</summary>
        public List<string> _isUpdatedIgnore = new() { nameof(IsUpdated), nameof(Msg), nameof(Error) };

        /// <summary>Flag opcional para indicar que debe ejecutarse la validacion</summary>
        public bool _Validate = false;

        /// <summary>
        /// Si se construye una instancia de data con valores por defecto, puede ser necesario acceder a la base de datos para definirlos.
        /// Db no debe definirse como propiedad para evitar errores en la serializacion/deserializacion
        /// </summary>
        public Db? db;

        public virtual void SetDb(Db db)
        {
            this.db = db;
        }

        /// <summary>Obtener instancia de values asociada</summary>
        /// <remarks>Debe estar definida la instancia de Db</remarks>
        public EntityValues GetValues()
        {
            if (values == null)
                values = db!.Values(entityName).SetValues(this);
            return values;
        }

        /// <summary>Obtener instancia de values asociadas y realizar un cast</summary>
        public T GetValues<T>() where T : EntityValues
        {
            return (T)GetValues();
        }

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

        public virtual void Default()
        {
            //sobrescribir si se deben definir valores por defecto
        }

        protected virtual string ValidateField(string columnName)
        {
            return "";
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

        public string Msg { get; set; } = "";


        /// <summary>Propiedad opcional para indicar que se esta actualizando</summary>
        /// <remarks>Cargar en false al finalizar la inicializacion</remarks>
        public string _Label = "";

        public string Label
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
        public bool _isUpdated = false;

        public bool IsUpdated
        {
            get { return _isUpdated; }
            set
            {
                if (_isUpdated != value)
                {
                    _isUpdated = value;
                    NotifyPropertyChanged(nameof(IsUpdated));
                }
            }
        }

        /// <summary>Indice dentro de una coleccíón</summary>
        /// <remarks>Facilita la impresion del número de fila, por ejemplo/remarks>
        public int? _Index = null;

        public int? Index
        {
            get { return _Index; }
            set
            {
                _Index = value; //por el momento no ejecuta NotifyPropertyChanged
            }
        }

        /// <summary>Propiedad opcional para indicar que se esta actualizando</summary>
        /// <remarks>Cargar en false al finalizar la inicializacion</remarks>
        public bool _isError = false;

        public bool IsError
        {
            get { return _isError; }
            set
            {
                if (_isError != value)
                {
                    _isError = value;
                    NotifyPropertyChanged(nameof(IsUpdated));
                }
            }
        }

        public event PropertyChangedEventHandler? PropertyChanged;

        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            if (!_isUpdatedIgnore.Contains(propertyName))
                IsUpdated = true;

            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }

        public EntityPersist Delete()
        {
            return db.Persist().DeleteIds(entityName, this.GetPropertyValue("id"));
        }

        public T AddToOC<T>(ObservableCollection<T> oc) where T : Data
        {
            oc.Add((T)this);
            return (T)this;
        }


    }


    public class InfoData : Data
    {
        /// <summary>Propiedad opcional para indicar que se esta actualizando</summary>
        /// <remarks>Cargar en false al finalizar la inicializacion</remarks>
        public string _Info = "";

        public string Info
        {
            get { return _Info; }
            set
            {
                if (_Info != value)
                {
                    _Info = value;
                    NotifyPropertyChanged(nameof(Info));
                }
            }
        }

    }

}
