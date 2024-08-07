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
        public static void SetCursoTimerTick(this DbApp db, System.Windows.Controls.ComboBox cursoComboBox, DispatcherTimer cursoTypingTimer, ObservableCollection<Data_curso_r> cursoOC)
        {
            (string? text, TextBox textBox, int? textBoxPos) = cursoComboBox.SetTimerTickInitializeItem<Data_curso_r>(cursoTypingTimer);
            if (text == null)
                return;

            IEnumerable<Dictionary<string, object?>> list = db.BusquedaAproximadaCurso(text).Size(30).ColOfDict(); //busqueda de valores a mostrar en funcion del texto

            db.ClearAndAddDataToOC(list, cursoOC);
            
            cursoComboBox.SetTimerTickFinalize(textBox!, text, (int)textBoxPos!);
        }

        public static void ConsultarCalificacionesAprobadasAsignacionesDesaprobadas(this Db db, object curso, ObservableCollection<Data_calificacion_r> calificacionAprobadaOC, ObservableCollection<Data_alumno_comision_r> asignacionDesaprobadaOC)
        {
            var cursoData = db.Sql("curso").Cache().Id(curso);

            var calificacionAprobadaData = db.CalificacionAprobadaCursoSql(curso).Cache().ColOfDict();
            db.ClearAndAddDataToOC(calificacionAprobadaData, calificacionAprobadaOC);

            var alumnosConCalificacionAprobada = calificacionAprobadaData.ColOfVal<object>("alumno");
            var asignacionDesaprobadaData = db.AsignacionesActivasRestantesComisionSql(cursoData["comision"], alumnosConCalificacionAprobada).Cache().ColOfDict();

            db.ClearAndAddDataToOC(asignacionDesaprobadaData, asignacionDesaprobadaOC);
        }

    }
}
