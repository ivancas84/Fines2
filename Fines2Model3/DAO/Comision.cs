using SqlOrganize;
using SqlOrganize.CollectionUtils;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public static class ComisionDAO
    {

        public static EntitySql BusquedaAproximadaComision(this Db db, string search)
        {
            return db.Sql("comision")
               .Fields()
               .Size(0)
               .Where(@"
                    $sede-nombre LIKE @0
                    OR
                    CONCAT($sede-numero, $division, '/', $planificacion-anio, $planificacion-semestre) LIKE @0
                    OR
                    $pfid LIKE @0
                    OR
                    CONCAT($calendario-anio, '-', $calendario-semestre) LIKE @0
                ")
               .Order("$sede-numero ASC, $division ASC, $calendario-anio DESC, $calendario-semestre DESC")
               .Parameters("%" + search + "%");

        }

        public static EntitySql ComisionesAutorizadasDePeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                    AND $autorizada = true
                ")
                .Parameters(anio, semestre);

        }

        public static EntitySql ComisionesDePeriodoSql(this Db db, object anio, object semestre)
        {
            return db.Sql("comision")
                .Size(0)
                .Where(@"
                    $calendario-anio = @0
                    AND $calendario-semestre = @1
                ")
                .Parameters(anio, semestre);

        }

        /// <summary>Persistencia de asignaciones</summary>
        /// <returns>persists y asignaciones persistidas</returns>
        public static (IEnumerable<EntityPersist> persists, ObservableCollection<InfoData> ocInfo) PersistAsignacionesComisionText(this Db db, object idComision, string text, params string[]? headers)
        {            
            if (headers.IsNoE())
                headers = ["persona-apellidos", "persona-nombres", "persona-numero_documento", "persona-genero", "persona-fecha_nacimiento", "persona-telefono", "persona-email"];

            ObservableCollection<InfoData> ocInfo = new();

            Data_comision_r? comObj = db.Sql("comision").Equal("id", idComision).Cache().Data<Data_comision_r>() ?? throw new Exception("comision inexistente");

            List<EntityPersist> persists = new();
        

            string[] _data = text.Split("\r\n");
            if (_data.IsNoE())
                throw new Exception("Datos vacios");

            ocInfo.Clear();
            for (var j = 0; j < _data.Length; j++)
            {
                try
                {
                    IDictionary<string, object?> dict = _data[j].DictFromText(headers!);

                    var personaValues = db.Values("persona","persona").SsetNotNull(dict);
                    CompareParams compare = new CompareParams
                    {
                        fieldsToCompare = ["nombres", "apellidos", "numero_documento"],
                    };
                    personaValues.PersistCompare(compare).AddTo(persists);

                    var alumnoVal = db.Values("alumno").
                        Sset("persona", personaValues.Get("id")!).
                        Sset("anio_ingreso", comObj.planificacion__anio!).
                        Sset("semestre_ingreso", comObj.planificacion__semestre!).
                        Sset("plan", comObj.plan__id);
                    alumnoVal.InsertIfNotExists()?.AddTo(persists);

                    var asignacionVal = db.Values("alumno_comision").
                        Sset("alumno", alumnoVal.Get("id")).
                        Sset("comision", comObj.id);
                    asignacionVal.InsertIfNotExists()?.AddTo(persists);

                    var otrasAsignaciones = db.Sql("alumno_comision").Where("$alumno = @0 AND $comision != @1").
                        Parameters(alumnoVal.Get("id"), comObj.id).Cache().ColOfDict();
                    foreach(var oa in otrasAsignaciones)
                        asignacionVal.Logging.AddLog("alumno_comision", "Asignacion existente " + db.Values("alumno_comision").SetValues(oa).ToString(), "persist", Logging.Level.Warning);
                    
                    asignacionVal.Logging.AddLogging(personaValues.Logging);
                    asignacionVal.Logging.AddLogging(alumnoVal.Logging);

                    InfoData info = new();
                    info.Info = _data[j];
                    info.Msg = asignacionVal.Logging.ToString();
                    ocInfo.Add(info);
                }
                catch (Exception ex)
                {
                    InfoData info = new();
                    info.Info = _data[j];
                    info.Msg = ex.Message;
                    ocInfo.Add(info);
                }

            }

            return (persists, ocInfo);
        }

    }
}
