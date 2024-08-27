﻿using SqlOrganize;
using SqlOrganize.CollectionUtils;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class ComisionDAO
    {

        public static EntitySql BusquedaAproximadaComision(this Db db, string search)
        {
            return db.Sql("comision")
               .Fields()
               .Size(0)
               .Where(@"
                    $sede-nombre LIKE @0
                    OR
                    CONCAT($sede-numero, $division, '/', $planificacion-anio, $planificacion-semestre) LIKE @0
                    OR
                    $pfid LIKE @0
                    OR
                    CONCAT($calendario-anio, '-', $calendario-semestre) LIKE @0
                ")
               .Order("$sede-numero ASC, $division ASC, $calendario-anio DESC, $calendario-semestre DESC")
               .Parameters("%" + search + "%");

        }

        public static EntitySql ComisionesAutorizadasDeCalendarioSql(this Db db, object idCalendario)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario = @0
                    AND $autorizada = true
                ")
                .Parameters(idCalendario);

        }

        public static EntitySql ComisionesAutorizadasDePeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $autorizada = true
                ")
                .Parameters(anio, semestre);

        }

        public static EntitySql ComisionesDePeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                ")
                .Parameters(anio, semestre);

        }

        /// <summary>Persistencia de asignaciones</summary>
        /// <returns>persists y asignaciones persistidas</returns>
        public static IEnumerable<EntityPersist> PersistAsignacionesComisionText(this Db db, object idComision, string text, params string[]? headers)
        {            
            if (headers.IsNoE())
                headers = ["persona-apellidos", "persona-nombres", "persona-numero_documento", "persona-genero", "persona-fecha_nacimiento", "persona-telefono", "persona-email"];

            Data_comision_r? comObj = db.Sql("comision").Equal("id", idComision).Cache().Data<Data_comision_r>() ?? throw new Exception("comision inexistente");

            List<EntityPersist> persists = new();
        

            string[] _data = text.Split("\r\n");
            if (_data.IsNoE())
                throw new Exception("Datos vacios");

            for (var j = 0; j < _data.Length; j++)
            {
                EntityPersist persist = db.Persist();

                try
                {
                    IDictionary<string, object?> dict = _data[j].DictFromText(headers!);

                    var personaValues = db.Values("persona","persona").SsetNotNull(dict);
                    CompareParams compare = new CompareParams
                    {
                        fieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                    };
                    personaValues.PersistCompare(persist, compare);

                    var alumnoVal = db.Values("alumno").
                        Sset("persona", personaValues.Get("id")!).
                        Sset("anio_ingreso", comObj.planificacion__anio!).
                        Sset("semestre_ingreso", comObj.planificacion__semestre!).
                        Sset("plan", comObj.plan__id).InsertIfNotExists(persist);

                    db.Values("alumno_comision").
                        Sset("alumno", alumnoVal.Get("id")).
                        Sset("comision", comObj.id).InsertIfNotExists(persist);

                    var otrasAsignaciones = db.Sql("alumno_comision").Where("$alumno = @0 AND $comision != @1").
                        Parameters(alumnoVal.Get("id"), comObj.id).Cache().ColOfDict();
                    foreach (var oa in otrasAsignaciones)
                        persist.logging.AddLog("alumno_comision", "Asignacion existente " + db.Values("alumno_comision").SetValues(oa).ToString(), "PersistAsignacionesComisionText", Logging.Level.Warning);

                    persist.AddTo(persists);
                }
                catch (Exception ex)
                {
                    persist.logging.AddLog("alumno_comision", "Asignacion existente " + ex.Message, "PersistAsignacionesComisionText", Logging.Level.Error);
                }

            }

            return persists;
        }


        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde un xlsx de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public static IEnumerable<EntityPersist> PersistComisionesPf(this Db db, Data_calendario calendarioObj, string data)
        {

            var pfidComisiones = db.ComisionesAutorizadasDeCalendarioSql(calendarioObj.id!).Cache().ColOfDict().ColOfVal<string>("pfid");
            List<string> dias = new() { "Lunes", "Martes", "Miercoles", "Jueves", "Viernes" };

            Dictionary<string, object> dict = new Dictionary<string, object>();

            bool procesar_docente = false;


            List<EntityPersist> persists = new();

            foreach (var line in data.Split(new string[] { Environment.NewLine }, StringSplitOptions.RemoveEmptyEntries))
            {
                EntityPersist persist = db.Persist();

                try
                {
                    if (procesar_docente) //se coloca primero el procesar docente porque esta ubicado en la linea despues del curso
                    {
                        if (line.Contains("*"))
                        {
                            procesar_docente = false;
                            throw new Exception("Docente sin designar en " + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString());
                        }
                        else if (!line.Contains("-"))
                        {
                            throw new Exception("Salto de línea, en curso " + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString());
                        }
                        else
                        {
                            persist.logging.AddLog("comision", "Procesando docente de curso " + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString(), "persist_comisiones_pf");
                            procesar_docente = false;
                            var _cuil = line.Substring(line.IndexOf("-") - 2);
                            (string dni, string cuil) = PersonaValues.CuilDni(_cuil);

                            object? id = null;
                            if (!dni.IsNoE())
                            {
                                var d = db.Sql("persona").Equal("numero_documento", dni!).Cache().Dict();
                                id = (d.IsNoE()) ? null : d["id"];
                            }

                            if (id.IsNoE())
                            {
                                persist.logging.AddLog("comision", "No existe docente " + cuil + ". Debe procesar primero los docentes.", "persist_comisiones_pf");
                            }
                            else
                            {
                                persist.UpdateValueIds("persona", "cuil", String.Join("", cuil), id!);
                            }
                            persist.AddTo(persists);
                            continue;
                        }

                    }

                    string? diaSeleccionado = null;

                    foreach (var dia in dias) //para determinar si la linea corresponde a una comision se verifican los dias
                        if (line.Contains(dia))
                        {
                            diaSeleccionado = dia;
                            break;
                        }

                    if (diaSeleccionado.IsNoE())
                        continue;

                    dict["comision__pfid"] = line.Substring(0, line.IndexOf("/"));
                    dict["asignatura__codigo"] = line.Substring(line.IndexOf("/") + 1, line.IndexOf(" ") - line.IndexOf("/") - 1);
                    if (dict["asignatura__codigo"].ToString().Length > 5)
                        dict["asignatura__codigo"] = dict["asignatura__codigo"].ToString().Substring(0, 5).Trim();
                    dict["descripcion_horario"] = line.Substring(line.IndexOf(diaSeleccionado));
                    
                    if (!pfidComisiones.Contains(dict["comision__pfid"]))
                        continue;
                    
                    var cursoData = db.CursoDeComisionPfidCodigoAsignaturaCalendarioSql(dict["comision__pfid"], dict["asignatura__codigo"], calendarioObj.id).Cache().Dict();

                    if (cursoData.IsNoE())
                        throw new Exception("No existe curso " + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString());

                    dict["id"] = cursoData["id"];
                    persist.UpdateValueIds("curso", "descripcion_horario", dict["descripcion_horario"].ToString()!, dict["id"]!).AddTo(persists);
                    procesar_docente = true;
                }
                catch (Exception ex)
                {
                    persist.logging.AddLog("comision", ex.Message, "persist_comisiones_pf", Logging.Level.Error);
                    persist.AddTo(persists);
                }

            }

            return persists;
        }

        public static EntityPersist GenerarCursos(this Db db, EntityValues comisionVal)
        {
            EntityPersist persist = db.Persist();
            if (comisionVal.IsNullOrEmpty("id") || comisionVal.IsNullOrEmpty("planificacion"))
                throw new Exception("No se pueden generar los cursos: No está correctamente definido el id o la planificación");

            IEnumerable<object> idsCursos = db.Sql("curso").
                Where("$comision = @0").
                Parameters(comisionVal.Get("id")).
                ColOfDict().
                ColOfVal<object>("id");

            if (idsCursos.Count() > 0)
                persist.DeleteIds("curso", idsCursos.ToArray());

            IEnumerable<Dictionary<string, object?>> distribucionesHorariasData = db.Sql("distribucion_horaria").
                Select("SUM($horas_catedra) AS suma_horas_catedra").
                Group("$disposicion-asignatura").
                Where("$disposicion-planificacion IN ( @0 )").
                Parameters(comisionVal.Get("planificacion")).ColOfDict();

            foreach (Dictionary<string, object?> dh in distribucionesHorariasData)
            {
                EntityValues cursoVal = db.Values("curso").
                    Set("comision", comisionVal.Get("id")).
                    Set("asignatura", dh["disposicion-asignatura"]).
                    Set("horas_catedra", dh["suma_horas_catedra"]).
                    Default().Reset();

                persist.Insert(cursoVal);
            }

            return persist;
        }
    }
}
