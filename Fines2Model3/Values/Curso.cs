using Fines2Model3.Item;
using System.Collections.Generic;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class CursoValues : EntityValues
    {
        public CursoValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public virtual object Get(string fieldName)
        {
            if (fieldName.Equals("disposicion"))
                return GetDisposicion();

            return values[fieldName]!;
        }

        public string GetDisposicion()
        {
            var id = db.DisposicionPlanificacionAsignaturaSql(Get("comision-planificacion"), Get("asignatura")).Cache().Dict()?["id"] ?? throw new Exception("Disposicion inexistente");
            return (string)id;
        }


        public override string ToString()
        {
            var s = "";

            EntityValues? v = GetValuesCache("sede");
            s += (v?.GetOrNull("numero")?.ToString() ?? "?") ?? "?";

            v = GetValuesCache("comision");
            s += (v?.GetOrNull("division")?.ToString() ?? "?") ?? "?";
            s += "/";
            v = GetValuesCache("planificacion");
            s += (v?.GetOrNull("anio")?.ToString() ?? "?") ?? "?";
            s += (v?.GetOrNull("semestre")?.ToString() ?? "?") ?? "?";
            s += " ";
            v = GetValuesCache("asignatura");
            s += (v?.GetOrNull("nombre")?.ToString() ?? "?") ?? "?";
            s += " ";
            s += (v?.GetOrNull("codigo")?.ToString() ?? "?") ?? "?";
            s += " ";
            v = GetValuesCache("comision");
            s += (v?.GetOrNull("pfid")?.ToString() ?? "?") ?? "?";
            return s.Trim();
        }

        public string ToStringDocente()
        {
            var idCurso = GetOrNull("id");
            if (idCurso.IsNoE()) 
                return "";

            var dd = db.Sql("toma").
                Where("$curso = @0 AND $estado = 'Aprobada' AND $estado_contralor = 'Pasar'").
                Parameters(idCurso).Cache().Dict();

            var s = ToString();
            if (!dd.IsNoE())
            {
                s += " ";
                var val = db.Values("toma").Set(dd!);
                s += val?.GetValuesCache("docente")?.ToString() ?? "?";

            }
            return s;
        }

        public string ToStringSede()
        {
            var s = ToString();
            s += " ";
            s += GetValuesCache("sede")?.GetOrNull("nombre") ?? "?";
            return s;
        }

        public string ToStringAsignaturaSedeDocente()
        {
            var s = ToString();
            s += " ";
            s += GetValuesCache("asignatura")?.GetOrNull("nombre") ?? "?";
            s += " ";
            s += GetValuesCache("sede")?.GetOrNull("nombre") ?? "?";
            s += " ";
            s += ValuesTomaActiva()?.GetValuesCache("docente")?.ToString() ?? "?";
            return s;
        }


        public EntityValues? ValuesTomaActiva()
        {
            var idCurso = GetOrNull("id");
            if (idCurso.IsNoE())
                return null;
            
            var data = db.Sql("toma").
                Where("$curso = @0 AND $estado = 'Aprobada' AND $estado_contralor = 'Pasar'").
                Parameters(idCurso).Cache().Dict();

            return db.Values("toma").Set(data!);
        }

        public override T GetData<T>()
        {
            var obj = base.GetData<T>();

            if (obj is Data_curso_r c)
                c.Label = ToString();
            
            return obj;
        }




    }
}
