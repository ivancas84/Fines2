using System;

namespace Fines2Wpf.Windows.Programafines.ProcesarInterfazAsignaciones
{
    internal class AsignacionPfItem : SqlOrganize.Sql.Data
    {
        public string nombre { get; set; }

        public string nacimiento { get; set; }

        public string pfid { get; set; } //identificacion en programafines de la asignacion

        public string dni { get; set; }

        public string comision { get; set; }
        public bool revisar { get; set; } = false; //debe hacerse una revision manual por inconsistencias

        protected override string ValidateField(string columnName)
        {
            throw new NotImplementedException();
        }
    }
}
