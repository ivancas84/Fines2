namespace SqlOrganize.Sql.Fines2Model3
{
    public class PlanificacionValues : EntityValues
    {
        public PlanificacionValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }
        public override string ToString()
        {
            var s = GetStr("?", "/", "anio", "semestre");

            EntityValues? planVal = GetValuesCache("plan");
            if (!planVal.IsNoE())
                s += " " + planVal!.ToString();
            
            return s.Trim();
        }

        public static (string anio, string semestre) AnioSemestreAnterior(string anio, string semestre)
        {
            if(semestre.Equals("1"))
            {
                semestre = "2";
                short anio_ = Convert.ToInt16(anio);
                anio_--;
                if (anio_ == 0) throw new Exception("Se esta queriendo obtener año y semestre anterior a 1/1");
                anio = anio_.ToString();
            } else
            {
                semestre = "1";
            }

            return (anio, semestre);
        }

        public static (string anio, string semestre) AnioSemestreSiguiente(object anio, object semestre)
        {

            if (semestre.ToString().Equals("2"))
            {
                semestre = "1";
                short anio_ = Convert.ToInt16(anio);
                anio_++;
                if (anio_ == 4) throw new Exception("Se esta queriendo obtener año y semestre anterior a 3/2");
                anio = anio_.ToString();
            }
            else
            {
                semestre = "2";
            }

            return (anio.ToString(), semestre.ToString());
        }

    }
}
