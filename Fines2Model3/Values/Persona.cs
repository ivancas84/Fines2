using System.Globalization;
using System.Text;
using Mysqlx.Crud;
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


        public override object? ValueField(string fieldName, object? value)
        {
            switch (fieldName)
            {
                case "cuil1":
                    return Value_cuil1(value);
                case "cuil2":
                    return Value_cuil2(value);
                case "fecha_nacimiento":
                    return Value_fecha_nacimiento(value);
                case "genero":
                    return Value_genero(value);
                case "numero_documento":
                    return Value_numero_documento(value);
                case "cuil":
                    return Value_cuil(value);
                default:
                    return base.ValueField(fieldName, value);

            }
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

        public string? Value_telefono(object? value)
        {
            if (value == null)
                return null;

            return value.ToString().CleanStringOfNonDigits();
        }

        public string? Value_numero_documento(object? value)
        {
            (string? dni, string? cuil) = CuilDni(value);
            if (dni == null)
                return null;

            return dni;
        }

        public string? Value_cuil(object? value)
        {
            (string? dni, string? cuil) = CuilDni(value);
            if (cuil == null)
                return null;

            return cuil;
        }


        public static (string? dni, string? cuil) CuilDni(object? value) {

            if (value == null)
                return (null, null);

            string numbers = value!.ToString()!.CleanStringOfNonDigits()!;

            if (numbers.Length == 11) //longitud de cuil
                return (numbers.Substring(2, 8), numbers);

            if (numbers.Length == 8 || numbers.Length == 7) //longitud de dni
                return (numbers, null);

            throw new Exception("Error al definir CUIL o DNI");
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

        public DateTime? Value_fecha_nacimiento(object? value)
        {
            if (value.IsNoE())
                return null;
        
            if (value is DateTime)
                return (DateTime)value;
            
            try
            {
                return DateTime.Parse(value.ToString()!);
            } 
            catch(Exception)
            {
                return null;
            }
        }

        public string? Value_genero(object? value)
        {
            if (value.IsNoE())
                return null;

            if (((string)value!)!.Contains("B"))
                return "No binario";

            if(((string)value!)!.Contains("F"))
                return "Femenino";

            if (((string)value!)!.Contains("M"))
                return "Masculino";

            return null;
        }

        public int? Value_cuil1(object? value)
        {

            if (value.IsNoE())
                return null;

            bool success = int.TryParse(value!.ToString(), out int cuil1);

            return (success && cuil1 >= 20 && cuil1 <= 30) ? cuil1 : null;
        }

        public int? Value_cuil2(object? value)
        {

            if (value.IsNoE())
                return null;

            bool success = int.TryParse(value!.ToString(), out int cuil1);

            return (success && cuil1 >= 0 && cuil1 <= 9) ? cuil1 : null;
        }

        /// <summary>
        /// Verifica el valor del source (this) con otro values para determinar si debe actualizar. 
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


        public override T GetData<T>()
        {
            var obj = db.Data<T>(Values());
            if(obj is Data_persona p)
            {
                p.Label = p.nombres + " " + p.apellidos + " " + p.numero_documento;
            }
            if (Logging.HasLogs())
                obj.Msg += Logging.ToString();

            return obj;
        }

        /*public EntityPersist PersistCompare()
        {            
            var personaExistenteData = db.PersonaDniSql(Get("numero_documento")).Dict();
            if (personaExistenteData.IsNoE())
            {
                if (!Default().Reset().Check())
                    throw new Exception("Error al insertar");

                logging.AddLog("persona", "Persona insertada", "insert", Logging.Level.Info);

                return this.Insert();
            } else
            {
                var personaExistenteVal = db.Values("persona").Set(personaExistenteData!);

                CompareParams compare = new()
                {
                    fieldsToCompare = new List<string> { "nombres", "apellidos" },
                    val = personaExistenteVal
                };

                var response = Compare(compare);

                if (!response.IsNoE())
                    throw new Exception("Comparacion de persona diferente: " + compare.val.ToStringFields("nombres", "apellidos"));

                Set("id", personaExistenteVal.Get("id")!);
                if (!Reset().Check())
                    throw new Exception("Error al actualizar");

                logging.AddLog("persona", "Persona actualizada", "update", Logging.Level.Info);

                return this.Update();
            }*/
        }
    }
}