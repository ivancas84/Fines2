using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;

namespace Fines2Wpf.Windows.AlumnoComision
{
    /// <summary>
    /// Lógica de interacción para AlumnosSemestreSinGenero.xaml
    /// </summary>
    public partial class AlumnosSemestreSinGenero : Window
    {
        private DAO.AlumnoComision dataDAO = new();
        private ObservableCollection<Data_alumno_r> alumnosSinGenero = new();
        public AlumnosSemestreSinGenero()
        {
            InitializeComponent();
            dataGrid.ItemsSource = alumnosSinGenero;
            this.Loaded += MainWindow_Loaded;
        }

        private void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        private void LoadData()
        {
            IEnumerable<Dictionary<string, object>> alumnos = dataDAO.AlumnosActivosDeComisionesAutorizadasPorSemestreSinGenero(ContainerApp.config.anio, ContainerApp.config.semestre);

            foreach (var alumno in alumnos)
            {
                var a = alumno.Obj<Data_alumno_r>();
                var nombres = a.persona__nombres.Split(" ");
                string? genero = null;

                foreach(var nombre in nombres)
                {
                    var p = ContainerApp.db.Persist();

                    var lastChar = nombre.ToLower()[nombre.Length - 1];
                    
                    if (lastChar.Equals('o'))
                    {
                        genero = "Masculino";
                    } else if (lastChar.Equals('a'))
                    {
                        genero = "Femenino";
                    }

                    if (!genero.IsNoE())
                    {
                        p.UpdateValueIds("persona", "genero", genero, a.persona__id).Exec().RemoveCache();
                        genero = null;
                        break;
                    }
                }

                alumnosSinGenero.Clear();
                alumnos = dataDAO.AlumnosActivosDeComisionesAutorizadasPorSemestreSinGenero("2023", "2");
                alumnosSinGenero.AddRange(alumnos.ColOfObj<Data_alumno_r>());
            }


        }
    }


    
}
