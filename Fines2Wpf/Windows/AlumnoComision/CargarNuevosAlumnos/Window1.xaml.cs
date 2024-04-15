using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Reflection.PortableExecutable;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;
using Utils;
using Fines2Wpf.Data;

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
        private SqlOrganize.DAO dao = new(ContainerApp.db);


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
            var data = dao.Get("comision", IdComision!);
            labelTextBox.Text = ((Values.Comision)ContainerApp.db.Values("comision").Values(data)).ToString();
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
                    if (_data[j].IsNullOrEmpty())
                    continue;
                var values = _data[j].Split("\t");

                var personaData = new Dictionary<string, object?>();
                for (var i = 0; i < _headers.Count(); i++)
                {
                    if (values.ElementAt(i).IsNullOrEmpty()) continue;
                    personaData.Add(_headers.ElementAt(i), values.ElementAt(i));
                }
                    #endregion

                    #region Procesar persona

                    Values.Persona personaVal = (Values.Persona)ContainerApp.db.Values("persona", "persona");
                    personaVal.Sset(personaData).Reset();


                    IDictionary<string, object?>? personaExistenteData = ContainerApp.db.Query("persona").
                        Unique(personaVal).
                        DictCache();

                    if (!personaExistenteData.IsNullOrEmpty()) //existen datos de persona en la base
                    {
                        Values.Persona personaExistenteVal = (Values.Persona)ContainerApp.db.Values("persona").Values(personaExistenteData);

                        var dataDifferent = personaVal.CompareFields(personaExistenteVal!, ignoreNull: true, fieldsToCompare:new List<string> { "apellidos", "nombres", "numero_documento" });
                        if (!dataDifferent.IsNullOrEmpty())
                        {
                            statusData.Add(new StatusData()
                            {
                                row = j,
                                status = "error",
                                detail = "Los valores de persona existente son diferentes no se realizara ningún registro",
                                data = "Nuevo: " + personaVal.ToStringFields("nombres","apellidos","numero_documento") + ". Existente: " + personaExistenteVal!.ToStringFields("nombres", "apellidos", "numero_documento")
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

                        dataDifferent = personaVal.Compare(personaExistenteVal!, ignoreNull: true, ignoreFields: new List<string> { "apellidos", "nombres", "numero_documento" });
                        if (!dataDifferent.IsNullOrEmpty())
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
                        persist.Insert(personaVal.Default().Reset());
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
                    var alumnoExistenteData = ContainerApp.db.Query("alumno").Unique(alumnoVal).DictCache();

                    if (!alumnoExistenteData.IsNullOrEmpty()) //existen datos de alumno en la base
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
                        if (alumnoExistente!.Get("plan").IsNullOrEmptyOrDbNull())
                        {
                            statusData.Add(new()
                            {
                                row = j,
                                status = "update",
                                detail = "Se actualizo el plan del alumno que estaba vacío.",
                                data = personaVal.ToString()
                            });
                        }
                        else if (alumnoExistente!.Get("plan")!.ToString()!.Equals(comision.planificacion__plan))
                        { 
                            statusData.Add(new()
                            {
                                row = j,
                                status = "warning",
                                detail = "El plan del alumno es diferente del plan de la comision.",
                                data = "Nuevo: " + comision.plan__orientacion + " " + comision.plan__resolucion + ". Existente: " + alumnoExistente.ValuesTree("plan")?.ToString()
                            });
                        }
                    }
                    else //no existen datos del alumno en la base
                    {
                        persist.Insert(alumnoVal.Default().Set("plan", comision.planificacion__plan!).Reset());
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

                    var asignacionExistenteData = ContainerApp.db.Query("alumno_comision").Unique(asignacion).DictCache();
                    if (!asignacionExistenteData.IsNullOrEmpty()) //existen datos de alumno en la base
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
                        persist.Insert(asignacion.Default().Reset());
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
                        var comD = ContainerApp.db.Query("comision").CacheById(a["comision"]);
                        var comV = (Values.Comision)ContainerApp.db.Values("comision").Values(comD!);

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
                        IDictionary<string, object?> comD = ContainerApp.db.Query("comision").CacheById(a["comision-id"]);
                        Values.Comision comV = (Values.Comision)ContainerApp.db.Values("comision").Values(comD!);

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
                persist.Transaction().RemoveCache();
            }
        }
    }
}
