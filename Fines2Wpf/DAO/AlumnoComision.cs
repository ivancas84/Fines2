using Fines2Wpf.Values;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.DAO
{
    public class AlumnoComision
    {



        public IEnumerable<Dictionary<string, object?>> buscarPorCuilsTemporal()
        {
            List<string> cuils= new () { "27421951212", "20357187711", "20420428562", "27442683331", "20426305357", "20423655527", "20437317136", "23451529064", "27442641515", "20448907210", "27444186890", "27454309443", "20437785695", "20458723479", "27955894346", "27430196117", "27411018631", "27443009669", "27415398226", "20438985205", "27418505139", "27432641010", "20345660411", "20448904009", "20455720193", "27458726006", "20463404268", "23458978034", "27452780858", "20391147982", "20941213007", "20437811742", "27416700503", "27434680080", "20458977861", "20422901117", "20422304771", "27386917014", "27440440113", "27464170265", "20948497582", "27475945064", "27442568397", "20417661558", "23445196134", "20423895773", "20446365909", "20456789278", "27338507998", "20453953794", "27406289538", "20435108661", "27949299916", "27387360900", "27465702821", "27422901316", "27435069091", "20419283267", "20439148897", "27423106285", "27448901098", "27415538907", "27957285991", "27491609740", "20419272923", "20447115280", "27415446042", "20426753538", "20451319486", "20953878330", "27420954684", "27445069995", "27453953349", "20441850558", "23427766594", "20462058153", "20460052212", "27429559893", "20459180983", "20453953220", "20416709514", "20436662387", "27435876558", "27505422363", "27430531978", "27378034758", "27456132990", "27453963123", "20443554220", "20456198024", "20459181076", "20464348787", "27393488404", "27405813780", "27443554756", "23400610614", "27948791493", "27958558185", "20447071917", "20460136939", "20451304330", "20445867978", "27441489140", "27458724534", "27444185274", "20459177524", "20448900607", "27360696222", "27444614612", "27954527285", "27421951468", "27423655319", "27374188351", "20445866882", "20419071057", "23456135269", "27439148050", "20462853751", "23436662599", "23950427329", "20429435553", "23461963234", "20467479076", "20416844098", "20435875565", "27433170623", "20435059326", "27412383791", "27452907564", "27418837913", "27385465608", "27441854957", "27464172691", "20422303880", "20457398310", "20947259165", "27953489312", "20441068698", "20458976326", "27419885512", "20459178318", "27457809013", "20456198245", "20456198253", "27471581661", "20459968696", "20498113789", "27421374541", "27480619965", "20446910664", "27440460912", "27941552515", "27416707044", "27441444392", "20459180894", "27436664384", "24400612877", "27452185844", "27445068026", "20466814319", "27503657665", "27422929865", "27430429774", "27444186939", "27461086131", "20428858841", "27422304318", "27425915571", "20482817905", "27947389918", "27379280868", "27952422966", "27430988552", "27486968716", "20460052085", "27365736524", "27464155827", "27438054311", "20460053464", "20452906016", "24445866519", "20460137633", "27467376611", "27451880166", "20429435561", "20422304828", "20956622396", "20949827535" } ;
            
            
            List<string> dnis = new ();

            foreach(var cuil in cuils)
            {
                dnis.Add(cuil.Substring(2, 8).TrimStart('0'));
            }


            return ContainerApp.db.Sql("alumno_comision")
                .Where("$persona-numero_documento IN (@0)")
                .Parameters(dnis).Cache().ColOfDict();

        }

        /// <summary>
        /// Cantidad de disposiciones aprobadas por alumno de comision
        /// </summary>
        /// <param name="comision"></param>
        /// <returns></returns>
        public IEnumerable<object> IdAlumnosParaTransferirDeComision(string comision, object anio, object semestre)
        {
            var alumnoComision_ = AsignacionesActivasPorComisionesQuery(new List<object>() { comision }).Cache().ColOfDict();
            var idAlumnos = alumnoComision_.ColOfVal<object>("alumno").Distinct().ToList();
            var idPlan = alumnoComision_.ElementAt(0)["planificacion-plan"];
            return ContainerApp.db.Sql("calificacion")
                .Select("$SUM($disposicion) AS cantidad")
                .Group("$alumno")
                .Size(0)
                .Where(@"
                    $alumno IN (@0)
                    AND $plan-alu = @1
                    AND ($nota_final >= 7 OR $crec >= 4)  
                 ")
                .Having("SUM($disposicion) > 3")
                .Parameters(idAlumnos, idPlan).Cache().ColOfDict().ColOfVal<object>("id");



        }


        public IEnumerable<object> IdsAlumnosDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                ")
                .Parameters(anio, semestre).Cache().ColOfDict().ColOfVal<object>("alumno");

        }


        public IEnumerable<object> IdsAlumnosActivosDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                    AND $estado = 'Activo'
                ")
                .Parameters(anio, semestre).Cache().ColOfDict().ColOfVal<object>("alumno");

        }

        public IEnumerable<object> IdsAlumnosActivosDeComisionesAutorizadasPorSemestreSinGenero(object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Fields("$alumno")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true
                    AND $estado = 'Activo'
                    AND $persona-genero IS NULL
                ")
                .Parameters(anio, semestre).Cache().ColOfDict().ColOfVal<object>("alumno");
        }


        public IEnumerable<Dictionary<string, object>> AsignacionesPorComisiones(List<object> idsComisiones)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $comision IN (@0) 
                ")
                .Parameters(idsComisiones).Cache().ColOfDict();
        }

        public EntitySql AsignacionesActivasPorComisionesQuery(IEnumerable<object> idsComisiones)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $comision IN (@0) AND $estado = 'Activo'
                ")
                .Parameters(idsComisiones);
        }


        public IEnumerable<object> IdsAlumnosPorComisiones(List<object> comisiones)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Fields("alumno")
                .Size(0)
                .Where(@"
                    $comision IN (@0)
                ").Cache().ColOfDict().ColOfVal<object>("alumno");
        }

        


        public EntitySql IdsAlumnosActivosDuplicadosPorSemestreDeComisionesAutorizadasQuery(object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
               .Select("COUNT($id) AS cantidad")
               .Group("$alumno")
               .Size(0)
               .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $estado = 'Activo'
                    AND $comision-autorizada = true
                ")
               .Having("cantidad > 1")
               .Parameters(anio, semestre);
        }

        public EntitySql AsignacionesActivasDeComisionesAutorizadasPorSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Size(0)
                .Where(@"$calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $comision-autorizada = true 
                    AND $estado = 'Activo'")
                .Parameters(anio, semestre);

        }

        public IEnumerable<Dictionary<string, object>> AsignacionesDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            return ContainerApp.db.Sql("alumno_comision")
                .Size(0)
                .Where("$calendario-anio = @0 AND $calendario-semestre = @1 AND $comision-autorizada = true")
                .Parameters(anio, semestre).Cache().ColOfDict();

        }

        public IEnumerable<Dictionary<string, object>> AlumnosDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            IEnumerable<object> ids = IdsAlumnosDeComisionesAutorizadasPorSemestre(anio, semestre);
            return ContainerApp.db.Sql("alumno").Cache().Ids(ids.ToArray());
        }

        public IEnumerable<Dictionary<string, object>> AlumnosActivosDeComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            IEnumerable<object> ids = IdsAlumnosActivosDeComisionesAutorizadasPorSemestre(anio, semestre);
            return DAO.Alumno.AlumnosPorIds(ids);
        } 


        public IEnumerable<Dictionary<string, object>> AlumnosActivosDeComisionesAutorizadasPorSemestreSinGenero(object anio, object semestre)
        {
            IEnumerable<object> ids = IdsAlumnosActivosDeComisionesAutorizadasPorSemestreSinGenero(anio, semestre);
            return DAO.Alumno.AlumnosPorIds(ids);
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
            var r = ContainerApp.db.Sql("alumno_comision")
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
        public IEnumerable<Dictionary<string, object?>> AsignacionesDelAlumnoEnOtrasComisionesAutorizadas(object idComision, object idAlumno)
        {
            return ContainerApp.db.Sql("alumno_comision")
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
