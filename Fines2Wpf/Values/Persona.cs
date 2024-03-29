﻿using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Reflection.Emit;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.Values
{
    class Persona : EntityValues
    {

        public Persona(Db db, string entityName, string? fieldId = null) : base(db, entityName, fieldId)
        {
        }

        public override string ToString()
        {
            TextInfo myTI = new CultureInfo("es-AR", false).TextInfo;

            string s = "";
            s += (GetOrNull("apellidos")?.ToString() ?? "?").ToUpper();
            s += ", ";
            s += myTI.ToTitleCase(GetOrNull("nombres")?.ToString() ?? "?");
            s += " ";
            s += GetOrNull("numero_documento")?.ToString() ?? "?";
            return s;

        }

      

        /// <summary>
        /// Vuelve a comparar ciertos campos que necesitan verificacion adicional
        /// </summary>
        /// <param name="response"></param>
        /// <returns></returns>
        protected IDictionary<string, object?> Recompare(IDictionary<string, object?> response)
        {
            if (response.ContainsKey("nombres") && !response["nombres"].IsNullOrEmpty())
            {
                IEnumerable<string> nombres = response["nombres"].ToString()!.Trim().ToUpper().RemoveMultipleSpaces().Split(" ");

                foreach (string nom in nombres)
                {
                    string n = nom.Substring(0, 3);

                    if (
                        (
                            values.ContainsKey("nombres")
                            && !values["nombres"].IsNullOrEmpty()
                            && values["nombres"].ToString().ToUpper().Contains(n)
                        )
                        ||
                        (
                            values.ContainsKey("apellidos")
                            && !values["apellidos"].IsNullOrEmpty()
                            && values["apellidos"].ToString().ToUpper().Contains(n)
                        )
                    )
                    {
                        response.Remove("nombres");
                        break;
                    }
                }
            }

            if (response.ContainsKey("apellidos") && !response["apellidos"].IsNullOrEmpty())
            {
                IEnumerable<string> apellidos = response["apellidos"].ToString()!.Trim().ToUpper().RemoveMultipleSpaces().Split(" ");

                foreach (string ape in apellidos)
                {
                    string a = ape.Substring(0, 3);

                    if (
                        (
                            values.ContainsKey("nombres")
                            && !values["nombres"].IsNullOrEmpty()
                            && values["nombres"].ToString().ToUpper().Contains(a)
                        )
                        ||
                        (
                            values.ContainsKey("apellidos")
                            && !values["apellidos"].IsNullOrEmpty()
                            && values["apellidos"].ToString().ToUpper().Contains(a)
                        )
                    )
                    {
                        response.Remove("apellidos");
                        break;
                    }
                }
            }

            if (
                response.ContainsKey("fecha_nacimiento")
                && !response["fecha_nacimiento"].IsNullOrEmptyOrDbNull()
                && values.ContainsKey("fecha_nacimiento")
                && !values["fecha_nacimiento"].IsNullOrEmptyOrDbNull()
            )
            {
                var f1 = (DateTime)response["fecha_nacimiento"];
                var f2 = (DateTime)values["fecha_nacimiento"];

                if (f1.ToString("dmy").Equals(f2.ToString("dmy")))
                    response.Remove("fecha_nacimiento");

            }

            return response;
        }

        public override IDictionary<string, object?> Compare(IDictionary<string, object?> val, IEnumerable<string>? ignoreFields = null, bool ignoreNull = true, bool ignoreNonExistent = true)
        {
            var response = base.Compare(val, ignoreFields, ignoreNull, ignoreNonExistent);
            return Recompare(response);
        }


        public override IDictionary<string, object?> CompareFields(IDictionary<string, object?> val, IEnumerable<string> fieldsToCompare, bool ignoreNull = true, bool ignoreNonExistent = true)
        {
            var response = base.CompareFields(val, fieldsToCompare, ignoreNull, ignoreNonExistent);
            return Recompare(response);
        }
    }
}