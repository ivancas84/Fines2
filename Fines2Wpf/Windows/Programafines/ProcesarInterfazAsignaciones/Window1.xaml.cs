using Fines2Wpf.DAO;
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
using Fines2Wpf.Data;
using HtmlAgilityPack;

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

            var comisionesData = ContainerApp.db.ComisionesAutorizadasDePeriodoSql(DateTime.Now.Year, DateTime.Now.ToSemester()).ColOfDictCache();
            var numbers = comisionesData.ColOfVal<string>("pfid");

            IDictionary<string, Data_alumno_comision_r> alumnosObj = ContainerApp.db.AsignacionesDeComisionesAutorizadasDelPeriodoSql(DateTime.Now.Year, DateTime.Now.ToSemester()).
                ColOfDictCache().
                ColOfObj<Data_alumno_comision_r>().
                DictOfObjByPropertyNames("persona__numero_documento");

            using (HttpClient client = new HttpClient(ContainerApp.pfHandler))
            {
                // Get the login page to retrieve any necessary cookies
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
                if (response.StatusCode == HttpStatusCode.OK)
                {
                    foreach (string number in numbers)
                    {
                        string protectedResourceUrl = $"https://www.programafines.ar/inicial/index4.php?a=12&&nom_comision={number}&mi_periodo=2"; //lista de alumnos de la comision
                        response = await client.GetAsync(protectedResourceUrl);
                        response.EnsureSuccessStatusCode(); // Ensure a successful response

                        // Read the content of the response
                        string responseData = await response.Content.ReadAsStringAsync();
                        string[] h2 = responseData.Split(new string[] { "<h2 align='left'>" }, StringSplitOptions.None);

                        foreach (string h2Item in h2)
                        {

                            #region Obtener y formatear alumno de la lista de programafines
                            string str1 = " ";
                            string str2 = " DNI ";
                            string str3 = "   </h2><h4 align='left'>Fecha Nacimiento: ";
                            string str4 = " Email";
                            string str5 = "Teléfono:";
                            string str6 = " <br>";
                            string str7 = "index4.php?a=12&b=1&id_alum_per=";
                            string str8 = "&dni_alum_borrar=";
                            string str9 = "\">Borrar de esta lista";

                            int pos1 = h2Item.IndexOf(str1);
                            if (pos1 == -1)
                            {
                                continue; // Start string not found
                            }

                            int pos2 = h2Item.IndexOf(str2, pos1 + str1.Length);
                            if (pos2 == -1)
                            {
                                continue; // End string not found
                            }

                            int pos3 = h2Item.IndexOf(str3, pos2 + str2.Length);
                            if (pos3 == -1)
                            {
                                continue; // End string not found
                            }

                            int pos4 = h2Item.IndexOf(str4, pos3 + str3.Length);
                            if (pos4 == -1)
                            {
                                continue; // End string not found
                            }


                            int pos5 = h2Item.IndexOf(str5, pos4 + str4.Length);
                            if (pos5 == -1)
                            {
                                continue; // End string not found
                            }

                            int pos6 = h2Item.IndexOf(str6, pos5 + str5.Length);
                            if (pos6 == -1)
                            {
                                continue; // End string not found
                            }


                            int pos7 = h2Item.IndexOf(str7);
                            if (pos7 == -1)
                            {
                                continue; // Start string not found
                            }

                            int pos8 = h2Item.IndexOf(str8, pos7 + str7.Length);
                            if (pos8 == -1)
                            {
                                continue; // End string not found
                            }

                            int pos9 = h2Item.IndexOf(str9, pos8 + str8.Length);
                            if (pos9 == -1)
                            {
                                continue; // End string not found
                            }



                            var data = new Data();
                            //data.nombre = h2Item.Substring(pos1 + str1.Length, pos2 - (pos1 + str1.Length));
                            data.telefono = h2Item.Substring(pos5 + str5.Length, pos6 - (pos5 + str5.Length)).Trim().Replace(" ", "");
                            bool success = int.TryParse(data.telefono, out int tel);
                            data.telefono = (success && tel > 0) ? tel.ToString() : null;
                            //data.nacimiento = h2Item.Substring(pos3 + str3.Length, pos4 - (pos3 + str3.Length));
                            data.pfid = h2Item.Substring(pos7 + str7.Length, pos8 - (pos7 + str7.Length)); data.comision = number;
                            data.dni = h2Item.Substring(pos2 + str2.Length, pos3 - (pos2 + str2.Length)); ;
                            #endregion


                            //Obtener datos del alumno del formulario de modificacion pf
                            IDictionary<string, string> dataForm = await ParseHtmlForm(client, data.dni);

                            #region Comparar datos del alumno en la base de datos local
                            if (alumnosObj.ContainsKey(data.dni))
                            {
                                data.existe = true;

                                var personaPfVal = (Values.Persona)ContainerApp.db.Values("persona").
                                    Sset("nombres", data.nombre).
                                    Sset("apellidos", data.nombre).
                                    Sset("telefono", data.telefono).
                                    Sset("numero_documento", data.dni).
                                    Sset("fecha_nacimiento", data.nacimiento);

                                var personaDbVal = (Values.Persona)ContainerApp.db.Values("persona", "persona").SetObj(alumnosObj[data.dni]);

                                var comp = personaDbVal.Compare(personaPfVal, ignoreNull: false);
                                
                                if (!comp.IsNullOrEmptyOrDbNull())
                                    data.comparacion = "db: " + personaDbVal.ToStringFields(comp.Keys.ToArray()) + ". pf: " + personaPfVal!.ToStringFields(comp.Keys.ToArray());

                                foreach (string key in comp.Keys)
                                {
                                    break;
                                    Dictionary<string, object?> valuesToUpdatePf = new();
                                    if (personaPfVal.IsNullOrEmpty(key) && !personaDbVal.IsNullOrEmpty(key))
                                    {
                                        switch (key)
                                        {
                                            case "fecha_nacimiento":
                                                break;

                                        }
                                    }
                                }
                                
                                
                              
    

                            }
                            else
                            {
                                data.comparacion = "El alumno no existe en la base de datos";
                            }
                            #endregion

                            //data.dni = h2Item.Substring(pos8 + str8.Length, pos9 - (pos8 + str8.Length));
                            infoOC.Add(data);

                        }
                    }
                }
                else
                {
                    Console.WriteLine("Login failed.");
                }
            }
        }

        async protected Task<IDictionary<string, string>> ParseHtmlForm(HttpClient client, string dni)
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


    }
}