using System.Collections.ObjectModel;
using System.Windows;
using Utils;
using SqlOrganize;


namespace Fines2Wpf.Windows.Toma.Contralor
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        ObservableCollection<Contralor.Toma> tomasOC = new();

        public Window1()
        {
            InitializeComponent();
            tomaDataGrid.ItemsSource = tomasOC;

            var idTomas = DAO.Toma2.IdTomasPasarSinPlanillaDocenteDePeriodo("2024", "1");
            var tomas = ContainerApp.db.Sql("toma").Where("$id IN (@0)").Parameters(idTomas).ColOfDictCache();

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
