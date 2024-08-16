
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;

namespace Fines2Wpf.Windows.Calificacion.CargarCalificacionesCurso
{
    class Calificacion : Data_calificacion_r
    {
        protected bool _procesar = true;
        public bool procesar
        {
            get { return _procesar; }
            set { _procesar = value; NotifyPropertyChanged(); }
        }

        protected bool _agregar_persona = false;
        public bool agregar_persona
        {
            get { return _agregar_persona; }
            set { _agregar_persona = value; NotifyPropertyChanged(); }
        }

        protected bool _agregar_alumno = false;
        public bool agregar_alumno
        {
            get { return _agregar_alumno; }
            set { _agregar_alumno = value; NotifyPropertyChanged(); }
        }

        protected bool _agregar_asignacion = false;
        public bool agregar_asignacion
        {
            get { return _agregar_asignacion; }
            set { _agregar_asignacion = value; NotifyPropertyChanged(); }
        }

    }
}
