using Fines2Wpf.Data;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;
using Utils;

namespace Fines2Wpf.Windows.Comision.GenerarComisionesSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        public Window1()
        {
            InitializeComponent();

            var comisionObj = new Data_comision_r(SqlOrganize.DataInitMode.Null);
            comisionObj.calendario__anio = Convert.ToInt16(DateTime.Now.Year);
            comisionObj.calendario__semestre = Convert.ToInt16(DateTime.Now.ToSemester());
            comisionObj.autorizada = true;
            formGroupBox.DataContext = comisionObj;
        }

        private void GenerarButton_Click(object sender, RoutedEventArgs e)
        {
            #region Consultar comisiones del semestre actual
            IEnumerable<Dictionary<string, object?>> comisionesSemestreActual = ContainerApp.db.Query("comision").
                SearchObj(formGroupBox.DataContext).
                Size(0).
                ColOfDictCache();
            #endregion


        }
    }
}
