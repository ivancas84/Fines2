using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fines2Model3.DAO
{
    public static class Disposicion
    {

        /// <summary> Todas las calificaciones del alumno para un determinado plan </summary>
        public static EntitySql DisposicionesPlanAnioSemestre(this Db db, object plan, object anio, object semestre)
        {
            return db.Sql("disposicion").
                Size(0).
                Where(@"
                        $planificacion-plan = @0 
                        AND $planificacion-anio >= @1 
                        AND $planificacion-semestre >= @2").
                Parameters(plan!, anio!, semestre!);
        }
    }
}
