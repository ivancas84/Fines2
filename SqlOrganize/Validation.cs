﻿using Utils;

namespace SqlOrganize
{
    /*
    Validacion basica
    */
    public class Validation
    {

        public object? value { get; set; } //valor a validar;

        public List<(string msg, string? type)> errors { get; set; } = new(); //log de errores

        public Validation(object? _value) {
            value = _value;
        }

        public Validation Required()
        {
            if (value.IsNullOrEmptyOrDbNull()) {
                errors.Add(("Valor nulo o vacío", "required"));
            }
            return this;
        }

        public Validation Type(string type)
        {
            switch (type)
            {
                case "string":
                    if(!value.IsNullOrEmptyOrDbNull() && value is not String)
                        errors.Add(("Valor no texto", "type"));
                break;

                case "integer":
                case "int":
                    if (!value.IsNullOrEmptyOrDbNull() && value is not int)
                        errors.Add(("Valor no entero", "type"));

                break;
            }
            return this;
        }

        public bool HasErrors() {
            return (errors.IsNullOrEmpty()) ? false : true;
        }

        public Validation Clear()
        {
            errors.Clear();
            return this;
        }

    }
}
