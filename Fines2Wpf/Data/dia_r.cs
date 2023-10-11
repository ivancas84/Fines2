using SqlOrganize;
using System;

namespace Fines2Wpf.Data
{
    public class Data_dia_r : Data_dia
    {

        public Data_dia_r () : base()
        {
            Initialize();
        }

        public Data_dia_r (DataInitMode mode = DataInitMode.Default) : base(mode)
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
