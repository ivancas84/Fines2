using Newtonsoft.Json;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{

    /// <summary>
    /// Curso con toma
    /// </summary>
    /// <example>
    ///     var comision = (Comision)comisionComboBox.SelectedItem;
    ///     var cursosData = ContainerApp.db.Sql("curso").Equal("$comision", comision.id).Cache().Dicts();
    ///     var tomaData = ContainerApp.db.TomaAprobadaDeComisionQuery(comision.id).Cache().Dicts();
    ///     cursosData.MergeByKeys(tomaData, "id", "curso", "toma_");
    ///     ContainerApp.db.AddDataToClearOC(cursosData, cursoOC);
    /// </example>
    public partial class Curso : EntityData
    {

        //curso.asignatura _o:o asignatura.id
        protected Toma? _toma_activa_ = null;
        public Toma? toma_activa_
        {
            get { return _toma_activa_; }
            set { _toma_activa_ = value; NotifyPropertyChanged(nameof(toma_activa_)); }
        }


    }
}
