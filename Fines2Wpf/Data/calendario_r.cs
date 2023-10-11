using SqlOrganize;
using System;

namespace Fines2Wpf.Data
{
    public class Data_calendario_r : Data_calendario
    {

        public Data_calendario_r () : base()
        {
            Initialize();
        }

        public Data_calendario_r (DataInitMode mode = DataInitMode.Default) : base(mode)
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
