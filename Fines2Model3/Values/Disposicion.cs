﻿namespace SqlOrganize.Sql.Fines2Model3
{
    public class DisposicionValues : EntityVal
    {
        public DisposicionValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public override string ToString()
        {
            var s = "";

            EntityVal? v = GetValuesCache("asignatura");
            if(v != null)
            {
                s += v.GetOrNull("nombre")?.ToString() ?? "?";
                s += " ";
                s += v.GetOrNull("codigo")?.ToString() ?? "?";
                s += " ";
            }

            v = GetValuesCache("planificacion");
            if (v != null)
            {
                s += v.GetOrNull("anio")?.ToString() ?? "?";
                s += "/";
                s += v.GetOrNull("semestre")?.ToString() ?? "?";
            }

            return s.Trim();
        }
    }
}

    