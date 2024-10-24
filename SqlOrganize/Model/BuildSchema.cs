﻿using Newtonsoft.Json;
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
                    sw.WriteLine("            set { if( _" + fieldName + " != value) { _" + fieldName + " = value; NotifyPropertyChanged(nameof(" + fieldName + ")); } }");
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

                #region fin clase, fin namespace
                sw.WriteLine("    }");
                sw.WriteLine("}");
                #endregion

            }
        }
    }
}

