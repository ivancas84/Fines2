using System;
using System.Collections.Generic;
using System.Net;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;

class Program
{
    static async Task Main(string[] args)
    {
        // URL of the login page and the login endpoint
        string loginPageUrl = "https://www.programafines.ar/index.php";
        string loginEndpointUrl = "https://www.programafines.ar/validar.php";

        // User credentials
        string usuario = "31073351";
        string password = "Hola1024";
        string button = "Entrar";

    
        
        List<string> numbers = new List<string>
        {
            "10068",
            "10171"
            // Add more URLs as needed
        };

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

                foreach(string number in numbers)
                {

                    string protectedResourceUrl = $"https://www.programafines.ar/inicial/index4.php?a=12&&nom_comision={number}&mi_periodo=2";
                    response = await client.GetAsync(protectedResourceUrl);
                    response.EnsureSuccessStatusCode(); // Ensure a successful response

                    // Read the content of the response
                    string responseData = await response.Content.ReadAsStringAsync();
                    string[] h2 = responseData.Split(new string[] { "<h2 align='left'>" }, StringSplitOptions.None);

                    foreach (string h2Item in h2)
                    {
                        string firstStr = "index4.php?a=12&b=1&id_alum_per=";
                        string secondStr = "&dni_alum_borrar=";
                        string thirdStr = "\">Borrar de esta lista";

                        int firstPos = h2Item.IndexOf(firstStr);
                        if (firstPos == -1)
                        {
                            continue; // Start string not found
                        }

                        int secondPos = h2Item.IndexOf(secondStr, firstPos + firstStr.Length);
                        if (secondPos == -1)
                        {
                            continue; // End string not found
                        }

                        int thirdPos = h2Item.IndexOf(thirdStr, secondPos + secondStr.Length);
                        if (thirdPos == -1)
                        {
                            continue; // End string not found
                        }

                        string pfid = h2Item.Substring(firstPos + firstStr.Length, secondPos - (firstPos + firstStr.Length));
                        string dni = h2Item.Substring(secondPos + secondStr.Length, thirdPos - (secondPos + secondStr.Length));

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




