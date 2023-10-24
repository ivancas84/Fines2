using SqlOrganize;
using System;
using System.ComponentModel;

namespace Fines2Wpf.Model
{
    public class Data_designacion : INotifyPropertyChanged
    {

        public Data_designacion ()
        {
            Initialize();
        }

        public Data_designacion(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("designacion").Default("id").Get("id");
                    _alta = (DateTime?)ContainerApp.db.Values("designacion").Default("alta").Get("alta");
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
        protected DateTime? _desde = null;
        public DateTime? desde
        {
            get { return _desde; }
            set { _desde = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _hasta = null;
        public DateTime? hasta
        {
            get { return _hasta; }
            set { _hasta = value; NotifyPropertyChanged(); }
        }
        protected string? _cargo = null;
        public string? cargo
        {
            get { return _cargo; }
            set { _cargo = value; NotifyPropertyChanged(); }
        }
        protected string? _sede = null;
        public string? sede
        {
            get { return _sede; }
            set { _sede = value; NotifyPropertyChanged(); }
        }
        protected string? _persona = null;
        public string? persona
        {
            get { return _persona; }
            set { _persona = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { _alta = value; NotifyPropertyChanged(); }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { _pfid = value; NotifyPropertyChanged(); }
        }
        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
