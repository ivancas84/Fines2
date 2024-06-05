using Microsoft.Extensions.Caching.Memory;
using SqlOrganize;
using SqlOrganizeMy;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Linq;
using System.Net.Http;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf
{
    static class ContainerApp
    {
        public static Db db;

        public static Config config = new Config
        {
            id = "id",
            fkId = true,
            connectionString = ConfigurationManager.AppSettings.Get("connectionString")!,
            emailDocenteUser = ConfigurationManager.AppSettings.Get("emailDocenteUser"),
            emailDocentePassword = ConfigurationManager.AppSettings.Get("emailDocentePassword"),
            emailDocenteHost = ConfigurationManager.AppSettings.Get("emailDocenteHost"),
            emailDocenteFromAddress = ConfigurationManager.AppSettings.Get("emailDocenteFromAddress"),
            emailDocenteFromName = ConfigurationManager.AppSettings.Get("emailDocenteFromName"),
            emailDocenteBcc = ConfigurationManager.AppSettings.Get("emailDocenteBcc"),
            upload = ConfigurationManager.AppSettings.Get("upload")!,
            downloadPath = ConfigurationManager.AppSettings.Get("downloadPath")!,
            ftpUserName = ConfigurationManager.AppSettings.Get("ftpUserName")!,
            ftpUserPassword = ConfigurationManager.AppSettings.Get("ftpUserPassword")!,
            imagePath = ConfigurationManager.AppSettings.Get("imagePath")!,
            pfUser = ConfigurationManager.AppSettings.Get("pfUser")!,
            pfPassword = ConfigurationManager.AppSettings.Get("pfPassword")!,


        };

        public static Db dbPedidos;

        public static HttpClientHandler pfHandler;

        internal static string pfLoginPageUrl = "https://www.programafines.ar/index.php";

        internal static string pfLoginEndpointUrl = "https://www.programafines.ar/validar.php";


        static ContainerApp()
        {

            MemoryCache cache = new MemoryCache(new MemoryCacheOptions());

            Schema model = new Fines2Model3.Schema();
            db = new DbApp(config, model, cache);

            SqlOrganize.Config configPedidos = new()
            {
                id = "id",
                fkId = true,
                connectionString = ConfigurationManager.AppSettings.Get("connectionStringPedidos")!,
            };
            dbPedidos = new DbMy(configPedidos, new Pedidos.Schema(), new MemoryCache(new MemoryCacheOptions()));

            // URL of the login page and the login endpoint
            

         
        }

    }
}
