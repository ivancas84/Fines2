using System.Reflection;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Mapear campos para que sean entendidos por el motor de base de datos
    /// </summary>
    /// <example>
    /// Ejemplo de subclase
    /// * class ComisionMapping extiende Mapping
    ///   Metodo numero()
    ///     return " CONCAT("+this.pf()+"this.numero, "+this.pt()+".division) "
    /// </example>
    public class EntityMapping : EntityFieldId
    {
        public EntityMapping(Db db, string entityName, string? fieldId) : base(db, entityName, fieldId)
        {
        }

        
        /// <summary> Valor de identificador (el identificador puede ser la pk y si no existe pk lo define a partir de sus campos) </summary>
        /// <remarks>  Para sql server se debe aplicar trim porque agrega espacios adicionales. Cuidado de no generar strings mayores a 255! </remarks>
        public string _Id()
        {
            List<string> map_ = new();
            
            foreach (string f in db.Entity(entityName).pk)
                map_.Add(Map(f));

            if (map_.Count == 1)
            {
                Field field = db.Field(entityName, map_[0]);
                return CastString(field.dataType, map_[0]);
            }

            return "TRIM(CAST(CONCAT_WS('"+ db.config.concatString + "'," + String.Join(",", map_) + ") AS varchar(255)))";
        }


        /// <summary> Aplicar sql para hacer un cast a string, dependiendo del tipo. </summary>
        /// <remarks> Esta implementada para sql server, cada motor debe tener su propio CastString. </remarks>
        protected string CastString(string dataType, string fieldName)
        {
            switch (dataType.ToLower())
            {
                case "date":
                    return "CONVERT(VARCHAR, " + fieldName + ", 105)";

                case "datetime": case "smalldatetime":
                case "datetime2":
                case "datetimeoffset":
                    return "CONVERT(VARCHAR, " + fieldName + ", 120)";

                case "time":
                    return "CONVERT(VARCHAR, " + fieldName + ", 108)";

                case "int":
                case "smallint":
                case "tinyint":
                    return "CAST(" + fieldName + " AS VARCHAR(10))";

                case "bigint":
                case "decimal":
                case "numeric":
                case "float":
                case "real":
                case "money":
                case "smallmoney":
                    return "CAST(" + fieldName + " AS VARCHAR(20))";

                case "uniqueidentifier":
                    return "CAST(" + fieldName + " AS VARCHAR(36))";

                default:
                    return fieldName;
            }
        }

        /// <summary>
        /// Mapeo de un fieldName
        /// </summary>
        /// <example>
        /// Db.mapping("persona").map("nombre") //correcto se mapea sin fieldId       
        /// Db.mapping("persona", "persona").map("nombre") //correcto se mapea con fieldId
        /// Db.mapping("persona", "persona").map("fecha_nacimiento.str") //correcto utiliza funcion especial para aplicar cast a str
        /// Db.mapping("alumno").map("persona__nombre") //error, la traduccion de fieldId se hace en otro nivel
        /// </example>
        public string Map(string fieldName)
        {
            return Pt() + "." + fieldName;
        }

    }
}
