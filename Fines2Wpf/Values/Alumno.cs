using SqlOrganize;
using Utils;

namespace Fines2Wpf.Values
{
    class Alumno : EntityValues
    {
        public Alumno(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public string ColorEstadoInscripcion(string? estadoInscripcion)
        {
            if (estadoInscripcion.IsNullOrEmptyOrDbNull())
                return ContainerApp.config.colorRed; //red

            if (estadoInscripcion!.Equals("Correcto"))
                return ContainerApp.config.colorGreen;//green

            else if (estadoInscripcion.Equals("Indeterminado"))
                return ContainerApp.config.colorRed; //red


            else if (estadoInscripcion.Equals("Caso particular"))
                return ContainerApp.config.colorRed;  //red


            else if (estadoInscripcion.Equals("Titulado"))
                return ContainerApp.config.colorGreen; //green

            return ContainerApp.config.colorGray; //gray
        }


        public string ColorCantidadAprobadasSemestreActual(long cantidadAprobadas, short anio, short semestre, short anioActual, short semestreActual, short? anioIngreso, short? semestreIngreso)
        {
            if(anioActual == anio && semestreActual == semestre)
                return "#d7d7d7"; //gray

            return ColorCantidadAprobadas(cantidadAprobadas, anio, semestre, anioIngreso, semestreIngreso);
        }

        public string ColorCantidadAprobadas(long cantidadAprobadas, short anio, short semestre, short? anioIngreso, short? semestreIngreso)
        {
            string defaultColor = "#7196bd"; //blue
            anioIngreso = anioIngreso ?? 1;
            semestreIngreso = semestreIngreso ?? 1;

            if ((anioIngreso == 1) && (semestreIngreso == 1))
                return _ColorCantidadAprobadas(cantidadAprobadas);

            if ((anioIngreso == 1) && (semestreIngreso == 2))
            {
                if ((anio == 1) && (semestre == 1))
                    return defaultColor;

                return _ColorCantidadAprobadas(cantidadAprobadas);
            }

            if ((anioIngreso == 2) && (semestreIngreso == 1))
            {
                if (anio == 1)
                    return defaultColor;

                return _ColorCantidadAprobadas(cantidadAprobadas);
            }

            if ((anioIngreso == 2) && (semestreIngreso == 2))
            {
                if ((anio == 1) || ((anio == 2) && (semestre == 1)))
                    return defaultColor;

                return _ColorCantidadAprobadas(cantidadAprobadas);
            }

            if ((anioIngreso == 3) && (semestreIngreso == 1))
            {
                if (anio == 1 || anio == 2)
                    return defaultColor;

                return _ColorCantidadAprobadas(cantidadAprobadas);
            }

            if ((anioIngreso == 3) && (semestreIngreso == 2))
            {
                if ((anio == 1) || (anio == 2) || ((anio == 3) && (semestre == 1)))
                    return defaultColor;

                return _ColorCantidadAprobadas(cantidadAprobadas);
            }

            return defaultColor;
        }

        private string _ColorCantidadAprobadas(long cantidad)
        {

            if (cantidad == 3 || cantidad == 4) return "#faebd7"; //yellow
            if (cantidad == 5) return "#cae7c2"; //green
            return "#fa91aa"; //red
        }

        public string TramoIngreso()
        {
            string tramo = "";
            tramo += GetOrNull("anio_ingreso")?.ToString() ?? "1";
            tramo += "/";
            tramo += GetOrNull("semestre_ingreso")?.ToString() ?? "1";
            return tramo;
        }
    }
}

    