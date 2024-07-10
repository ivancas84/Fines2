using System.Globalization;
using System.Text;
using SqlOrganize.ValueTypesUtils;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class PersonaValues : EntityValues
    {

        public PersonaValues(Db db, string entityName, string? fieldId = null) : base(db, entityName, fieldId)
        {
            fieldNames.Add("cuil_dni");
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

        public override IDictionary<string, object?> Compare(CompareParams cp)
        {
            var response = base.Compare(cp);
            if (!response.Any())
                return response;
            if (response.ContainsKey("nombres") && !response["nombres"].IsNoE())
            {
                IEnumerable<string> nombres = response["nombres"].ToString()!.Trim().RemoveMultipleSpaces().Normalize(NormalizationForm.FormD).RemoveDiacritics().Split(" ");

                foreach (string nom in nombres)
                {
                    int ll = (nom.Length >= 3) ? 3 : nom.Length;
                    string n = nom.Substring(0, ll).Normalize(NormalizationForm.FormD).RemoveDiacritics();

                    if (
                        (
                            values.ContainsKey("nombres")
                            && !values["nombres"].IsNoE()
                            && values["nombres"].ToString().Contains(n, StringComparison.OrdinalIgnoreCase)
                        )
                        ||
                        (
                            values.ContainsKey("apellidos")
                            && !values["apellidos"].IsNoE()
                            && values["apellidos"].ToString().Contains(n, StringComparison.OrdinalIgnoreCase)
                        )
                    )
                    {
                        response.Remove("nombres");
                        response.Remove("apellidos");
                        break;
                    }
                }
            }

            if (response.ContainsKey("apellidos") && !response["apellidos"].IsNoE())
            {
                IEnumerable<string> apellidos = response["apellidos"].ToString()!.Trim().RemoveMultipleSpaces().Normalize(NormalizationForm.FormD).RemoveDiacritics().Split(" ");

                /// string source = "olá mundo";
                /// string substring = "ola";
                /// // Normalize and remove diacritics from both strings
                /// string processedSource = RemoveDiacritics(source.Normalize(NormalizationForm.FormD));
                /// string processedSubstring = RemoveDiacritics(substring.Normalize(NormalizationForm.FormD));
                /// // Check if the processed source string contains the processed substring
                /// bool contains = processedSource.Contains(processedSubstring, StringComparison.OrdinalIgnoreCase);
                /// 
                /// Console.WriteLine($"Does \"{source}\" contain \"{substring}\" (ignoring accents)? {contains}");</example>


                foreach (string ape in apellidos)
                {
                    int ll = (ape.Length >= 3) ? 3 : ape.Length;

                    string a = ape.Substring(0, ll);

                    if (
                        (
                            values.ContainsKey("nombres")
                            && !values["nombres"].IsNoE()
                            && values["nombres"].ToString().Normalize(NormalizationForm.FormD).RemoveDiacritics().Contains(a, StringComparison.OrdinalIgnoreCase)
                        )
                        ||
                        (
                            values.ContainsKey("apellidos")
                            && !values["apellidos"].IsNoE()
                            && values["apellidos"].ToString().Normalize(NormalizationForm.FormD).RemoveDiacritics().Contains(a, StringComparison.OrdinalIgnoreCase)
                        )
                    )
                    {
                        response.Remove("apellidos");
                        response.Remove("nombres");
                        break;
                    }
                }
            }

            if (
                response.ContainsKey("fecha_nacimiento")
                && !response["fecha_nacimiento"].IsNoE()
                && values.ContainsKey("fecha_nacimiento")
                && !values["fecha_nacimiento"].IsNoE()
            )
            {
                var f1 = (DateTime)response["fecha_nacimiento"];
                var f2 = (DateTime)values["fecha_nacimiento"];

                if (f1.ToString("dmy").Equals(f2.ToString("dmy")))
                    response.Remove("fecha_nacimiento");

            }

            return response;
        }

        public void Sset_telefono(object? value)
        {
            if (value == null)
            {
                values["telefono"] = null;
                return;
            }

            values["telefono"] = value.ToString().CleanStringOfNonDigits();
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

        public void Reset_dia_nacimiento()
        {
            DateTime? fechaNacimiento = (DateTime?)GetOrNull("fecha_nacimiento");

            if (!fechaNacimiento.IsNoE() && GetOrNull("dia_nacimiento").IsNoE())
                Set("dia_nacimiento", ((DateTime)fechaNacimiento!).Day);
        }

        public void Reset_mes_nacimiento()
        {
            DateTime? fechaNacimiento = (DateTime?)GetOrNull("fecha_nacimiento");

            if (!fechaNacimiento.IsNoE() && GetOrNull("mes_nacimiento").IsNoE())
                Set("mes_nacimiento", ((DateTime)fechaNacimiento!).Month);
        }

        public void Reset_anio_nacimiento()
        {
            DateTime? fechaNacimiento = (DateTime?)GetOrNull("fecha_nacimiento");

            if (!fechaNacimiento.IsNoE() && GetOrNull("anio_nacimiento").IsNoE())
                Set("anio_nacimiento", ((DateTime)fechaNacimiento!).Year);
        }

        public void Reset_numero_documento()
        {
            string? numero_documento = (string?)GetOrNull("numero_documento");

            if (!numero_documento.IsNoE())
                Set("numero_documento", ((string)numero_documento!).CleanStringOfNonDigits());
        }

        public void Reset_genero()
        {
            byte? sexo = (byte?)GetOrNull("sexo");

            if (!sexo.IsNoE() && GetOrNull("genero").IsNoE())
            {
                if (sexo.Equals(1))
                    Set("genero", "Masculino");
                else if (sexo.Equals(2))
                    Set("genero", "Femenino");
                else
                    Set("genero", "Otro");
            }
        }

        public void Reset_sexo()
        {
            string? genero = (string?)GetOrNull("genero");

            if (!genero.IsNoE() && GetOrNull("sexo").IsNoE())
            {
                if (((string)genero!).ToLower().Contains("mas"))
                    Set("sexo", 1);

                else if (((string)genero!).ToLower().Contains("fem"))
                    Set("sexo", 2);

                else
                    Set("sexo", 3);
            }
        }



        public void Reset_cuil1()
        {
            if (
                values.ContainsKey("cuil1") 
                && values.ContainsKey("cuil2") 
                && values.ContainsKey("numero_documento")
                && (!values["cuil1"].IsNoE() && !values["cuil1"].ToString().Equals("0") && values["cuil1"].ToString()!.Length == 2)
                && (!values["cuil2"].IsNoE() && !values["cuil2"].ToString().Equals("0") && values["cuil2"].ToString()!.Length == 1)
                && (!values["numero_documento"].IsNoE() && 
                (
                    values["numero_documento"].ToString()!.Length == 8
                    || values["numero_documento"]!.ToString().Length == 7
                ))
            )
            {
                values["cuil"] = values["cuil1"]!.ToString() + values["numero_documento"]!.ToString() + values["cuil2"]!.ToString();

            }
            
        }

        public void Sset_fecha_nacimiento(object? value)
        {
            if (value.IsNoE())
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

        public void Sset_cuil1(object? value)
        {

            if (value.IsNoE())
            { 
                values["cuil1"] = null;
                return;

            }
            bool success = int.TryParse(value!.ToString(), out int cuil1);

            values["cuil1"] = (success && cuil1 >= 20 && cuil1 <= 30) ? cuil1 : null;
        }

        public void Sset_cuil2(object? value)
        {

            if (value.IsNoE())
            {
                values["cuil2"] = null;
                return;
            }

            bool success = int.TryParse(value!.ToString(), out int cuil1);

            values["cuil2"] = (success && cuil1 >= 0 && cuil1 <= 9) ? cuil1 : null;
        }

        /// <summary>
        /// Verifica el valor del source (this) con otro values para determinar si debe actualizar. 
        /// Si debe actualizar completa
        /// </summary>
        public bool CompareToUpdate(string fieldName, PersonaValues personaVal, string? pfContains = null)
        {
            if (GetOrNull(fieldName).IsNoE() 
                || (!pfContains.IsNoE() 
                && Get(fieldName).ToString()!.Contains(pfContains!)))
                return false;

            if (personaVal.GetOrNull(fieldName).IsNoE())
            {
                return true;
            }

            return false;

        }
    }
}