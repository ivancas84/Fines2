using SqlOrganize;
using System;

namespace Fines2Wpf.Model
{
    public class Data_tipo_sede_r : Data_tipo_sede
    {

        public Data_tipo_sede_r () : base()
        {
            Initialize();
        }

        public Data_tipo_sede_r (DataInitMode mode = DataInitMode.Default) : base(mode)
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
