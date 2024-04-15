using Fines2Wpf.Data;
using System.Collections.ObjectModel;
using System.Windows;
using Utils;


namespace Fines2Wpf.Windows.Toma.Contralor
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        DAO.Toma tomaDAO = new();

        ObservableCollection<Contralor.Toma> tomasOC = new();

        public Window1()
        {
            InitializeComponent();
            tomaDataGrid.ItemsSource = tomasOC;

            var tomas = tomaDAO.TomasAprobadasConfirmadasQuery("2024", "1").ColOfDictCache();

            tomasOC.Clear();
            foreach (var item in tomas)
            {
                Contralor.Toma tomaObj = item.Obj<Contralor.Toma>();
                tomaObj.docente__Label = tomaObj.docente__apellidos!.ToUpper() + " " + tomaObj.docente__nombres!.ToTitleCase();
                tomasOC.Add(tomaObj);
                tomaObj.plan__Label = tomaObj.plan__orientacion!.Acronym();

                if (tomaObj.comision__turno.IsNullOrEmpty())
                    tomaObj.planificacion__Label = "V";
                else
                    tomaObj.planificacion__Label = tomaObj.comision__turno!.Acronym();

            }


        }
    }
}
