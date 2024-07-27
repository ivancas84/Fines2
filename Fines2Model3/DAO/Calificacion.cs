using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class CalificacionDAO
    {

        /// <summary> Todas las calificaciones del alumno para un determinado plan </summary>
        public static EntitySql CalificacionesDeAlumnoPlanQuery(this Db db, object idAlumno, object idPlan, bool archivado = false)
        {
            return db.Sql("calificacion")
               .Size(0)
               .Where(@"
                    $alumno = @0
                    AND $planificacion_dis-plan = @1
                ")
                .Order("$planificacion_dis-anio ASC, $planificacion_dis-semestre ASC, $asignatura-nombre")
               .Parameters(idAlumno, idPlan, archivado);
        }

        public static EntitySql CalificacionesDesaprobadasDeAlumnoSql(this Db db, object alumno)
        {
            return db.Sql("calificacion").
                Size(0).
                Where(@"
                        $alumno = @0
                        AND (
                            ($nota_final < 7 AND $crec < 4)
                            OR ($nota_final < 7 AND $crec IS NULL)
                            OR ($nota_final IS NULL AND $crec < 4)
                            OR ($nota_final IS NULL AND $crec IS NULL)
                        )
                    ").
                Parameters(alumno);
        }

        public static EntitySql CalificacionesAprobadasAnterioresDeAlumnoPlanConAnioSemestreIngresoSql(this Db db, object plan, object alumno, object anio_actual, object semestre_actual)
        {
            return db.Sql("calificacion").
                    Size(0).
                    Where(@"
                        $planificacion_dis-plan = @0
                        AND $planificacion_dis-anio < @1 AND $planificacion_dis-semestre < @2 
                        AND $alumno = @3
                        AND ($nota_final >= 7 OR $crec >= 4)").
                    Parameters(plan!, anio_actual!, semestre_actual!, alumno!);
        }

        public static EntitySql CalificacionesAprobadasDeAlumnoPlanConAnioSemestreIngresoSql(this Db db, object plan, object alumno, object anio, object semestre)
        {
            return db.Sql("calificacion").
                    Size(0).
                    Where(@"
                        $planificacion_dis-plan = @0
                        AND $planificacion_dis-anio >= @1 AND $planificacion_dis-semestre >= @2 
                        AND $alumno = @3
                        AND ($nota_final >= 7 OR $crec >= 4)").
                    Parameters(plan!, anio!, semestre!, alumno!);
        }

        public static EntitySql CalificacionesAprobadasDeAlumnoPlanDistintoSql(this Db db, object alumno, object plan)
        {
            return db.Sql("calificacion").
                    Size(0).
                    Where(@"
                        $planificacion_dis-plan != @0
                        AND $alumno = @1
                        AND ($nota_final >= 7 OR $crec >= 4)").
                    Parameters(plan!, alumno!);
        }

        public static EntitySql CalificacionDisposicionAlumnosSql(this Db db, object disposicion, params object[] alumnos)
        {
            return db.Sql("calificacion").
                    Size(0).
                    Where("$disposicion = @0 AND $alumno IN (@1)").
                    Parameters(disposicion!, alumnos!);
        }


        public static EntitySql CalificacionAprobadaCursoSql(this Db db, object curso)
        {
            CursoValues cursoVal = (CursoValues)db.ValuesFromId("curso", curso);

            string subSql = "SELECT DISTINCT alumno FROM alumno_comision WHERE comision = @0";

            return db.Sql("calificacion").
                    Size(0).
                    Where("$alumno IN (" + subSql + ") AND $disposicion = @1 AND (nota_final >= 7 OR crec >= 4)").
                    Parameters(cursoVal.Get("comision")!, cursoVal.GetDisposicion()!);
        }

        public static EntitySql CalificacionDesaprobadaCursoAlumnosActivosSql(this Db db, object curso)
        {
            CursoValues cursoVal = (CursoValues)db.ValuesFromId("curso", curso);

            string subSql = "SELECT DISTINCT alumno FROM alumno_comision WHERE estado = 'Activo' AND comision = @0";

            return db.Sql("calificacion").
                    Size(0).
                    Where(@"$alumno IN (" + subSql + ") AND $disposicion = @1 AND nota_final < 7 AND crec < 4").
                    Parameters(cursoVal.Get("comision")!, cursoVal.GetDisposicion()!);
        }

        public static EntitySql CalificacionesCursoSql(this Db db, object curso)
        {
            var cursoData = db.Sql("curso").Cache().Id(curso);

            var alumnos = db.AsignacionesDeComisionesSql(cursoData["comision"]).Cache().ColOfDict().ColOfVal<object>("alumno");

            var disposicion = ((CursoValues)db.Values("curso").Values(cursoData)).GetDisposicion();

            return db.CalificacionDisposicionAlumnosSql(disposicion, alumnos.ToArray());
        }
    }
}
