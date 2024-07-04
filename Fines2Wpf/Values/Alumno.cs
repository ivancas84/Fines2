using Fines2Model3.DAO;
using Fines2Model3.Data;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using Utils;

namespace Fines2Wpf.Values
{
    class Alumno : EntityValues
    {
        public Alumno(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public void GenerarCalificaciones()
        {
            List<EntityPersist> persists = new();

            if (IsNullOrEmpty("id", "plan", "anio_ingreso", "semestre_ingreso"))
                    throw new Exception("No se encuentran definidos los datos principales del alumno para generar las calificaciones.");

            #region Eliminar calificaciones desaprobadas
            IEnumerable<object> idsCalificaciones = ContainerApp.db.CalificacionesDesaprobadasDeAlumnoSql(Get("id")).
                Column<object>("id");

            if (idsCalificaciones.Count() > 0)
                ContainerApp.db.Persist().
                    DeleteIds("calificacion", idsCalificaciones.ToArray()).
                    AddTo(persists);
            #endregion

            #region Archivar calificaciones aprobadas del mismo plan pero con año y semestre inferior
            if (!Get("anio_ingreso").Equals("1") && (short)Get("semestre_ingreso") != 1)
            {
                IEnumerable<object> idsCalificacionesAnteriores = ContainerApp.db.
                    CalificacionesAprobadasAnterioresDeAlumnoPlanConAnioSemestreIngresoSql(Get("plan"), Get("id"), Get("anio_ingreso"), Get("semestre_ingreso")).
                    Column<object>("id");

                if (idsCalificacionesAnteriores.Any())
                    ContainerApp.db.Persist().
                        UpdateValueIds("calificacion", "archivado", true, idsCalificacionesAnteriores.ToArray()).
                        AddTo(persists);
            }
            #endregion

            #region Archivar calificaciones aprobadas de otro plan
            idsCalificaciones = ContainerApp.db.CalificacionesAprobadasDeAlumnoPlanDistintoSql(Get("id"), Get("plan")).
                Column<object>("id");

            if (idsCalificaciones.Count() > 0)
                ContainerApp.db.Persist().
                    UpdateValueIds("calificacion", "archivado", true, idsCalificaciones.ToArray()).
                    AddTo(persists);
            #endregion

            #region Desarchivar calificaciones aprobadas del mismo plan
            IEnumerable < Dictionary<string, object?>> calificacionesAprobadas = ContainerApp.db.CalificacionesAprobadasDeAlumnoPlanConAnioSemestreIngresoSql(Get("plan"), Get("id"), Get("anio_ingreso"), Get("semestre_ingreso")).
                ColOfDict();

            idsCalificaciones = calificacionesAprobadas.ColOfVal<object>("id");

            if (idsCalificaciones.Count() > 0)
                ContainerApp.db.Persist().
                    UpdateValueIds("calificacion", "archivado", false, idsCalificaciones.ToArray()).
                    AddTo(persists);
            #endregion

            #region Insertar calificaciones de disposiciones faltantes
            IEnumerable<object> idsDisposicionesAprobadas = calificacionesAprobadas.ColOfVal<object>("disposicion");

            IEnumerable<object> idsDisposiciones = ContainerApp.db.DisposicionesPlanAnioSemestre(Get("plan"), Get("anio_ingreso"),Get("semestre_ingreso")).
                Column<object>("id");

            foreach (var id in idsDisposiciones)
            {
                if (!idsDisposicionesAprobadas.Contains(id))
                {
                    Data_calificacion calificacionObj = new(ContainerApp.db);
                    calificacionObj.Default();
                    calificacionObj.disposicion = (string)id;
                    calificacionObj.alumno = (string)Get("id");
                    calificacionObj.archivado = false;
                    ContainerApp.db.Persist().
                        Insert("calificacion", calificacionObj).
                        AddTo(persists);
                }
            }
            #endregion

            /*#region Archivar calificaciones repetidas
            idsDisposiciones = ContainerApp.db.Sql("calificacion").
                Select("$disposicion, COUNT(*) as cantidad").
                Size(0).
                Group("$disposicion").
                Where(@"
                        $planificacion_dis-plan = @0 
                        AND $planificacion_dis-anio >= @1 
                        AND $planificacion_dis-semestre >= @2 
                        AND $archivado = false  
                        AND ($nota_final >= 7 OR $crec >= 4)
                        AND $alumno = @3").
                Having("cantidad > 1").
                Parameters(alumnoObj.plan!, alumnoObj.anio_ingreso!, alumnoObj.semestre_ingreso!, alumnoObj.id!).
                Column<object>("disposicion");

            if (idsDisposiciones.Count() > 0)
            {
                idsCalificaciones = ContainerApp.db.Sql("calificacion").
                    Select("$disposicion, MAX($id) AS id").
                    Group("$disposicion").
                    Size(0).
                    Where("$disposicion IN ( @0 ) ").
                    Parameters(idsDisposiciones.ToList()).
                    Column<object>("id");

                if (idsCalificaciones.Count() > 0)
                    ContainerApp.db.Persist().UpdateValueIds("calificacion", "archivado", false, idsCalificaciones.ToArray()).
                        AddTo(persists);
            }
            #endregion*/

            persists.Transaction().RemoveCache();
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

    