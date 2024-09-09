using SqlOrganize;
using SqlOrganize.CollectionUtils;
using System.Collections.Generic;

namespace Fines2Wpf.Windows.ProcesarDocentesProgramaFines
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
                .Param("@0", "2024").Param("@1", "1").Cache().ColOfDict().ColOfVal<string>("pfid");

        }

        public object? IdCurso(object pfidComision, object asignaturaCodigo)
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
                .Param("@0", pfidComision).Param("@1", asignaturaCodigo).Param("@2", "2024").Param("@3", "1").Cache().Dict();


            if (d.IsNoE()) return null;
            return d["id"];
        }

        public IDictionary<string, object>? TomaActiva(object idCurso)
        {
            return ContainerApp.db.Sql("toma")
                .Size(0)
                .Where(@"
                    $curso = @0 
                    AND $estado = 'Aprobada'
                    AND ($estado_contralor = 'Pasar' OR $estado_contralor = 'Pendiente')
                ")
                .Param("@0", idCurso).Cache().Dict();

        }


        public IDictionary<string, object>? RowByEntityFieldValue(string entityName, string fieldName, object value)
        {
            return ContainerApp.db.Sql(entityName).Where("$" + fieldName + " = @0").Param("@0", value).Cache().Dict();
        }

        public IDictionary<string, object>? RowByEntityUnique(string entityName, IDictionary<string, object> source)
        {
            var q = ContainerApp.db.Sql(entityName).Unique(source);

            if (source.ContainsKey(ContainerApp.config.id) && !source[ContainerApp.config.id].IsNoE())
                q.Where("($" + ContainerApp.config.id + " != @0)").Param("@0", source[ContainerApp.config.id]);

            return q.Cache().Dict();
        }
    }
}
