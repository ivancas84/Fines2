using Newtonsoft.Json;
using Org.BouncyCastle.Tsp;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SqlOrganize.CollectionUtils;

namespace SqlOrganize.Sql.Fines2Model3
{

    /// <summary>
    /// Curso con toma
    /// </summary>
    /// <example>
    ///     var comision = (Comision)comisionComboBox.SelectedItem;
    ///     var cursosData = Context.db.Sql("curso").Equal("$comision", comision.id).Cache().Dicts();
    ///     var tomaData = Context.db.TomaAprobadaDeComisionQuery(comision.id).Cache().Dicts();
    ///     cursosData.MergeByKeys(tomaData, "id", "curso", "toma_");
    ///     Context.db.AddEntityToClearOC(cursosData, cursoOC);
    /// </example>
    public partial class Curso
    {

        public override string? Label
        {
            get
            {
                if (!_Label.IsNoE())
                    return _Label;

                return (comision_?.Label ?? "?") + " " + (disposicion_?.Label ?? "?");
            }
            set
            {
                _Label = value;
                NotifyPropertyChanged(nameof(Label));
            }
        }

        //curso.asignatura _o:o asignatura.id
        protected Toma? _toma_activa_ = null;
        public Toma? toma_activa_
        {
            get { return _toma_activa_; }
            set { _toma_activa_ = value; NotifyPropertyChanged(nameof(toma_activa_)); }
        }

        public void ConsultarCalificacionesAprobadasAsignacionesDesaprobadas()
        {
            if (id.IsNoE())
                throw new Exception("Id no definido");

            CalificacionDAO.CalificacionAprobadaCursoSql(id).Cache().AddEntityToClearOC(Calificacion_);
            var idAlumnosConCalificacionAprobada = Calificacion_.ColOfProp<object, Calificacion>("alumno");

            AsignacionDAO.AsignacionesActivasRestantesComisionSql(comision!, idAlumnosConCalificacionAprobada).Cache().AddEntityToClearOC(comision_!.AlumnoComision_);
        }
    }
}
