using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Model
{
    public class Data_dia : INotifyPropertyChanged, IDataErrorInfo
    {

        public bool Validate = false;

        public Data_dia ()
        {
            Initialize();
        }

        public Data_dia(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (string?)ContainerApp.db.Values("dia").Default("id").Get("id");
                break;
            }
        }

        public string? Label { get; set; }

        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected short? _numero = null;
        public short? numero
        {
            get { return _numero; }
            set { _numero = value; NotifyPropertyChanged(); }
        }
        protected string? _dia = null;
        public string? dia
        {
            get { return _dia; }
            set { _dia = value; NotifyPropertyChanged(); }
        }
        public event PropertyChangedEventHandler? PropertyChanged;
        protected void NotifyPropertyChanged([System.Runtime.CompilerServices.CallerMemberName] String propertyName = "")
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
        public string Error
        {
            get
            {
                PropertyInfo[] properties = this.GetType().GetProperties();

                List<string> errors = new ();
                foreach (PropertyInfo property in properties)
                    if (this[property.Name] != "")
                    {
                        NotifyPropertyChanged(property.Name);
                        errors.Add(this[property.Name]);
                    }

                if(errors.Count > 0)
                    return String.Join(" - ", errors.ToArray());

                return "";
            }
        }

        public string this[string columnName]
        {
            get
            {
                if (!Validate)
                    return "";

                // If there's no error, empty string gets returned
                return ValidateField(columnName);
            }
        }

        protected virtual string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "numero":
                    if (_numero == null)
                        return "Debe completar valor.";
                    if (!_numero.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("dia").Where("$numero = @0").Parameters(_numero).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row["id"].ToString()))
                            return "Valor existente.";
                    }
                    return "";

                case "dia":
                    if (_dia == null)
                        return "Debe completar valor.";
                    if (!_dia.IsNullOrEmptyOrDbNull()) {
                        var row = ContainerApp.db.Query("dia").Where("$dia = @0").Parameters(_dia).DictCache();
                        if (!row.IsNullOrEmpty() && !_id.ToString().Equals(row["id"].ToString()))
                            return "Valor existente.";
                    }
                    return "";

            }
        }
    }
}
