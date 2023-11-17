using Fines2Wpf.Model;
using Fines2Wpf.Values;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
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
using System.Windows.Threading;
using Utils;

namespace Fines2Wpf.Windows.Calificacion.CargarCalificacionesComision
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private ObservableCollection<Data_calendario> calendarioOC = new(); //datos consultados de la base de datos
        private ObservableCollection<Data_curso> cursoOC = new(); //datos consultados de la base de datos
        private DispatcherTimer cursoTypingTimer;
        private DAO.Curso cursoDAO = new();
        private FormData formData = new();

        public Window1()
        {
            InitializeComponent();

            #region calendarioComboBox
            calendarioComboBox.ItemsSource = calendarioOC;
            calendarioComboBox.DisplayMemberPath = "Label";
            calendarioComboBox.SelectedValuePath = "id";

            var data = ContainerApp.db.Query("calendario").ColOfDictCache();
            calendarioOC.Clear();
            foreach (var item in data)
            {
                var cal = item.Obj<Data_calendario>();
                cal.Label = ContainerApp.db.Values("calendario").ToString();
                calendarioOC.Add(cal);
            }
            #endregion

            #region cursoComboBox
            cursoComboBox.ItemsSource = cursoOC;
            cursoComboBox.DisplayMemberPath = "Label";
            cursoComboBox.SelectedValuePath = "id";
            #endregion

            formGroupBox.DataContext = formData;
        }


        private void LoadCursos()
        {
            cursoOC.Clear();

            if (formData.calendario.IsNullOrEmptyOrDbNull())
                return;

            var data = ContainerApp.db.Query("curso").
                Where("$calendario-id = @0").
                Parameters(formData.calendario!).ColOfDictCache();

            foreach (var item in data)
            {
                var curso = item.Obj<Data_curso>();
                curso.Label = ContainerApp.db.Values("curso").Set(item).ToString();
                cursoOC.Add(curso);
            }
        }


    }
}
