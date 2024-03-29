﻿using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection.Emit;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2Wpf.Values
{
    class Planificacion : EntityValues
    {
        public Planificacion(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }
        public override string ToString()
        {
            var s = "";

            s += GetOrNull("anio")?.ToString() ?? "?";
            s += "-";
            s += GetOrNull("semestre")?.ToString() ?? "?";
            s += "";
            EntityValues? planVal = ValuesTree("plan");
            if (!planVal.IsNullOrEmpty())
            {
                s += planVal.GetOrNull("distribucion_horaria")?.ToString() ?? "?";
            }
            return s.Trim();
        }

        public (string anio, string semestre) AnioSemestreAnterior()
        {
            string anio = (string)Get("anio");
            string semestre = (string)Get("semestre");

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

        public (string anio, string semestre) AnioSemestreSiguiente()
        {
            string anio = (string)Get("anio");
            string semestre = (string)Get("semestre");

            if (semestre.Equals("2"))
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

            return (anio, semestre);
        }

    }
}
