﻿using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.ComponentModel;
using System.Windows.Threading;
using SqlOrganize;
using WpfUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.CollectionUtils;

namespace Fines2Wpf.Windows.Comision.ListaComisionesSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private Data_comision_r comisionSearch = new();
        private DAO.Comision comisionDAO = new();

        #region Filter con delay v1 - Atributos
        private ObservableCollection<Data_comision_r> comisionOC = new();
        private ICollectionView comisionCV;
        DispatcherTimer comisionTypingTimer;
        #endregion

        public Window1()
        {
            InitializeComponent();

            #region Filter con delay v1 - Inicializar atributos
            var comisionCVS = new CollectionViewSource() { Source = comisionOC };
            comisionCV = comisionCVS.View;
            comisionCV.Filter = ComisionCV_Filter;
            comisionGrid.ItemsSource = comisionCV;
            #endregion

            comisionGrid.CellEditEnding += ComisionGrid_CellEditEnding!;
            comisionSearch.calendario__anio  = ContainerApp.config.anio;
            comisionSearch.calendario__semestre  = ContainerApp.config.semestre;
            comisionSearch.autorizada  = true;

            DataContext = comisionSearch;

            this.autorizadaCombo.SelectedValuePath = "Key";
            this.autorizadaCombo.DisplayMemberPath = "Value";
            this.autorizadaCombo.Items.Add(new KeyValuePair<bool?, string>(null, "(Todos)"));
            this.autorizadaCombo.Items.Add(new KeyValuePair<bool, string>(true, "Sí"));
            this.autorizadaCombo.Items.Add(new KeyValuePair<bool, string>(false, "No"));

            Loaded += MainWindow_Loaded;
        }

        /// <summary>Filter con delay v1 - Filter</summary>
        private bool ComisionCV_Filter(object obj)
        {
            var o = obj as Data_comision_r;
            string tramo = o.planificacion__anio + "/" + o.planificacion__semestre;
            return comisionFilterTextBox.Text.IsNoE()
                || (tramo.ToLower().Contains(comisionFilterTextBox.Text.ToLower()))
                || (!o.pfid.IsNoE() && o.pfid!.ToString().ToLower().Contains(comisionFilterTextBox.Text.ToLower()))
                || (!o.plan__orientacion.IsNoE() && o.plan__orientacion!.ToString().ToLower().Contains(comisionFilterTextBox.Text.ToLower()))
                || (!o.Label.IsNoE() && o.Label!.ToString().ToLower().Contains(comisionFilterTextBox.Text.ToLower()))
                || (!o.sede__nombre.IsNoE() && o.sede__nombre!.ToString().ToLower().Contains(comisionFilterTextBox.Text.ToLower()))
                || (!o.sede__numero.IsNoE() && o.sede__numero!.ToString().ToLower().Contains(comisionFilterTextBox.Text.ToLower()));

        }

        /// <summary>Filter con delay v1 - TextChanged</summary>
        private void ComisionFilterTextBox_TextChanged(object sender, TextChangedEventArgs e)
        {

            if (comisionTypingTimer == null)
            {
                comisionTypingTimer = new DispatcherTimer();
                comisionTypingTimer.Interval = TimeSpan.FromMilliseconds(300);
                comisionTypingTimer.Tick += new EventHandler(ComisionFilterTextBox_HandleTypingTimerTimeout);

            }

            comisionTypingTimer.Stop(); // Resets the timer
            comisionTypingTimer.Tag = (sender as TextBox).Text; // This should be done with EventArgs
            comisionTypingTimer.Start();
        }

        /// <summary>Filter con delay v1 - HandleTypingTimerTimeout</summary>
        private void ComisionFilterTextBox_HandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer;
            if (timer == null)
                return;


            if (comisionCV != null)
                comisionCV.Refresh();

            timer.Stop(); // The timer must be stopped! We want to act only once per keystroke.
        }
        private void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        private void LoadData()
        {
            IEnumerable<Dictionary<string, object?>> list = ContainerApp.db.Sql("comision").Search(comisionSearch).Size(0).Cache().Dicts();
            IEnumerable<object> idsSede = list.ColOfVal<object>("sede");
            IEnumerable<object> idsComision = list.ColOfVal<object>("id");

            Dictionary<string, List<Dictionary<string, object?>>> referentesData = new();
            if(idsSede.Count() > 0 ) { 
                referentesData = (Dictionary<string, List<Dictionary<string, object?>>>)ContainerApp.db.Sql("designacion").
                Where("$cargo__descripcion IN ('Colaborador', 'Referente') AND $sede IN( @0 ) AND $hasta IS NULL").
                Param("@0", idsSede).
                Cache().Dicts().
                DictOfListByKeys("sede");
            }

            Dictionary<string, List<Dictionary<string, object?>>> horariosComision = new();


            Dictionary<string, object?> cantidadAlumnosActivosPorComision = new();
            Dictionary<string, object?> cantidadAlumnosPorComision = new();


            if (idsComision.Count() > 0 )
            {
                cantidadAlumnosActivosPorComision = (Dictionary<string, object?>)ContainerApp.db.Sql("alumno_comision").
                    Select("COUNT($id) AS cantidad").
                    Group("$comision").
                    Size(0).
                    Where(@"
                        $comision IN ( @0 ) AND $estado = 'Activo'
                    ").
                    Param("@0", idsComision).
                    Dicts().
                    DictOfDictByKeysValue("cantidad", "comision");

                cantidadAlumnosPorComision = (Dictionary<string, object?>)ContainerApp.db.Sql("alumno_comision").
                    Select("COUNT($id) AS cantidad").
                    Group("$comision").
                    Size(0).
                    Where(@"
                        $comision IN ( @0 )
                    ").
                    Param("@0", idsComision).
                    Dicts().
                    DictOfDictByKeysValue("cantidad", "comision");

                horariosComision = (Dictionary<string, List<Dictionary<string, object?>>>)comisionDAO.HorariosQuery(idsComision).Cache().Dicts().DictOfListByKeys("curso__comision");

            }

            comisionOC.Clear();
            foreach (IDictionary<string, object> item in list)
            {
                var comision = (ComisionValues)ContainerApp.db.Values("comision").SetValues(item);
                var o = item.Obj<Comision>();
                o.Label = comision.Numero();
                o.domicilio__Label = comision.GetValuesCache("domicilio")?.ToString() ?? "";

                List<string> referentes = new();
                if (referentesData.ContainsKey(o.sede!))
                    foreach (Dictionary<string, object?> designacion in referentesData[o.sede!])
                        referentes.Add(ContainerApp.db.Values("designacion").Set(designacion).ToString());

                o.referentes = referentes;

                if(horariosComision.ContainsKey(o.id))
                    o.horario= comision.Horario(horariosComision[o.id]);

                o.cantidad_alumnos_activos = cantidadAlumnosActivosPorComision.ContainsKey(o.id!) ? (long?)cantidadAlumnosActivosPorComision[o.id!] : 0;
                o.cantidad_alumnos = cantidadAlumnosPorComision.ContainsKey(o.id!) ? (long?)cantidadAlumnosPorComision[o.id!] : 0;

                comisionOC.Add(o);
            }
        }


        private void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            LoadData();
        }


        private void ComisionGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            if (e.EditAction == DataGridEditAction.Commit)
            {
                var column = e.Column as DataGridBoundColumn;
                if (column != null)
                {
                    string key = ((Binding)column.Binding).Path.Path; //column's binding
                    Dictionary<string, object> source = (Dictionary<string, object>)((Data_comision_r)e.Row.DataContext).Dict();
                    string value = (e.EditingElement as TextBox)!.Text;
                    ContainerApp.db.Persist().UpdateValueRel("comision", key, value, source).Exec().RemoveCache();
                }
            }
        }

        #region eventos alumnos
        private void CargarAlumnos_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var comision = (Data_comision)button.DataContext;
            AlumnoComision.CargarNuevosAlumnos.Window1 win = new(comision.id);
            win.Show();
        }

        private void EliminarAlumnos_Click(object sender, RoutedEventArgs e)
        {
            try { 
            var button = (e.OriginalSource as Button);
            var comision = (Data_comision)button.DataContext;
            object[] ids = ContainerApp.db.AsignacionesDeComisionesSql(comision.id).Column<object>("id").ToArray();
            ContainerApp.db.Persist().DeleteIds("alumno_comision", ids).Exec().RemoveCache();
            ToastUtils.Show("Asignaciones eliminadas correctamente");
            } catch (Exception ex)
            {
                ToastUtils.ShowExceptionMessageWithFileNameAndLineNumber(ex);
            }
        }
        #endregion

        void OnNumeroClick(object sender, RoutedEventArgs e)
        {
            var data = ((Hyperlink)e.OriginalSource).DataContext as Data_comision;
            AdministrarComision.Window1 win = new(data!.id!);
            win.Show();
        }

        
    }
}
