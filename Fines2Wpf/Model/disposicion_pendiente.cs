using SqlOrganize;
using System;
using System.ComponentModel;

namespace Fines2Wpf.Model
{
    public class Data_disposicion_pendiente : INotifyPropertyChanged
    {

        public Data_disposicion_pendiente ()
        {
            Initialize();
        }

        public Data_disposicion_pendiente(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("disposicion_pendiente").Default("id").Get("id");
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
        protected string? _disposicion = null;
        public string? disposicion
        {
            get { return _disposicion; }
            set { _disposicion = value; NotifyPropertyChanged(); }
        }
        protected string? _alumno = null;
        public string? alumno
        {
            get { return _alumno; }
            set { _alumno = value; NotifyPropertyChanged(); }
        }
        protected string? _modo = null;
        public string? modo
        {
            get { return _modo; }
            set { _modo = value; NotifyPropertyChanged(); }
        }
        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
