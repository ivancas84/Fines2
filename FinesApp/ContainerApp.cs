using Microsoft.Extensions.Caching.Memory;
using System.Configuration;
using System.Net.Http;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;

namespace FinesApp
{
    static class ContainerApp
    {
        public static DbApp db;

        public static SqlOrganize.Sql.Fines2Model3.Config config = new SqlOrganize.Sql.Fines2Model3.Config
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

            SqlOrganize.Sql.Fines2Model3.Schema model = new SqlOrganize.Sql.Fines2Model3.Schema();
            db = new DbApp(config, model, cache);

            SqlOrganize.Sql.Config configPedidos = new()
            {
                id = "id",
                fkId = true,
                connectionString = ConfigurationManager.AppSettings.Get("connectionStringPedidos")!,
            };
            //dbPedidos = new DbMy(configPedidos, new PedidosModel2.Schema(), new MemoryCache(new MemoryCacheOptions()));
        }

    }
}
