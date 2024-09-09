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
                    $calendario__anio = @0 
                    AND $calendario__semestre = @1 
                    AND $pfid IS NOT NULL
                ")
                .Param("@0", "2024").Param("@1", "1").Cache().ColOfDict().ColOfVal<string>("pfid");

        }

        public object? IdCurso(string pfidComision, string asignaturaCodigo)
        {

            var d = ContainerApp.db.Sql("curso")
                .Fields("id")
                .Size(0)
                .Where(@"
                    $comision__pfid = @0 
                    AND ($asignatura__codigo = @1 OR $codigo = @1)
                    AND $calendario__anio = @2
                    AND $calendario__semestre = @3
                ")
                .Param("@0", pfidComision).Param("@1", asignaturaCodigo).Param("@2", "2024").Param("@3", "1").Cache().Dict();

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
                .Param("@0", dni).Cache().Dict();

            if (d.IsNoE()) return null;
            return d["id"];

        }
    }
}
