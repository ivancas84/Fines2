using Fines2Wpf.DAO;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.DateTimeUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Windows;

namespace Fines2Wpf.Windows.AlumnoComision.ProcesarAlumnosListadoGeneral
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        DAO.Comision comisionDAO = new();
        DAO.AlumnoComision alumnoComisionDAO = new();
        private ObservableCollection<StatusData> statusData = new();
        private List<EntityPersist> persists;

        public Window1()
        {
            InitializeComponent();
            statusGrid.ItemsSource = statusData;
            headerTextBox.Text = "comision-pfid, persona-cuil1, persona-numero_documento, persona-cuil2, persona-apellidos, persona-nombres, persona-genero, persona-fecha_nacimiento";
        }

        private void ProcesarAlumnosButton_Click(object sender, RoutedEventArgs e)
        {
            persists = new();

            #region Consultar todas las asignaciones activas o no de comisiones autorizadas del semestre
            object[] asignacionesIds = ContainerApp.db.AsignacionesDeComisionesAutorizadasDelPeriodoSql(DateTime.Now.Year, DateTime.Now.ToSemester()).
                Column<object>("id").ToArray();
            #endregion

            #region Poner false el campo programafines
            //El campo programafines esta obsoleto, utilizar pfid con el id de la asignacion en el programafines
            if (!asignacionesIds.IsNoE())
            ContainerApp.db.Persist().UpdateValueIds("alumno_comision", "programafines", false, asignacionesIds).AddTo(persists);
            #endregion
            IDictionary<string, Data_comision_r> comisiones = ContainerApp.db.ComisionesAutorizadasDePeriodoSql(DateTime.Now.Year, DateTime.Now.ToSemester()).
                Cache().ColOfDict().
                ColOfObj<Data_comision_r>().
                DictOfObjByPropertyNames("pfid");

            Dictionary<string, object> dict = new Dictionary<string, object>();

            IEnumerable<string> _headers = headerTextBox.Text.Split(", ").Select(s => s.Trim());

            string[] _data = alumnoTextBox.Text.Split("\r\n");

            statusData.Clear();

            for (var j = 0; j < _data.Length; j++)
            {
                try
                {
                    #region Inicializar datos de persona
                    if (_data[j].IsNoE())
                        continue;

                    var values = _data[j].Split("\t");

                    var asignacionData = new Dictionary<string, object?>();
                    for (var i = 0; i < _headers.Count(); i++)
                        asignacionData.Add(_headers.ElementAt(i), values.ElementAt(i));
                    #endregion

                    string pfid = (string)asignacionData["comision-pfid"]!;

                    if (!comisiones.ContainsKey(pfid))
                        continue;

                    #region Procesar persona
                    PersonaValues personaVal = (PersonaValues)ContainerApp.db.Values("persona", "persona");
                    personaVal.Sset(asignacionData).Reset();

                    IDictionary<string, object?>? personaExistenteData = ContainerApp.db.Sql("persona").
                        Unique(personaVal).
                        Cache().Dict();

                    if (!personaExistenteData.IsNoE()) //existen datos de persona en la base
                    {
                        PersonaValues personaExistenteVal = (PersonaValues)ContainerApp.db.Values("persona").Values(personaExistenteData);

                        CompareParams cp = new()
                        {
                            val = personaExistenteVal!,
                            ignoreNull = true,
                            fieldsToCompare = new List<string> { "apellidos", "nombres", "numero_documento" },
                        };
                        var dataDifferent = personaVal.Compare(cp);
                        if (!dataDifferent.IsNoE())
                        {
                            statusData.Add(new StatusData()
                            {
                                row = j,
                                status = "error",
                                detail = "Los valores de persona existente son diferentes no se realizara ningún registro (procesando comision " + pfid + ")",
                                data = "Nuevo: " + personaVal.ToStringFields("nombres", "apellidos", "numero_documento") + ". Existente: " + personaExistenteVal!.ToStringFields("nombres", "apellidos", "numero_documento")
                            });
                            continue;
                        }
                        personaVal.Set("id", personaExistenteVal!.Get("id"));
                        statusData.Add(new StatusData()
                        {
                            row = j,
                            status = "info",
                            detail = "Persona existente (procesando comision " + pfid + ")",
                            data = personaVal.ToString()
                        });

                        cp = new()
                        {
                            val = personaExistenteVal,
                            ignoreNull = true,
                            ignoreFields = new List<string> { "apellidos", "nombres", "numero_documento" },
                        };
                        dataDifferent = personaVal.Compare(cp);
                        if (!dataDifferent.IsNoE())
                        {
                            statusData.Add(new StatusData()
                            {
                                row = j,
                                status = "warning",
                                detail = "Los valores de persona existente son diferentes, verificar y si es necesario cambiar en comision " + pfid,
                                data = "Nuevo: " + personaVal.ToStringFields(dataDifferent.Keys.ToArray()) + ". Existente: " + personaExistenteVal!.ToStringFields(dataDifferent.Keys.ToArray())
                            });
                        }

                    }
                    else //no existen datos de persona en la base
                    {
                        ContainerApp.db.Persist().
                            Insert(personaVal.Default().Reset()).
                            AddTo(persists);

                        statusData.Add(new StatusData()
                        {
                            row = j,
                            id = (string?)personaVal.GetOrNull("id"),
                            status = "insert persona",
                            detail = "Persona agregada  (procesando comision " + pfid + ")",
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
                            id = (string?)alumnoExistente.GetOrNull("id"),
                            row = j,
                            status = "info alumno",
                            detail = "Alumno existente.",
                            data = personaVal.ToString()
                        });
                        alumnoVal.Set("id", alumnoExistente!.Get("id"));
                        if (alumnoExistente!.Get("plan").IsNoE())
                        {
                            statusData.Add(new()
                            {
                                id = (string?)alumnoExistente.GetOrNull("id"),
                                row = j,
                                status = "update alumno",
                                detail = "Se actualizo el plan del alumno que estaba vacío.",
                                data = personaVal.ToString()
                            });
                        }
                        else if (!alumnoExistente!.Get("plan")!.ToString()!.Equals(comisiones[pfid].planificacion__plan))
                        {
                            statusData.Add(new()
                            {

                                id = (string?)alumnoExistente.GetOrNull("id"),
                                row = j,
                                status = "warning alumno",
                                detail = "El plan del alumno es diferente del plan de la comision.",
                                data = "Nuevo: " + comisiones[pfid].plan__orientacion + " " + comisiones[pfid].plan__resolucion + ". Existente: " + alumnoExistente.ValuesRel("plan")?.ToString()
                            });
                        }
                    }
                    else //no existen datos del alumno en la base
                    {
                        ContainerApp.db.Persist().
                            Insert(alumnoVal.Default().Set("plan", comisiones[pfid].planificacion__plan!).Reset()).
                            AddTo(persists);

                        statusData.Add(new StatusData()
                        {
                            row = j,
                            id = (string?)alumnoVal.GetOrNull("id"),
                            status = "insert alumno",
                            detail = "Alumno agregado correspondiente a comision " + pfid,
                            data = personaVal.ToString()
                        });
                    }
                    #endregion

                    #region procesar asignacion
                    var asignacion = ContainerApp.db.Values("alumno_comision").
                        Set("comision", comisiones[pfid].id!).
                        Set("alumno", alumnoVal.Get("id"));

                    var asignacionExistenteData = ContainerApp.db.Sql("alumno_comision").Unique(asignacion).Cache().Dict();
                    if (!asignacionExistenteData.IsNoE()) //existen datos de alumno en la base
                    {
                        ContainerApp.db.Persist().
                            UpdateValueIds("alumno_comision", "programafines", true, asignacionExistenteData["id"]).
                            AddTo(persists);


                        statusData.Add(new()
                        {
                            row = j,
                            id = (string?)asignacionExistenteData["id"],
                            status = "warning asignacion",
                            detail = "Ya existe asignacion en la comisión " + pfid + " actual con estado " + asignacionExistenteData["estado"].ToString() + " se actualizo programafines",
                            data = personaVal.ToString()
                        });
                    }
                    else //no existen datos de asignacion
                    {
                        asignacion.Set("programafines", true);
                        ContainerApp.db.Persist().Insert(asignacion.Default().Reset()).
                            AddTo(persists);
                        statusData.Add(
                        new StatusData()
                        {
                            row = j,
                            id = (string?)asignacion.GetOrNull("id"),
                            status = "insert asignacion",
                            detail = "Asignacion agregada en comision " + pfid + ". ",
                            data = personaVal.ToString()!
                        });
                    }
                    #endregion

                    #region controlar asignaciones activas del semestre
                    var otrasAsignacionesDelSemestre = alumnoComisionDAO.AsignacionesDelAlumnoEnOtrasComisionesAutorizadasDelSemestre(comisiones[pfid].calendario__anio!, comisiones[pfid].calendario__semestre!, comisiones[pfid].id!, alumnoVal.Get("id"));
                    foreach (var a in otrasAsignacionesDelSemestre)
                    {
                        var comD = ContainerApp.db.Sql("comision").Cache().Id(a["comision"]);
                        var comV = (ComisionValues)ContainerApp.db.Values("comision").Values(comD!);

                        statusData.Add(new StatusData()
                        {
                            row = j,
                            id = (string?)alumnoVal.Get("id"),
                            status = "warning alumno",
                            detail = "Se encuentra en otra comision del mismo semestre ademas de " + pfid,
                            data = personaVal.ToString() + " en " + comV.ToString() + " con estado " + a["estado"].ToString()
                        });
                    }
                    #endregion

                    #region informar otras asignaciones activas del alumno
                    var otrasAsignaciones = alumnoComisionDAO.AsignacionesDelAlumnoEnOtrasComisionesAutorizadas(comisiones[pfid].id!, alumnoVal.Get("id"));
                    foreach (var a in otrasAsignaciones)
                    {
                        IDictionary<string, object?> comD = ContainerApp.db.Sql("comision").Cache().Id(a["comision-id"]);
                        ComisionValues comV = (ComisionValues)ContainerApp.db.Values("comision").Values(comD!);

                        statusData.Add(new StatusData()
                        {
                            row = j,
                            id = (string?)alumnoVal.Get("id"),
                            status = "info alumno",
                            detail = "Asignación en otras comisiones ademas de " + pfid,
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
                persists.Transaction().RemoveCache();
            }
        }
    }
}
