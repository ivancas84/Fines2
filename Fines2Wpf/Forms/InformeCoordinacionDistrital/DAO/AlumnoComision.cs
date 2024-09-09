using System;
using System.Collections.Generic;
using SqlOrganize;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;

namespace Fines2Wpf.Forms.InformeCoordinacionDistrital.DAO
{
    class AlumnoComision
    {
        protected IEnumerable<Dictionary<string, object>> FiltroInformeCoordinacionDistrital(string modalidad, string anioCalendario, int semestreCalendario, bool? comisionSiguienteNull = null)
        {
            var q = ContainerApp.db.Sql("alumno_comision").
                Fields("estado, sede__nombre, comision__identificacion, alumno__id, plan_alu__id, persona__apellidos, persona__nombres, persona__numero_documento, persona__genero, persona__fecha_nacimiento, persona__telefono, persona__email, alumno__tiene_dni, alumno__tiene_partida, alumno__tiene_certificado, alumno__creado, alumno__estado_inscripcion, planificacion__plan").
                Select(@"CONCAT($planificacion__anio, '°', $planificacion__semestre, 'C') AS tramo").
                Size(0).
                Where(@"
                    $modalidad__id = @0 AND $calendario__anio = @1 AND $calendario__semestre = @2 AND
                    $estado != 'Mesa' AND $comision__autorizada IS TRUE
                ").
                Order("$sede__numero ASC, $comision__division ASC, $persona__apellidos ASC, $persona__nombres ASC").
                Param("@0", modalidad).Param("@1", anioCalendario).Param("@2", semestreCalendario);

            if (!comisionSiguienteNull.IsNoE() && comisionSiguienteNull == true)
                q.Where("AND $comision__comision_siguiente IS NULL");
            else if (!comisionSiguienteNull.IsNoE() && !comisionSiguienteNull == false)
                q.Where("AND $comision__comision_siguiente IS NOT NULL");

            return q.ColOfDict();
        }

        public IEnumerable<Dictionary<string, object>> InformeCoordinacionDistrital(string modalidad, string anioCalendario, int semestreCalendario, bool? comisionSiguienteNull = null)
        {
            var calificacionDAO = new Calificacion();
            var alumno_comision_ = FiltroInformeCoordinacionDistrital(modalidad, anioCalendario, semestreCalendario, comisionSiguienteNull);

            foreach (Dictionary<string, object> alu_com in alumno_comision_)
            {
                var v = (AlumnoComisionValues)ContainerApp.db.Values("alumno_comision").Set(alu_com);
                alu_com["persona__genero"] = alu_com["persona__genero"].ToString().ToUpper();
                alu_com["tiene_dni"] = (bool)alu_com["alumno__tiene_dni"] ? "SÍ" : "NO";
                alu_com["tiene_cuil"] = (bool)alu_com["alumno__tiene_dni"] ? "SÍ" : "NO";
                alu_com["tiene_partida"] = (bool)alu_com["alumno__tiene_partida"] ? "SÍ" : "NO";
                alu_com["tiene_certificado"] = (bool)alu_com["alumno__tiene_certificado"] ? "SÍ" : "NO";
                DateTime creado = (DateTime)alu_com["alumno__creado"];
                string estado = (alu_com["estado"].IsDbNull()) ? "Activo" : (string)alu_com["estado"];
                alu_com["cuatrimestre_ingreso"] = null;
                alu_com["estado_ingreso"] = v.EstadoIngreso();
                alu_com["asignatura111"] = null;
                alu_com["asignatura112"] = null;
                alu_com["asignatura113"] = null;
                alu_com["asignatura114"] = null;
                alu_com["asignatura115"] = null;
                alu_com["asignatura121"] = null;
                alu_com["asignatura122"] = null;
                alu_com["asignatura123"] = null;
                alu_com["asignatura124"] = null;
                alu_com["asignatura125"] = null;
                alu_com["asignatura211"] = null;
                alu_com["asignatura212"] = null;
                alu_com["asignatura213"] = null;
                alu_com["asignatura214"] = null;
                alu_com["asignatura215"] = null;
                alu_com["asignatura221"] = null;
                alu_com["asignatura222"] = null;
                alu_com["asignatura223"] = null;
                alu_com["asignatura224"] = null;
                alu_com["asignatura225"] = null;
                alu_com["asignatura311"] = null;
                alu_com["asignatura312"] = null;
                alu_com["asignatura313"] = null;
                alu_com["asignatura314"] = null;
                alu_com["asignatura315"] = null;
                alu_com["asignatura321"] = null;
                alu_com["asignatura322"] = null;
                alu_com["asignatura323"] = null;
                alu_com["asignatura324"] = null;
                alu_com["asignatura325"] = null;

                var plan = (!alu_com["plan_alu__id"].IsDbNull()) ? alu_com["plan_alu__id"] : alu_com["planificacion__plan"];
                var calificaciones = calificacionDAO.AprobadasPorAlumnoPlan((string)alu_com["alumno__id"], (string)plan);

                foreach (Dictionary<string, object> calificacion in calificaciones)
                {
                    string? nota = null;
                    if ((!calificacion["nota_final"].IsDbNull() && (decimal)calificacion["nota_final"] >= 7))
                    {
                        nota = Decimal.ToInt32((decimal)calificacion["nota_final"]).ToString();
                    }
                    else if ((!calificacion["crec"].IsDbNull() && (decimal)calificacion["crec"] >= 4))

                    {
                        nota = Decimal.ToInt32((decimal)calificacion["crec"]).ToString() + "c";
                    }
                    string key = "asignatura" + calificacion["planificacion_dis__anio"].ToString() + calificacion["planificacion_dis__semestre"].ToString() + (string)calificacion["disposicion__orden_informe_coordinacion_distrital"].ToString();
                    alu_com[key] = nota;
                }
            }

            return alumno_comision_;
        }
    }
}
