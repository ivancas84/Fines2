using Newtonsoft.Json;
using SqlOrganize.Sql;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Model
{
    public class BuildSchema
    {

        /// <summary> Definir datos del esquema y arbol de relaciones </summary>
        public BuildSchema(Config config, ISchema schema = null)
        {
            if (!Directory.Exists(config.dataClassesPath))
                Directory.CreateDirectory(config.dataClassesPath);

            foreach (var (entityName, entity) in schema.entities)
            {
                using StreamWriter sw = File.CreateText(config.dataClassesPath + entityName.ToCamelCase() + ".cs");
                sw.WriteLine("#nullable enable");
                sw.WriteLine("using System;");
                sw.WriteLine("using System.ComponentModel;");
                sw.WriteLine("using System.Collections.Generic;");
                sw.WriteLine("using System.Collections.ObjectModel;");
                sw.WriteLine("using System.Text.RegularExpressions;");
                sw.WriteLine("using Dapper;");
                sw.WriteLine("using System.Data;");
                sw.WriteLine("");
                sw.WriteLine("namespace SqlOrganize.Sql." + config.dataClassesNamespace);
                sw.WriteLine("{");
                sw.WriteLine("    public partial class " + entityName.ToCamelCase() + " : Entity");
                sw.WriteLine("    {");
                sw.WriteLine("");
                sw.WriteLine("        public " + entityName.ToCamelCase() + "()");
                sw.WriteLine("        {");
                sw.WriteLine("            _entityName = \"" + entityName + "\";");
                sw.WriteLine("            _db = Context.db;");
                sw.WriteLine("            Default();");
                foreach (var (id, rref) in entity.om)
                    sw.WriteLine("            " + id + ".CollectionChanged += " + id + "CollectionChanged;");
                sw.WriteLine("        }");
                sw.WriteLine("");


                sw.WriteLine("        #region CollectionChanged");
                foreach (var (id, rref) in entity.om)
                {
                    sw.WriteLine("        private void " + id + "CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)");
                    sw.WriteLine("        {");
                    sw.WriteLine("            if ( e.NewItems != null )");
                    sw.WriteLine("                foreach (" + rref.entityName.ToCamelCase() + " obj in e.NewItems)");
                    sw.WriteLine("                    if(obj." + rref.fieldName + "_ != this)");
                    sw.WriteLine("                        obj." + rref.fieldName + "_ = this;");
                    sw.WriteLine("        }");
                }
                sw.WriteLine("        #endregion");
                sw.WriteLine("");

                /*if (!_fields.ContainsKey(config.id))
                {
                    Field _Id = new Field()
                    {
                        entityName = entityName,
                        name = config.id,
                        type = "string"
                    };
                    _fields[config.id] = _Id;
                }*/

                foreach (var (fieldName, field) in entity.fields)
                {
                    sw.WriteLine("        #region " + fieldName + "");
                    sw.WriteLine("        protected " + field.type + "? _" + fieldName + " = null;");
                    sw.WriteLine("        public " + field.type + "? " + fieldName);
                    sw.WriteLine("        {");
                    sw.WriteLine("            get { return _" + fieldName + "; }");
                    sw.WriteLine("            set {");
                    sw.WriteLine("                if( _" + fieldName + " != value)");
                    sw.WriteLine("                {");
                    sw.WriteLine("                    _" + fieldName + " = value; NotifyPropertyChanged(nameof(" + fieldName + "));");
                    if (entity.fk.Contains(fieldName))
                    {
                        Sql.Field fk = entity.fields[fieldName];

                        sw.WriteLine("                    //desactivado hasta implementar cache");
                        sw.WriteLine("                    //if (_" + fieldName + ".HasValue && (" + fieldName + "_.IsNoE() || !" + fieldName + "_!.Get(db.config.id).ToString()!.Equals(_" + fieldName + ".Value.ToString())))");
                        sw.WriteLine("                    //    " + fieldName + "_ = CreateFromId<" + fk.refEntityName!.ToCamelCase() + ">(_" + fieldName + ");");
                        sw.WriteLine("                    //else if(_" + fieldName + ".IsNoE())");
                        sw.WriteLine("                    //    " + fieldName + "_ = null;");
                    }
                    sw.WriteLine("                }");
                    sw.WriteLine("            }");
                    sw.WriteLine("        }");
                    sw.WriteLine("        #endregion");
                    sw.WriteLine("");
                }

                #region atributos fk
                foreach (var (fieldId, relation) in entity.relations)
                {
                    if (!relation.parentId.IsNoE() || !entity.fieldNames.Contains(relation.fieldName))
                        continue;

                    string refFieldName = (relation.fieldName.Contains(relation.refEntityName)) ? "" : relation.fieldName + "_";

                    if (entity.unique.Contains(relation.fieldName)) //o
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
                        sw.WriteLine("                    " + relation.fieldName + " = value." + config.id + ";");
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
                        sw.WriteLine("                if ( _" + relation.fieldName + "_ != value)");
                        sw.WriteLine("                {");
                        sw.WriteLine("                    _" + relation.fieldName + "_ = value;");
                        sw.WriteLine("                    if(value != null)");
                        sw.WriteLine("                        " + relation.fieldName + " = value." + config.id + ";");
                        sw.WriteLine("                    else");
                        sw.WriteLine("                        " + relation.fieldName + " = null;");
                        sw.WriteLine("                    NotifyPropertyChanged(nameof(" + relation.fieldName + "_));");
                        sw.WriteLine("                }");
                        sw.WriteLine("            }");
                        sw.WriteLine("        }");
                        sw.WriteLine("        #endregion");
                        sw.WriteLine("");
                    }
                }
                #endregion

                #region atributos oo
                foreach (var (id, rref) in entity.oo)
                {

                    sw.WriteLine("        #region " + id + "(ref " + rref.entityName + "." + rref.fieldName + " _o:o " + entityName + ".id)");
                    sw.WriteLine("        protected " + rref.entityName.ToCamelCase() + "? _" + id + " = null;");
                    sw.WriteLine("        public " + rref.entityName.ToCamelCase() + "? " + id);
                    sw.WriteLine("        {");
                    sw.WriteLine("            get { return _" + id + "; }");
                    sw.WriteLine("            set { _" + id + " = value; NotifyPropertyChanged(nameof(" + id + ")); }");
                    sw.WriteLine("        }");
                    sw.WriteLine("        #endregion");
                    sw.WriteLine("");
                }
                #endregion

                #region atributos om
                foreach (var (id, rref) in entity.om)
                {

                    sw.WriteLine("        #region " + id + " (ref " + rref.entityName + "." + rref.fieldName + " _m:o " + entityName + ".id)");
                    sw.WriteLine("        protected ObservableCollection<" + rref.entityName.ToCamelCase() + "> _" + id + " = new ();");
                    sw.WriteLine("        public ObservableCollection<" + rref.entityName.ToCamelCase() + "> " + id);
                    sw.WriteLine("        {");
                    sw.WriteLine("            get { return _" + id + "; }");
                    sw.WriteLine("            set { if( _" + id + " != value) { _" + id + " = value; NotifyPropertyChanged(nameof(" + id + ")); } }");
                    sw.WriteLine("        }");
                    sw.WriteLine("        #endregion");
                    sw.WriteLine("");
                }
                #endregion

                #region Metodo QueryDapper
                List<string> classes = new();
                classes.Add(entityName.ToCamelCase());
                int i = 0;
                foreach (var (fieldId, relation) in entity.relations)
                {
                    if (i == 6)
                        break;
                    classes.Add(relation.refEntityName.ToCamelCase());
                    i++;
                }

                List<string> _classes = new(classes);
                if(classes.Count() > 1)
                    _classes.Add(entityName.ToCamelCase());

                List<string> instances = new();
                instances.Add("main");
                i = 0;
                foreach (var (fieldId, relation) in entity.relations)
                {
                    if (i == 6)
                        break;
                    instances.Add(fieldId);
                    i++;
                }



                sw.WriteLine("        public static IEnumerable<" + entityName.ToCamelCase() + "> QueryDapper(IDbConnection connection, string sql, object? parameters = null)");
                sw.WriteLine("        {");
                sw.WriteLine("            return connection.Query<" + String.Join(", ", _classes) + ">(");
                sw.WriteLine("                sql,");
                if(classes.Count() > 1)
                { 
                    sw.WriteLine("                (" + String.Join(", ", instances) + ") =>");
                    sw.WriteLine("                {");
                    i = 0;
                    foreach (var (fieldId, relation) in entity.relations)
                    {
                        if (i == 6)
                            break;

                        if (relation.parentId.IsNoE())
                            sw.WriteLine("                    main." + relation.fieldName + "_ = " + fieldId + ";");

                        else
                            sw.WriteLine("                    if(!" + fieldId + ".IsNoE()) " + relation.parentId + "." + relation.fieldName + "_ = " + fieldId + ";");

                        i++;

                    }

                    sw.WriteLine("                    return main;");
                    sw.WriteLine("                },");
                    sw.WriteLine("                parameters,");
                    sw.WriteLine("                splitOn:\"id\"");
                }
                else
                {
                    sw.WriteLine("                parameters");
                }
                sw.WriteLine("            );");
                sw.WriteLine("        }");
                sw.WriteLine("");
                sw.WriteLine("        public static " + entityName.ToCamelCase() + " CreateFromId_(object id)");
                sw.WriteLine("        {");
                sw.WriteLine("            using (var connection = Context.db.Connection().Open())");
                sw.WriteLine("            {");
                sw.WriteLine("                string sql = Context.db.Sql().ByIdDapper(\"" + entityName + "\");");
                sw.WriteLine("                IEnumerable<" + entityName.ToCamelCase() + "> elements = QueryDapper(connection, sql, new { Id = id });");
                sw.WriteLine("                return elements.ElementAt(0);");
                sw.WriteLine("            }");
                sw.WriteLine("        }");
                #endregion

                #region fin clase, fin namespace
                sw.WriteLine("    }");
                sw.WriteLine("}");
                #endregion

            }
        }
    }
}

