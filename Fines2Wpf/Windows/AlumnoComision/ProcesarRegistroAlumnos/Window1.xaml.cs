using Fines2Wpf.DAO;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
using SqlOrganize;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.CollectionUtils;

namespace Fines2Wpf.Windows.AlumnoComision.ProcesarRegistroAlumnos
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {


        private ObservableCollection<Data_alumno_comision_r> asignacionRegistroOC = new();

        public Window1()
        {
            InitializeComponent();
        }

 

        private (IDictionary<string, object?> pfidsComisiones, IDictionary<string, Data_alumno_comision_r> asignacionesDb) ConsultarDatosIniciales()
        {
            IDictionary<string, object?>  pfidsComisiones = ContainerApp.db.
                ComisionesAutorizadasDePeriodoSql(DateTime.Now.Year, 1).
                Cache().Dicts().
                DictOfDictByKeysValue("id", "pfid");

            IDictionary<string, Data_alumno_comision_r> asignacionesDb = ContainerApp.db.AsignacionesDeComisionesAutorizadasDelPeriodoSql(DateTime.Now.Year, 1).
                Cache().Dicts().
                Objs<Data_alumno_comision_r>().
                DictOfObjByPropertyNames("persona__numero_documento");

            return (pfidsComisiones, asignacionesDb);

        }

        private void ProcesarButton_Click(object sender, RoutedEventArgs e)
        {
            asignacionRegistroOC.Clear();

            var datosIniciales = ConsultarDatosIniciales();
        }

        private void GuardarButton_Click(object sender, RoutedEventArgs e)
        {

        }
    }
}
