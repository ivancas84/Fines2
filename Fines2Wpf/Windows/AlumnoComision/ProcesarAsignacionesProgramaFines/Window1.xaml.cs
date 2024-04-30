using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Net;
using System.Windows;
using SqlOrganize;
using System.Collections.ObjectModel;

namespace Fines2Wpf.Windows.AlumnoComision.ProcesarAsignacionesProgramaFines
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
            // URL of the login page and the login endpoint
            string loginPageUrl = "https://www.programafines.ar/index.php";
            string loginEndpointUrl = "https://www.programafines.ar/validar.php";

            // User credentials
            string usuario = ContainerApp.config.pfUser;
            string password = ContainerApp.config.pfPassword;
            string button = "Entrar";

            List<string> numbers = new();

            var comisionesData = DAO.Comision2.ComisionesAutorizadasDeAnioSemestreQuery("2024", "1").ColOfDictCache();

            foreach( var comision in comisionesData)
                numbers.Add((string)comision["pfid"]);


            // Create an HttpClientHandler to store cookies and maintain session
            var handler = new HttpClientHandler
            {
                CookieContainer = new CookieContainer(),
                UseCookies = true,
                UseDefaultCredentials = false,
                AllowAutoRedirect = true // Allow automatic redirection
            };

            // Create an HttpClient instance with the handler
            using (HttpClient client = new HttpClient(handler))
            {
                // Get the login page to retrieve any necessary cookies
                HttpResponseMessage response = await client.GetAsync(loginPageUrl);
                response.EnsureSuccessStatusCode(); // Ensure a successful response

                // Prepare the form data to be sent in the POST request
                var formData = new Dictionary<string, string>
            {
                { "usuario", usuario },
                { "password", password },
                { "button", button } // Additional form fields if needed
            };

                // Encode the form data
                var encodedFormData = new FormUrlEncodedContent(formData);

                // Send a POST request to the login endpoint with the form data
                response = await client.PostAsync(loginEndpointUrl, encodedFormData);
                response.EnsureSuccessStatusCode(); // Ensure a successful response

                // Check if the login was successful
                if (response.StatusCode == HttpStatusCode.OK)
                {
                    Console.WriteLine("Login successful!");
                    // Perform further actions after successful login

                    foreach (string number in numbers)
                    {

                        string protectedResourceUrl = $"https://www.programafines.ar/inicial/index4.php?a=12&&nom_comision={number}&mi_periodo=2";
                        response = await client.GetAsync(protectedResourceUrl);
                        response.EnsureSuccessStatusCode(); // Ensure a successful response

                        // Read the content of the response
                        string responseData = await response.Content.ReadAsStringAsync();
                        string[] h2 = responseData.Split(new string[] { "<h2 align='left'>" }, StringSplitOptions.None);

                        foreach (string h2Item in h2)
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
                            data.nombre = h2Item.Substring(pos1 + str1.Length, pos2 - (pos1 + str1.Length));
                            data.telefono = h2Item.Substring(pos5 + str5.Length, pos6 - (pos5 + str5.Length));
                            data.nacimiento = h2Item.Substring(pos3 + str3.Length, pos4 - (pos3 + str3.Length));
                            data.pfid = h2Item.Substring(pos7 + str7.Length, pos8 - (pos7 + str7.Length));                             data.comision = number;
                            data.dni = h2Item.Substring(pos2 + str2.Length, pos3 - (pos2 + str2.Length)); ;
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

        
    }
}
