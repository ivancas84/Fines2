using Google.Protobuf.WellKnownTypes;
using SqlOrganize;
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
            fieldNames.Add("cuil_dni");
            fieldNames.Add("cuil1");
            fieldNames.Add("cuil2");
            //fieldNames = new List<string>(db.FieldNames(entityName));


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

        public void Sset_cuil_dni(object? value)
        {
            if (value == null)
            {
                values["cuil"] = null;
                values["numero_documento"] = null;
                return;
            }

            string numbers = value!.ToString()!.CleanStringOfNonDigits()!;

            if (numbers.Length == 11)
            {
                values["cuil"] = numbers;
                values["numero_documento"] = numbers.Substring(2, 8);
            }

            else if (numbers.Length == 8 || numbers.Length == 7)
            {
                values["numero_documento"] = numbers;
            }

            else
            {
                throw new Exception("Error al definir CUIL o DNI");
            }

        }

        public void Reset_cuil1()
        {
            if (
                values.ContainsKey("cuil1") 
                && values.ContainsKey("cuil2") 
                && values.ContainsKey("numero_documento")
                && (!values["cuil1"].IsNullOrEmptyOrDbNull() && !((string)values["cuil1"]!).Equals("0") && ((string)values["cuil1"]!).Length == 2)
                && (!values["cuil2"].IsNullOrEmptyOrDbNull() && !((string)values["cuil2"]!).Equals("0") && ((string)values["cuil2"]!).Length == 1)
                && (!values["numero_documento"].IsNullOrEmptyOrDbNull() && 
                (
                    ((string)values["numero_documento"]!).Length == 8
                    || ((string)values["numero_documento"]!).Length == 7)
                )
            )
            {
                values["cuil"] = values["cuil1"]!.ToString() + values["numero_documento"]!.ToString() + values["cuil2"]!.ToString();

            }
            
        }

        public void Sset_fecha_nacimiento(object? value)
        {
            if (value.IsNullOrEmptyOrDbNull())
                values["fecha_nacimiento"] = null;
        
            else if (value is DateTime)
                values["fecha_nacimiento"] = (DateTime)value;
            else
                try
                {
                    values["fecha_nacimiento"] = DateTime.Parse(value.ToString()!);
                } 
                catch(Exception)
                {
                    values["fecha_nacimiento"] = null;
                }
        }

        public void Sset_genero(object? value)
        {
            values["genero"] = null;

            if (((string)value!)!.Contains("B"))
            {
                values["genero"] = "No binario";
                return;
            }

            if(((string)value!)!.Contains("F"))
            {
                values["genero"] = "Femenino";
                return;
            }

            if (((string)value!)!.Contains("M"))
            {
                values["genero"] = "Masculino";
                return;
            }



        }
    }
}