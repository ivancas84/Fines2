using Newtonsoft.Json;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Reflection;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Comportamiento general para las clases de datos
    /// </summary>
    public class EntityData : INotifyPropertyChanged, IDataErrorInfo
    {
        public virtual string entityName { get; }

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
        public EntityVal GetValues()
        {
            return db!.Values(entityName).SetValues(this);
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

        public string _Msg = "";

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
        public bool isPersisted = false;

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
        public int index = 0;

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
        public bool isError = false;

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

        public EntityPersist Delete()
        {
            return db.Persist().DeleteIds(entityName, this.GetPropertyValue("id"));
        }

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

    }

}
