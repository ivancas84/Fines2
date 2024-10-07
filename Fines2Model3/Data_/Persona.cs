using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Persona
    {
        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return (apellidos?.ToUpper() ?? "?") + " " + (nombres?.ToTitleCase() ?? "?") + " " + (numero_documento ?? "?");
            }
            set
            {
                _Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }

        public static (string? dni, string? cuil) CuilDni(object? value)
        {

            if (value == null)
                return (null, null);

            string numbers = value!.ToString()!.CleanStringOfNonDigits()!;

            if (numbers.Length == 11) //longitud de cuil
                return (numbers.Substring(2, 8), numbers);

            if (numbers.Length == 8 || numbers.Length == 7) //longitud de dni
                return (numbers, null);

            throw new Exception("Error al definir CUIL o DNI");
        }

        public override void Sset(string fieldName, object? value)
        {
            switch (fieldName)
            {
                case "cuil1":
                    cuil1 = ValueCuil1(value);
                    break;

                case "cuil2":
                    cuil2 = ValueCuil2(value);
                    break;

                case "fecha_nacimiento":
                    fecha_nacimiento = ValueFechaNacimiento(value);
                    break;

                case "genero":
                    genero =  ValueGenero(value);
                    break;
                case "numero_documento":
                    numero_documento = Persona.CuilDni(value).dni;
                    break;
                case "cuil":
                    cuil = Persona.CuilDni(value).cuil;
                    break;
                default:
                    base.Sset(fieldName, value);
                    break;
            }
        }
        public byte? ValueCuil1(object? value)
        {

            if (value.IsNoE())
                return null;

            bool success = byte.TryParse(value!.ToString(), out byte cuil1);

            return (success && cuil1 >= 20 && cuil1 <= 30) ? cuil1 : null;
        }

        public byte? ValueCuil2(object? value)
        {

            if (value.IsNoE())
                return null;

            bool success = byte.TryParse(value!.ToString(), out byte cuil2);

            return (success && cuil2 >= 0 && cuil2 <= 9) ? cuil2 : null;
        }

        public DateTime? ValueFechaNacimiento(object? value)
        {
            if (value.IsNoE())
                return null;

            if (value is DateTime)
                return (DateTime)value;

            try
            {
                return DateTime.Parse(value.ToString()!);
            }
            catch (Exception)
            {
                return null;
            }
        }

        public string? ValueGenero(object? value)
        {
            if (value.IsNoE())
                return null;

            if (((string)value!)!.Contains("B"))
                return "No binario";

            if (((string)value!)!.Contains("F"))
                return "Femenino";

            if (((string)value!)!.Contains("M"))
                return "Masculino";

            return null;
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
                            !this.nombres.IsNoE()
                            && this.nombres!.Contains(n, StringComparison.OrdinalIgnoreCase)
                        )
                        ||
                        (
                            !this.apellidos.IsNoE()
                            && this.apellidos!.Contains(n, StringComparison.OrdinalIgnoreCase)
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
                            !this.nombres.IsNoE()
                            && this.nombres!.Normalize(NormalizationForm.FormD).RemoveDiacritics().Contains(a, StringComparison.OrdinalIgnoreCase)
                        )
                        ||
                        (
                            !this.apellidos.IsNoE()
                            && this.apellidos!.Normalize(NormalizationForm.FormD).RemoveDiacritics().Contains(a, StringComparison.OrdinalIgnoreCase)
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
                && !fecha_nacimiento.IsNoE()
            )
            {
                var f1 = (DateTime)response["fecha_nacimiento"];
                var f2 = (DateTime)fecha_nacimiento;

                if (f1.ToString("dmy").Equals(f2.ToString("dmy")))
                    response.Remove("fecha_nacimiento");

            }

            return response;
        }


        public override void Reset(string fieldName)
        {
            switch (fieldName)
            {
                case "dia_nacimiento":
                    ResetDiaNacimiento(); break;
                case "mes_nacimiento":
                    ResetMesNacimiento(); break;
                case "anio_nacimiento":
                    ResetAnioNacimiento(); break;
                case "numero_documento":
                    ResetNumeroDocumento(); break;
                case "genero":
                    ResetGenero(); break;
                case "sexo":
                    ResetSexo(); break;
                case "cuil":
                    ResetCuil(); break;
                default:
                    base.Reset(fieldName);
                    break;
            }
        }



        public void ResetDiaNacimiento()
        {
            if (!fecha_nacimiento.IsNoE() && dia_nacimiento.IsNoE())
                Set("dia_nacimiento", ((DateTime)fecha_nacimiento!).Day);
        }

        public void ResetMesNacimiento()
        {
            if (!fecha_nacimiento.IsNoE() && dia_nacimiento.IsNoE())
                Set("mes_nacimiento", ((DateTime)fecha_nacimiento!).Month);
        }

        public void ResetAnioNacimiento()
        {
            if (!fecha_nacimiento.IsNoE() && dia_nacimiento.IsNoE())
                Set("anio_nacimiento", ((DateTime)fecha_nacimiento!).Year);
        }

        public void ResetNumeroDocumento()
        {

            if (this.numero_documento.IsNoE() && !this.cuil.IsNoE())
            { //en el caso de que numero_documento este vacio, se intenta agregar desde cuil
                (string? cuil, string? dni) = Persona.CuilDni(this.cuil);
                Set("numero_documento", dni);
            }
            else
            {
                Set("numero_documento", numero_documento.CleanStringOfNonDigits());
            }
        }

        public void ResetGenero()
        {
            if (!sexo.IsNoE() && genero.IsNoE())
            {
                if (sexo.Equals(1))
                    Set("genero", "Masculino");
                else if (sexo.Equals(2))
                    Set("genero", "Femenino");
                else
                    Set("genero", "Otro");
            }
        }

        public void ResetSexo()
        {
            if (!genero.IsNoE() && sexo.IsNoE())
            {
                if (((string)genero!).ToLower().Contains("mas"))
                    Set("sexo", 1);

                else if (((string)genero!).ToLower().Contains("fem"))
                    Set("sexo", 2);

                else
                    Set("sexo", 3);
            }
        }



        public void ResetCuil()
        {
            if (
                cuil.IsNoE() &&
                !cuil1.IsNoE() && !cuil1.ToString().Equals("0") && cuil1.ToString().Length == 2 
                && !cuil2.IsNoE() && !cuil2.ToString().Equals("0") && cuil2.ToString().Length == 1
                && !numero_documento.IsNoE() &&
                (
                    numero_documento!.Length == 8
                    || numero_documento!.Length == 7
                ))
            {
                cuil = cuil1 + numero_documento + cuil2;
            }

        }


    }
}
