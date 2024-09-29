using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using SqlOrganize.CollectionUtils;
using Newtonsoft.Json;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Calendario : Entity
    {
        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return anio?.ToString() ?? "?" + " " + semestre ?? "?" + " " + descripcion ?? "?";
            }
            set
            {
                Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }

        public static (short anio, short semestre) AnioSemestreAnterior(short anio, short semestre)
        {
            if (semestre == 1)
            {
                semestre = 2;
                anio--;
            }
            else
            {
                semestre = 1;
            }

            return (anio, semestre);
        }

        public static (short anio, short semestre) AnioSemestreSiguiente(short anio, short semestre)
        {
            if (semestre == 2)
            {
                semestre = 1;
                anio++;
            }
            else
            {
                semestre = 2;
            }

            return (anio, semestre);
        }

        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde un xlsx de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public void PersistComisionesPf(string data)
        {

            var pfidComisiones = ComisionDAO.ComisionesAutorizadasDeCalendarioSql(id!).Cache().Dicts().ColOfVal<string>("pfid");
            List<string> dias = new() { "Lunes", "Martes", "Miercoles", "Jueves", "Viernes" };

            Dictionary<string, object> dict = new ();

            bool procesar_docente = false;


            foreach (var line in data.Split(new string[] { Environment.NewLine }, StringSplitOptions.RemoveEmptyEntries))
            {
                PersistContext persist = db.Persist();
                
                try
                {
                    #region 2da parte
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
                            (string dni, string cuil) = Persona.CuilDni(_cuil);

                            object? id = null;
                            if (!dni.IsNoE())
                            {
                                var d = Context.db.Sql("persona").Equal("numero_documento", dni!).Cache().Dict();
                                id = (d.IsNoE()) ? null : d["id"];
                            }

                            if (id.IsNoE())
                            {
                                persist.logging.AddLog("comision", "No existe docente " + cuil + ". Debe procesar primero los docentes.", "persist_comisiones_pf");
                            }
                            else
                            {
                                persist.UpdateFieldIds("persona", "cuil", System.String.Join("", cuil), id!);
                            }
                            continue;
                        }

                    }
                    #endregion

                    #region 1ra parte
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

                    var cursoData = CursoDAO.CursoDeComisionPfidCodigoAsignaturaCalendarioSql(dict["comision__pfid"], dict["asignatura__codigo"], id!).Cache().Dict();

                    if (cursoData.IsNoE())
                        throw new Exception("No existe curso " + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString());

                    dict["id"] = cursoData["id"];
                    persist.UpdateFieldIds("curso", "descripcion_horario", dict["descripcion_horario"].ToString()!, dict["id"]!);
                    procesar_docente = true;
                    #endregion
                }
                catch (Exception ex)
                {
                    persist.logging.AddLog("comision", ex.Message, "persist_comisiones_pf", Logging.Level.Error);
                }

            }

        }


        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde el html procesado con pagemanipulator de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public void PersistTomasPfHtml(string data)
        {

            var pfidComisiones = ComisionDAO.ComisionesAutorizadasDeCalendarioSql(id).Cache().Dicts().ColOfVal<string>("pfid");
            var docentes = JsonConvert.DeserializeObject<List<DocentePfItem>>(data)!;

            foreach (DocentePfItem docenteItem in docentes)
            {
                PersistContext persist = Context.db.Persist();

                try
                {
                    #region insertar o actualizar docente (se insertan o actualizan todos)
                    var d = docenteItem.Dict();
                    if (!d["anio_nacimiento"].IsNoE() && !d["mes_nacimiento"].IsNoE() && !d["dia_nacimiento"].IsNoE())
                        d["fecha_nacimiento"] = new DateTime((int)d["anio_nacimiento"], (int)d["mes_nacimiento"], (int)d["dia_nacimiento"]);

                    (string? dni, string? cuil) = Persona.CuilDni(d["numero_documento"]);

                    CompareParams compare = new CompareParams
                    {
                        FieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                    };

                    Persona persona = new Persona();
                    persona.SetNotNull(d);
                    persona.numero_documento = dni;
                    persona.Reset();
                    persist.PersistCompare(persona, compare);
                    #endregion


                    #region insertar o actualizar cargo
                    bool existenCargos = false;

                    foreach (var cargo in docenteItem.cargos)
                    {

                        if (!pfidComisiones.Contains(cargo["comision"]))
                            continue;

                        existenCargos = true;

                        try
                        {

                            object idCurso = CursoDAO.CursoDeComisionPfidCodigoAsignaturaCalendarioSql(cargo["comision"], cargo["codigo"], id).Cache().Dict()?["id"]!;
                            if (idCurso.IsNoE())
                                throw new Exception("No existe curso " + cargo["comision"] + " " + cargo["codigo"]);


                            Toma? rowTomaActiva = TomaDAO.TomaAprobadaDeCursoQuery(idCurso).Cache().ToEntity<Toma>();
                            if (rowTomaActiva != null)
                            {
                                if (!rowTomaActiva.docente!.Equals(persona.id))
                                    throw new Exception("Existe una toma activa con otro docente en " + cargo["comision"] + " " + cargo["codigo"]);
                                else
                                    persist.logging.AddLog("calendario", "La toma ya se encuentra cargada", "PersistTomasPfHtml");
                            }
                            else
                            {
                                Toma toma = new();
                                toma.curso = (string)idCurso;
                                toma.docente_ = persona;
                                toma.estado = "Aprobada";
                                toma.estado_contralor = "Pendiente";
                                toma.tipo_movimiento = "AI";
                                toma.fecha_toma = inicio;
                                toma.Reset();
                                persist.Insert(toma);
                            }
                        }
                        catch (Exception ex)
                        {
                            persist.logging.AddLog("calendario", "ERROR " + ex.Message + " (" + docenteItem.Dict().ToStringKeyValuePair() + ")", "PersistTomasPfHtml");
                        }

                    }
                    #endregion

                }
                catch (Exception ex)
                {
                    persist.logging.AddLog("calendario", "ERROR " + ex.Message + " (" + docenteItem.Dict().ToStringKeyValuePair() + ")", "PersistTomasPfHtml");
                }

            }
        }

        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde un xlsx de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public void PersistTomasPf(string data)
        {

            string[] headers = { "persona__apellidos", "persona__nombres", "persona__numero_documento", "persona__descripcion_domicilio", "persona__localidad", "persona__fecha_nacimiento", "persona__telefono", "persona__email_abc", "comision__pfid", "descripcion_asignatura", "CENS" };

            var _data = data.Split("\r\n");
            if (_data.IsNoE())
                throw new Exception("Datos vacios");

            for (var j = 0; j < _data.Length; j++)
            {
                PersistContext persist = Context.db.Persist();

                try
                {

                    var values = _data[j].Split("\t");

                    if (values.Count() < headers.Length)
                        throw new Exception("La cantidad de datos no es suficiente");

                    Dictionary<string, object?> dict = new();
                    for (var k = 0; k < headers.Length; k++)
                        dict[headers[k]] = values[k];

                    if (!dict["CENS"]!.ToString()!.Equals("462"))
                        throw new Exception("CENS " + dict["CENS"] + ": no corresponde al 462");

                    (string? dni, string? cuil) = Persona.CuilDni(dict["persona__numero_documento"]);
                    CompareParams compare = new CompareParams
                    {
                        FieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                    };

                    Persona persona = new();
                    persona.Set(dict, "persona");
                    persona.Set("numer_docuemento", dni);
                    persist.PersistCompare(persona, compare);

                    object pfid = dict["comision__pfid"]!;
                    string codigo = dict["descripcion_asignatura"]!.ToString()!.SubstringBetween("(", ")");

                    object? idCurso = CursoDAO.CursoDeComisionPfidCodigoAsignaturaCalendarioSql(pfid, codigo, id!).Value<object>("id");
                    if (idCurso.IsNoE())
                        throw new Exception("No existe curso");

                    var toma = TomaDAO.TomaAprobadaDeCursoQuery(idCurso!).ToEntity<Toma>();
                    if (toma.IsNoE())
                    {
                        toma = new Toma();
                        toma.Set("curso", idCurso);
                        toma.Set("docente", persona.Get("id")!);
                        toma.Set("fecha_toma", inicio!);
                        toma.Set("estado", "Aprobada");
                        toma.Set("estado_contralor", "Pasar");
                        toma.Set("tipo_movimiento", "AI");
                        toma.Reset();
                        if (!toma.Check())
                            throw new Exception(toma.Logging.ToString());

                        persist.Insert(toma);
                    }
                    else
                    {
                        if (!toma.docente!.Equals(persona.Get("id")))
                            throw new Exception("Existe una toma asignada a un docente diferente");
                        else
                            throw new Exception("Ya existe la toma");
                    }


                    persist.logging.AddLog("calendario", "proceso finalizado", "persist_tomas_pf", Logging.Level.Info);

                }
                catch (Exception ex)
                {
                    persist.logging.AddLog("calendario", ex.Message, "persist_tomas_pf", Logging.Level.Error);
                    continue;
                }

            }

        }

        public void GenerarComisionesSemestreSiguiente(string idCalendarioComisionesSiguientes)
        {
            IEnumerable<Comision> comisionesAutorizadasSemestre = Context.db.Sql("comision").
               Where(@" 
                        $calendario__id = @0 
                        AND $comision_siguiente IS NULL
                        AND $autorizada is true
                        AND (($planificacion__anio = '3' AND $planificacion__semestre = '1')
                        OR ($planificacion__anio = '2' AND $planificacion__semestre = '2')
                        OR ($planificacion__anio = '2' AND $planificacion__semestre = '1')
                        OR ($planificacion__anio = '1' AND $planificacion__semestre = '2')
                        OR ($planificacion__anio = '1' AND $planificacion__semestre = '1'))
                    ").
               Size(0).
               Param("@0", id).
               Cache().
               Entities<Comision>();

            for (int i = 0; i < comisionesAutorizadasSemestre.Count(); i++)
            {
                PersistContext persist = Context.db.Persist();

                Comision comisionExistente = comisionesAutorizadasSemestre.ElementAt(i);
                Comision nuevaComision = comisionExistente.ShallowCopy<Comision>();
                nuevaComision.apertura = false;
                nuevaComision.calendario = idCalendarioComisionesSiguientes;
                nuevaComision.SetDefault("id");
                nuevaComision.SetDefault("alta");
                nuevaComision.Reset();

                Planificacion? nuevaPlanificacion = PlanificacionDAO.PlanificacionSiguienteSql(comisionExistente.planificacion_.anio!, comisionExistente.planificacion_.semestre!, comisionExistente.planificacion_.plan_.id!).Cache().ToEntity<Planificacion>();

                if (nuevaPlanificacion == null)
                {
                    persist.logging.AddErrorLog(i.ToString(), "No está definida la planificación para la nueva comision " + nuevaComision.Label, "generar_comisiones_siguientes");
                    continue;
                }

                nuevaComision.planificacion_ = nuevaPlanificacion;

                if (!nuevaComision.Check())
                {
                    persist.logging.AddErrorLog(i.ToString(), "Error al verificar " + nuevaComision.Label, "generar_comisiones_siguientes");
                    continue;
                }

                persist.Insert(nuevaComision);
                comisionExistente.comision_siguiente = nuevaComision.id;
                
                persist.UpdateField(comisionExistente, "comision_siguiente");
            }

        }
    }
}
