using Fines2Model3.DAO;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Net.Http;
using System.Windows;
using SqlOrganize;
using Utils;
using CommunityToolkit.WinUI.Notifications;
using Fines2Model3.Data;
using System.Windows.Controls;
using WpfUtils;


namespace Fines2Wpf.Windows.Programafines.ProcesarInterfazAsignaciones
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        private ObservableCollection<AsignacionPfItem> asignacionPfOC = new();
        private ObservableCollection<AsignacionDbItem> asignacionDbOC = new();
        HttpClientHandler handler;
        HttpClient client;

        public Window1()
        {
            InitializeComponent();

            formGroupBox.DataContext = new FormItem();
            asignacionPfDataGrid.ItemsSource = asignacionPfOC;
            asignacionDbDataGrid.ItemsSource = asignacionDbOC;
            Closing += Window_Closing;


        }

        private void Window_Closing(object sender, System.ComponentModel.CancelEventArgs e)
        {
            // Dispose HttpClient and HttpClientHandler
            if (!client.IsNullOrEmpty())
                client.Dispose();

            if (!handler.IsNullOrEmpty())
                handler.Dispose();
        }

        private bool CheckToUpdateBoth(string fieldName, Values.Persona personaPfVal, Values.Persona personaDbVal, IDictionary<string, string> dataForm, IDictionary<string, object?> updatePersonaDb, string? pfContains = null)
        {

            if (personaPfVal.GetOrNull(fieldName).IsNullOrEmptyOrDbNull() || (!pfContains.IsNullOrEmptyOrDbNull() && personaPfVal.Get(fieldName).ToString()!.Contains(pfContains!)))
            {
                if (!personaDbVal.GetOrNull(fieldName).IsNullOrEmptyOrDbNull())
                {
                    personaPfVal.Set("update_pf", true);
                    dataForm[fieldName] = personaDbVal.Get(fieldName).ToString()!;
                }
                return true;
            }
            
            if (personaDbVal.GetOrNull(fieldName).IsNullOrEmptyOrDbNull())
            { 
                updatePersonaDb[fieldName] = personaPfVal.GetOrNull(fieldName);
                return true;
            }

            return false; 

        }

        private bool CheckToUpdateDb(string fieldName, Values.Persona personaPfVal, Values.Persona personaDbVal, IDictionary<string, string> dataForm, IDictionary<string, object?> updatePersonaDb, string? pfContains = null)
        {
            if (personaPfVal.GetOrNull(fieldName).IsNullOrEmptyOrDbNull() || (!pfContains.IsNullOrEmptyOrDbNull() && personaPfVal.Get(fieldName).ToString()!.Contains(pfContains!)))
                return true;

            if (personaDbVal.GetOrNull(fieldName).IsNullOrEmptyOrDbNull())
            {
                updatePersonaDb[fieldName] = personaPfVal.GetOrNull(fieldName);
                return true;
            }

            return false;

        }



        
    
        private AsignacionPfItem? ObtenerAsignacionPfDeInfoAlumno(string infoAlumno)
        {
            string str1 = " ";
            string str2 = " DNI ";
            string str3 = "   </h2><h4 align='left'>Fecha Nacimiento: ";
            string str4 = " Email";
            string str5 = "Teléfono:";
            string str6 = " <br>";
            string str7 = "index4.php?a=12&b=1&id_alum_per=";
            string str8 = "&dni_alum_borrar=";
            string str9 = "\">Borrar de esta lista";

            int pos1 = infoAlumno.IndexOf(str1);
            if (pos1 == -1)
                return null;

            int pos2 = infoAlumno.IndexOf(str2, pos1 + str1.Length);
            if (pos2 == -1)
                return null;

            int pos3 = infoAlumno.IndexOf(str3, pos2 + str2.Length);
            if (pos3 == -1)
                return null;

            int pos4 = infoAlumno.IndexOf(str4, pos3 + str3.Length);
            if (pos4 == -1)
                return null;

            int pos5 = infoAlumno.IndexOf(str5, pos4 + str4.Length);
            if (pos5 == -1)
                return null;

            int pos6 = infoAlumno.IndexOf(str6, pos5 + str5.Length);
            if (pos6 == -1)
                return null;

            int pos7 = infoAlumno.IndexOf(str7);
            if (pos7 == -1)
                return null;


            int pos8 = infoAlumno.IndexOf(str8, pos7 + str7.Length);
            if (pos8 == -1)
                return null;


            int pos9 = infoAlumno.IndexOf(str9, pos8 + str8.Length);
            if (pos9 == -1)
                return null;

            var item = new AsignacionPfItem();
            item.nombre = infoAlumno.Substring(pos1 + str1.Length, pos2 - (pos1 + str1.Length));
            //data.telefono = asignacionPfDataGrid.Substring(pos5 + str5.Length, pos6 - (pos5 + str5.Length)).Trim().Replace(" ", "");
            //bool success = int.TryParse(data.telefono, out int tel);
            //data.telefono = (success && tel > 0) ? tel.ToString() : null;
            item.nacimiento = infoAlumno.Substring(pos3 + str3.Length, pos4 - (pos3 + str3.Length));
            item.pfid = infoAlumno.Substring(pos7 + str7.Length, pos8 - (pos7 + str7.Length));
            item.dni = infoAlumno.Substring(pos2 + str2.Length, pos3 - (pos2 + str2.Length));

            return item;
        }

        private (Dictionary<string, object?> pfidsComisiones, Dictionary<string, AsignacionDbItem> asignacionesDb) ConsultarDatosIniciales()
        {
            Dictionary<string, object?> pfidsComisiones = new();
            Dictionary<string, AsignacionDbItem> asignacionesDb = new();

            var formItem = (FormItem)formGroupBox.DataContext;


            if (!formItem.comision.IsNullOrEmptyOrDbNull())
            {
                var com = ContainerApp.db.Sql("comision").GetByFieldValue("pfid", formItem.comision!);

                if (!com.IsNullOrEmptyOrDbNull())
                {
                    pfidsComisiones.Add(formItem.comision!, com!["id"]!);

                    asignacionesDb = (Dictionary<string, AsignacionDbItem>)ContainerApp.db.AsignacionesDeComisionesSql(com["id"]!).
                        ColOfDictCache().
                        ColOfObj<AsignacionDbItem>().
                        DictOfObjByPropertyNames("persona__numero_documento");
                }
                else
                {
                    new ToastContentBuilder()
                        .AddText(Title)
                        .AddText("ERROR: No existen comisiones")
                        .Show();
                }
            }
            else
            {
                pfidsComisiones = (Dictionary<string, object?>)ContainerApp.db.
                    ComisionesAutorizadasDePeriodoSql(DateTime.Now.Year, 1).
                    ColOfDictCache().
                    DictOfDictByKeysValue("id", "pfid");

                asignacionesDb = (Dictionary<string, AsignacionDbItem>)ContainerApp.db.AsignacionesDeComisionesAutorizadasDelPeriodoSql(DateTime.Now.Year, 1).
                    ColOfDictCache().
                    ColOfObj<AsignacionDbItem>().
                    DictOfObjByPropertyNames("persona__numero_documento");
            }

            return (pfidsComisiones, asignacionesDb);

        }

        private async void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            asignacionPfOC.Clear();

            var formItem = (FormItem)formGroupBox.DataContext;
            var datosIniciales = ConsultarDatosIniciales();


            using (handler = ProgramaFines.NewHandler())
            {
                using (client = new HttpClient(handler))
                {
                    await ProgramaFines.PF_Login(client);

                    foreach (var (pfidComision, idComision) in datosIniciales.pfidsComisiones)
                    {
                        string[] infoListaAlumnos = await ProgramaFines.PF_InfoListaAlumnos(client, (string)pfidComision);

                        foreach (string infoAlumno in infoListaAlumnos)
                        {
                            var persist = ContainerApp.db.Persist();
                            AsignacionPfItem? asignacionPf = ObtenerAsignacionPfDeInfoAlumno(infoAlumno);
                            if (asignacionPf == null)
                                continue;

                            formItem.alumnosProcesados += 1;

                            asignacionPf.comision = (string)pfidComision;

                            //Obtener datos del alumno del formulario de modificacion pf
                            IDictionary<string, string> dataForm = await ProgramaFines.PF_InfoAlumnoFormularioModificacion(client, asignacionPf.dni);

                            if (!dataForm.ContainsKey("nombre"))
                            {
                                asignacionPf.revisar = true;
                                asignacionPf.Msg += "Nombre del formulario vacio";
                                asignacionPfOC.Add(asignacionPf);
                                continue;
                            }

                            Values.Persona personaPfVal = (Values.Persona)ContainerApp.db.Values("persona").
                                    Sset("nombres", dataForm["nombre"]).
                                    Sset("apellidos", dataForm["apellido"]).
                                    Sset("cuil1", dataForm["cuil1"]).
                                    Sset("numero_documento", asignacionPf.dni).
                                    Sset("cuil2", dataForm["cuil2"]).
                                    Sset("descripcion_domicilio", dataForm["direccion"]).
                                    Sset("departamento", dataForm["departamento"]).
                                    Sset("localidad", dataForm["localidad"]).
                                    Sset("partido", dataForm["partido"]).
                                    Sset("nacionalidad", dataForm["nacionalidad"]).
                                    Sset("email", dataForm["email"]).
                                    Sset("codigo_area", dataForm["cod_area"]).
                                    Sset("telefono", dataForm["nro_telefono"]).
                                    Sset("sexo", dataForm["sexo"]).
                                    Sset("fecha_nacimiento", asignacionPf.nacimiento).
                                    Set("update_pf", false);

                            //si esta correctamente seteada la fecha nacimiento, significa que la fecha es correcta
                            if (personaPfVal.GetOrNull("fecha_nacimiento").IsNullOrEmptyOrDbNull())
                            {
                                personaPfVal.Sset("dia_nacimiento", dataForm["dia_nac"]).
                                    Sset("mes_nacimiento", dataForm["mes_nac"]).
                                    Sset("anio_nacimiento", dataForm["ano_nac"]);
                            }

                            if (datosIniciales.asignacionesDb.ContainsKey(asignacionPf.dni))
                            {
                                AsignacionDbItem asignacionDb = datosIniciales.asignacionesDb[asignacionPf.dni];
                                asignacionDb.existeEnPf = true;

                                #region Comparar datos de persona pf con persona db
                                var personaDbVal = (Values.Persona)ContainerApp.db.Values("persona", "persona").Set(asignacionDb);

                                CompareParams cp = new() { val = personaPfVal, ignoreNull = false };
                                var comp = personaDbVal.Compare(cp);

                                Dictionary<string, object?> updatePersonaDb = new(); //datos a actualizar de la base local

                                foreach (string key in comp.Keys)
                                {
                                    bool check = true;
                                    switch (key)
                                    {
                                        //por el momento no imprimimos el telefono y el codigo de area si es diferente, porque lo quise actualizar del pf y me tiraba error
                                        case "descripcion_domicilio":
                                            CheckToUpdateDb(key, personaPfVal, personaDbVal, dataForm, updatePersonaDb, "40");
                                            break;

                                        //por el momento no imprimimos el telefono y el codigo de area si es diferente, porque lo quise actualizar del pf y me tiraba error
                                        case "telefono":
                                        case "codigo_area":
                                            CheckToUpdateDb(key, personaPfVal, personaDbVal, dataForm, updatePersonaDb, "0");
                                            break;

                                        default:
                                            check = CheckToUpdateBoth(key, personaPfVal, personaDbVal, dataForm, updatePersonaDb);
                                            break;

                                    }

                                    if (!check)
                                    {
                                        asignacionPf.Msg += key + " diferente PF = " + personaPfVal.Get(key) + ". DB = " + personaDbVal.Get(key) + ". ";
                                    }

                                }

                                if ((bool)personaPfVal.Get("update_pf"))
                                {
                                    await ProgramaFines.PF_ActualizarFormularioAlumno(client, dataForm);
                                }

                                if (!updatePersonaDb.IsNullOrEmpty())
                                {
                                    updatePersonaDb["id"] = personaDbVal.Get("id");
                                    persist.Update("persona", updatePersonaDb);
                                }
                                #endregion

                                #region comparar datos asignacion pf con asignacion db
                                if (!asignacionDb.comision__pfid.Equals(pfidComision))
                                {
                                    asignacionPf.revisar = true;
                                }
                                else if (asignacionDb.pfid.IsNullOrEmptyOrDbNull() || !asignacionDb.pfid.ToString().Equals(asignacionPf.pfid))
                                {
                                    ContainerApp.db.Persist().UpdateValueIds("alumno_comision", "pfid", asignacionPf.pfid, asignacionDb.id!).Exec().RemoveCache();
                                }
                                #endregion

                            }

                            else
                            {
                                #region Db Persist persona / alumno / Insert asignacion
                                personaPfVal.Persist(persist);
                                var alumnoVal = ContainerApp.db.Values("alumno").
                                    Set("persona", personaPfVal.Get("id")).
                                    Persist(persist);
                                ContainerApp.db.Values("alumno_comision").
                                    Set("alumno", alumnoVal.Get("id")).
                                    Set("comision", idComision).
                                    Default().Reset().Insert(persist);
                                #endregion
                            }

                            try
                            {
                                if (!persist.sql.IsNullOrEmptyOrDbNull())
                                    persist.Transaction().RemoveCache();
                            }
                            catch (Exception ex)
                            {
                                asignacionPf.Msg += ex.Message;
                                asignacionPf.revisar = true;
                            }

                            if (asignacionPf.revisar || !asignacionPf.Msg.IsNullOrEmptyOrDbNull())
                                asignacionPfOC.Add(asignacionPf);

                        }
                    }


                }
            }

            asignacionDbOC.Clear();
            foreach(var (key, asignacion ) in datosIniciales.asignacionesDb)
            {
                if (!asignacion.existeEnPf)
                    asignacionDbOC.Add(asignacion);
            }      
        }

        private async void AgregarAsignacionPF_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var asignacion = (AsignacionDbItem)button!.DataContext;
            try
            {
                var values = ContainerApp.db.Values("persona", "persona").Set(asignacion);

                using (handler = ProgramaFines.NewHandler())
                {
                    using (client = new HttpClient(handler))
                    {
                        await ProgramaFines.PF_Login(client);

                        await ProgramaFines.PF_InscribirEstudianteValues(client, asignacion.comision__pfid!, values);

                        new ToastContentBuilder()
                                        .AddText("Inscripción PF")
                                        .AddText("Inscripción PF realizado correctamente")
                                    .Show();
                    }
                }
            }
            catch (Exception ex)
            {
                ToastUtils.ShowError(ex.Message, "Error al inscribir estudiante");
            }
        }

        private async void CambiarComisionPF_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var asignacion = (AsignacionDbItem)button!.DataContext;
            try
            {
                var values = ContainerApp.db.Values("persona", "persona").Set(asignacion);

                using (handler = ProgramaFines.NewHandler())
                {
                    using (client = new HttpClient(handler))
                    {
                        await ProgramaFines.PF_Login(client);

                        await ProgramaFines.PF_CambiarComision(client, asignacion.persona__numero_documento!, asignacion.comision__pfid);

                        new ToastContentBuilder()
                                        .AddText("Cambio Comisión")
                                        .AddText("Cambio Comisión realizado correctamente")
                                    .Show();
                    }
                }
            }
            catch (Exception ex)
            {
                ToastUtils.ShowError(ex.Message, "Error al inscribir estudiante");
            }
        }
    }


}