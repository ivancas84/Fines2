using System;
using System.ComponentModel;

namespace Fines2Wpf.Data
{
    public class Data_domicilio : INotifyPropertyChanged
    {

        public string? Label { get; set; }

        protected string? _id = (string?)ContainerApp.db.DefaultValue("domicilio", "id");
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _calle = null;
        public string? calle
        {
            get { return _calle; }
            set { _calle = value; NotifyPropertyChanged(); }
        }
        protected string? _entre = null;
        public string? entre
        {
            get { return _entre; }
            set { _entre = value; NotifyPropertyChanged(); }
        }
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { _numero = value; NotifyPropertyChanged(); }
        }
        protected string? _piso = null;
        public string? piso
        {
            get { return _piso; }
            set { _piso = value; NotifyPropertyChanged(); }
        }
        protected string? _departamento = null;
        public string? departamento
        {
            get { return _departamento; }
            set { _departamento = value; NotifyPropertyChanged(); }
        }
        protected string? _barrio = null;
        public string? barrio
        {
            get { return _barrio; }
            set { _barrio = value; NotifyPropertyChanged(); }
        }
        protected string? _localidad = null;
        public string? localidad
        {
            get { return _localidad; }
            set { _localidad = value; NotifyPropertyChanged(); }
        }
        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
