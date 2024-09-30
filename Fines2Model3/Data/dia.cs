#nullable enable
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text.RegularExpressions;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Dia : Entity
    {

        public override bool EnableSynchronization
        {
            get => _enableSynchronization;
            set
            {
                if(_enableSynchronization != value)
                {
                    _enableSynchronization = value;

                    if(_enableSynchronization)
                    {
                        foreach(var obj in Horario_)
                        {
                             obj.EnableSynchronization = true;
                             if( obj.dia_ != this)
                                 obj.dia_ = this;
                        }

                    }
                }
            }
        }

        public Dia()
        {
            _entityName = "dia";
            _db = Context.db;
            Default();
            Horario_.CollectionChanged += Horario_CollectionChanged;
        }

        private void Horario_CollectionChanged(object sender, System.Collections.Specialized.NotifyCollectionChangedEventArgs e)
        {
            if (_enableSynchronization)
            {
                foreach (Horario obj in e.NewItems)
                {
                    obj.EnableSynchronization = true;
                    if(obj.dia_ != this)
                        obj.dia_ = this;
                }
            }
        }
        #region id
        protected string? _id = null;
        public string? id
        {
            get { return _id; }
            set { if( _id != value) { _id = value; NotifyPropertyChanged(nameof(id)); } }
        }
        #endregion

        #region numero
        protected short? _numero = null;
        public short? numero
        {
            get { return _numero; }
            set { if( _numero != value) { _numero = value; NotifyPropertyChanged(nameof(numero)); } }
        }
        #endregion

        #region dia
        protected string? _dia = null;
        public string? dia
        {
            get { return _dia; }
            set { if( _dia != value) { _dia = value; NotifyPropertyChanged(nameof(dia)); } }
        }
        #endregion

        #region Horario_ (ref horario.dia _m:o dia.id)
        public ObservableCollection<Horario> Horario_ { get; set; } = new ();
        #endregion

    }
}
