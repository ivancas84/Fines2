using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Windows;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;

namespace Fines2Wpf.Windows.AlumnoComision.CargarNuevosAlumnos
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private string? IdComision;
        private Data_comision_r comision;
        private ObservableCollection<StatusData> statusData = new();
        private DAO.AlumnoComision alumnoComisionDAO = new();
        private EntityPersist persist = ContainerApp.db.Persist();

        public Window1(string? idComision = null)
        {

            InitializeComponent();
            this.IdComision = idComision;
            labelTextBox.IsReadOnly = true;
            Loaded += CargarNuevosAlumnos_Loaded;
            headersTextBox.Text = "persona-nombres, persona-apellidos, persona-numero_documento, persona-fecha_nacimiento";
            statusGrid.ItemsSource = statusData;
        }

        private void CargarNuevosAlumnos_Loaded(object sender, RoutedEventArgs e)
        {
            var data = ContainerApp.db.Sql("comision").Cache().Id(IdComision!);
            labelTextBox.Text = ((ComisionValues)ContainerApp.db.Values("comision").Values(data)).ToString();
            comision = data.Obj<Data_comision_r>();
        }

        private void ProcesarButton_Click(object sender, RoutedEventArgs e)
        {
            IEnumerable<string> _headers = headersTextBox.Text.Split(",").Select(s => s.Trim());
            var _data = data.Text.Split("\r\n");
            statusData.Clear();
            for (var j = 0; j < _data.Length; j++)
            {
                try
                {
                    #region Inicializar datos de persona
                    if (_data[j].IsNoE())
                    continue;
                var values = _data[j].Split("\t");

                var personaData = new Dictionary<string, object?>();
                for (var i = 0; i < _headers.Count(); i++)
                {
                    if (values.ElementAt(i).IsNoE()) continue;
                    personaData.Add(_headers.ElementAt(i), values.ElementAt(i));
                }
                    #endregion

                    #region Procesar persona

                    PersonaValues personaVal = (PersonaValues)ContainerApp.db.Values("persona", "persona").
                        Sset(personaData).Reset();


                    IDictionary<string, object?>? personaExistenteData = ContainerApp.db.Sql("persona").
                        Unique(personaVal).
                        Cache().Dict();

                    if (!personaExistenteData.IsNoE()) //existen datos de persona en la base
                    {
                        PersonaValues personaExistenteVal = (PersonaValues)ContainerApp.db.Values("persona").Values(personaExistenteData);

                        CompareParams cp = new()
                        {
                            val = personaExistenteVal,
                            ignoreNull = true,
                            fieldsToCompare = new List<string> { "apellidos", "nombres", "numero_documento" }
                        };
                        var dataDifferent = personaVal.Compare(cp);

                        if (!dataDifferent.IsNoE())
                        {
                            statusData.Add(new StatusData()
                            {
                                row = j,
                                status = "error",
                                detail = "Los valores de persona existente son diferentes no se realizara ningún registro",
                                data = "Nuevo: " + personaVal.ToStringFields(dataDifferent.Keys.ToArray()) + ". Existente: " + personaExistenteVal!.ToStringFields(dataDifferent.Keys.ToArray())
                            });
                            continue;
                        }
                        personaVal.Set("id", personaExistenteVal!.Get("id"));
                        statusData.Add(new StatusData()
                        {
                            row = j,
                            status = "info",
                            detail = "Persona existente.",
                            data = personaVal.ToString()
                        });

                        cp = new()
                        {
                            val = personaExistenteVal,
                            ignoreNull = true,
                            ignoreFields = new List<string> { "apellidos", "nombres", "numero_documento" }
                        };
                      
                        dataDifferent = personaVal.Compare(cp);

                        foreach (var (key, value) in dataDifferent)
                        {
                            switch (key)
                            {
                                case "telefono":
                                    var t = personaExistenteVal.GetOrNull("telefono");
                                    if (t.IsNoE() || t.ToString().Equals("0"))
                                    {
                                        persist.UpdateValue(personaExistenteVal, "telefono", personaVal.GetOrNull("telefono"));
                                        dataDifferent.Remove("telefono");
                                        statusData.Add(new StatusData()
                                        {
                                            row = j,
                                            status = "info",
                                            detail = "Se actualizará el valor de telefono",
                                            data = "Nuevo: " + personaVal.GetOrNull("telefono") + ". Existente: " + personaExistenteVal!.GetOrNull("telefono")
                                        });
                                    }
                                    break;
                            }
                        }

                        if (!dataDifferent.IsNoE())
                        {
                            statusData.Add(new StatusData()
                            {
                                row = j,
                                status = "warning",
                                detail = "Los valores de persona existente son diferentes, verificar y si es necesario cambiar",
                                data = "Nuevo: " + personaVal.ToStringFields(dataDifferent.Keys.ToArray()) + ". Existente: " + personaExistenteVal!.ToStringFields(dataDifferent.Keys.ToArray())
                            });
                        }

                    }
                    else //no existen datos de persona en la base
                    {
                        personaVal.Default().Reset().Insert(persist);
                        statusData.Add(new StatusData()
                        {
                            row = j,
                            status = "insert",
                            detail = "Persona agregada.",
                            data = personaVal.ToString()
                        });
                    }
                    #endregion

                    #region Procesar alumno
                    var alumnoVal = ContainerApp.db.Values("alumno").Set("persona", personaVal.Get("id"));
                    var alumnoExistenteData = ContainerApp.db.Sql("alumno").Unique(alumnoVal).Cache().Dict();

                    if (!alumnoExistenteData.IsNoE()) //existen datos de alumno en la base
                    {
                        var alumnoExistente = ContainerApp.db.Values("alumno").Values(alumnoExistenteData!);

                        statusData.Add(new()
                        {
                            row = j,
                            status = "info",
                            detail = "Alumno existente.",
                            data = personaVal.ToString()
                        });
                        alumnoVal.Set("id", alumnoExistente!.Get("id"));
                        if (alumnoExistente!.Get("plan").IsNoE())
                        {
                            persist.UpdateValue(alumnoExistente, "plan", comision.plan__id);
                            statusData.Add(new()
                            {
                                row = j,
                                status = "update",
                                detail = "Se actualizará el plan del alumno que estaba vacío.",
                                data = personaVal.ToString()
                            });
                        }
                        else if (!alumnoExistente!.Get("plan")!.ToString()!.ToLower().Equals(comision.planificacion__plan.ToString().ToLower()))
                        {
                            var alumnoPlan = alumnoExistente.ValuesTree("plan");
                            statusData.Add(new()
                            {
                                row = j,
                                status = "warning",
                                detail = "El plan del alumno es diferente del plan de la comision.",
                                data = "Nuevo: " + comision.plan__orientacion + " " + comision.plan__resolucion + ". Existente: " + alumnoPlan.Get("orientacion").ToString() + " " +alumnoPlan.Get("resolucion").ToString()
                            });
                        }
                    }
                    else //no existen datos del alumno en la base
                    {
                        alumnoVal.Default().Set("plan", comision.planificacion__plan!).Reset().Insert(persist);
                        statusData.Add( new StatusData()
                        {
                            row = j,
                            status = "insert",
                            detail = "Alumno agregado.",
                            data = personaVal.ToString()
                        });
                    }
                    #endregion

                    #region procesar asignacion
                    var asignacion = ContainerApp.db.Values("alumno_comision").
                        Set("comision", comision.id!).
                        Set("alumno", alumnoVal.Get("id"));

                    var asignacionExistenteData = ContainerApp.db.Sql("alumno_comision").Unique(asignacion).Cache().Dict();
                    if (!asignacionExistenteData.IsNoE()) //existen datos de alumno en la base
                    {
                        statusData.Add(new()
                        {
                            row = j,
                            status = "warning",
                            detail = "Ya existe asignacion en la comisión actual con estado " + asignacionExistenteData["estado"].ToString(),
                            data = personaVal.ToString()
                        });
                    }
                    else //no existen datos de asignacion
                    {
                        asignacion.Default().Reset().Insert(persist);
                        statusData.Add(
                        new StatusData()
                        {
                            row = j,
                            status = "insert",
                            detail = "Asignacion agregada.",
                            data = personaVal.ToString()!
                        });
                    }
                    #endregion

                    #region controlar asignaciones activas del semestre
                    var otrasAsignacionesDelSemestre =  alumnoComisionDAO.AsignacionesDelAlumnoEnOtrasComisionesAutorizadasDelSemestre(comision.calendario__anio!, comision.calendario__semestre!, comision.id!, alumnoVal.Get("id"));
                    foreach(var a in otrasAsignacionesDelSemestre)
                    {
                        var comD = ContainerApp.db.Sql("comision").Cache().Id(a["comision"]);
                        var comV = (ComisionValues)ContainerApp.db.Values("comision").Values(comD!);

                        statusData.Add(new StatusData()
                        {
                            row = j,
                            status = "warning",
                            detail = "Se encuentra en otra comision del mismo semestre",
                            data = personaVal.ToString() + " en " + comV.ToString() + " con estado " + a["estado"].ToString()
                        });
                    }
                    #endregion

                    #region informar otras asignaciones activas del alumno
                    var otrasAsignaciones = alumnoComisionDAO.AsignacionesDelAlumnoEnOtrasComisionesAutorizadas(comision.id!, alumnoVal.Get("id"));
                    foreach (var a in otrasAsignaciones)
                    {
                        IDictionary<string, object?> comD = ContainerApp.db.Sql("comision").Cache().Id(a["comision-id"]);
                        ComisionValues comV = (ComisionValues)ContainerApp.db.Values("comision").Values(comD!);

                        statusData.Add(new StatusData()
                        {
                            row = j,
                            status = "info",
                            detail = "Asignación en otra comisión",
                            data = personaVal.ToString() + " en " + comV.ToString() + " con estado " + a["estado"]
                        });
                    }
                    #endregion

                }
                catch (Exception ex)
                {
                    statusData.Add(new StatusData()
                    {
                        row = j,
                        status = "error",
                        detail = ex.Message,
                        data = "persona no definida"
                    });
                    continue;
                }
            }



        }

        private void GuardarButton_Click(object sender, RoutedEventArgs e)
        {
            if (MessageBox.Show("Desea guardar los alumnos?",
                    "Guardar",
                    MessageBoxButton.YesNo,
                    MessageBoxImage.Question) == MessageBoxResult.Yes)
            {
                persist.Exec().RemoveCache();
            }
        }
    }
}
