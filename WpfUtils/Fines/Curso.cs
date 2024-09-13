using SqlOrganize.Sql;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.Windows.Threading;
using WpfUtils.Controls;
using System.Windows.Controls;

namespace WpfUtils.Fines.Curso
{
    public static class Curso
    {
        public static void SetCursoTimerTick(this DbApp db, System.Windows.Controls.ComboBox cursoComboBox, DispatcherTimer cursoTypingTimer, ObservableCollection<Curso_> cursoOC)
        {
            (string? text, TextBox textBox, int? textBoxPos) = cursoComboBox.SetTimerTickInitializeItem<Curso_>(cursoTypingTimer);
            if (text == null)
                return;

            IEnumerable<Dictionary<string, object?>> list = db.BusquedaAproximadaCurso(text).Size(30).Dicts(); //busqueda de valores a mostrar en funcion del texto

            db.ClearAndAddDataToOC(list, cursoOC);
            
            cursoComboBox.SetTimerTickFinalize(textBox!, text, (int)textBoxPos!);
        }

        public static void ConsultarCalificacionesAprobadasAsignacionesDesaprobadas(this Db db, object curso, ObservableCollection<Calificacion_> calificacionAprobadaOC, ObservableCollection<AlumnoComision_> asignacionDesaprobadaOC)
        {
            var cursoData = db.Sql("curso").Cache().Id(curso);

            var calificacionAprobadaData = db.CalificacionAprobadaCursoSql(curso).Cache().Dicts();
            db.ClearAndAddDataToOC(calificacionAprobadaData, calificacionAprobadaOC);

            var alumnosConCalificacionAprobada = calificacionAprobadaData.ColOfVal<object>("alumno");
            var asignacionDesaprobadaData = db.AsignacionesActivasRestantesComisionSql(cursoData["comision"], alumnosConCalificacionAprobada).Cache().Dicts();

            db.ClearAndAddDataToOC(asignacionDesaprobadaData, asignacionDesaprobadaOC);
        }

    }
}
