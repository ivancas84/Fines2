using Newtonsoft.Json;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Model
{
    public abstract class BuildModel
    {
        public Config Config { get; }

        public List<Table> Tables { get; } = new();

        public Dictionary<string, EntityMetadata> entities { get; } = new();

        public Dictionary<string, Dictionary<string, Field>> fields { get; set; } = new();

        protected void resetField(Field f)
        {
            f.checks = new()
                    {
                        { "type", f.type },
                    };

            if (f.notNull)
                f.checks["required"] = true;

            if (f.type == "string")
            {
                f.resets = new()
                        {
                            { "trim", ' '},
                            { "removeMultipleSpaces", true },
                        };
                if (!f.notNull)
                    f.resets["nullIfEmpty"] = true;
            }

            if (f.type == "bool" && f.defaultValue is not null)
                f.defaultValue = ((string)f.defaultValue).ToBool();

            if (f.type == "string" && f.defaultValue is not null)
                f.defaultValue = f.defaultValue.ToString()!.Trim('\'');
        }

        protected void defineField(Column c, Field f)
        {
            if (!c.CHARACTER_MAXIMUM_LENGTH.IsNoE() && !c.CHARACTER_MAXIMUM_LENGTH.IsDbNull() && Convert.ToInt64(c.CHARACTER_MAXIMUM_LENGTH) > 0)
                f.maxLength = Convert.ToUInt64(c.CHARACTER_MAXIMUM_LENGTH)!;
            else if (!c.MAX_LENGTH.IsNoE() && !c.MAX_LENGTH.IsDbNull())
                f.maxLength = Convert.ToUInt64(c.MAX_LENGTH)!;

            f.dataType = c.DATA_TYPE!;
            switch (c.DATA_TYPE)
            {
                case "varbinary":
                case "binary":
                    if (f.maxLength == 1)
                        f.type = "byte";
                    else
                        f.type = "Byte[]";
                    break;

                case "image":
                case "rowversion":
                    f.type = "Byte[]";
                    break;

                case "money":
                case "smallmoney":
                case "numeric":
                case "decimal":
                    f.type = "decimal";
                    break;

                case "varchar":
                case "char":
                case "nchar":
                case "nvarchar":
                case "text":
                case "mediumtext":
                case "tinytext":
                case "longtext":
                    f.type = "string";
                    break;
                case "real":
                    f.type = "Single";
                    break;

                case "float":
                    f.type = "Double";
                    break;

                case "bit":
                    f.type = "bool";
                    break;

                case "datetime":
                case "smalldatetime":
                case "timestamp":
                case "date":
                case "time":
                    f.type = "DateTime";
                    break;

                case "smallint":
                case "year":
                    f.type = (c.IS_UNSIGNED == 1) ? "ushort" : "short";
                    break;

                case "int":
                case "mediumint":
                    f.type = (c.IS_UNSIGNED == 1) ? "uint" : "int";
                    break;

                case "tinyint": 
                    if (f.maxLength == 1)
                        f.type = "bool";
                    else
                        f.type = "byte";
                    break;

                case "bigint":
                    f.type = (c.IS_UNSIGNED == 1) ? "ulong" : "long";
                    break;

                case "uniqueidentifier":
                    f.type = "Guid";
                    break;

                case "sql_variant":
                case "table":
                case "cursor":
                case "xml":
                    f.type = "object";
                    break;

                default:
                    f.type = c.DATA_TYPE!;
                    break;
            }

            if (!c.COLUMN_DEFAULT.IsNoE() && !c.COLUMN_DEFAULT.IsDbNull() && (c.COLUMN_DEFAULT as string) != "NULL")
                f.defaultValue = c.COLUMN_DEFAULT;

            if (!c.REFERENCED_TABLE_NAME.IsNoE())
                f.refEntityName = c.REFERENCED_TABLE_NAME;

            if (!c.REFERENCED_COLUMN_NAME.IsNoE()) //se compara por REFERENCED_TABLE_NAME para REFERENCED_COLUMN_NAME
                f.refFieldName = c.REFERENCED_COLUMN_NAME;

            f.notNull = (c.IS_NULLABLE == 1) ? false : true;
        }

        /// <summary> Definir datos del esquema y arbol de relaciones </summary>
        public BuildModel(Config config)
        {
            Config = config;

            #region Definicion inicial de tables y columns
            List<string> tableAlias = new List<string>(Config.reservedAlias);

            foreach (string tableName in GetTableNames())
            {
                if (Config.reservedEntities.Contains(tableName!))
                    continue;

                Table table = new();
                table.Name = tableName;
                table.Alias = GetAlias(tableName, tableAlias, 4);
                tableAlias.Add(table.Alias);
                table.Columns = GetColumns(table.Name);

                List<string> fieldsAliases = new List<string>(Config.reservedAlias);
                foreach (Column col in table.Columns)
                {
                    if (col.IS_FOREIGN_KEY == 1 && !Config.reservedEntities.Contains(col.REFERENCED_TABLE_NAME!)) {
                        string idSource = ((Config.idSource == "field_name") ? col.COLUMN_NAME : col.REFERENCED_TABLE_NAME)!;
                        col.Alias = GetAlias(idSource, fieldsAliases, 3);
                        fieldsAliases.Add(col.Alias);
                    }
                    table.ColumnNames.Add(col.COLUMN_NAME);

                    if (col.IS_FOREIGN_KEY == 1 && !Config.reservedEntities.Contains(col.REFERENCED_TABLE_NAME!))
                        table.Fk.Add(col.COLUMN_NAME);
                    if (col.IS_PRIMARY_KEY == 1)
                        table.Pk.Add(col.COLUMN_NAME);
                    if (col.IS_NULLABLE == 0)
                        table.NotNull.Add(col.COLUMN_NAME);
                }

                Tables.Add(table);
            }
            #endregion

            #region Definicion de campos unicos de tables
            foreach (var table in Tables)
            {
                var infoUnique = GetInfoUnique(table.Name!);

                foreach(var (constraintName, columnNames) in infoUnique!)
                {
                    if (columnNames.Count > 1)
                        table.UniqueMultiple.Add(columnNames);
                    else
                        table.Unique.Add(columnNames[0]);
                }

            }

            #endregion

            #region Definicion de entities
            foreach (Table t in Tables)
            {
                if (Config.reservedEntities.Contains(t.Name!))
                    continue;

                var e = new EntityMetadata();
                e.name = t.Name!;
                e.alias = t.Alias!;
                e.fields = t.ColumnNames;
                e.pk = t.Pk;
                e.fk = t.Fk;
                e.unique = t.Unique;
                e.uniqueMultiple = t.UniqueMultiple;
                e.notNull = t.NotNull;
                entities[e.name!] = e;
            }
            #endregion

            #region Definir id de entities
            foreach (var (name, e) in entities)
                e.id = DefineId(e);
            #endregion

            #region Redefinicion de entities en base a configuracion
            /*if (File.Exists(config.configPath + "entities.json"))
            {
                using (StreamReader r = new StreamReader(config.configPath + "entities.json"))
                {
                    Dictionary<string, EntityMetadataAux> entitiesAux = JsonConvert.DeserializeObject<Dictionary<string, EntityMetadataAux>>(r.ReadToEnd())!;
                    foreach (KeyValuePair<string, EntityMetadataAux> e in entitiesAux)
                    {
                        if (!entities.ContainsKey(e.Key))
                            continue;

                        entities[e.Key].CopyValues(e.Value, sourceNotNull:true);

                        var f = new List<string>();
                        f.AddRange(entities[e.Key].fields);
                        if(!e.Value.fieldsAdd.IsNoE())
                            f.AddRange(e.Value.fieldsAdd);
                        if (!e.Value.fieldsSub.IsNoE())
                            f = f.Except(e.Value.fieldsSub).ToList();
                        entities[e.Key].fields = f;

                        entities[e.Key].fk = entities[e.Key].fk.Intersect(f).ToList();
                        entities[e.Key].unique = entities[e.Key].unique.Intersect(f).ToList();
                        entities[e.Key].notNull = entities[e.Key].notNull.Intersect(f).ToList();

                        f = new List<string>();
                        f.AddRange(entities[e.Key].fk);
                        if (!e.Value.fkAdd.IsNoE())
                            f.AddRange(e.Value.fkAdd);
                        if (!e.Value.fkSub.IsNoE())
                            f = f.Except(e.Value.fkSub).ToList();
                        entities[e.Key].fk = f;

                        f = new List<string>();
                        f.AddRange(entities[e.Key].unique);
                        if (!e.Value.uniqueAdd.IsNoE())
                            f.AddRange(e.Value.uniqueAdd);
                        if (!e.Value.uniqueSub.IsNoE())
                            f = f.Except(e.Value.uniqueSub).ToList();
                        entities[e.Key].unique = f;

                        f = new List<string>();
                        f.AddRange(entities[e.Key].notNull);

                        if (!e.Value.notNullAdd.IsNoE())
                            f.AddRange(e.Value.notNullAdd);

                        if (!e.Value.notNullSub.IsNoE())
                            f = f.Except(e.Value.notNullSub).ToList();

                        entities[e.Key].notNull = f;
                    }
                }
            }*/
            #endregion

            #region Definicion de fields
            foreach (Table t in Tables)
            {
                if (Config.reservedEntities.Contains(t.Name!))
                    continue;

                foreach (Column c in t.Columns)
                {
                    if (!entities[t.Name!]!.fields.Contains(c.COLUMN_NAME))
                        continue;

                    var f = new Field();
                    f.entityName = t.Name!;
                    f.name = c.COLUMN_NAME;
                    f.alias = c.Alias;

                    defineField(c, f);
                    resetField(f);

                    if (!fields.ContainsKey(t.Name!))
                        fields[t.Name!] = new();

                    fields[t.Name!][f.name] = f;
                }
            }
            #endregion

            #region Redefinicion de fields en base a configuracion
            /*foreach (string entityName in entities.Keys)
                if (fields.ContainsKey(entityName))
                    if (File.Exists(config.configPath + "fields/" + entityName + ".json"))
                        using (StreamReader r = new StreamReader(config.configPath + "fields/" + entityName + ".json"))
                        {
                            Dictionary<string, FieldAux> fieldsAux = JsonConvert.DeserializeObject<Dictionary<string, FieldAux>>(r.ReadToEnd())!;
                            foreach (KeyValuePair<string, FieldAux> e in fieldsAux)
                            {
                                if (fields[entityName].ContainsKey(e.Key))
                                {
                                    fields[entityName][e.Key].CopyValues(e.Value, targetNull:false, sourceNotNull:true, compareNotNull:false);

                                    resetField(fields[entityName][e.Key]);

                                    Dictionary<string, object> f = fields[entityName][e.Key].checks;
                                    if (!e.Value.checks.IsNoE())
                                        f = e.Value.checks;
                                    if (!e.Value.checksAdd.IsNoE())
                                        f.Merge(e.Value.checks);
                                    if (!e.Value.checksSub.IsNoE())
                                        foreach (string k in e.Value.checksSub)
                                            f.Remove(k);
                                    fields[entityName][e.Key].checks = f;

                                    f = fields[entityName][e.Key].resets;
                                    if (!e.Value.resets.IsNoE())
                                        f = e.Value.resets;
                                    if (!e.Value.resetsAdd.IsNoE())
                                        f.Merge(e.Value.checks);
                                    if (!e.Value.resetsSub.IsNoE())
                                        foreach (string k in e.Value.resetsSub)
                                            f.Remove(k);
                                    fields[entityName][e.Key].resets = f;
                                }
                            }
                        }*/
            #endregion

            #region Definicion de tree y relations de entities            
            /*
             * La definicion de tree y relations no se debe modificar 
             * en la configuracion, ya que se basa en otras llaves 
             * (entity.fk y field.ref*)
             */
            foreach (var (name, e) in entities)
            {
                var bet = new BuildEntityTree(config, entities, fields, e.name!);
                e.tree = bet.Build();
                RelationsRecursive(e.relations, e.tree);

            }
            #endregion
        }

        /// <summary>
        /// Definir alias para tablas y campos
        /// </summary>
        /// <param name="name">Nombre para el cual se definira alias</param>
        /// <param name="reserved">Palabras reservadas que no seran definidas como alias</param>
        /// <param name="length">Longitud del alias</param>
        /// <param name="separator">String utilizado para separar palabras de 'name'</param>
        /// <returns></returns>
        protected string GetAlias(string name, List<string> reserved, int length = 3, string separator = "_")
        {
            var n = name.Trim('_');
            string[] words = n.Split(separator);

            string nameAux = "";

            if (n.Length < length)
                length = n.Length;

            if (words.Length > 1)
                foreach (string word in words)
                    nameAux += word[0];

            string aliasAux = name.Substring(0, length);

            int c = 0;

            while (reserved.Contains(aliasAux))
            {
                c++;
                aliasAux = aliasAux.Substring(0, length - 1);
                aliasAux += c.ToString();
            }

            return aliasAux;
        }

        protected abstract IEnumerable<string> GetTableNames();

        protected abstract IEnumerable<Column> GetColumns(string tableName);

        protected abstract Dictionary<string, List<string>> GetInfoUnique(string tableName);


        protected void RelationsRecursive(Dictionary<string, EntityRelation> rel, Dictionary<string, EntityTree> tree, string? parentId = null)
        {
            foreach (var (fieldId, t) in tree)
            {
                EntityRelation r = new()
                {
                    fieldName = t.fieldName,
                    refEntityName = t.refEntityName,
                    refFieldName = t.refFieldName,
                    parentId = parentId
                };
                rel[fieldId] = r;
                RelationsRecursive(rel, t.children, fieldId);
            }
        }

        public void CreateFileEntitites()
        {
            if (!Directory.Exists(Config.docPath))
                Directory.CreateDirectory(Config.docPath);

            if (File.Exists(Config.docPath + "entities.json"))
                File.Delete(Config.docPath + "entities.json");

            var file = JsonConvert.SerializeObject(entities, Newtonsoft.Json.Formatting.Indented);
            File.WriteAllText(Config.docPath + "entities.json", file);
        }

        public void CreateModelTree(StreamWriter sw, Dictionary<string, EntityTree> tree, string prefix = "")
        {
            sw.WriteLine(prefix + "                            children = new() {");

            foreach (var (fieldId, tree_) in tree)
            {
                sw.WriteLine(prefix + "                                { \"" + fieldId + "\", new () {");
                sw.WriteLine(prefix + "                                    fieldName = \"" + tree_.fieldName + "\",");
                sw.WriteLine(prefix + "                                    refFieldName = \"" + tree_.refFieldName + "\",");
                sw.WriteLine(prefix + "                                    refEntityName = \"" + tree_.refEntityName + "\",");

                if (tree_.children.Any())
                    CreateModelTree(sw, tree_.children, prefix + "        ");
                sw.WriteLine(prefix + "                                } },");

            }

            sw.WriteLine(prefix + "                            },");

        }

        public void CreateModel()
        {

            if (!Directory.Exists(Config.schemaClassPath))
                Directory.CreateDirectory(Config.schemaClassPath);

            if (File.Exists(Config.schemaClassPath + "Schema.cs")) { 
                Console.WriteLine("Ya existe el esquema, no será creado");
                return;
            }

            using StreamWriter sw = File.CreateText(Config.schemaClassPath + "Schema.cs");
            sw.WriteLine("using System.Collections.Generic;");
            sw.WriteLine("");
            sw.WriteLine("namespace SqlOrganize.Sql." + Config.schemaClassNamespace);
            sw.WriteLine("{");
            sw.WriteLine("    public class  Schema : Sql.ISchema");
            sw.WriteLine("    {");
            sw.WriteLine("         Dictionary<string, EntityMetadata> Sql.ISchema.entities => new() {");


            foreach (var (entityName, entity) in entities)
            {
                sw.WriteLine("            #region entity " + entityName);
                sw.WriteLine("            {");
                sw.WriteLine("                \"" + entityName + "\", new () {");
                sw.WriteLine("                    name = \"" + entityName + "\",");
                sw.WriteLine("                    alias = \"" + entity.alias + "\",");
                sw.WriteLine("                    schema = \"" + entity.schema + "\",");

                if (entity.pk.Any())
                    sw.WriteLine("                    pk = [ \"" + String.Join("\", \"", entity.pk) + "\" ],");

                if (entity.fk.Any())
                    sw.WriteLine("                    fk = [ \"" + String.Join("\", \"", entity.fk) + "\" ],");

                if (entity.noAdmin.Any())
                    sw.WriteLine("                    noAdmin = [ \"" + String.Join("\", \"", entity.noAdmin) + "\" ],");

                if (entity.unique.Any())
                    sw.WriteLine("                    unique = [ \"" + String.Join("\", \"", entity.unique) + "\" ],");

                if (entity.notNull.Any())
                    sw.WriteLine("                    notNull = [ \"" + String.Join("\", \"", entity.notNull) + "\" ],");

                if (entity.tree.Any())
                {
                    sw.WriteLine("                    tree = {");

                    foreach (var (fieldId, tree) in entity.tree)
                    {
                        sw.WriteLine("                        { \"" + fieldId + "\", new () {");
                        sw.WriteLine("                            fieldName = \"" + tree.fieldName + "\",");
                        sw.WriteLine("                            refFieldName = \"" + tree.refFieldName + "\",");
                        sw.WriteLine("                            refEntityName = \"" + tree.refEntityName + "\",");

                        if (tree.children.Any())
                            CreateModelTree(sw, tree.children);

                        sw.WriteLine("                        } },");

                    }
                    sw.WriteLine("                    },");
                }

                if (entity.relations.Any())
                {
                    sw.WriteLine("                    relations = {");

                    foreach (var (fieldId, relation) in entity.relations)
                    {
                        sw.WriteLine("                        { \"" + fieldId + "\", new () {");
                        sw.WriteLine("                            fieldName = \"" + relation.fieldName + "\",");
                        sw.WriteLine("                            refFieldName = \"" + relation.refFieldName + "\",");
                        sw.WriteLine("                            refEntityName = \"" + relation.refEntityName + "\",");
                        sw.WriteLine("                            parentId = \"" + relation.parentId + "\",");
                        sw.WriteLine("                        } },");
                    }
                    sw.WriteLine("                    },");
                }

                

                sw.WriteLine("                    fieldsMetadata = {");

                foreach (var (fieldName, field) in fields[entityName])
                {
                    sw.WriteLine("                        #region " + entityName + "." + fieldName);
                    sw.WriteLine("                        {");
                    sw.WriteLine("                            \"" + fieldName + "\", new () {");
                    sw.WriteLine("                                entityName = \"" + entityName + "\",");
                    sw.WriteLine("                                name = \"" + fieldName + "\",");
                    sw.WriteLine("                                alias = \"" + field.alias + "\",");
                    sw.WriteLine("                                refEntityName = \"" + field.refEntityName + "\",");
                    sw.WriteLine("                                refFieldName = \"" + field.refFieldName + "\",");
                    sw.WriteLine("                                dataType = \"" + field.dataType + "\",");
                    sw.WriteLine("                                type = \"" + field.type + "\",");

                    if (field.checks.Any())
                    {
                        sw.WriteLine("                                checks = new () {");

                        foreach (var (key, value) in field.checks)
                        {
                            sw.WriteLine("                                        { \"" + key + "\", \"" + value + "\" },");
                        }
                        sw.WriteLine("                                },");
                    }

                    if (field.resets.Any())
                    {
                        sw.WriteLine("                                resets = new () {");

                        foreach (var (key, value) in field.resets)
                        {
                            sw.WriteLine("                                        { \"" + key + "\", \"" + value + "\" },");
                        }
                        sw.WriteLine("                                },");
                    }

                    sw.WriteLine("                            } //end new " + fieldName);

                    sw.WriteLine("                        }, //end pair");
                    sw.WriteLine("                        #endregion");

                }
                sw.WriteLine("                    },"); //end fieldsMetadata
                sw.WriteLine("                }"); //end new entityName
                sw.WriteLine("            },"); //end pair)
                sw.WriteLine("            #endregion"); //end pair)


            }

            sw.WriteLine("        };"); //end entities
            sw.WriteLine("    }"); //end class
            sw.WriteLine("}"); //end namespace



        }

        public void CreateModelFields()
        {

            if (!Directory.Exists(Config.schemaClassPath))
                Directory.CreateDirectory(Config.schemaClassPath);

            foreach (var (entityName, entity) in entities)
            {
                using StreamWriter sw = File.CreateText(Config.schemaClassPath + entity.name.ToCamelCase() + "Metadata.cs");
                sw.WriteLine("using System.Collections.Generic;");
                sw.WriteLine("");
                sw.WriteLine("namespace SqlOrganize.Sql." + Config.schemaClassNamespace);
                sw.WriteLine("{");
                sw.WriteLine("    public class " + entity.name.ToCamelCase() + "Metadata");
                sw.WriteLine("    {");
                sw.WriteLine("        protected Dictionary<string, Field> fields = new() {");

                foreach (var (fieldName, field) in fields[entityName])
                {
                    sw.WriteLine("            {");
                    sw.WriteLine("                \"" + fieldName + "\", new () {");
                    sw.WriteLine("                    entityName = \"" + entityName + "\",");
                    sw.WriteLine("                    name = \"" + fieldName + "\",");
                    sw.WriteLine("                    alias = \"" + field.alias + "\",");
                    sw.WriteLine("                    refEntityName = \"" + field.refEntityName + "\",");
                    sw.WriteLine("                    refFieldName = \"" + field.refFieldName + "\",");
                    sw.WriteLine("                    dataType = \"" + field.dataType + "\",");
                    sw.WriteLine("                    type = \"" + field.type + "\",");

                    if (field.checks.Any())
                    {
                        sw.WriteLine("                    checks = new () {");

                        foreach (var (key, value) in field.checks)
                        {
                            sw.WriteLine("                        { \"" + key + "\", \"" + value + "\" },");
                        }
                        sw.WriteLine("                    },");
                    }

                    if (field.resets.Any())
                    {
                        sw.WriteLine("                    resets = new () {");

                        foreach (var (key, value) in field.resets)
                        {
                            sw.WriteLine("                        { \"" + key + "\", \"" + value + "\" },");
                        }
                        sw.WriteLine("                    },");
                    }


                    sw.WriteLine("                }");
                    sw.WriteLine("            },");
                    sw.WriteLine("");
                }
                
                sw.WriteLine("        };");
                sw.WriteLine("    }");
                sw.WriteLine("}");
            }
        }

        public void CreateClassModel()
        {
            if (!Directory.Exists(Config.schemaClassPath))
                Directory.CreateDirectory(Config.schemaClassPath);

            using StreamWriter sw = File.CreateText(Config.schemaClassPath + "Schema.cs");

            var file = JsonConvert.SerializeObject(entities, Newtonsoft.Json.Formatting.Indented);
            sw.WriteLine("using System.Collections.Generic;");
            sw.WriteLine("");
            sw.WriteLine("namespace SqlOrganize.Sql." + Config.schemaClassNamespace);
            sw.WriteLine("{");
            sw.WriteLine("    public class Schema : Sql.Schema");
            sw.WriteLine("    {");
            sw.WriteLine("        private string _entities = " + JsonConvert.ToString(file) + ";");
            sw.WriteLine("");
            sw.WriteLine("        private Dictionary<string, string> _fields = new() {");

            foreach (var (entityName, entity) in entities)
            {
                    file = JsonConvert.SerializeObject(fields[entityName], Newtonsoft.Json.Formatting.Indented);
                    sw.WriteLine("            { \"" + entityName + "\", " + JsonConvert.ToString(file) + " },");
            }

            sw.WriteLine("        };");
            sw.WriteLine("");
            sw.WriteLine("        protected override string entities => _entities;");
            sw.WriteLine("");
            sw.WriteLine("        protected override Dictionary<string, string> fields => _fields;");
            sw.WriteLine("");
            sw.WriteLine("    }");
            sw.WriteLine("}");
        }

        public void CreateFileFields()
        {
            if (!Directory.Exists(Config.docPath + "Fields/"))
                Directory.CreateDirectory(Config.docPath + "Fields/");

            foreach(var (entityName, field) in fields)
            {
                if (File.Exists(Config.docPath + entityName + ".json"))
                    File.Delete(Config.docPath + entityName + ".json");

                var file = JsonConvert.SerializeObject(fields[entityName], Newtonsoft.Json.Formatting.Indented);
                File.WriteAllText(Config.docPath + "Fields/" + entityName + ".json", file);
            }

        }

        public List<EntityTree> RelationsRef(string entityName)
        {
            List<EntityTree> response = new();

            foreach (var (eN, entity) in entities)
                foreach (var (fid, et) in entity.tree)
                    if (et.refEntityName.Equals(entityName))
                        response.Add(et);


            return response;
        }

        public void CreateFileData()
        {
            if (!Directory.Exists(Config.dataClassesPath))
                Directory.CreateDirectory(Config.dataClassesPath);

            foreach (var (entityName, entity) in entities)
            {
                using StreamWriter sw = File.CreateText(Config.dataClassesPath + entityName.ToCamelCase() + ".cs");
                sw.WriteLine("#nullable enable");
                sw.WriteLine("using System;");
                sw.WriteLine("using System.ComponentModel;");
                sw.WriteLine("using System.Collections.Generic;");
                sw.WriteLine("using System.Collections.ObjectModel;");
                sw.WriteLine("using System.Text.RegularExpressions;");
                sw.WriteLine("");
                sw.WriteLine("namespace SqlOrganize.Sql." + Config.dataClassesNamespace);
                sw.WriteLine("{");
                sw.WriteLine("    public partial class " + entityName.ToCamelCase() + " : Entity");
                sw.WriteLine("    {");
                sw.WriteLine("");
                sw.WriteLine("        public " + entityName.ToCamelCase() + "()");
                sw.WriteLine("        {");
                sw.WriteLine("            _entityName = \"" + entityName + "\";");
                sw.WriteLine("            _db = Context.db;");
                sw.WriteLine("            Default();");
                sw.WriteLine("        }");
                sw.WriteLine("");

                Dictionary<string, Field> _fields = new(fields[entityName]);
                /*if (!_fields.ContainsKey(Config.id))
                {
                    Field _Id = new Field()
                    {
                        entityName = entityName,
                        name = Config.id,
                        type = "string"
                    };
                    _fields[Config.id] = _Id;
                }*/

                foreach (var (fieldName, field) in _fields)
                {
                    sw.WriteLine("        #region " + fieldName + "");
                    sw.WriteLine("        protected " + field.type + "? _" + fieldName + " = null;");
                    sw.WriteLine("        public " + field.type + "? " + fieldName);
                    sw.WriteLine("        {");
                    sw.WriteLine("            get { return _" + fieldName + "; }");
                    sw.WriteLine("            set { if( _" + fieldName + " != value) { _" + fieldName + " = value; NotifyPropertyChanged(nameof(" + fieldName + ")); } }");
                    sw.WriteLine("        }");
                    sw.WriteLine("        #endregion");
                    sw.WriteLine("");
                }

                #region atributos fk
                foreach (var (fieldId, relation) in entities[entityName].relations)
                {
                    if (!relation.parentId.IsNoE())
                        continue;

                    string refFieldName = (relation.fieldName.Contains(relation.refEntityName)) ? "" : relation.fieldName + "_";

                    if (entities[entityName].unique.Contains(relation.fieldName)) //o
                    {
                        sw.WriteLine("        #region " + relation.fieldName + " (fk " + entityName + "." + relation.fieldName + " _o:o " + relation.refEntityName + ".id)");
                        sw.WriteLine("        protected " + relation.refEntityName.ToCamelCase() + "? _" + relation.fieldName + "_ = null;");
                        sw.WriteLine("        public " + relation.refEntityName.ToCamelCase() + "? " + relation.fieldName + "_");
                        sw.WriteLine("        {");
                        sw.WriteLine("            get { return _" + relation.fieldName + "_; }");
                        sw.WriteLine("            set {");
                        sw.WriteLine("                if(_" + relation.fieldName + "_ != null)");
                        sw.WriteLine("                    _" + relation.fieldName + "_!." + entityName.ToCamelCase() + "_" + refFieldName + " = null;");
                        sw.WriteLine("");
                        sw.WriteLine("                _" + relation.fieldName + "_ = value;");
                        sw.WriteLine("");
                        sw.WriteLine("                if(value != null)");
                        sw.WriteLine("                {");
                        sw.WriteLine("                    _" + relation.fieldName + "_!." + entityName.ToCamelCase() + "_" + refFieldName + " = this;");
                        sw.WriteLine("                    " + relation.fieldName + " = value." + this.Config.id + ";");
                        sw.WriteLine("                }");
                        sw.WriteLine("                else");
                        sw.WriteLine("                {");
                        sw.WriteLine("                    " + relation.fieldName + " = null;");
                        sw.WriteLine("                }");
                        sw.WriteLine("                NotifyPropertyChanged(nameof(" + relation.fieldName + "_));");
                        sw.WriteLine("            }");
                        sw.WriteLine("        }");
                        sw.WriteLine("        #endregion");
                        sw.WriteLine("");
                    }
                    else //m
                    {
                        sw.WriteLine("        #region " + relation.fieldName + " (fk " + entityName + "." + relation.fieldName + " _m:o " + relation.refEntityName + ".id)");
                        sw.WriteLine("        protected " + relation.refEntityName.ToCamelCase() + "? _" + relation.fieldName + "_ = null;");
                        sw.WriteLine("        public " + relation.refEntityName.ToCamelCase() + "? " + relation.fieldName + "_");
                        sw.WriteLine("        {");
                        sw.WriteLine("            get { return _" + relation.fieldName + "_; }");
                        sw.WriteLine("            set {");
                        sw.WriteLine("                if( _" + relation.fieldName + "_ != null && AutoAddToCollection)");
                        sw.WriteLine("                    _" + relation.fieldName + "_!." + entityName.ToCamelCase() + "_" + refFieldName + ".Remove(this);");
                        sw.WriteLine("");
                        sw.WriteLine("                _" + relation.fieldName + "_ = value;");
                        sw.WriteLine("");
                        sw.WriteLine("                if(value != null)");
                        sw.WriteLine("                {");
                        sw.WriteLine("                    " + relation.fieldName + " = value." + this.Config.id + ";");
                        sw.WriteLine("                    if(AutoAddToCollection && !_" + relation.fieldName + "_!." + entityName.ToCamelCase() + "_" + refFieldName + ".Contains(this))");
                        sw.WriteLine("                        _" + relation.fieldName + "_!." + entityName.ToCamelCase() + "_" + refFieldName + ".Add(this);");
                        sw.WriteLine("                }");
                        sw.WriteLine("                else");
                        sw.WriteLine("                {");
                        sw.WriteLine("                    " + relation.fieldName + " = null;");
                        sw.WriteLine("                }");
                        sw.WriteLine("                NotifyPropertyChanged(nameof(" + relation.fieldName + "_));");
                        sw.WriteLine("            }");
                        sw.WriteLine("        }");
                        sw.WriteLine("        #endregion");
                        sw.WriteLine("");
                    }
                }
                #endregion

                #region atributos ref
                foreach (var rel in RelationsRef(entityName))
                {
                    string fn = (rel.fieldName.Contains(rel.refEntityName)) ? "" : rel.fieldName + "_";

                    if (entities[rel.entityName].unique.Contains(rel.fieldName))
                    {
                        sw.WriteLine("        #region " + rel.entityName.ToCamelCase() + "_" + fn + "(ref " + rel.entityName + "." + rel.fieldName + " _o:o " + rel.refEntityName + ".id)");
                        sw.WriteLine("        protected " + rel.entityName.ToCamelCase() + "? _" + rel.entityName.ToCamelCase() + "_" + fn + " = null;");
                        sw.WriteLine("        public " + rel.entityName.ToCamelCase() + "? " + rel.entityName.ToCamelCase() + "_" + fn);
                        sw.WriteLine("        {");
                        sw.WriteLine("            get { return _" + rel.entityName.ToCamelCase() + "_" + fn + "; }");
                        sw.WriteLine("            set { _" + rel.entityName.ToCamelCase() + "_" + fn + " = value; NotifyPropertyChanged(nameof(" + rel.entityName.ToCamelCase() + "_" + fn + ")); }");
                        sw.WriteLine("        }");
                        sw.WriteLine("        #endregion");
                        sw.WriteLine("");
                    }
                    else
                    {
                        sw.WriteLine("        #region " + rel.entityName.ToCamelCase() + "_" + fn + " (ref " + rel.entityName + "." + rel.fieldName + " _m:o " + rel.refEntityName + ".id)");
                        sw.WriteLine("        public ObservableCollection<" + rel.entityName.ToCamelCase() + "> " + rel.entityName.ToCamelCase() + "_" + fn + " { get; set; } = new ();");
                        sw.WriteLine("        #endregion");
                        sw.WriteLine("");
                    }
                    
                }
                #endregion

                #region fin clase, fin namespace
                sw.WriteLine("    }");
                sw.WriteLine("}");
                #endregion

            }
        }

        protected List<string> DefineId(EntityMetadata entity)
        {
            if (entity.pk.Count == 1)
                return entity.pk;

            foreach (string f in entity.unique)
                if (entity.notNull.Contains(f))
                    return new List<string> { f };



            bool uniqueMultipleFlag = true;
            foreach (List<string> um in entity.uniqueMultiple) { 
                foreach (string f in um)
                    if (!entity.notNull.Contains(f))
                    {
                        uniqueMultipleFlag = false;
                        break;
                    }
                if (uniqueMultipleFlag)
                    return um;

                uniqueMultipleFlag = true;
            }

            if (entity.notNull.Count > 1)
            {
                return entity.notNull;
            }

            return entity.fields;
        }

    }
}

