using Fines2Wpf.Model;
using SqlOrganize;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{
    internal class Alumno : Data_alumno
    {
        public Alumno() : base()
        {
        }

        public Alumno(DataInitMode mode = DataInitMode.Default) : base(mode)
        {
        }




        public string? _color_estado_inscripcion { set; get; } = null;

        public string? color_estado_inscripcion
        {
            get { return _color_estado_inscripcion; }
            set { _color_estado_inscripcion = value; NotifyPropertyChanged(); }
        }





        public string? _color_anio_ingreso { set; get; } = null;

        public string? color_anio_ingreso
        {
            get { return _color_anio_ingreso; }
            set { _color_anio_ingreso = value; NotifyPropertyChanged(); }
        }








        public string? _color_semestre_ingreso { set; get; } = null;

        public string? color_semestre_ingreso
        {
            get { return _color_semestre_ingreso; }
            set { _color_semestre_ingreso = value; NotifyPropertyChanged(); }
        }







        public string? _color_plan { set; get; } = null;

        public string? color_plan
        {
            get { return _color_plan; }
            set { _color_plan = value; NotifyPropertyChanged(); }
        }



        public string? _color_tiene_constancia { set; get; } = null;

        public string? color_tiene_constancia
        {
            get { return _color_tiene_constancia; }
            set { _color_tiene_constancia = value; NotifyPropertyChanged(); }
        }




        public string? _color_tiene_certificado { set; get; } = null;

        public string? color_tiene_certificado
        {
            get { return _color_tiene_certificado; }
            set { _color_tiene_certificado = value; NotifyPropertyChanged(); }
        }




        public string? _color_previas_completas { set; get; } = null;

        public string? color_previas_completas
        {
            get { return _color_previas_completas; }
            set { _color_previas_completas = value; NotifyPropertyChanged(); }
        }

        public string? _color_tiene_dni { set; get; } = null;

        public string? color_tiene_dni
        {
            get { return _color_tiene_dni; }
            set { _color_tiene_dni = value; NotifyPropertyChanged(); }
        }



        public string? _color_tiene_partida { set; get; } = null;

        public string? color_tiene_partida
        {
            get { return _color_tiene_partida; }
            set { _color_tiene_partida = value; NotifyPropertyChanged(); }
        }



        protected string? _color_confirmado_direccion = null;
        public string? color_confirmado_direccion
        {
            get { return _color_confirmado_direccion; }
            set { _color_confirmado_direccion = value; NotifyPropertyChanged(); }
        }
    }
}
