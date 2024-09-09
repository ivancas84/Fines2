using Newtonsoft.Json;
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
    ///     var comision = (Data_comision)comisionComboBox.SelectedItem;
    ///     var cursosData = ContainerApp.db.Sql("curso").Equal("$comision", comision.id).Cache().ColOfDict();
    ///     var tomaData = ContainerApp.db.TomaAprobadaDeComisionQuery(comision.id).Cache().ColOfDict();
    ///     cursosData.MergeByKeys(tomaData, "id", "curso", "toma_");
    ///     ContainerApp.db.ClearAndAddDataToOC(cursosData, cursoOC);
    /// </example>
    public class CursoConTomaItem : Data_curso_r
    {
        protected string? _toma_docente__Label = null;

        public string? toma_docente__Label
        {
            get { return _toma_docente__Label; }
            set { _toma_docente__Label = value; NotifyPropertyChanged(nameof(toma_docente__Label)); }
        }

        protected string? _toma_docente__nombres = null;

        public string? toma_docente__nombres
        {
            get { return _toma_docente__nombres; }
            set { _toma_docente__nombres = value; NotifyPropertyChanged(nameof(toma_docente__nombres)); }
        }
        protected string? _toma_docente__apellidos = null;

        public string? toma_docente__apellidos
        {
            get { return _toma_docente__apellidos; }
            set { _toma_docente__apellidos = value; NotifyPropertyChanged(nameof(toma_docente__apellidos)); }
        }
        protected DateTime? _toma_docente__fecha_nacimiento = null;

        public DateTime? toma_docente__fecha_nacimiento
        {
            get { return _toma_docente__fecha_nacimiento; }
            set { _toma_docente__fecha_nacimiento = value; NotifyPropertyChanged(nameof(toma_docente__fecha_nacimiento)); }
        }
        protected string? _toma_docente__numero_documento = null;

        public string? toma_docente__numero_documento
        {
            get { return _toma_docente__numero_documento; }
            set { _toma_docente__numero_documento = value; NotifyPropertyChanged(nameof(toma_docente__numero_documento)); }
        }

        protected string? _toma_docente__telefono = null;

        public string? toma_docente__telefono
        {
            get { return _toma_docente__telefono; }
            set { _toma_docente__telefono = value; NotifyPropertyChanged(nameof(toma_docente__telefono)); }
        }
        protected string? _toma_docente__email = null;

        public string? toma_docente__email
        {
            get { return _toma_docente__email; }
            set { _toma_docente__email = value; NotifyPropertyChanged(nameof(toma_docente__email)); }
        }
        protected string? _toma_docente__email_abc = null;

        public string? toma_docente__email_abc
        {
            get { return _toma_docente__email_abc; }
            set { _toma_docente__email_abc = value; NotifyPropertyChanged(nameof(toma_docente__email_abc)); }
        }
    }
}
