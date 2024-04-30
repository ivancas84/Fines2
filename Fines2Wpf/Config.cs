﻿using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Wpf
{
    public class Config : SqlOrganize.Config
    {
        public string? emailDocentePassword { get; set; }
        public string? emailDocenteHost { get; set; }
        public string? emailDocenteFromAddress { get; set; }
        public string? emailDocenteFromName { get; set; }
        public string? emailDocenteBcc { get; set; }
        public string? emailDocenteUser { get; set; }

        public string colorRed { get; set; } = "#fa91aa";
        public string colorGreen { get; set; } = "#cae7c2";
        public string colorBlue { get; set; } = "#7196bd";
        public string colorYellow { get; set; } = "#faebd7";
        public string colorGray { get; set; } = "#d7d7d7";

        public override string id { get; set; } = "id";

        public string upload { get; set; } = "./upload/";
        public string downloadPath { get; set; } = "Downloads/";
        public string imagePath { get; set; } = "Images/";

        public string ftpUserName { get; set; }
        public string ftpUserPassword { get; set; }
        
        public string pfUser { get; set; }
        public string pfPassword { get; set; }




    }
}
