using SqlOrganize;
using System;
using System.ComponentModel;

namespace Fines2Wpf.Model
{
    public class Data_asignatura : INotifyPropertyChanged
    {

        public Data_asignatura ()
        {
            Initialize();
        }

        public Data_asignatura(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("asignatura").Default("id").Get("id");
                break;
            }
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { _nombre = value; NotifyPropertyChanged(); }
        }
        protected string? _formacion = null;
        public string? formacion
        {
            get { return _formacion; }
            set { _formacion = value; NotifyPropertyChanged(); }
        }
        protected string? _clasificacion = null;
        public string? clasificacion
        {
            get { return _clasificacion; }
            set { _clasificacion = value; NotifyPropertyChanged(); }
        }
        protected string? _codigo = null;
        public string? codigo
        {
            get { return _codigo; }
            set { _codigo = value; NotifyPropertyChanged(); }
        }
        protected string? _perfil = null;
        public string? perfil
        {
            get { return _perfil; }
            set { _perfil = value; NotifyPropertyChanged(); }
        }
        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
