﻿using Utils;

namespace Fines2App.DAO
{
    public class AlumnoComision
    {

        /// <summary>
        /// Cantidad de disposiciones aprobadas por alumno de comision
        /// </summary>
        /// <param name="comision"></param>
        /// <returns></returns>
        public IEnumerable<object> IdAlumnosParaTransferirDeComision(string comision, object anio, object semestre)
        {
            var alumnoComision_ = AsignacionesActivasPorComision(comision);
            var idAlumnos = alumnoComision_.ColOfVal<object>("alumno").Distinct().ToList();
            var idPlan = alumnoComision_.ElementAt(0)["planificacion-plan"];
            return ContainerApp.db.Query("calificacion")
                .Select("$SUM($disposicion) AS cantidad")
                .Group("$alumno")
                .Size(0)
                .Where(@"
                    $alumno IN (@0)
                    AND $plan-alu = @1
                    AND ($nota_final >= 7 OR $crec >= 4)  
                 ")
                .Having("SUM($disposicion) > 3")
                .Parameters(idAlumnos, idPlan).ColOfDictCache().ColOfVal<object>("id");
        }


        public IEnumerable<object> IdsAlumnosDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                ")
                .Parameters(anio, semestre).ColOfDictCache().ColOfVal<object>("alumno");

        }
        public IEnumerable<object> IdsAlumnosActivosDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                    AND $estado = 'Activo'
                ")
                .Parameters(anio, semestre).ColOfDictCache().ColOfVal<object>("alumno");

        }

        public IEnumerable<object> IdsAlumnosActivosDeComisionesAutorizadasPorSemestreSinGenero(object anio, object semestre)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                    AND $estado = 'Activo'
                    AND $persona-genero IS NULL
                ")
                .Parameters(anio, semestre).ColOfDictCache().ColOfVal<object>("alumno");
        }


        public IEnumerable<Dictionary<string, object?>> AsignacionesPorComisiones(List<object> idsComisiones)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $comision IN (@0) 
                ")
                .Parameters(idsComisiones).ColOfDictCache();
        }

        public IEnumerable<Dictionary<string, object?>> AsignacionesActivasPorComisiones(IEnumerable<object> idsComisiones)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $comision IN (@0) AND $estado = 'Activo'
                ")
                .Parameters(idsComisiones).ColOfDictCache();
        }

        public IEnumerable<Dictionary<string, object>> AsignacionesActivasPorComision(object comision)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Size(0)
                .Where(@"
                    $comision = @0 AND $activo = true
  
                ")
                .Parameters(comision).ColOfDictCache();
        }

        public IEnumerable<object> IdsAlumnosPorComisiones(List<object> comisiones)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Fields("alumno")
                .Size(0)
                .Where(@"
                    $comision IN (@0)
                ").ColOfDictCache().ColOfVal<object>("alumno");
        }

        public IEnumerable<object> IdAlumnosConPlanDiferenteDeComision(object comision, object plan)
        {
            return ContainerApp.db.Query("alumno_comision")
               .Fields("alumno")
               .Size(0)
               .Where(@"
                    $alumno-plan != @0
                    AND $comision = @1
                    AND $activo = true
                ")
               .Parameters(plan, comision).ColOfDictCache().ColOfVal<object>("alumno");

        }


        public IEnumerable<object> IdsAlumnosActivosDuplicadosPorSemestre(object anio, object semestre)
        {
            return ContainerApp.db.Query("alumno_comision")
               .Select("COUNT($id) AS cantidad")
               .Group("$alumno")
               .Size(0)
               .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $estado = 'Activo'
                ")
               .Having("cantidad > 1")
               .Parameters(anio, semestre).ColOfDictCache().ColOfVal<object>("cantidad");
        }

        public IEnumerable<Dictionary<string, object?>> AsignacionesActivasDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Size(0)
                .Where("$calendario-anio = @0 " +
                    "AND $calendario-semestre = @1 " +
                    "AND $comision-autorizada = true " +
                    "AND $estado = 'Activo'")
                .Parameters(anio, semestre).ColOfDictCache();

        }

        public IEnumerable<Dictionary<string, object>> AsignacionesDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Size(0)
                .Where("$calendario-anio = @0 AND $calendario-semestre = @1 AND $comision-autorizada = true")
                .Parameters(anio, semestre).ColOfDictCache();

        }

        public IEnumerable<Dictionary<string, object>> AlumnosDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            IEnumerable<object> ids = IdsAlumnosDeComisionesAutorizadasPorSemestre(anio, semestre);
            return ContainerApp.db.Query("alumno").CacheByIds(ids.ToArray());
        }

        public IEnumerable<Dictionary<string, object>> AlumnosActivosDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            var alumnoDao = new DAO.Alumno();
            IEnumerable<object> ids = IdsAlumnosActivosDeComisionesAutorizadasPorSemestre(anio, semestre);
            return alumnoDao.AlumnosPorIds(ids);
        } 


        public IEnumerable<Dictionary<string, object>> AlumnosActivosDeComisionesAutorizadasPorSemestreSinGenero(object anio, object semestre)
        {
            var alumnoDao = new DAO.Alumno();
            IEnumerable<object> ids = IdsAlumnosActivosDeComisionesAutorizadasPorSemestreSinGenero(anio, semestre);
            return alumnoDao.AlumnosPorIds(ids);
        }

        /// <summary>
        /// Consulta para verificar si el alumno pertenece a otras comisiones autorizadas en un mismo semestre
        /// </summary>
        /// <param name="anio">año del semestre</param>
        /// <param name="semestre">numero del semestre</param>
        /// <param name="idComision">numero de comision a la que actualmente pertenece el alumno</param>
        /// <param name="idAlumno">numero de alumno</param>
        /// <returns></returns>
        public IEnumerable<Dictionary<string, object>> AsignacionesDelAlumnoEnOtrasComisionesAutorizadasDelSemestre(object anio, object semestre, object idComision, object idAlumno)
        {
            var r = ContainerApp.db.Query("alumno_comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision-id != @2
                    AND $alumno = @3
                    AND $comision-autorizada = true
                ")
                .Parameters(anio, semestre, idComision, idAlumno).ColOfDict();
            return r;
        }

        /// <summary>
        /// Consulta para verificar si el alumno pertenece a otras comisiones autorizadas en un mismo semestre
        /// </summary>
        /// <param name="anio">año del semestre</param>
        /// <param name="semestre">numero del semestre</param>
        /// <param name="idComision">numero de comision a la que actualmente pertenece el alumno</param>
        /// <param name="idAlumno">numero de alumno</param>
        /// <returns></returns>
        public IEnumerable<Dictionary<string, object>> AsignacionesDelAlumnoEnOtrasComisionesAutorizadas(object idComision, object idAlumno)
        {
            return ContainerApp.db.Query("alumno_comision")
                .Size(0)
                .Where(@"
                    $comision-id != @0
                    AND $alumno = @1
                    AND $comision-autorizada = true
                ")
                .Parameters(idComision, idAlumno).ColOfDict();

        }
    }
}
