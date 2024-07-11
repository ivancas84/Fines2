namespace Fines2Wpf.Windows.Informe.CantidadesPorComision
{
    internal class ItemEdad : SqlOrganize.Sql.Data
    {
        protected string? _pfid = null;

        public string? pfid
        {
            get { return _pfid; }
            set { _pfid = value; NotifyPropertyChanged(nameof(pfid)); }
        }


        protected string? _anio = null;

        public string? anio
        {
            get { return _anio; }
            set { _anio = value; NotifyPropertyChanged(nameof(anio)); }
        }
        protected string? _semestre = null;

        public string? semestre
        {
            get { return _semestre; }
            set { _semestre = value; NotifyPropertyChanged(nameof(semestre)); }
        }

        protected string? _orientacion = null;

        public string? orientacion
        {
            get { return _orientacion; }
            set { _orientacion = value; NotifyPropertyChanged(nameof(orientacion)); }
        }

        protected string? _edad = null;

        public string? edad
        {
            get { return _edad; }
            set { _edad = value; NotifyPropertyChanged(nameof(edad)); }
        }

        protected int? _cantidad = null;

        public int? cantidad
        {
            get { return _cantidad; }
            set { _cantidad = value; NotifyPropertyChanged(nameof(cantidad)); }
        }
    }
}
