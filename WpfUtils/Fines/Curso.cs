using SqlOrganize.Sql;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.Windows.Threading;
using WpfUtils.Controls;
using System.Windows.Controls;

namespace WpfUtils.Fines
{
    public static class CursoWpfUtils
    {
        public static void SetCursoTimerTick(this System.Windows.Controls.ComboBox cursoComboBox, DispatcherTimer cursoTypingTimer, ObservableCollection<SqlOrganize.Sql.Fines2Model3.Curso> cursoOC)
        {
            (string? text, TextBox textBox, int? textBoxPos) = cursoComboBox.SetTimerTickInitializeItem<SqlOrganize.Sql.Fines2Model3.Curso>(cursoTypingTimer);
            if (text == null)
                return;

            var list = CursoDAO.Cursos__By_Search(text);
            list.AddEntitiesToClearOC(cursoOC);
            
            cursoComboBox.SetTimerTickFinalize(textBox!, text, (int)textBoxPos!);
        }

       

    }
}
