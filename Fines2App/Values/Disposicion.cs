﻿using SqlOrganize;
using Utils;

namespace Fines2App.Values
{
    class Disposicion : EntityValues
    {
        public Disposicion(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public override string ToString()
        {
            var s = "";

            EntityValues? v = ValuesTree("asignatura");
            if(v != null)
            {
                s += v.GetOrNull("nombre")?.ToString() ?? "?";
                s += " ";
                s += v.GetOrNull("codigo")?.ToString() ?? "?";
                s += " ";
            }

            v = ValuesTree("planificacion");
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

    