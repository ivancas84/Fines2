using System.Collections.Generic;
using SqlOrganize;
using SqlOrganize.CollectionUtils;

namespace Fines2Wpf.Windows.ProcesarComisionesProgramaFines
{
    internal class DAO
    {
        public IEnumerable<string> PfidComisiones()
        {
            return ContainerApp.db.Sql("comision")
                .Fields("pfid")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $pfid IS NOT NULL
                ")
                .Parameters("2024", "1").Cache().ColOfDict().ColOfVal<string>("pfid");

        }

        public object? IdCurso(string pfidComision, string asignaturaCodigo)
        {

            var d = ContainerApp.db.Sql("curso")
                .Fields("id")
                .Size(0)
                .Where(@"
                    $comision-pfid = @0 
                    AND ($asignatura-codigo = @1 OR $codigo = @1)
                    AND $calendario-anio = @2
                    AND $calendario-semestre = @3
                ")
                .Parameters(pfidComision, asignaturaCodigo, "2024", "1").Cache().Dict();

            if (d.IsNoE()) return null;
            return d["id"];

        }

        public object? IdPersona(string dni)
        {
            var d = ContainerApp.db.Sql("persona")
                .Fields("id")
                .Size(0)
                .Where(@"
                    $numero_documento = @0 
                ")
                .Parameters(dni).Cache().Dict();

            if (d.IsNoE()) return null;
            return d["id"];

        }
    }
}
