namespace SqlOrganize.Sql.Fines2Model3
{
    public class CursoValues : EntityValues
    {
        public CursoValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
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
            s += (v?.GetOrNull("codigo")?.ToString() ?? "?") ?? "?";
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

    }
}
