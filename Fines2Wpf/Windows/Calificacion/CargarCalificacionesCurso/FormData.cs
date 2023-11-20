using System;
using System.ComponentModel;

namespace Fines2Wpf.Windows.Calificacion.CargarCalificacionesCurso
{
    class FormData : INotifyPropertyChanged
    {

        private string? _curso = null;

        public string? curso
        {
            get { return _curso; }
            set { _curso = value; NotifyPropertyChanged(); }
        }

        private string? _encabezados = "persona-apellidos, persona-nombres, persona-numero_documento, nota_final, crec";

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
