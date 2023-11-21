using Fines2Wpf.Model;
using SqlOrganize;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using Utils;
using ToastNotifications;
using ToastNotifications.Lifetime;
using ToastNotifications.Position;
using ToastNotifications.Messages;
using System;
using Google.Protobuf.WellKnownTypes;

namespace Fines2Wpf.Windows.Calificacion.CargarCalificacionesCurso
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        private object idCurso;
        private Data_curso_r curso;
        private FormData formData = new FormData();
        private IEnumerable<object> idsAlumnos; //ids de alumnos
        private IEnumerable<string> dnis; //ids de alumnos
        private ObservableCollection<Calificacion> calificacionOC = new(); 
        private ObservableCollection<Data_calificacion_r> calificacionExistenteOC = new();
        private ObservableCollection<Data_alumno_comision_r> asignacionOC = new();
        private Notifier notifier = new Notifier(cfg =>
        {
            cfg.PositionProvider = new WindowPositionProvider(
                parentWindow: Application.Current.MainWindow,
                corner: Corner.TopRight,
                offsetX: 10,
                offsetY: 10);

            cfg.LifetimeSupervisor = new TimeAndCountBasedLifetimeSupervisor(
                notificationLifetime: TimeSpan.FromSeconds(3),
                maximumNotificationCount: MaximumNotificationCount.FromCount(5));

            cfg.Dispatcher = Application.Current.Dispatcher;
        });

        //private ObservableCollection<Data_alumno> alumnoExistenteOC = new();
        //private ObservableCollection<Data_persona> personaExistenteOC = new();

        public Window1(object idCurso)
        {
            InitializeComponent();
            this.idCurso = idCurso;

            #region consulta de curso
            {
                IDictionary<string, object?> data = ContainerApp.db.Query("curso").CacheById(idCurso);
                Values.Curso val = (Values.Curso)ContainerApp.db.Values("curso").Values(data!);
                formData.curso = val.ToStringSede();
                curso = val.values.Obj<Data_curso_r>();
            }
            #endregion
            #region consulta de asignaciones del curso
            {
                IEnumerable<Dictionary<string, object?>> data = ContainerApp.db.Query("alumno_comision").
                Where("$comision = @0").
                Order("$estado ASC, $persona-apellidos ASC, $persona-nombres ASC").
                Parameters(curso.comision!).ColOfDictCache();
                idsAlumnos = data.ColOfVal<object>("alumno");
                dnis = data.ColOfVal<string>("persona-numero_documento");
                asignacionOC.Clear();
                if (data.Count() > 0)
                {
                    asignacionOC.AddRange(data);
                    notifier.ShowSuccess(data.Count() + " alumnos");
                } else
                {
                    notifier.ShowError("Sin alumnos");
                }
            }
            #endregion
            #region consulta de calificaciones existentes
            {
                IEnumerable<Dictionary<string, object?>> data = ContainerApp.db.Query("calificacion").
                Where("$disposicion-asignatura = @0").
                Where(" AND $disposicion-planificacion = @1").
                Where(" AND $alumno IN (@2)").
                Where(" AND ($nota_final >= 7 OR $crec >= 4)").
                Parameters(curso.asignatura!, curso.comision__planificacion!,idsAlumnos).ColOfDictCache();
                calificacionExistenteOC.Clear();
                if (data.Count() > 0)
                {
                    calificacionExistenteOC.AddRange(data);
                    notifier.ShowWarning(data.Count() + " calificaciones");
                }

            }
            #endregion


            formGroupBox.DataContext = formData;
            calificacionDataGrid.ItemsSource = calificacionOC;
            calificacionExistenteDataGrid.ItemsSource = calificacionExistenteOC;
            asignacionDataGrid.ItemsSource = asignacionOC;
        }

        private void AnalizarCalificaciones()
        {
            var dnisCalificaciones = calificacionOC.ColOfProp<string, Calificacion>("persona__numero_documento");
            
            IDictionary<string, Dictionary<string, object?>> personasExistentes = ContainerApp.db.Query("persona").
                Where("$numero_documento IN (@0)").
                Parameters(dnisCalificaciones).
                ColOfDictCache().
                DictOfDictByKey<string>("numero_documento");

            foreach (Calificacion calificacion in calificacionOC)
            {
                var c = calificacion.Dict();
                EntityValues personaV = ContainerApp.db.Values("persona", "persona").Set(c);
                string dni = (string)personaV.Get("numero_documento");
                if (personasExistentes.ContainsKey(dni))
                {
                    Dictionary<string, object?> pe = personasExistentes[dni];
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
                } else
                {
                    calificacion.observaciones += "La persona no existe en la base de datos, será agregada. ";
                    calificacion.agregar_persona = true;
                }
            }
        }

        private void SubmitButton_Click(object sender, RoutedEventArgs e)
        {
            var fd = (FormData)(sender as Button)!.DataContext;

            IEnumerable<string> encabezados = fd.encabezados!.Split(",").Select(s => s.Trim());
            IEnumerable<string> datos = fd.datos!.Split("\r\n");
            IDictionary<string, Dictionary<string, object?>> calificacionesExistentesPorDNI = calificacionExistenteOC.ColOfDict().DictOfDictByKey<string>("persona-numero_documento");
            List<string> dnisProcesados = new();

            EntityPersist p = ContainerApp.db.Persist();
            calificacionOC.Clear();
            for (var j = 0; j < datos.Count(); j++)
            {
                if (datos.ElementAt(j).IsNullOrEmpty())
                    continue;

                var values = datos.ElementAt(j).Split("\t");

                EntityValues calificacion = ContainerApp.db.Values("calificacion");
                for (var i = 0; i < encabezados.Count(); i++)
                {
                    if (values.ElementAt(i).IsNullOrEmpty()) continue;
                    calificacion.Sset(encabezados.ElementAt(i), values.ElementAt(i));
                }

                var o = calificacion.values.Obj<Calificacion>();
                o.curso = (string)idCurso;
                o.observaciones = "";
                if (o.persona__numero_documento.IsNullOrEmpty())
                {
                    o.observaciones += "No se encuentra definido el DNI. ";
                    o.procesar = false;
                    continue;
                } 
                
                if(o.nota_final.IsNullOrEmptyOrDbNull() && o.crec.IsNullOrEmptyOrDbNull())
                {
                    o.observaciones += "Calificacion vacía. ";
                    o.procesar = false;
                    continue;

                } else if (o.nota_final <= 7 && o.crec <= 4)
                {
                    o.observaciones += "Desaprobado (se cargara la calificacion archivada). ";
                    o.archivado = true;
                }

                if (dnisProcesados.Contains(o.persona__numero_documento!))
                {
                    o.observaciones += "DNI repetido";
                    o.procesar = false;
                    continue;
                }

                dnisProcesados.Add(o.persona__numero_documento!);

                if (calificacionesExistentesPorDNI.ContainsKey(o.persona__numero_documento)){
                    Calificacion cal = calificacionesExistentesPorDNI[o.persona__numero_documento].Obj<Calificacion>();
                    if (cal.archivado == o.archivado) { 
                        o.observaciones += "Calificacion existente. ";
                        o.procesar = false;

                        if (!o.nota_final.IsNullOrEmptyOrDbNull() && o.nota_final >= 7)
                        {
                            string n1 = o.nota_final.ToString()!;
                            string n2 = cal.nota_final?.ToString() ?? "0";
                            if (n1 != n2)
                                o.observaciones += "Nota final diferente. ";
                        } else if (!o.crec.IsNullOrEmptyOrDbNull() && o.crec >= 4)
                        {
                            string n1 = o.crec.ToString()!;
                            string n2 = cal.crec?.ToString() ?? "0";
                            if (n1 != n2)
                                o.observaciones += "Crec diferente. ";
                        }
                    }
                }

                calificacionOC.Add(o);
            }

            AnalizarCalificaciones();
            
        }

        private void ProcessButton_Click(object sender, RoutedEventArgs e)
        {
            foreach(Calificacion cal in calificacionOC)
            {
                var calData = cal.Dict();

                if (!cal.procesar)
                    return;

                EntityPersist persist = ContainerApp.db.Persist();
                if (cal.agregar_persona)
                {
                    EntityValues valPer = ContainerApp.db.Values("persona", "persona").Set(calData);
                    if (valPer.Check())
                        persist.Insert(valPer);
                    else
                    { 
                        cal.observaciones += valPer.logging.ToString();
                        continue;
                    }
                }

                EntityValues valCal = ContainerApp.db.Values("calificacion").Set(calData).Reset();
                if (valCal.Check())
                    persist.Insert(valCal);
                else { 
                    cal.observaciones += valCal.logging.ToString();
                    continue;
                }

                persist.Transaction().RemoveCache();
            }
        }
    }
}
