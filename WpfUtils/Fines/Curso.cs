using SqlOrganize.Sql;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.Windows.Threading;
using WpfUtils.Controls;
using System.Windows.Controls;

namespace WpfUtils.Fines
{
    public static class CursoWpf
    {
        public static void SetCursoTimerTick(System.Windows.Controls.ComboBox cursoComboBox, DispatcherTimer cursoTypingTimer, ObservableCollection<SqlOrganize.Sql.Fines2Model3.Curso> cursoOC)
        {
            (string? text, TextBox textBox, int? textBoxPos) = cursoComboBox.SetTimerTickInitializeItem<SqlOrganize.Sql.Fines2Model3.Curso>(cursoTypingTimer);
            if (text == null)
                return;

            IEnumerable<Dictionary<string, object?>> list = Context.db.BusquedaAproximadaCurso(text).Size(30).Dicts(); //busqueda de valores a mostrar en funcion del texto

            Context.db.AddEntityToClearOC(list, cursoOC);
            
            cursoComboBox.SetTimerTickFinalize(textBox!, text, (int)textBoxPos!);
        }

        public static void ConsultarCalificacionesAprobadasAsignacionesDesaprobadas(this Db db, object curso, ObservableCollection<Calificacion> calificacionAprobadaOC, ObservableCollection<AlumnoComision> asignacionDesaprobadaOC)
        {
            var cursoData = db.Sql("curso").Cache().Id(curso);

            var calificacionAprobadaData = CalificacionDAO.CalificacionAprobadaCursoSql(curso).Cache().Dicts();
            db.AddEntityToClearOC(calificacionAprobadaData, calificacionAprobadaOC);

            var alumnosConCalificacionAprobada = calificacionAprobadaData.ColOfVal<object>("alumno");
            var asignacionDesaprobadaData = AsignacionDAO.AsignacionesActivasRestantesComisionSql(cursoData["comision"], alumnosConCalificacionAprobada).Cache().Dicts();

            db.AddEntityToClearOC(asignacionDesaprobadaData, asignacionDesaprobadaOC);
        }

    }
}
