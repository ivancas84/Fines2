using SqlOrganize;
using System;

namespace Fines2Wpf.Data
{
    public class Data_domicilio_r : Data_domicilio
    {

        public Data_domicilio_r () : base()
        {
            Initialize();
        }

        public Data_domicilio_r (DataInitMode mode = DataInitMode.Default) : base(mode)
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
