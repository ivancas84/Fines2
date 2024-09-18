#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Sede : SqlOrganize.Sql.EntityData
    {

        public override string entityName => "sede";

        public override void Default()
        {
            EntityVal val = db!.Values("sede");
            _id = (string?)val.GetDefault("id");
            _alta = (DateTime?)val.GetDefault("alta");
        }


        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        protected string? _numero = null;
        public string? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
        }
        protected string? _nombre = null;
        public string? nombre
        {
            get { return _nombre; }
            set { if( _nombre != value) { _nombre = value; NotifyPropertyChanged(nameof(nombre)); } }
        }
        protected string? _observaciones = null;
        public string? observaciones
        {
            get { return _observaciones; }
            set { if( _observaciones != value) { _observaciones = value; NotifyPropertyChanged(nameof(observaciones)); } }
        }
        protected DateTime? _alta = null;
        public DateTime? alta
        {
            get { return _alta; }
            set { if( _alta != value) { _alta = value; NotifyPropertyChanged(nameof(alta)); } }
        }
        protected DateTime? _baja = null;
        public DateTime? baja
        {
            get { return _baja; }
            set { if( _baja != value) { _baja = value; NotifyPropertyChanged(nameof(baja)); } }
        }
        protected string? _domicilio = null;
        public string? domicilio
        {
            get { return _domicilio; }
            set { if( _domicilio != value) { _domicilio = value; NotifyPropertyChanged(nameof(domicilio)); } }
        }
        protected string? _centro_educativo = null;
        public string? centro_educativo
        {
            get { return _centro_educativo; }
            set { if( _centro_educativo != value) { _centro_educativo = value; NotifyPropertyChanged(nameof(centro_educativo)); } }
        }
        protected DateTime? _fecha_traspaso = null;
        public DateTime? fecha_traspaso
        {
            get { return _fecha_traspaso; }
            set { if( _fecha_traspaso != value) { _fecha_traspaso = value; NotifyPropertyChanged(nameof(fecha_traspaso)); } }
        }
        protected string? _organizacion = null;
        public string? organizacion
        {
            get { return _organizacion; }
            set { if( _organizacion != value) { _organizacion = value; NotifyPropertyChanged(nameof(organizacion)); } }
        }
        protected string? _pfid = null;
        public string? pfid
        {
            get { return _pfid; }
            set { if( _pfid != value) { _pfid = value; NotifyPropertyChanged(nameof(pfid)); } }
        }
        protected string? _pfid_organizacion = null;
        public string? pfid_organizacion
        {
            get { return _pfid_organizacion; }
            set { if( _pfid_organizacion != value) { _pfid_organizacion = value; NotifyPropertyChanged(nameof(pfid_organizacion)); } }
        }
        protected override string ValidateField(string columnName)
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
                    return "";

                case "nombre":
                    if (_nombre == null)
                        return "Debe completar valor.";
                    return "";

                case "observaciones":
                    return "";

                case "alta":
                    if (_alta == null)
                        return "Debe completar valor.";
                    return "";

                case "baja":
                    return "";

                case "domicilio":
                    return "";

                case "centro_educativo":
                    return "";

                case "fecha_traspaso":
                    return "";

                case "organizacion":
                    return "";

                case "pfid":
                    return "";

                case "pfid_organizacion":
                    return "";

            }

            return "";
        }
        //sede.domicilio _o:o domicilio.id
        protected Domicilio? _domicilio_ = null;
        public Domicilio? domicilio_
        {
            get { return _domicilio_; }
            set {
                _domicilio_ = value;
                domicilio = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(domicilio_));
            }
        }

        //sede.centro_educativo _o:o centro_educativo.id
        protected CentroEducativo? _centro_educativo_ = null;
        public CentroEducativo? centro_educativo_
        {
            get { return _centro_educativo_; }
            set {
                _centro_educativo_ = value;
                centro_educativo = (value != null) ? value.id : null;
                NotifyPropertyChanged(nameof(centro_educativo_));
            }
        }

        //comision.sede _m:o sede.id
        public ObservableCollection<Comision> Comision_sede_ { get; set; } = new ();

        //designacion.sede _m:o sede.id
        public ObservableCollection<Designacion> Designacion_sede_ { get; set; } = new ();

    }
}
