﻿using Fines2Model3.Data;
using MaterialDesignColors;
using SqlOrganize;

namespace Fines2Wpf.Windows.Alumno.AdministrarAlumno
{
    internal class DetallePersona : Data_detalle_persona_r
    {

        public DetallePersona() : base()
        {
        }

        public DetallePersona(Db db, bool init = true) : base(db, init)
        {
        }

        public DetallePersona(Db db, bool init = true, params string[] fieldIds) : base(db, init, fieldIds)
        {
        }


        protected string? _arch = null;

        public string? arch
        {
            get { return _arch; }
            set { _arch = value; NotifyPropertyChanged(); }
        }

    }
}
