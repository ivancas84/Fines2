using System;
using System.ComponentModel;

namespace Fines2Wpf.Data
{
    public class Data_asignacion_planilla_docente : INotifyPropertyChanged
    {

        public string? Label { get; set; }

        protected string? _id = (string?)ContainerApp.db.DefaultValue("asignacion_planilla_docente", "id");
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _planilla_docente = null;
        public string? planilla_docente
        {
            get { return _planilla_docente; }
            set { _planilla_docente = value; NotifyPropertyChanged(); }
        }
        protected string? _toma = null;
        public string? toma
        {
            get { return _toma; }
            set { _toma = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _insertado = (DateTime?)ContainerApp.db.DefaultValue("asignacion_planilla_docente", "insertado");
        public DateTime? insertado
        {
            get { return _insertado; }
            set { _insertado = value; NotifyPropertyChanged(); }
        }
        protected string? _comentario = null;
        public string? comentario
        {
            get { return _comentario; }
            set { _comentario = value; NotifyPropertyChanged(); }
        }
        protected bool? _reclamo = (bool?)ContainerApp.db.DefaultValue("asignacion_planilla_docente", "reclamo");
        public bool? reclamo
        {
            get { return _reclamo; }
            set { _reclamo = value; NotifyPropertyChanged(); }
        }
        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
