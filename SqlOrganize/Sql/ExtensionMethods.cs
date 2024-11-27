using Microsoft.Extensions.Caching.Memory;
using Newtonsoft.Json.Linq;
using SqlOrganize.Model;
using SqlOrganize.Sql.Exceptions;
using System.Collections.ObjectModel;
using System.Data.Common;
using System.Net;
using Dapper;

namespace SqlOrganize.Sql
{
    public static class ExtensionMethods
    {

        /// <summary> Armar arbol de valores a partir del resultado de una consulta </summary>
        /// <remarks> Los campos deben estar organizados de la forma fieldId__fieldName para poder identificar las ramas </remarks>
        public static IDictionary<string, object?> ValuesTree(this Db db, IDictionary<string, object?> values, string entityName)
        {
            Dictionary<string, object?> response = new();

            if (values.ContainsKey("Label"))
                response["Label"] = values["Label"];

            foreach (string fieldName in db.FieldNames(entityName))
            {
                if (values.ContainsKey(fieldName))
                    response[fieldName] = values[fieldName];

            }

            db.ValuesTreeRecursive(values, db.Entity(entityName).tree, response);

            return response;
        }

        /// <summary> Metodo recursivo para armar arbol de valores a partir del resultado de una consulta </summary>
        /// <remarks> Los campos deben estar organizados de la forma fieldId__fieldName para poder identificar las ramas </remarks>
        public static void ValuesTreeRecursive(this Db db, IDictionary<string, object?> values, IDictionary<string, EntityTree> tree, IDictionary<string, object?> response)
        {
            foreach (var (fieldId, et) in tree)
            {
                if (response.ContainsKey(et.fieldName) && !response[et.fieldName].IsNoE())
                {
                    response[et.fieldName + "_"] = new Dictionary<string, object?>();

                    if (values.ContainsKey(fieldId + db.config.separator + "Label"))
                        (response[et.fieldName + "_"] as Dictionary<string, object?>)!["Label"] = values[fieldId + db.config.separator + "Label"];

                    foreach (string fieldName in db.FieldNames(et.refEntityName))
                    {
                        if (values.ContainsKey(fieldId + db.config.separator + fieldName))
                            (response[et.fieldName + "_"] as Dictionary<string, object?>)![fieldName] = values[fieldId + db.config.separator + fieldName];
                    }

                    if (!et.children.IsNoE())
                        db.ValuesTreeRecursive(values, et.children, (response[et.fieldName + "_"] as Dictionary<string, object?>)!);
                }
            }
        }

        /// <summary>Retorna una lista de los campos principales</summary>
        public static List<string> MainKeys(this Db db, string entityName)
        {
            var entity = db.Entity(entityName);
            List<string> fields = new();
            foreach (string f in entity.unique)
                if (entity.notNull.Contains(f))
                    fields.Add(f);

            bool uniqueMultipleFlag = true;
            foreach (List<string> um in entity.uniqueMultiple)
            {
                foreach (string f in um)
                    if (!entity.notNull.Contains(f))
                    {
                        uniqueMultipleFlag = false;
                        break;
                    }

                if (uniqueMultipleFlag)
                    foreach (var f in um)
                        fields.Add(f);

                uniqueMultipleFlag = true;
            }

            if (fields.IsNoE())
                fields = entity.notNull;

            if (fields.IsNoE())
                fields = entity.fieldNames;

            if (fields.Count() > 1 && fields.Contains(db.config.id))
                fields.Remove(db.config.id);

            return fields;
        }

        //
        public static (string? fieldId, string fieldName, string entityName, object? value) ParentVariables(this Db db, string mainEntityName, IDictionary<string, object?> values, string fieldId)
        {
            object? value;
            string fieldName;
            string entityName = mainEntityName;
            string? newFieldId = null;

            string? parentId = db.Entity(mainEntityName).relations[fieldId!].parentId;
            if (parentId != null)
            {
                //sea por ejemplo alumnoT.personaF (con fieldId alumno) = personaT.id (con fieldId = persona), entones:
                //parentFieldName = personaF
                //value = personaValues.values["id"]
                //fieldId = alumno
                //fieldName = personaF
                //entityName = alumnoT
                string parentFieldName = db.Entity(mainEntityName).relations[fieldId!].fieldName;
                value = values[db.Entity(mainEntityName).relations[fieldId!].refFieldName];
                newFieldId = parentId;
                fieldName = parentFieldName;
                entityName = db.Entity(mainEntityName).relations[parentId].refEntityName;

            }
            else
            {
                fieldName = db.Entity(mainEntityName).relations[fieldId!].fieldName;
                value = values[db.Entity(mainEntityName).relations[fieldId!].refFieldName];
            }

            return (newFieldId, fieldName, entityName, value);
        }

        /// <summary>
        /// Reset _Id
        /// </summary>
        /// <remarks>_Id depende de otros valores de la misma entidad, se reasigna luego de definir el resto de los valores</remarks>
        /// <example>db.Values("entityName").Set(source).Set("_Id", null).Reset("_Id"); //inicializa y reasigna _Id individualmente //<br/>
        /// db.Values("entityName").Set(source).Default().Reset() //inicializa y reasigna _Id conjuntamente</example>
        /// <returns></returns>
        public static void ResetId(this Db db, string entityName, IDictionary<string, object?> values)
        {
            List<string> fieldsId = db.Entity(entityName).id;
            foreach (string fieldName in fieldsId)
                if (!values.ContainsKey(fieldName) || values[fieldName].IsNoE())
                    return; //no se reasigna si no esta definido o si es distinto de null

            if (fieldsId.Count == 1)
            {
                values["_Id"] = values[fieldsId[0]].ToString();
                return;
            }

            List<string> valuesId = new();
            foreach (string fieldName in fieldsId)
                valuesId.Add(values[fieldName].ToString()!);

            values["_Id"] = String.Join(db.config.concatString, valuesId);
        }


        /// <summary>Formato SQL</summary>
        /// <remarks>La conversion de formato es realizada directamente por la libreria SQL, pero para ciertos casos puede ser necesario una transformación directa</remarks>
        public static object SqlValue(this Db db, string entityName, string fieldName, object value)
        {
            if (value == null)
                return "null";

            Field field = db.Field(entityName, fieldName);

            switch (field.dataType) //solo funciona para tipos especificos, para mapear correctamente deberia almacenarse en field, el tipo original sql.
            {
                case "varchar":
                    return "'" + (string)value + "'";

                case "datetime": //puede que no funcione correctamente, es necesario almacenar el tipo original sql
                    return "'" + ((DateTime)value).ToString("u");

                default:
                    return value;

            }

        }

    }
}
