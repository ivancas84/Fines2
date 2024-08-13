﻿namespace SqlOrganize.Sql.Fines2Model3
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

        public static  (short anio, short semestre)  AnioSemestreAnterior(short anio, short semestre)
        { 
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


        public static  (short anio, short semestre) AnioSemestreSiguiente(short anio, short semestre)
        {
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

        public override T GetData<T>()
        {

            string label = ToString();

            var obj = db.Data<T>(Values());
            if (obj is Data_calendario p)
                p.Label = label;
            if (Logging.HasLogs())
                obj.Msg += Logging.ToString();

            return obj;
        }

    }
}
