using System;
using System.ComponentModel;

namespace Fines2Wpf.Data
{
    public class Data_plan : INotifyPropertyChanged
    {

        public string? Label { get; set; }

        protected string? _id = (string?)ContainerApp.db.DefaultValue("plan", "id");
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _orientacion = null;
        public string? orientacion
        {
            get { return _orientacion; }
            set { _orientacion = value; NotifyPropertyChanged(); }
        }
        protected string? _resolucion = null;
        public string? resolucion
        {
            get { return _resolucion; }
            set { _resolucion = value; NotifyPropertyChanged(); }
        }
        protected string? _distribucion_horaria = null;
        public string? distribucion_horaria
        {
            get { return _distribucion_horaria; }
            set { _distribucion_horaria = value; NotifyPropertyChanged(); }
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
