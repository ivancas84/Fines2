﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.Windows.ProcesarDocentesProgramaFines
{
    internal class DAO
    {
        public IEnumerable<string> PfidComisiones()
        {
           return ContainerApp.db.Query("comision")
                .Fields("pfid")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0 
                    AND $calendario-semestre = @1 
                    AND $pfid IS NOT NULL
                ")
                .Parameters("2024", "1").ColOfDictCache().ColOfVal<string>("pfid");

        }

        public object? IdCurso(object pfidComision, object asignaturaCodigo)
        {
            var d = ContainerApp.db.Query("curso")
                .Fields("id")
                .Size(0)
                .Where(@"
                    $comision-pfid = @0 
                    AND ($asignatura-codigo = @1 OR $codigo = @1)
                    AND $calendario-anio = @2
                    AND $calendario-semestre = @3
                ")
                .Parameters(pfidComision, asignaturaCodigo, "2024", "1").DictCache();


            if (d.IsNullOrEmptyOrDbNull()) return null;
            return d["id"];
        }

        public IDictionary<string, object>? TomaActiva(object idCurso)
        {
            return ContainerApp.db.Query("toma")
                .Size(0)
                .Where(@"
                    $curso = @0 
                    AND $estado = 'Aprobada'
                    AND ($estado_contralor = 'Pasar' OR $estado_contralor = 'Pendiente')
                ")
                .Parameters(idCurso).DictCache();

        }


        public IDictionary<string, object>? RowByEntityFieldValue(string entityName, string fieldName, object value)
        {
            return ContainerApp.db.Query(entityName).Where("$" + fieldName + " = @0").Parameters(value).DictCache();
        }

        public IDictionary<string, object>? RowByEntityUnique(string entityName, IDictionary<string, object> source)
        {
            var q = ContainerApp.db.Query(entityName).Unique(source);

            if (source.ContainsKey(ContainerApp.config.id) && !source[ContainerApp.config.id].IsNullOrEmpty())
                q.Where("($" + ContainerApp.config.id + " != @0)").Parameters(source[ContainerApp.config.id]);

            return q.DictCache();
        }
    }
}
