using MySqlX.XDevAPI;
using SqlOrganize.Model;
using System.Configuration;

var c = new Config()
{
    connectionString = ConfigurationManager.AppSettings.Get("connectionString"),
    docPath = ConfigurationManager.AppSettings.Get("docPath"),
    dbName = ConfigurationManager.AppSettings.Get("dbName"),
    dataClassesPath = ConfigurationManager.AppSettings.Get("dataClassesPath"),
    dataClassesNamespace = ConfigurationManager.AppSettings.Get("dataClassesNamespace"),
    schemaClassPath = ConfigurationManager.AppSettings.Get("schemaClassPath"),
    schemaClassNamespace = ConfigurationManager.AppSettings.Get("schemaClassNamespace"),
    idSource = "field_name",
    id = "id",
};

BuildModelMy t = new(c);
foreach (var (key, field) in t.fields)
{
    foreach (var (k, f) in field)
    {
        if (f.name == "id")
        {
            f.defaultValue = "guid";
        }
    }

}

//t.CreateFileEntitites();
//t.CreateFileFields();
//t.CreateClassModel();

SqlOrganize.Sql.Fines2Model3.Schema schema = new SqlOrganize.Sql.Fines2Model3.Schema();
t.CreateFileData(schema);
//t.CreateModel();

