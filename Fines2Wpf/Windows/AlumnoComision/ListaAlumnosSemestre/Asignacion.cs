using SqlOrganize.Sql.Fines2Model3;

namespace Fines2Wpf.Windows.AlumnoComision.ListaAlumnosSemestre
{
    internal class Asignacion : Data_alumno_comision_r
    {
        protected string _color_estado_inscripcion = "";
        public string color_estado_inscripcion
        {
            get { return _color_estado_inscripcion; }
            set { _color_estado_inscripcion = value; NotifyPropertyChanged(); }
        }


        protected long _cantidad_aprobadas11 = 0;
        public long cantidad_aprobadas11
        {
            get { return _cantidad_aprobadas11; }
            set { _cantidad_aprobadas11 = value; NotifyPropertyChanged(); }
        }

        protected string _color_aprobadas11 = "#0336FF";
        public string color_aprobadas11
        {
            get { return _color_aprobadas11; }
            set { _color_aprobadas11 = value; NotifyPropertyChanged(); }
        }



        protected long _cantidad_aprobadas12 = 0;
        public long cantidad_aprobadas12
        {
            get { return _cantidad_aprobadas12; }
            set { _cantidad_aprobadas12 = value; NotifyPropertyChanged(); }
        }

        protected string _color_aprobadas12 = "#0336FF";
        public string color_aprobadas12
        {
            get { return _color_aprobadas12; }
            set { _color_aprobadas12 = value; NotifyPropertyChanged(); }
        }



        protected long _cantidad_aprobadas21 = 0;
        public long cantidad_aprobadas21
        {
            get { return _cantidad_aprobadas21; }
            set { _cantidad_aprobadas21 = value; NotifyPropertyChanged(); }
        }

        protected string _color_aprobadas21 = "#0336FF";
        public string color_aprobadas21
        {
            get { return _color_aprobadas21; }
            set { _color_aprobadas21 = value; NotifyPropertyChanged(); }
        }



        protected long _cantidad_aprobadas22 = 0;
        public long cantidad_aprobadas22
        {
            get { return _cantidad_aprobadas22; }
            set { _cantidad_aprobadas22 = value; NotifyPropertyChanged(); }
        }

        protected string _color_aprobadas22 = "#0336FF";
        public string color_aprobadas22
        {
            get { return _color_aprobadas22; }
            set { _color_aprobadas22 = value; NotifyPropertyChanged(); }
        }



        protected long _cantidad_aprobadas31 = 0;
        public long cantidad_aprobadas31
        {
            get { return _cantidad_aprobadas31; }
            set { _cantidad_aprobadas31 = value; NotifyPropertyChanged(); }
        }

        protected string _color_aprobadas31 = "#0336FF";
        public string color_aprobadas31
        {
            get { return _color_aprobadas31; }
            set { _color_aprobadas31 = value; NotifyPropertyChanged(); }
        }



        protected long _cantidad_aprobadas32 = 0;
        public long cantidad_aprobadas32
        {
            get { return _cantidad_aprobadas32; }
            set { _cantidad_aprobadas32 = value; NotifyPropertyChanged(); }
        }

        protected string _color_aprobadas32 = "#0336FF";
        public string color_aprobadas32
        {
            get { return _color_aprobadas32; }
            set { _color_aprobadas32 = value; NotifyPropertyChanged(); }
        }

        public string _tramo_ingreso = "1/1";

        public string tramo_ingreso
        {
            get { return _tramo_ingreso; }
            set { _tramo_ingreso = value; NotifyPropertyChanged(); }
        }
    }
}
