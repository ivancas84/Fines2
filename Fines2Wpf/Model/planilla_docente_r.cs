using SqlOrganize;
using System;

namespace Fines2Wpf.Model
{
    public class Data_planilla_docente_r : Data_planilla_docente
    {

        public Data_planilla_docente_r () : base()
        {
            Initialize();
        }

        public Data_planilla_docente_r (DataInitMode mode = DataInitMode.Default) : base(mode)
        {
            Initialize(mode);
        }

        protected override void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            base.Initialize(mode);
            switch(mode)
            {
                case DataInitMode.Default:
                break;
            }
        }
    }
}
