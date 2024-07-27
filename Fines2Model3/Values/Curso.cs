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

            EntityValues? v = ValuesRel("sede");
            s += (v?.GetOrNull("numero")?.ToString() ?? "?") ?? "?";

            v = ValuesRel("comision");
            s += (v?.GetOrNull("division")?.ToString() ?? "?") ?? "?";
            s += "/";
            v = ValuesRel("planificacion");
            s += (v?.GetOrNull("anio")?.ToString() ?? "?") ?? "?";
            s += (v?.GetOrNull("semestre")?.ToString() ?? "?") ?? "?";
            s += " ";
            v = ValuesRel("asignatura");
            s += (v?.GetOrNull("nombre")?.ToString() ?? "?") ?? "?";
            s += " ";
            s += (v?.GetOrNull("codigo")?.ToString() ?? "?") ?? "?";
            s += " ";
            v = ValuesRel("comision");
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
                s += val?.ValuesRel("docente")?.ToString() ?? "?";

            }
            return s;
        }

        public string ToStringSede()
        {
            var s = ToString();
            s += " ";
            s += ValuesRel("sede")?.GetOrNull("nombre") ?? "?";
            return s;
        }

        public string ToStringAsignaturaSedeDocente()
        {
            var s = ToString();
            s += " ";
            s += ValuesRel("asignatura")?.GetOrNull("nombre") ?? "?";
            s += " ";
            s += ValuesRel("sede")?.GetOrNull("nombre") ?? "?";
            s += " ";
            s += ValuesTomaActiva()?.ValuesRel("docente")?.ToString() ?? "?";
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
            
            if (obj is Data_curso_r ob)
                ob.Label = ToString();
            
            return obj;
        }

    }
}
