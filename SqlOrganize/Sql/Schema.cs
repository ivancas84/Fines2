using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql
{

    /// <summary>
    /// Configuracion del esquema de la base de datos
    /// </summary>
    public interface ISchema
    {
        /// <summary>
        /// JSON con entidades del modelo
        /// </summary>
        /// <remarks>
        /// Se genera a traves de proyecto ModelOrganize
        /// </remarks>
        /// 
        public Dictionary<string, EntityMetadata> entities { get; }

        /// <summary>
        /// Procesa atributo entities para generar el diccionario de entidades que seran utilizadas durante todo el proyecto
        /// </summary>


    }
}
