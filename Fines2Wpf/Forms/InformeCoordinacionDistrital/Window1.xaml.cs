using SqlOrganize;
using System.Collections.Generic;
using System.Windows;

namespace Fines2Wpf.Forms.InformeCoordinacionDistrital
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        public Window1()
        {
            InitializeComponent();
        }

        private void OnLoaded(object sender, RoutedEventArgs e)
        {
            var alumnoComisionData = new DAO.AlumnoComision();
            IEnumerable<Dictionary<string, object>> data = alumnoComisionData.InformeCoordinacionDistrital("1", "2023", 1);
            InformeCoordinacionDistritalDataGrid.ItemsSource = data.Objs<InformeCoordinacionDistrital>();
        }

    }
}
