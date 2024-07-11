using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.DateTimeUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Windows;

namespace Fines2Wpf.Windows.Curso.GenerarCursosSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        ObservableCollection<Data_comision> comisionesConCursosOC = new();
        ObservableCollection<Data_comision> comisionesSinCursosOC = new();

        public Window1()
        {
            InitializeComponent();

            Data_comision_r comisionObj = new();
            comisionObj.calendario__anio = Convert.ToInt16(DateTime.Now.Year);
            comisionObj.calendario__semestre = Convert.ToInt16(DateTime.Now.ToSemester());
            comisionObj.autorizada = true;
            formGroupBox.DataContext = comisionObj;

            comisionesConCursosDataGrid.ItemsSource = comisionesConCursosOC;
            comisionesSinCursosDataGrid.ItemsSource = comisionesSinCursosOC;
        }

        private void GenerarButton_Click(object sender, RoutedEventArgs e)
        {

            #region Consultar comisiones del semestre para generar cursos
            IEnumerable<Dictionary<string, object?>> comisionesAutorizadasSemestre = ContainerApp.db.Sql("comision").
                Search((Data_comision_r)formGroupBox.DataContext).
                Size(0).
                ColOfDict();
            #endregion

            #region consultar cursos de comisiones para verificar si ya existen cursos  (las comisiones con cursos generados se ignoran)
            IEnumerable<string> idComisionesAutorizadasSemestre = comisionesAutorizadasSemestre.ColOfVal<string>("id");

            IDictionary<string, List<Dictionary<string, object?>>> cursosDeComisionesAutorizadasSemestre = ContainerApp.db.Sql("curso").
                Where("$comision IN (@0)").
                Size(0).
                Parameters(idComisionesAutorizadasSemestre).
                ColOfDict().DictOfListByKeys("comision");
            #endregion

            EntityPersist persist = ContainerApp.db.Persist();
            foreach (Dictionary<string, object?> com in comisionesAutorizadasSemestre)
            {
                Data_comision_r comObj = com.Obj<Data_comision_r>();

                if (cursosDeComisionesAutorizadasSemestre.ContainsKey(comObj.id))
                {
                    comisionesConCursosOC.Add(comObj);
                    continue;
                }

                comisionesSinCursosOC.Add(comObj);

                IDictionary<string, object?> asignaturasDeComision = ContainerApp.db.Sql("distribucion_horaria").
                    Select("$disposicion-asignatura, SUM($horas_catedra) AS suma_horas_catedra").
                    Group("$disposicion-asignatura").
                    Where("$disposicion-planificacion = @0").
                    Parameters(comObj.planificacion).
                    ColOfDict().
                    DictOfDictByKeysValue("suma_horas_catedra", "disposicion-asignatura");

                foreach(var (asignatura, suma_horas_catedra) in asignaturasDeComision)
                {
                    EntityValues cursoVal = ContainerApp.db.Values("curso").
                        Set("comision", comObj.id).
                        Set("asignatura", asignatura).
                        Set("horas_catedra", suma_horas_catedra).
                        Default().Reset();

                    persist.Insert(cursoVal);
                }

            }

            if(comisionesSinCursosOC.Count() > 0)
                  persist.Transaction().RemoveCache();

        }
    }
}