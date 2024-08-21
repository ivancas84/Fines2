

using MySql.Data.MySqlClient;
using SqlOrganize;
using System.Configuration;

List<string> files = Directory.GetFiles(ConfigurationManager.AppSettings.Get("path")).ToList();
string path = ConfigurationManager.AppSettings.Get("path");
string pathLast = path + ConfigurationManager.AppSettings.Get("last");

if (!ConfigurationManager.AppSettings.Get("last").IsNoE())
    files.RemoveAll(x => String.Compare(x.Substring(0, path.Count() + 16), pathLast, comparisonType: StringComparison.OrdinalIgnoreCase) <= 0);

foreach (string file in files)
{
    var f = file.Substring(0, ConfigurationManager.AppSettings.Get("path").ToString().Count() + 16);
    using StreamReader r = new StreamReader(file);
    string text = r.ReadToEnd();
    var sqls = text.Split(';').Select(x => x.Trim()).ToList();
    sqls.RemoveAll(x => x.IsNoE());

    foreach (string sql in sqls)
    {
        if (sql.IsNoE()) continue;
        Console.WriteLine(sql);
        using MySqlConnection connection = new MySqlConnection(ConfigurationManager.AppSettings.Get("connectionString"));
        {
            connection.Open();
            using MySqlCommand command = new MySqlCommand();
            {

                command.Connection = connection;
                command.CommandText = sql;
                command.ExecuteNonQuery();
            }
        }
    }
}

