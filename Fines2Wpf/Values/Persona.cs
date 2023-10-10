﻿using SqlOrganize;
using System;
using System.Collections.Generic;
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
            string s = "";
            s += GetOrNull("nombres")?.ToString() ?? "?";
            s += " ";
            s += GetOrNull("apellidos")?.ToString() ?? "?";
            s += " ";
            s += GetOrNull("numero_documento")?.ToString() ?? "?";
            return s;

        }
        public override IDictionary<string, object> Compare(IDictionary<string, object> val, IEnumerable<string>? ignoreFields = null, bool ignoreNull = true, bool ignoreNonExistent = true)
        {
            var response = base.Compare(val, ignoreFields, ignoreNull, ignoreNonExistent);

            if (response.ContainsKey("nombres") && !response["nombres"].IsNullOrEmpty())
            {
                IEnumerable<string> nombres = response["nombres"].ToString()!.Trim().RemoveMultipleSpaces().Split(" ");

                foreach (string nom in nombres)
                {
                    string n = nom.Substring(0, 3);

                    if (
                        (
                            values.ContainsKey("nombres")
                            && !values["nombres"].IsNullOrEmpty()
                            && values["nombres"].ToString().Contains(n)
                        )
                        ||
                        (
                            values.ContainsKey("apellidos")
                            && !values["apellidos"].IsNullOrEmpty()
                            && values["apellidos"].ToString().Contains(n)
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
                IEnumerable<string> apellidos = response["apellidos"].ToString()!.Trim().RemoveMultipleSpaces().Split(" ");

                foreach (string ape in apellidos)
                {
                    string a = ape.Substring(0, 3);

                    if (
                        (
                            values.ContainsKey("nombres") 
                            && !values["nombres"].IsNullOrEmpty() 
                            && values["nombres"].ToString().Contains(a)
                        )
                        || 
                        (
                            values.ContainsKey("apellidos")
                            && !values["apellidos"].IsNullOrEmpty()
                            && values["apellidos"].ToString().Contains(a)
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
                && !response["fecha_nacimiento"].IsNullOrEmpty()
                && values.ContainsKey("fecha_nacimiento") 
                && !values["fecha_nacimiento"].IsNullOrEmpty()
            )
            {
                var f1 = (DateTime)response["fecha_nacimiento"];
                var f2 = (DateTime)values["fecha_nacimiento"];

                if (f1.ToString("dmy").Equals(f2.ToString("dmy")))
                    response.Remove("fecha_nacimiento");

            }

            return response;
        }
    }
}