using Fines2App.DAO;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection.Emit;
using System.Text;
using System.Threading.Tasks;
using Utils;

namespace Fines2App.Values
{
    class Curso : EntityValues
    {
        public Curso(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
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
            DAO.Curso cursoDAO = new DAO.Curso();
            var idCurso = GetOrNull("id");
            if (idCurso.IsNullOrEmpty()) 
                return "";
            
            var s = ToString();
            var dd = cursoDAO.TomaActivaDeCursoQuery(idCurso!).DictCache();
            if (!dd.IsNullOrEmptyOrDbNull()) { 
            var val = ContainerApp.db.Values("toma").Set(dd!);
                s += " ";
                s += val?.ValuesRel("docente")?.ToString() ?? "?";
            } else
                s += " ?";
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
            DAO.Curso cursoDAO = new DAO.Curso();
            var idCurso = GetOrNull("id");
            if (idCurso.IsNullOrEmpty())
                return null;
            
            var data = cursoDAO.TomaActivaDeCursoQuery(idCurso!).DictCache();
            return ContainerApp.db.Values("toma").Set(data!);
        }

    }
}
