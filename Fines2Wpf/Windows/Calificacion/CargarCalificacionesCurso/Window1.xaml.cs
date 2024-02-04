using Fines2Wpf.Data;
using SqlOrganize;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using Utils;
using CommunityToolkit.WinUI.Notifications;
using System;
using Microsoft.Extensions.Primitives;
using Google.Protobuf.WellKnownTypes;

namespace Fines2Wpf.Windows.Calificacion.CargarCalificacionesCurso
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        private DAO.Toma tomaDAO = new();
        private FormData formData = new FormData();

        private object idCurso;
        Data_curso_r curso = new();

        private IEnumerable<object> idsAlumnos; //ids de alumnos
        private object idDisposicion;
        
        private IEnumerable<string> dnis; //ids de alumnos

        private List<Dictionary<string, object?>> calificacionData = new();
        private ObservableCollection<Calificacion> calificacionOC = new(); 

        private IEnumerable<Dictionary<string, object?>> asignacionData;
        private ObservableCollection<Data_alumno_comision_r> asignacionOC = new();

        private ObservableCollection<Data_alumno_comision_r> asignacionExistenteNoEvaluadaOC = new();

        private IEnumerable<Dictionary<string, object?>> calificacionExistenteData;
        private ObservableCollection<Data_calificacion_r> calificacionExistenteOC = new();

        private IDictionary<string, Dictionary<string, object?>> calificacionesExistentesPorDNI;






        public Window1(object idCurso)
        {
            InitializeComponent();
            this.idCurso = idCurso;

            #region  consulta de curso
            {
                var cursoData = ContainerApp.db.Query("curso").CacheById(idCurso)!;
                curso = cursoData.Obj<Data_curso_r>();
                Values.Curso val = (Values.Curso)ContainerApp.db.Values("curso").Values(cursoData!);
                formData.curso__Label = curso.sede__numero + curso.comision__division + "/"+curso.planificacion__anio + curso.planificacion__semestre + " " + curso.asignatura__nombre + " " + curso.asignatura__codigo;
            }
            #endregion

            #region consulta de docente
            {
                Data_toma_r toma = tomaDAO.TomaAprobadaDeCursoQuery(idCurso).Dict()!.Obj<Data_toma_r>();
                formData.docente__Label = toma.docente__apellidos!.ToUpper() + " " + toma.docente__nombres!.ToTitleCase() + " " + toma.docente__numero_documento + " " + toma.docente__telefono;
            }
            #endregion
            #region consulta de id disposicion
            {
                idDisposicion = ContainerApp.db.Query("disposicion").
                Where("$asignatura = @0").
                Where(" AND $planificacion = @1").
                Parameters(curso.asignatura!, curso.comision__planificacion!).DictCache()!["id"]!;
            }
            #endregion

            ConsultarAsignacionesExistentes();
            ConsultarCalificacionesExistentes();


            formGroupBox.DataContext = formData;
            calificacionDataGrid.ItemsSource = calificacionOC;
            calificacionExistenteDataGrid.ItemsSource = calificacionExistenteOC;
            asignacionDataGrid.ItemsSource = asignacionOC;
            asignacionExistenteNoEvaluadaDataGrid.ItemsSource = asignacionExistenteNoEvaluadaOC;
            calificacionDataGrid.RowEditEnding += CalificacionDataGrid_RowEditEnding;

        }

        private void ConsultarAsignacionesExistentes()
        {
            {
                asignacionData = ContainerApp.db.Query("alumno_comision").
                Where("$comision = @0").
                Order("$estado ASC, $persona-apellidos ASC, $persona-nombres ASC").
                Parameters(curso.comision!).ColOfDictCache();
                idsAlumnos = asignacionData.ColOfVal<object>("alumno");
                dnis = asignacionData.ColOfVal<string>("persona-numero_documento");
                asignacionOC.Clear();
                if (asignacionData.Count() > 0)
                {
                    foreach (Dictionary<string, object?> kvp in asignacionData)
                    {
                        Data_alumno_comision_r asignacion = kvp.Obj<Data_alumno_comision_r>();
                        asignacionOC.Add(asignacion);
                    }
                    new ToastContentBuilder()
                    .AddText(asignacionData.Count() + " alumnos")
                    .Show();
                }
                else
                {
                    new ToastContentBuilder()
                   .AddText("Sin alumnos")
                   .Show();
                }
            }
        }

        private void ConsultarCalificacionesExistentes()
        {
            {
                calificacionExistenteData = ContainerApp.db.Query("calificacion").
                Where("$disposicion-asignatura = @0").
                Where(" AND $disposicion-planificacion = @1").
                Where(" AND $alumno IN (@2)").
                Where(" AND ($nota_final >= 7 OR $crec >= 4)").
                Parameters(curso.asignatura!, curso.comision__planificacion!, idsAlumnos).ColOfDictCache();
                calificacionExistenteOC.Clear();
                foreach (Dictionary<string, object?> kvp in calificacionExistenteData)
                {
                    Data_calificacion_r calificacion = new(DataInitMode.Null);
                    calificacion.SetData(kvp);
                    calificacionExistenteOC.Add(calificacion);
                }

                if (calificacionExistenteData.Count() > 0)
                {
                    new ToastContentBuilder()
                    .AddText(calificacionExistenteData.Count() + " calificaciones")
                    .Show();
                }
                calificacionesExistentesPorDNI = calificacionExistenteData.DictOfDictByKeys("persona-numero_documento");

            }
        }

        private void CalificacionDataGrid_RowEditEnding(object sender, DataGridRowEditEndingEventArgs e)
        {
            AnalizarCalificaciones();
        }

     
        private void AnalizarCalificaciones()
        {
            #region inicializar datos para analisis
            List<string> dnisProcesados = new();
            List<string> dnisCalificaciones = new();
            foreach (Calificacion calificacion in calificacionOC)
            {
                dnisCalificaciones.Add(calificacion.persona__numero_documento!);
            }

            IDictionary<string, Dictionary<string, object?>> personasExistentesPorDNI = ContainerApp.db.Query("persona").
                Where("$numero_documento IN (@0)").
                Parameters(dnisCalificaciones).
                ColOfDictCache().
                DictOfDictByKeys("numero_documento");

            IDictionary<string, Dictionary<string, object?>>  alumnosExistentesPorDNI = ContainerApp.db.Query("alumno").
                Where("$persona-numero_documento IN (@0)").
                Parameters(dnisCalificaciones).
                ColOfDictCache().
                DictOfDictByKeys("persona-numero_documento");

            IDictionary<string, Dictionary<string, object?>> asignacionesExistentesPorDNI = asignacionData.DictOfDictByKeys("persona-numero_documento");
            #endregion

            foreach (Calificacion calificacion in calificacionOC)
            {
                #region inicializacion y chequeos iniciales (calificacion vacía, desaprobada, dni definido, dni repetido)
                calificacion.observaciones = "";
                calificacion.procesar = true;
                calificacion.agregar_persona = false;
                calificacion.agregar_asignacion = false;
                calificacion.agregar_alumno = false;
                var dni = calificacion.persona__numero_documento!;

                if (calificacion.nota_final == 0 && calificacion.crec == 0)
                {
                    calificacion.observaciones += "Calificacion vacía. ";
                    calificacion.procesar = false;
                    continue;

                }
                else if (calificacion.nota_final < 7 && calificacion.crec < 4)
                {

                    calificacion.observaciones += "Desaprobado (no se cargara la calificacion). ";
                    calificacion.procesar = false;
                }

                if (dni.IsNullOrEmpty())
                {
                    calificacion.observaciones = "No se encuentra definido el DNI. ";
                    calificacion.procesar = false;
                    continue;
                }

                if (dnisProcesados.Contains(dni!))
                {
                    calificacion.observaciones += "DNI repetido";
                    calificacion.procesar = false;
                    continue;
                }

                dnisProcesados.Add(dni!);
                #endregion

                #region chequeo de calificacion existente
                if (calificacionesExistentesPorDNI.ContainsKey(calificacion.persona__numero_documento!))
                {
                    Calificacion cal = calificacionesExistentesPorDNI[calificacion.persona__numero_documento!].Obj<Calificacion>();
                    if (cal.archivado == calificacion.archivado)
                    {
                        calificacion.observaciones += "Calificacion existente. ";
                        calificacion.procesar = false;

                        if (calificacion.nota_final >= 7)
                        {
                            decimal n2 = Math.Round(cal.nota_final ?? 0, MidpointRounding.AwayFromZero);
                            if (calificacion.nota_final != n2)
                                calificacion.observaciones += "Nota final diferente. ";
                        }
                        else if (calificacion.crec >= 4)
                        {
                            decimal n2 = Math.Round(cal.crec ?? 0);
                            if (calificacion.crec != n2)
                                calificacion.observaciones += "Crec diferente. ";
                        }
                    }
                }
                #endregion

                #region chequeo de asignacion, alumno, persona existente, nombres de persona
                if (!asignacionesExistentesPorDNI.ContainsKey(dni!))
                {
                    calificacion.observaciones += "Asignación inexistente, será agregada. ";                              
                    calificacion.agregar_asignacion = true;

                    if (!alumnosExistentesPorDNI.ContainsKey(dni!))
                    {
                        calificacion.observaciones += "Alumno inexistente, será agregado. ";
                        calificacion.agregar_alumno = true;

                        if (!personasExistentesPorDNI.ContainsKey(dni!))
                        {
                            calificacion.observaciones += "Persona inexistente, será agregada. ";
                            calificacion.agregar_persona = true;
                        }
                        else
                        {
                            Values.Persona personaV = (Values.Persona)ContainerApp.db.Values("persona", "persona").SetObj(calificacion!);
                            Dictionary<string, object?> pe = personasExistentesPorDNI[dni!];
                            var l = new List<string> { "nombres", "apellidos", "numero_documento" };
                            IDictionary<string, object?> compareResult = personaV.CompareFields(pe, l);
                            if (!compareResult.IsNullOrEmpty())
                            {
                                foreach (string key in compareResult.Keys)
                                {
                                    calificacion.observaciones += "Diferente " + key + ". ";
                                    calificacion.procesar = false;
                                }
                            }

                            calificacion.alumno__persona = (string)personasExistentesPorDNI[dni!]["id"]!;
                        }
                    } else
                    {
                        calificacion.alumno = (string)alumnosExistentesPorDNI[dni!]["id"]!;

                        #region si no existe asignacion y existe alumno, informar las asignaciones existentes para verificar
                        if (!calificacion.agregar_alumno)
                        {
                            IEnumerable<Dictionary<string, object?>> asignacionesExistentes = ContainerApp.db.Query("alumno_comision").
                                Where("$alumno = @0").
                                Parameters(calificacion.alumno).
                                Order("$calendario-anio DESC, $calendario-semestre DESC").
                                ColOfDictCache();
                            List<string> asignacionesExistentesLabel = new();
                            foreach (var item in asignacionesExistentes)
                            {
                                var asig = item.Obj<Data_alumno_comision_r>();
                                asignacionesExistentesLabel.Add(asig.sede__numero! + asig.comision__division! + "/" + asig.planificacion__anio + asig.planificacion__semestre + " " + asig.calendario__anio + "-" +asig.calendario__semestre + " " + asig.estado?.Acronym() ?? "?");
                            }
                            if (asignacionesExistentesLabel.Count() > 0)
                                calificacion.observaciones += " Asignaciones: " + String.Join(", ", asignacionesExistentesLabel);
                        }
                        #endregion

                    }


                } else
                {
                    calificacion.alumno = (string)asignacionesExistentesPorDNI[dni!]["alumno"]!;
                }
                #endregion

                #region chequeo de asignaciones existentes no evaluadas
                asignacionExistenteNoEvaluadaOC.Clear();
                foreach (var item in asignacionOC)
                {
                    if (!dnisCalificaciones.Contains(item.persona__numero_documento))
                        asignacionExistenteNoEvaluadaOC.Add(item);
                }
                #endregion
            }
        }

        private void SubmitButton_Click(object sender, RoutedEventArgs e)
        {
            var fd = (FormData)(sender as Button)!.DataContext;

            IEnumerable<string> encabezados = fd.encabezados!.Split(",").Select(s => s.Trim());
            IEnumerable<string> datos = fd.datos!.Split("\r\n");
            List<string> dnisProcesados = new();

            EntityPersist p = ContainerApp.db.Persist();
            calificacionOC.Clear();
            calificacionData.Clear();
            for (var j = 0; j < datos.Count(); j++)
            {
                if (datos.ElementAt(j).IsNullOrEmpty())
                    continue;

                var values = datos.ElementAt(j).Split("\t");

                EntityValues calificacion = ContainerApp.db.Values("calificacion");
                for (var i = 0; i < encabezados.Count(); i++)
                {
                    if (values.ElementAt(i).IsNullOrEmpty()) continue;
                    if (encabezados.ElementAt(i) == "persona-numero_documento")
                    {
                        var value = ((string?)values.ElementAt(i)).CleanStringOfNonDigits();
                        calificacion.Sset(encabezados.ElementAt(i), value);
                    }
                    else if ((encabezados.ElementAt(i) == "nota_final") || (encabezados.ElementAt(i) == "crec"))

                    {
                        var value = ((string?)values.ElementAt(i)).CleanStringOfNonDigits();
                        calificacion.Sset(encabezados.ElementAt(i), value);
                    } else
                    {
                        calificacion.Sset(encabezados.ElementAt(i), values.ElementAt(i));
                    }

                }

                calificacionData.Add((Dictionary<string, object?>)calificacion.values);
                Calificacion o = new (DataInitMode.Default);
                o.SetData(calificacion.values);
                o.curso = (string)idCurso;
                o.disposicion = (string)idDisposicion;
                o.nota_final = Math.Round(o.nota_final ?? 0, MidpointRounding.AwayFromZero);
                o.crec = Math.Round(o.crec ?? 0, MidpointRounding.AwayFromZero);
                calificacionOC.Add(o);
            }

            AnalizarCalificaciones();
            
        }

        private void ProcessButton_Click(object sender, RoutedEventArgs e)
        {

            foreach(Calificacion cal in calificacionOC)
            {
                if (!cal.procesar)
                    continue;

                EntityPersist persist = ContainerApp.db.Persist();


                if (cal.agregar_persona)
                {
                    cal.domicilio_per__id = null;
                    EntityValues valPer = ContainerApp.db.Values("persona","persona").SetObj(cal).Default().Reset();
                    if (valPer.Check())
                    {
                        persist.Insert(valPer);
                        cal.alumno__persona = (string)valPer.Get("id");
                    }

                    else
                    {
                        cal.observaciones += valPer.logging.ToString();
                        continue;
                    }
                }

                if (cal.agregar_alumno)
                {
                    cal.alumno__plan = curso.plan__id;
                    cal.alumno__anio_ingreso = "1";
                    cal.alumno__semestre_ingreso = 1;
                    cal.alumno__resolucion_inscripcion = null;
                    EntityValues valAlu = ContainerApp.db.Values("alumno", "alumno").SetObj(cal!).Default().Reset();
                    if (valAlu.Check())
                    {
                        persist.Insert(valAlu);
                        cal.alumno = (string)valAlu.Get("id");
                    }
                    else
                    {
                        cal.observaciones += valAlu.logging.ToString();
                        continue;
                    }
                }

                if (cal.agregar_asignacion)
                {
                    //Si el alumno existe pero no existe la asignacion, se consultan las asignaciones existentes del alumno
                    
                    EntityValues valAc = ContainerApp.db.Values("alumno_comision").
                        Set("comision", curso.comision).
                        Set("alumno", cal.alumno).
                        Set("estado", "Activo").
                        Default().
                        Reset();

                    if (valAc.Check())
                        persist.Insert(valAc);
                    else
                    {
                        cal.observaciones += valAc.logging.ToString();
                        continue;
                    }
                }

                EntityValues valCal = ContainerApp.db.Values("calificacion").SetObj(cal).Default().Reset();
                if (valCal.Check())
                    persist.Insert(valCal);
                else { 
                    cal.observaciones += valCal.logging.ToString();
                    continue;
                }
                persist.Transaction().RemoveCache();

            }

            new ToastContentBuilder()
                .AddText("Calificaciones Guardadas")
                .Show();
            calificacionOC.Clear();
            ConsultarAsignacionesExistentes();
            ConsultarCalificacionesExistentes();

        }
    }
}
