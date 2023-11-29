using HarfBuzzSharp;
using Microsoft.Extensions.Caching.Memory;
using SqlOrganize;
using SqlOrganizeMy;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Linq;
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
            download = ConfigurationManager.AppSettings.Get("download")!,
            ftpUserName = ConfigurationManager.AppSettings.Get("ftpUserName")!,
            ftpUserPassword = ConfigurationManager.AppSettings.Get("ftpUserPassword")!,
        };

        public static Db dbPedidos;

        public static SqlOrganize.DAO dao;

        static ContainerApp()
        {

            MemoryCache cache = new MemoryCache(new MemoryCacheOptions());

            Schema model = new Schema();
            db = new DbApp(config, model, cache);
            dao = new(db);


            SqlOrganize.Config configPedidos = new()
            {
                id = "id",
                fkId = true,
                connectionString = ConfigurationManager.AppSettings.Get("connectionStringPedidos")!,
            };
            dbPedidos = new DbMy(configPedidos, new Pedidos.Schema(), new MemoryCache(new MemoryCacheOptions()));


        }

    }
}
