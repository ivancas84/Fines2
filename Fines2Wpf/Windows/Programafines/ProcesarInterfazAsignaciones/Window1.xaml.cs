using Fines2Model3.DAO;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Net.Http;
using System.Net;
using System.Threading.Tasks;
using System.Windows;
using SqlOrganize;
using Utils;
using Fines2Model3.Data;
using HtmlAgilityPack;
using Mysqlx.Crud;
using System.Linq;
using System.Collections;


namespace Fines2Wpf.Windows.Programafines.ProcesarInterfazAsignaciones
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        private ObservableCollection<AsignacionPfItem> asignacionPfOC = new();
        private ObservableCollection<AsignacionDbItem> asignacionDbOC = new();

        public Window1()
        {
            InitializeComponent();

            asignacionPfDataGrid.ItemsSource = asignacionPfOC;
            asignacionDbDataGrid.ItemsSource = asignacionDbOC;

            Loaded += MainWindow_Loaded;
        }

        private async void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            var pfidsComisiones = ContainerApp.db.
                ComisionesAutorizadasDePeriodoSql(DateTime.Now.Year, DateTime.Now.ToSemester()).
                ColOfDictCache().
                DictOfDictByKeysValue("id","pfid");

            IDictionary<string, AsignacionDbItem> asignacionesDb = ContainerApp.db.AsignacionesDeComisionesAutorizadasDelPeriodoSql(DateTime.Now.Year, DateTime.Now.ToSemester()).
                ColOfDictCache().
                ColOfObj<AsignacionDbItem>().
                DictOfObjByPropertyNames("persona__numero_documento");

            using (HttpClient client = new HttpClient(ContainerApp.pfHandler))
            {
                await PF_Login(client);

                foreach (var (pfidComision, idComision) in pfidsComisiones)
                {
                    string[] infoListaAlumnos = await PF_InfoListaAlumnos(client, (string)pfidComision);

                    foreach (string infoAlumno in infoListaAlumnos)
                    {
                        var persist = ContainerApp.db.Persist();
                        AsignacionPfItem? asignacionPf = ObtenerAsignacionPfDeInfoAlumno(infoAlumno);
                        if (asignacionPf == null)
                            continue;

                        asignacionPf.comision = (string)pfidComision;

                        //Obtener datos del alumno del formulario de modificacion pf
                        IDictionary<string, string> dataForm = await PF_InfoAlumnoFormularioModificacion(client, asignacionPf.dni);

                        if (!dataForm.ContainsKey("nombre"))
                            continue;

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



                            if (asignacionesDb.ContainsKey(asignacionPf.dni))
                        {
                            AsignacionDbItem asignacionDb = asignacionesDb[asignacionPf.dni];
                            asignacionDb.existeEnPf = true;

                            #region Comparar datos de persona pf con persona db
                            var personaDbVal = (Values.Persona)ContainerApp.db.Values("persona", "persona").Set(asignacionDb);
                           
                            var comp = personaDbVal.Compare(personaPfVal, ignoreNull: false);
                                
                            if (!comp.IsNullOrEmptyOrDbNull())
                                asignacionPf.Msg += "Comparacion diferente PF = " + personaPfVal.ToStringFields(comp.Keys.ToArray()) + ". DB = " + personaDbVal!.ToStringFields(comp.Keys.ToArray()) + ". ";

                            Dictionary<string, object?> updatePersonaDb = new(); //datos a actualizar de la base local
                            
                            foreach (string key in comp.Keys)
                            {

                                bool check = true;

                                switch (key)
                                {
                                 

                                    case "descripcion_domicilio":
                                        CheckFieldToUpdate(key, personaPfVal, personaDbVal, dataForm, updatePersonaDb, "40");                                        
                                        break;

                                    case "telefono":
                                    case "codigo_area":
                                        CheckFieldToUpdate(key, personaPfVal, personaDbVal, dataForm, updatePersonaDb, "0");
                                        break;


                                    default:
                                        check = CheckFieldToUpdate(key, personaPfVal, personaDbVal, dataForm, updatePersonaDb);
                                        break;

                                }

                                if (!check)
                                {
                                    asignacionPf.Msg += key + " diferente PF = " + personaPfVal.Get(key) + ". DB = " + personaDbVal.Get(key) + ". ";
                                    asignacionPf.revisar = true;
                                }

                            }

                            if ((bool)personaPfVal.Get("update_pf"))
                            {
                                asignacionPf.Msg += "Alumno PF actualizado. ";
                                await PF_ActualizarFormularioAlumno(client, dataForm);
                            }

                            if (!updatePersonaDb.IsNullOrEmpty())
                            {
                                asignacionPf.Msg += "Persona DB actualizada. ";
                                updatePersonaDb["id"] = personaDbVal.Get("id");
                                persist.Update("persona", updatePersonaDb);
                            }
                            #endregion

                            #region comparar datos asignacion pf con asignacion db
                            if (!asignacionDb.comision__pfid.Equals(pfidComision))
                            {
                                asignacionPf.Msg += "Comisiones diferentes PF = " + asignacionPf.comision + ". DB = " + asignacionDb.comision__pfid + ". ";
                                asignacionPf.revisar = true;
                            }
                            else if (asignacionDb.pfid.IsNullOrEmptyOrDbNull() || !asignacionDb.pfid.ToString().Equals(asignacionPf.pfid))
                            {
                                ContainerApp.db.Persist().UpdateValueIds("alumno_comision", "pfid", asignacionPf.pfid, asignacionDb.id!).Exec().RemoveCache();
                                asignacionPf.Msg += "Asignacion.pfid DB actualizada. "; 
                            }
                            #endregion

                        }

                        else
                        {
                            #region Insertar persona / alumno / asignacion en DB
                            personaPfVal.Default().Reset();
                            persist.Insert("persona", personaPfVal);
                            var alumnoVal = ContainerApp.db.Values("alumno").
                                Set("persona", personaPfVal.Get("id")).
                                Default().Reset().Insert(persist);
                            ContainerApp.db.Values("alumno_comision").
                                Set("alumno", alumnoVal.Get("id")).
                                Set("comision", idComision).
                                Default().Reset().Insert(persist);
                            asignacionPf.Msg += "Persona / Alumno / Asignacion DB insertadas. ";
                            #endregion
                        }

                        try
                        {
                            if(!persist.sql.IsNullOrEmptyOrDbNull())
                                persist.Transaction().RemoveCache();
                        } catch(Exception ex)
                        {
                            asignacionPf.Msg += ex.Message;
                            asignacionPf.revisar = true;
                        }

                        asignacionPfOC.Add(asignacionPf);
                    }
                }
            }

            ObservableCollection<AsignacionDbItem> asignacionesDbFiltradas = new ObservableCollection<AsignacionDbItem>(asignacionesDb.Values.Where(p => p.existeEnPf));

            asignacionDbOC.Clear();
            foreach (var asig in asignacionesDbFiltradas)
            {
                asignacionDbOC.Add(asig);
            }
        }

        private bool CheckFieldToUpdate(string fieldName, Values.Persona personaPfVal, Values.Persona personaDbVal, IDictionary<string, string> dataForm, IDictionary<string, object?> updatePersonaDb, string? pfContains = null)
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

        async protected Task<IDictionary<string, string>> PF_InfoAlumnoFormularioModificacion(HttpClient client, string dni)
        {
            var formDataSeleccionarDNI = new Dictionary<string, string>
            {
                { "dni_cargar", dni },
            };
            var encodedFormDataSeleccionarDNI = new FormUrlEncodedContent(formDataSeleccionarDNI);

            // Send a POST request to the login endpoint with the form data
            var response = await client.PostAsync("https://www.programafines.ar/inicial/index4.php?a=8&b=1", encodedFormDataSeleccionarDNI);
            response.EnsureSuccessStatusCode(); // Ensure a successful response

            string responseBody = await response.Content.ReadAsStringAsync();

            var doc = new HtmlDocument();
            doc.LoadHtml(responseBody);

            var inputs = doc.DocumentNode.SelectNodes("//form//input");

            var inputFields = new Dictionary<string, string>();

            if (inputs != null)
            {
                // Dictionary to keep track of radio button groups
                var radioGroups = new Dictionary<string, string>();

                foreach (var input in inputs)
                {
                    var typeAttribute = input.GetAttributeValue("type", string.Empty);
                    var nameAttribute = input.GetAttributeValue("name", null);
                    var valueAttribute = input.GetAttributeValue("value", string.Empty);

                    if (string.IsNullOrEmpty(nameAttribute))
                    {
                        continue;
                    }

                    if (typeAttribute.Equals("radio", StringComparison.OrdinalIgnoreCase))
                    {
                        // Handle radio buttons
                        var checkedAttribute = input.GetAttributeValue("checked", null);
                        if (checkedAttribute != null)
                        {
                            radioGroups[nameAttribute] = valueAttribute;
                        }
                        else if (!radioGroups.ContainsKey(nameAttribute))
                        {
                            // Initialize the radio group if no radio button is selected by default
                            radioGroups[nameAttribute] = string.Empty;
                        }
                    }
                    else
                    {
                        // Handle other input types
                        inputFields[nameAttribute] = valueAttribute;
                    }

                    // Add radio button groups to the final input fields
                    foreach (var radioGroup in radioGroups)
                    {
                        inputFields[radioGroup.Key] = radioGroup.Value;
                    }
                }
            }

            // Select all select elements within the form
            var selects = doc.DocumentNode.SelectNodes("//form//select");
            if (selects != null)
            {
                foreach (var select in selects)
                {
                    var nameAttribute = select.GetAttributeValue("name", null);
                    if (!string.IsNullOrEmpty(nameAttribute))
                    {
                        // Get the selected option
                        var selectedOption = select.SelectSingleNode(".//option[@selected]");
                        var valueAttribute = selectedOption?.GetAttributeValue("value", null)
                                             ?? select.SelectSingleNode(".//option")?.GetAttributeValue("value", null)
                                             ?? string.Empty;

                        inputFields[nameAttribute] = valueAttribute;
                    }
                }
            }

            return inputFields;
        }

        public async Task PF_Login(HttpClient client)
        {
            HttpResponseMessage response = await client.GetAsync(ContainerApp.pfLoginPageUrl);
            response.EnsureSuccessStatusCode(); // Ensure a successful response

            // Prepare the form data to be sent in the POST request
            var formData = new Dictionary<string, string>
                {
                    { "usuario", ContainerApp.config.pfUser },
                    { "password", ContainerApp.config.pfPassword },
                    { "button", "Entrar" } // Additional form fields if needed
                };

            // Encode the form data
            var encodedFormData = new FormUrlEncodedContent(formData);

            // Send a POST request to the login endpoint with the form data
            response = await client.PostAsync(ContainerApp.pfLoginEndpointUrl, encodedFormData);
            response.EnsureSuccessStatusCode(); // Ensure a successful response

            // Check if the login was successful
            if (response.StatusCode != HttpStatusCode.OK)
                throw new Exception("Login error:" + response.StatusCode.ToString());
        }

        public async Task<string[]> PF_InfoListaAlumnos(HttpClient client, string pfid)
        {
            string protectedResourceUrl = $"https://www.programafines.ar/inicial/index4.php?a=12&&nom_comision={pfid}&mi_periodo=2"; //lista de alumnos de la comision
            HttpResponseMessage response = await client.GetAsync(protectedResourceUrl);
            response.EnsureSuccessStatusCode(); // Ensure a successful response

            // Read the content of the response
            string responseData = await response.Content.ReadAsStringAsync();
            return responseData.Split(new string[] { "<h2 align='left'>" }, StringSplitOptions.None);

        }

        public async Task PF_ActualizarFormularioAlumno(HttpClient client, IDictionary<string, string> formData)
        {
            var content = new FormUrlEncodedContent(formData);

            var response = await client.PostAsync("https://www.programafines.ar/inicial/index4.php?a=8&b=2", content);
            response.EnsureSuccessStatusCode(); // Ensure a successful response

            // Check if the login was successful
            if (response.StatusCode != HttpStatusCode.OK)
                throw new Exception("Error:" + response.StatusCode.ToString());

            string res = await response.Content.ReadAsStringAsync();
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
            //data.nombre = h2Item.Substring(pos1 + str1.Length, pos2 - (pos1 + str1.Length));
            //data.telefono = infoAlumno.Substring(pos5 + str5.Length, pos6 - (pos5 + str5.Length)).Trim().Replace(" ", "");
            //bool success = int.TryParse(data.telefono, out int tel);
            //data.telefono = (success && tel > 0) ? tel.ToString() : null;
            item.nacimiento = infoAlumno.Substring(pos3 + str3.Length, pos4 - (pos3 + str3.Length));
            item.pfid = infoAlumno.Substring(pos7 + str7.Length, pos8 - (pos7 + str7.Length));
            item.dni = infoAlumno.Substring(pos2 + str2.Length, pos3 - (pos2 + str2.Length));

            return item;
        }
    }
}