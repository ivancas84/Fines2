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
using MySqlX.XDevAPI;
using static System.Runtime.InteropServices.JavaScript.JSType;
using Windows.Media.Protection.PlayReady;
using static MaterialDesignThemes.Wpf.Theme.ToolBar;

namespace Fines2Wpf.Windows.Programafines.ProcesarInterfazAsignaciones
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        private ObservableCollection<Data> infoOC = new();

        public Window1()
        {
            InitializeComponent();

            infoDataGrid.ItemsSource = infoOC;

            Loaded += MainWindow_Loaded;
        }

        private async void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            var pfidsComisiones = ContainerApp.db.
                ComisionesAutorizadasDePeriodoSql(DateTime.Now.Year, DateTime.Now.ToSemester()).
                ColOfDictCache().
                ColOfVal<string>("pfid");

            IDictionary<string, Data_alumno_comision_r> alumnosObj = ContainerApp.db.AsignacionesDeComisionesAutorizadasDelPeriodoSql(DateTime.Now.Year, DateTime.Now.ToSemester()).
                ColOfDictCache().
                ColOfObj<Data_alumno_comision_r>().
                DictOfObjByPropertyNames("persona__numero_documento");

            using (HttpClient client = new HttpClient(ContainerApp.pfHandler))
            {
                await PF_Login(client);

                foreach (string pfid in pfidsComisiones)
                {
                    string[] infoListaAlumnos = await PF_InfoListaAlumnos(client, pfid);

                    foreach (string infoAlumno in infoListaAlumnos)
                    {
                        Data? data = ProcesarAlumnoLista(infoAlumno);
                        if (data == null)
                            continue;

                        data.comision = pfid;

                        //Obtener datos del alumno del formulario de modificacion pf
                        IDictionary<string, string> dataForm = await PF_InfoAlumnoFormularioModificacion(client, data.dni);

                        var personaPfVal = (Values.Persona)ContainerApp.db.Values("persona").
                                Sset("nombres", dataForm["nombre"]).
                                Sset("apellidos", dataForm["apellido"]).
                                Sset("cuil1", dataForm["cuil1"]).
                                Sset("numero_documento", data.dni).
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
                                Sset("fecha_nacimiento", data.nacimiento).
                                Sset("dia_nacimiento", dataForm["dia_nac"]).
                                Sset("mes_nacimiento", dataForm["mes_nac"]).
                                Sset("anio_nacimiento", dataForm["ano_nac"]);


                        #region Comparar datos del alumno en la base de datos local
                        if (alumnosObj.ContainsKey(data.dni))
                        {
                            data.existe = true;

                            var personaDbVal = (Values.Persona)ContainerApp.db.Values("persona", "persona").Set(alumnosObj[data.dni]);
                           
                            
                            var comp = personaDbVal.Compare(personaPfVal, ignoreNull: false);
                                
                            if (!comp.IsNullOrEmptyOrDbNull())
                                data.comparacion = "db: " + personaDbVal.ToStringFields(comp.Keys.ToArray()) + ". pf: " + personaPfVal!.ToStringFields(comp.Keys.ToArray());

                            Dictionary<string, object?> updatePersonaDb = new(); //datos a actualizar de la base local
                            bool updatePf = false; //flag para indicar que se debe actualizar programafines
                            foreach (string key in comp.Keys)
                            {



                                switch (key)
                                {
                                    case "cuil1":
                                        if (personaPfVal.GetOrNull("cuil1").IsNullOrEmptyOrDbNull()){
                                            if (!personaDbVal.GetOrNull("cuil1").IsNullOrEmptyOrDbNull())
                                            {
                                                updatePf = true;
                                                dataForm["cuil1"] = personaDbVal.Get("cuil1").ToString()!;
                                            }
                                        } else
                                        {
                                            if (personaDbVal.GetOrNull("cuil1").IsNullOrEmptyOrDbNull())
                                                updatePersonaDb["cuil1"] = personaPfVal.GetOrNull("cuil1");
                                        }

                                        break;

                                    case "cuil2":
                                        if (personaPfVal.GetOrNull("cuil2").IsNullOrEmptyOrDbNull())
                                        {
                                            if (!personaDbVal.GetOrNull("cuil2").IsNullOrEmptyOrDbNull())
                                            {
                                                updatePf = true;
                                                dataForm["cuil2"] = personaDbVal.Get("cuil2").ToString()!;
                                            }
                                        }
                                        else
                                        {
                                            if (personaDbVal.GetOrNull("cuil2").IsNullOrEmptyOrDbNull())
                                                updatePersonaDb["cuil2"] = personaPfVal.GetOrNull("cuil2");
                                        }

                                        break;

                                    case "descripcion_domicilio":
                                        if (personaPfVal.GetOrNull("descripcion_domicilio").IsNullOrEmptyOrDbNull() || personaPfVal.GetOrNull("descripcion_domicilio").ToString().Contains("40"))
                                        {
                                            if (!personaDbVal.GetOrNull("descripcion_domicilio").IsNullOrEmptyOrDbNull())
                                            {
                                                updatePf = true;
                                                dataForm["descripcion_domicilio"] = personaDbVal.Get("descripcion_domicilio").ToString()!;
                                            }
                                        }
                                        else
                                        {
                                            if (personaDbVal.GetOrNull("descripcion_domicilio").IsNullOrEmptyOrDbNull())
                                                updatePersonaDb["descripcion_domicilio"] = personaPfVal.GetOrNull("descripcion_domicilio");
                                        }
                                        break;

                                    case "telefono":
                                        if (personaPfVal.GetOrNull("telefono").IsNullOrEmptyOrDbNull() || personaPfVal.GetOrNull("telefono").ToString().Equals("0"))
                                        {
                                            if (!personaDbVal.GetOrNull("telefono").IsNullOrEmptyOrDbNull())
                                            {
                                                updatePf = true;
                                                dataForm["nro_telefono"] = personaDbVal.Get("telefono").ToString()!;
                                            }
                                        }
                                        else
                                        {
                                            if (personaDbVal.GetOrNull("telefono").IsNullOrEmptyOrDbNull())
                                                updatePersonaDb["telefono"] = personaPfVal.GetOrNull("telefono");
                                        }
                                        break;
                                }
                                if (personaPfVal.IsNullOrEmpty(key) && !personaDbVal.IsNullOrEmpty(key))
                                {
                                    switch (key)
                                    {
                                        case "fecha_nacimiento":
                                            break;

                                    }
                                }
                            }

                            if (updatePf)
                            {
                                await PF_ActualizarFormularioAlumno(client, dataForm);
                            }

                            if (!updatePersonaDb.IsNullOrEmpty())
                            {
                                updatePersonaDb["id"] = personaDbVal.Get("id");
                                ContainerApp.db.Persist().Update("persona", updatePersonaDb);
                            }
                        }
                       else
                       {
                            data.comparacion = "Se agregó al alumno en la base de datos";
                            personaPfVal.Default().Reset();
                            ContainerApp.db.Persist().Insert("persona", personaPfVal);

                        }
                        #endregion

                        //data.dni = h2Item.Substring(pos8 + str8.Length, pos9 - (pos8 + str8.Length));
                        infoOC.Add(data);

                    }
                }
               
            }
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

        private Data? ProcesarAlumnoLista(string infoAlumno)
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

            var data = new Data();
            //data.nombre = h2Item.Substring(pos1 + str1.Length, pos2 - (pos1 + str1.Length));
            data.telefono = infoAlumno.Substring(pos5 + str5.Length, pos6 - (pos5 + str5.Length)).Trim().Replace(" ", "");
            bool success = int.TryParse(data.telefono, out int tel);
            data.telefono = (success && tel > 0) ? tel.ToString() : null;
            //data.nacimiento = h2Item.Substring(pos3 + str3.Length, pos4 - (pos3 + str3.Length));
            data.pfid = infoAlumno.Substring(pos7 + str7.Length, pos8 - (pos7 + str7.Length)); 
            data.dni = infoAlumno.Substring(pos2 + str2.Length, pos3 - (pos2 + str2.Length));

            return data;
        }
    }
}