using Fines2Wpf;
using HtmlAgilityPack;
using System;
using System.Collections.Generic;
using System.Net;
using System.Net.Http;
using System.Threading.Tasks;

namespace Fines2Model3.DAO
{
    internal static class ProgramaFines
    {
        public static async Task PF_Login(HttpClient client)
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

        public static async Task<string[]> PF_InfoListaAlumnos(HttpClient client, string pfid)
        {
            string protectedResourceUrl = $"https://www.programafines.ar/inicial/index4.php?a=12&&nom_comision={pfid}&mi_periodo=2"; //lista de alumnos de la comision
            HttpResponseMessage response = await client.GetAsync(protectedResourceUrl);
            response.EnsureSuccessStatusCode(); // Ensure a successful response

            // Read the content of the response
            string responseData = await response.Content.ReadAsStringAsync();
            return responseData.Split(new string[] { "<h2 align='left'>" }, StringSplitOptions.None);
        }

        public static async Task<IDictionary<string, string>> PF_InfoAlumnoFormularioModificacion(HttpClient client, string dni)
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
                        inputFields[nameAttribute] = HtmlEntity.DeEntitize(valueAttribute);
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

        public static async Task PF_ActualizarFormularioAlumno(HttpClient client, IDictionary<string, string> formData)
        {
            var content = new FormUrlEncodedContent(formData);

            var response = await client.PostAsync("https://www.programafines.ar/inicial/index4.php?a=8&b=2", content);
            response.EnsureSuccessStatusCode(); // Ensure a successful response

            // Check if the login was successful
            if (response.StatusCode != HttpStatusCode.OK)
                throw new Exception("Error:" + response.StatusCode.ToString());

            string res = await response.Content.ReadAsStringAsync();
        }

        public static void Dispose(HttpClient client)
        {
            client.Dispose();
            ContainerApp.pfHandler.Dispose();
            ContainerApp.pfHandler.CookieContainer = new CookieContainer();
        }

    }
}
