using Microsoft.Extensions.Caching.Memory;
using System.Configuration;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class Context
    {
        public static DbMy db;

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

        public static IMemoryCache CreateCache()
        {
            return new MemoryCache(new MemoryCacheOptions());

        }
        static Context()
        {

            MemoryCache cache = (MemoryCache)CreateCache();

            Schema model = new Schema();
            db = new DbMy(config, model, cache);

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
