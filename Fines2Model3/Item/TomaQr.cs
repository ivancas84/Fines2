﻿namespace SqlOrganize.Sql.Fines2Model3
{
    public class TomaQrItem : Toma
    {
        public Byte[] qr_code { get; set; }

        public IEnumerable<string> estados_contralor = new List<string>();
    }
}
