﻿using SqlOrganize;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.Generic;

namespace Fines2Wpf.Windows.Comision.ListaComisionesSemestre
{
    public class Comision : Data_comision_r
    {
        protected string? _colorApertura = null;
        public string? colorApertura
        {
            get { return _colorApertura; }
            set { _colorApertura = value; NotifyPropertyChanged(); }
        }

        public new bool? apertura
        {
            get { return _apertura; }
            set { 
                _apertura = value; 
                if(value == true)
                    colorApertura = "green";
                NotifyPropertyChanged(); 
            }
        }

        protected string? _horario = null;
        public string? horario
        {
            get { return _horario; }
            set { _horario = value; NotifyPropertyChanged(); }
        }

        protected long? _cantidad_alumnos_activos = null;
        public long? cantidad_alumnos_activos
        {
            get { return _cantidad_alumnos_activos; }
            set { _cantidad_alumnos_activos = value; NotifyPropertyChanged(); }
        }

        protected long? _cantidad_alumnos = null;
        public long? cantidad_alumnos
        {
            get { return _cantidad_alumnos; }
            set { _cantidad_alumnos = value; NotifyPropertyChanged(); }
        }

        protected List<string> _referentes = new();

        public List<string>? referentes
        {
            get { return _referentes; }
            set { 
                _referentes = value;
                referentesJoin = (!value.IsNoE()) ? string.Join(",",  value) : "";
                NotifyPropertyChanged(); }
        }

        protected string? _referentesJoin = null;

        public string? referentesJoin
        {
            get { return _referentesJoin; }
            set { _referentesJoin = value; NotifyPropertyChanged(); }
        }
    }
}
