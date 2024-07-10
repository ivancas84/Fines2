namespace SqlOrganize.Sql.Fines2Model3
{
    public class CalendarioValues : EntityValues
    {
        public CalendarioValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }
        public override string ToString()
        {
            var s = "";

            s += GetOrNull("anio")?.ToString() ?? "?";
            s += "-";
            s += GetOrNull("semestre")?.ToString() ?? "?";
            s += "";
            s += GetOrNull("descripcion")?.ToString() ?? "?";
            return s.Trim();
        }

        public (short anio, short semestre) AnioSemestreAnterior()
        {
            short anio = (short)Get("anio");
            short semestre = (short)Get("semestre");

            if(semestre == 1)
            {
                semestre = 2;
                anio--; 
            } else
            {
                semestre = 1;
            }

            return (anio, semestre);
        }

        public (short anio, short semestre) AnioSemestreSiguiente()
        {
            short anio = (short)Get("anio");
            short semestre = (short)Get("semestre");

            if (semestre == 2)
            {
                semestre = 1;
                anio++;
            }
            else
            {
                semestre = 2;
            }

            return (anio, semestre);
        }

    }
}
