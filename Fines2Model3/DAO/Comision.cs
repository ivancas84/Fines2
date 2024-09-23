using SqlOrganize;
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
                    $sede__nombre LIKE @0
                    OR
                    CONCAT($sede__numero, $division, '/', $planificacion__anio, $planificacion__semestre) LIKE @0
                    OR
                    $pfid LIKE @0
                    OR
                    CONCAT($calendario__anio, '/', $calendario__semestre) LIKE @0
                ")
               .Order("$sede__numero ASC, $division ASC, $calendario__anio DESC, $calendario__semestre DESC")
               .Param("@0", "%" + search + "%");

        }

        public static EntitySql ComisionesAutorizadasDeCalendarioSql(this Db db, object idCalendario)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario = @0
                    AND $autorizada = true
                ")
                .Param("@0", idCalendario);

        }

        public static EntitySql ComisionesAutorizadasDePeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0
                    AND $calendario__semestre = @1
                    AND $autorizada = true
                ")
                .Param("@0", anio).Param("@1", semestre);

        }

        public static EntitySql ComisionesDePeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario__anio = @0
                    AND $calendario__semestre = @1
                ").
                Order("$pfid ASC")
                .Param("@0",anio).Param("@1", semestre);

        }

        /// <summary>Persistencia de asignaciones</summary>
        /// <returns>persists y asignaciones persistidas</returns>
        public static IEnumerable<PersistContext> PersistAsignacionesComisionText(this Db db, object idComision, string text, params string[]? headers)
        {            
            if (headers.IsNoE())
                headers = ["persona__apellidos", "persona__nombres", "persona__numero_documento", "persona__genero", "persona__fecha_nacimiento", "persona__telefono", "persona__email"];

            Comision? comObj = db.Sql("comision").Equal("id", idComision).Cache().Data<Comision>() ?? throw new Exception("comision inexistente");

            List<PersistContext> persists = new();
        

            string[] _data = text.Split("\r\n");
            if (_data.IsNoE())
                throw new Exception("Datos vacios");

            for (var j = 0; j < _data.Length; j++)
            {
                PersistContext persist = db.Persist();

                try
                {
                    IDictionary<string, object?> dict = _data[j].DictFromText(headers!);

                    var personaValues = db.Values("persona","persona").SsetNotNull(dict);
                    CompareParams compare = new CompareParams
                    {
                        FieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                    };
                    personaValues.PersistCompare(persist, compare);

                    var alumnoVal = db.Values("alumno").
                        Sset("persona", personaValues.Get("id")!).
                        Sset("anio_ingreso", comObj.planificacion_.anio!).
                        Sset("semestre_ingreso", comObj.planificacion_.semestre!).
                        Sset("plan", comObj.planificacion_.plan_.id).InsertIfNotExists(persist);

                    db.Values("alumno_comision").
                        Sset("alumno", alumnoVal.Get("id")).
                        Sset("comision", comObj.id).InsertIfNotExists(persist);

                    var otrasAsignaciones = db.Sql("alumno_comision").Where("$alumno = @0 AND $comision != @1").
                        Param("@0", alumnoVal.Get("id")).Param("@1", comObj.id).Cache().Dicts();
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
        public static IEnumerable<PersistContext> PersistComisionesPf(this Db db, Calendario calendarioObj, string data)
        {

            var pfidComisiones = db.ComisionesAutorizadasDeCalendarioSql(calendarioObj.id!).Cache().Dicts().ColOfVal<string>("pfid");
            List<string> dias = new() { "Lunes", "Martes", "Miercoles", "Jueves", "Viernes" };

            Dictionary<string, object> dict = new Dictionary<string, object>();

            bool procesar_docente = false;


            List<PersistContext> persists = new();

            foreach (var line in data.Split(new string[] { Environment.NewLine }, StringSplitOptions.RemoveEmptyEntries))
            {
                PersistContext persist = db.Persist();

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
                                persist.UpdateFieldIds("persona", "cuil", String.Join("", cuil), id!);
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
                    persist.UpdateFieldIds("curso", "descripcion_horario", dict["descripcion_horario"].ToString()!, dict["id"]!).AddTo(persists);
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

        public static PersistContext GenerarCursos(this Db db, EntityVal comisionVal)
        {
            PersistContext persist = db.Persist();
            if (comisionVal.IsNullOrEmpty("id") || comisionVal.IsNullOrEmpty("planificacion"))
                throw new Exception("No se pueden generar los cursos: No está correctamente definido el id o la planificación");

            IEnumerable<object> idsCursos = db.Sql("curso").
                Where("$comision = @0").
                Param("@0", comisionVal.Get("id")).
                Dicts().
                ColOfVal<object>("id");

            if (idsCursos.Count() > 0)
                persist.DeleteIds("curso", idsCursos.ToArray());

            IEnumerable<Dictionary<string, object?>> distribucionesHorariasData = db.Sql("distribucion_horaria").
                Select("SUM($horas_catedra) AS suma_horas_catedra").
                Group("$disposicion__asignatura").
                Where("$disposicion__planificacion IN ( @0 )").
                Param("@0", comisionVal.Get("planificacion")).Dicts();

            foreach (Dictionary<string, object?> dh in distribucionesHorariasData)
            {
                EntityVal cursoVal = db.Values("curso").
                    Set("comision", comisionVal.Get("id")).
                    Set("asignatura", dh["disposicion__asignatura"]).
                    Set("horas_catedra", dh["suma_horas_catedra"]).
                    Default().Reset();

                persist.Insert(cursoVal);
            }

            return persist;
        }
    }
}
