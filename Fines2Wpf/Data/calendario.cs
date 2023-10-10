using System;
using System.ComponentModel;

namespace Fines2Wpf.Data
{
    public class Data_calendario : INotifyPropertyChanged
    {

        public string? Label { get; set; }

        protected string? _id = (string?)ContainerApp.db.DefaultValue("calendario", "id");
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _inicio = null;
        public DateTime? inicio
        {
            get { return _inicio; }
            set { _inicio = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _fin = null;
        public DateTime? fin
        {
            get { return _fin; }
            set { _fin = value; NotifyPropertyChanged(); }
        }
        protected short? _anio = null;
        public short? anio
        {
            get { return _anio; }
            set { _anio = value; NotifyPropertyChanged(); }
        }
        protected short? _semestre = null;
        public short? semestre
        {
            get { return _semestre; }
            set { _semestre = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _insertado = (DateTime?)ContainerApp.db.DefaultValue("calendario", "insertado");
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(); }
        }
        protected string? _descripcion = null;
        public string? descripcion
        {
            get { return _descripcion; }
            set { _descripcion = value; NotifyPropertyChanged(); }
        }
        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
