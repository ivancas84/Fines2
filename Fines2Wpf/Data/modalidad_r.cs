using SqlOrganize;
using System;

namespace Fines2Wpf.Data
{
    public class Data_modalidad_r : Data_modalidad
    {

        public Data_modalidad_r () : base()
        {
            Initialize();
        }

        public Data_modalidad_r (DataInitMode mode = DataInitMode.Default) : base(mode)
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
