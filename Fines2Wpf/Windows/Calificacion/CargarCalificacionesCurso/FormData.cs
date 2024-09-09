using System;
using System.ComponentModel;

namespace Fines2Wpf.Windows.Calificacion.CargarCalificacionesCurso
{
    class FormData : INotifyPropertyChanged
    {

        private string? _curso__Label = null;

        public string? curso__Label
        {
            get { return _curso__Label; }
            set { _curso__Label = value; NotifyPropertyChanged(); }
        }

        private string? _docente__Label = null;

        public string? docente__Label
        {
            get { return _docente__Label; }
            set { _docente__Label = value; NotifyPropertyChanged(); }
        }

        private string? _encabezados = "persona__apellidos, persona__nombres, persona__numero_documento, nota_final, crec";

        public string? encabezados
        {
            get { return _encabezados; }
            set { _encabezados = value; NotifyPropertyChanged(); }
        }

        private string? _datos = null;

        public string? datos
        {
            get { return _datos; }
            set { _datos = value; NotifyPropertyChanged(); }
        }

        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
