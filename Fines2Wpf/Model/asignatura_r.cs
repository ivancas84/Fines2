using SqlOrganize;
using System;

namespace Fines2Wpf.Model
{
    public class Data_asignatura_r : Data_asignatura
    {

        public Data_asignatura_r () : base()
        {
            Initialize();
        }

        public Data_asignatura_r (DataInitMode mode = DataInitMode.Default) : base(mode)
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
