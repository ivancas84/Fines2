using Org.BouncyCastle.Utilities;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Net;

namespace Fines2Wpf.DAO
{
    public class Comision
    {

        public void UpdateValueRel(string key, object value, Dictionary<string, object?> source)
        {
            EntityPersist p = ContainerApp.db.Persist().UpdateValueRel("comision", key, value, source).Exec().RemoveCache();
        }

        public IEnumerable<Dictionary<string, object?>> ComisionesSemestre(object calendarioAnio, object calendarioSemestre, object? sede = null, bool? autorizada = null)
        {
            var q = ContainerApp.db.Sql("comision")
                .Fields()
                .Select("CONCAT($sede-numero, $division, '/', $planificacion-anio, $planificacion-semestre) AS numero")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                ")
                .Parameters(calendarioAnio, calendarioSemestre);
            var count = 2;
            if (!autorizada.IsNoE())
            {
                q.Where("AND $autorizada = @" + count + " ");
                q.Parameters(autorizada!);
                count++;
            }
            if (!sede.IsNoE())
            {
                q.Where("AND sede = @" + count + " ");
                q.Parameters(sede!);
            }

            return q.Cache().ColOfDict();
        }



        public IEnumerable<Dictionary<string, object?>> ComisionesPorIds(List<object> ids)
        {
            return ContainerApp.db.Sql("comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $id IN ( @0 ) 
                ")
                .Parameters(ids).Cache().ColOfDict();
            
        }

        public IEnumerable<Dictionary<string, object?>> ComisionesConSiguientePorCalendario(object anio, object semestre)
        {
            return ContainerApp.db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1 
                    AND $comision_siguiente IS NOT NULL
                ")
                .Parameters(anio, semestre).Cache().ColOfDict();

        }

        public IEnumerable<object> IdsComisionesAutorizadasPorCalendario(object anio, object semestre)
        {
            return ContainerApp.db.Sql("comision")
                .Fields(ContainerApp.db.config.id)
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $autorizada = true
                ")
                .Parameters(anio, semestre).Cache().ColOfDict().ColOfVal<object>(ContainerApp.db.config.id);

        }

        public IEnumerable<Dictionary<string, object>> ComisionesAutorizadasPorSemestre(object anio, object semestre)
        {
            IEnumerable<object> ids = IdsComisionesAutorizadasPorCalendario(anio, semestre);
            return ContainerApp.db.Sql("comision").Cache().Ids(ids.ToArray());
        }

        public EntitySql ComisionesAutorizadasPorSemestreQuery(object anio, object semestre)
        {
            return ContainerApp.db.Sql("comision")
                .Fields()
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $autorizada = true
                ")
                .Parameters(anio, semestre);
        }


        public EntitySql BusquedaAproximadaQuery(string search)
        {
            return ContainerApp.db.Sql("comision")
               .Fields()
               .Size(0)
               .Where(@"
                    $sede-nombre LIKE @0
                    OR
                    CONCAT($sede-numero, $division, '/', $planificacion-anio, $planificacion-semestre) LIKE @0
                    OR
                    CONCAT($calendario-anio, '-', $calendario-semestre) LIKE @0
                ")
               .Order("$sede-numero ASC, $division ASC, $calendario-anio DESC, $calendario-semestre DESC")
               .Parameters("%"+search+"%");
        }


        public EntitySql HorariosQuery(params object[] idComisiones)
        {
            return ContainerApp.db.Sql("horario").
                Where("$curso-comision IN ( @0 )").
                Parameters(idComisiones).
                Size(0);
        }

        /// <summary> Persistencia de tomas obtenidas desde PF </summary>
        /// <remarks> Los datos se obtienen desde un xlsx de https://programafines.ar/inicial/index4.php?a=46</remarks>
        public static IEnumerable<EntityPersist> PersistComisionesPf(this Db db, Data_calendario calendarioObj, string data)
        {

            var pfidComisiones = db.ComisionesAutorizadasDeCalendarioSql(calendarioObj.id!).Cache().ColOfDict().ColOfVal<string>("pfid");
            List<string> dias = new() { "Lunes", "Martes", "Miercoles", "Jueves", "Viernes" };

            Dictionary<string, object> dict = new Dictionary<string, object>();

            bool procesar_docente = false;
            bool procesar_comision = false;


            List<EntityPersist> persists = new();

            foreach (var line in data.Split(new string[] { Environment.NewLine }, StringSplitOptions.RemoveEmptyEntries))
            {
                EntityPersist persist = db.Persist();

                try { 
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
                            var _cuil = line.Substring(line.IndexOf("-") - 2, 13);
                            (string dni, string cuil) = PersonaValues.CuilDni(_cuil);

                            object? id = null;
                            if (!dni.IsNoE())
                            {
                                var d = ContainerApp.db.Sql("persona").Equal("numero_documento", dni!).Cache().Dict();
                                id = (d.IsNoE()) ? null : d["id"];
                            }
                        
                            if (id.IsNoE() )
                            {
                                persist.logging.AddLog("comision", "No existe docente " + cuil + ". Debe procesar primero los docentes.", "persist_comisiones_pf");
                            } else
                            {
                                persist.UpdateValueIds("persona", "cuil", String.Join("", cuil), id!);

                            }
                            persist.AddTo(persists);
                            continue;
                        }

                    }

                    string diaSeleccionado = null;

                    foreach (var dia in dias) //para determinar si la linea corresponde a una comision se verifican los dias
                        if (line.Contains(dia)) {
                            diaSeleccionado = dia;
                            break;
                        }

                    if (diaSeleccionado.IsNoE())
                        throw new Exception("No se encontró día");

                    dict["comision__pfid"] = line.Substring(0, line.IndexOf("/"));
                    dict["asignatura__codigo"] = line.Substring(line.IndexOf("/") + 1, line.IndexOf(" ") - line.IndexOf("/") - 1);
                    if (dict["asignatura__codigo"].ToString().Length > 5)
                        dict["asignatura__codigo"] = dict["asignatura__codigo"].ToString().Substring(0, 5).Trim();
                    dict["descripcion_horario"] = line.Substring(line.IndexOf(diaSeleccionado));
                    if (pfidComisiones.Contains(dict["comision__pfid"]))
                    {
                        persist.logging.AddLog("comision", "Procesando comision" + dict["comision__pfid"], "persist_comisiones_pf");

                        var cursoData = db.CursoDeComisionPfidCodigoAsignaturaCalendarioSql(dict["comision__pfid"], dict["asignatura__codigo"], calendarioObj.id).Cache().Dict();
                        dict["id"] = cursoData["id"];

                        if (cursoData.IsNoE())
                            throw new Exception("No existe curso " + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString());
                                    
                        var p = ContainerApp.db.Persist().UpdateValueIds("curso", "descripcion_horario", dict["descripcion_horario"].ToString()!, dict["id"]!);
                        procesar_docente = true;
                    }

                    persist.AddTo(persists);
                }
                catch (Exception ex)
                {
                    persist.logging.AddLog("comision", ex.Message, "persist_comisiones_pf", Logging.Level.Error);
                    persist.AddTo(persists);
                }

            }

            return persists;






        }

    }
}
