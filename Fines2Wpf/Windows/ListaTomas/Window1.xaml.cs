using CommunityToolkit.WinUI.Notifications;
using Fines2Wpf.Data;
using Fines2Wpf.Windows.EnviarEmailToma;
using QRCoder;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Drawing;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using Utils;
using QuestPDF.Fluent;
using System.Linq;
using WpfUtils;
using System.Windows.Media;

namespace Fines2Wpf.Windows.ListaTomas
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        Search search = new();
        Fines2Wpf.DAO.Toma tomaDAO = new();
        QRCodeGenerator qrGenerator = new QRCodeGenerator();
        DataGridUtils dgu = new DataGridUtils(ContainerApp.db);

        private ObservableCollection<TomaPosesionPdf.ConstanciaData> tomaData = new();

        public Window1()
        {
            InitializeComponent();
            DataContext = search;
            tomaGrid.ItemsSource = tomaData;
            this.Loaded += MainWindow_Loaded;
            tomaGrid.CellEditEnding += TomaDataGrid_CellEditEnding!;
        }

        private void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        private void LoadData()
        {
            IEnumerable<Dictionary<string, object>> list = tomaDAO.TomasSemestre(search.calendario__anio, search.calendario__semestre);
            tomaData.Clear();
            tomaData.AddRange(list.ColOfObj<TomaPosesionPdf.ConstanciaData>());
        }
        private void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        /// <summary>Edición de celdas (no boolean) - CellEditEnding v1</summary>
        private void TomaDataGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            string key = "";
            object? value = null;

            var column = e.Column as DataGridBoundColumn;
            if (column != null)
            {
                key = ((Binding)column.Binding).Path.Path; //column's binding
                value = (e.EditingElement as TextBox)!.Text;
            }

            var columnT = e.Column as DataGridTemplateColumn;
            if (columnT != null)
            {
                var datePicker = VisualTreeHelper.GetChild(e.EditingElement, 0) as DatePicker;
                if (datePicker != null)
                {
                    key = datePicker.Name;
                    value = datePicker.SelectedDate;
                }
            }

            if (key.IsNullOrEmpty())
                return;

            var reload = dgu.DataGridCellEditEndingEventArgs_CellEditEnding<Data_toma_r>(e, "toma", key, value);
            if (reload)
                LoadData(); //debe recargarse para visualizar los cambios realizados en otras iteraciones
                                    //Dada una relacion a : b, si se modifica b correspondiente a a.b, se deberan actualizar todas las filas
        }

        /// <summary>DataGrid Delete Button v3 > Usuario</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/68</remarks>
        private void EliminarToma_Click(object sender, RoutedEventArgs e)
        {
            if (MessageBox.Show("¿Está seguro que desea eliminar?",
                "Eliminar Toma",
                MessageBoxButton.YesNo,
                MessageBoxImage.Question) == MessageBoxResult.Yes)
            {
                var button = (e.OriginalSource as Button);
                var data = (TomaPosesionPdf.ConstanciaData)button.DataContext;
                dgu.DeleteRowFromDataGrid("toma", tomaData, data, "Eliminar Toma");
            }
        }

        /// <summary>DataGrid Add Button v3 > Seccion</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/68</remarks>
        private void AgregarToma_Click(object sender, RoutedEventArgs e)
        {
            var data = new TomaPosesionPdf.ConstanciaData(DataInitMode.Default);
            //var someDataRelated = (Data_related)someGroupBox.DataContext; 
            //data.data_related = someDataRelated.id;
            tomaData.Add(data);
        }

        /// <summary>DataGrid Save Button v3 > Seccion</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/68</remarks>
        private void GuardarToma_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var data = (TomaPosesionPdf.ConstanciaData)button.DataContext;
            dgu.SaveRowFromDataGrid("seccion", data, Title);
        }

        /// <summary>
        /// Actualizar checkbox
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void TomaGrid_CellCheckBoxClick(object sender, RoutedEventArgs e)
        {
            var cell = (sender as DataGridCell);
            var column = cell.Column as DataGridBoundColumn;
            var value = (cell.Content as CheckBox).IsChecked;
            if (column != null && cell.DataContext is Data_toma_r)
            {
                string key = ((Binding)column.Binding).Path.Path; //column's binding.
                IDictionary<string, object> source = (cell.DataContext as Data_toma_r).Dict(); 
                if((bool)source[key] != value)
                {
                    string? fieldId = null;
                    string entityName = "toma", fieldName = key;
                    if (key.Contains("__"))
                        (fieldId, fieldName, entityName) = ContainerApp.db.KeyDeconstruction(entityName, key);

                    EntityValues v = ContainerApp.db.Values(entityName, fieldId).Set(source);
                    v.Sset(fieldName, value);

                    if (v.Check())
                        ContainerApp.dao.Persist(v);

                    DataGridRow row = DataGridRow.GetRowContainingElement(cell);
                    (row.Item as Data_toma_r).CopyValues<Data_toma_r>(v.Get().Obj<Data_toma_r>(),sourceNotNull:true);

                    if(!fieldId.IsNullOrEmpty())
                        LoadData(); //debe recargarse para visualizar los cambios realizados en otras iteraciones
                }
            }
        }

        private void EmailTomaButton_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var toma = (Data_toma_r)button.DataContext;
            if (toma.docente__email_abc.IsNullOrEmpty())
            {
                new ToastContentBuilder()
                .AddText("Email abc no definido")
                .Show();
                  return;
            }
            Email email = new Email(toma);
            email.Send();
            new ToastContentBuilder()
            .AddText("Email de toma enviado")
            .Show();
        }

        private void GenerarTomaButton_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var toma = (TomaPosesionPdf.ConstanciaData)button.DataContext;
            QRCodeData qrCodeData = qrGenerator.CreateQrCode("https://planfines2.com.ar/validar-toma/" + toma.id, QRCodeGenerator.ECCLevel.Q);
            QRCode qrCode = new QRCode(qrCodeData);
            Bitmap qrCodeImage = qrCode.GetGraphic(20);
            ImageConverter converter = new ImageConverter();
            toma.qr_code = (byte[])converter.ConvertTo(qrCodeImage, typeof(byte[]));
            TomaPosesionPdf.ConstanciaDocument document = new(toma);
            document.GeneratePdf("C:\\Users\\ivan\\Downloads\\" + toma.comision__pfid + "_" + toma.asignatura__codigo + "_" + toma.docente__numero_documento + ".pdf");
        }

        private void EliminarTomaButton_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var toma = (TomaPosesionPdf.ConstanciaData)button.DataContext;
            try { 
                ContainerApp.db.Persist().DeleteIds("toma", toma.id!).Exec().RemoveCache();
                LoadData();
                new ToastContentBuilder()
                        .AddText(Title)
                        .AddText("Toma eliminada")
                        .Show();
            } catch (Exception ex)
            {
                new ToastContentBuilder()
                    .AddText(Title)
                    .AddText("ERROR: " + ex.Message)
                    .Show();

            }
        }

        private void ActualizarPlanillaDocente_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                var idTomas = DAO.Toma2.IdTomasAprobadasSinPlanillaDocenteSemestre(calendarioAnioText.Text, calendarioSemestreText.Text);

                if (!idTomas.Any())
                    throw new Exception("No existen tomas para cargar planilla docente");

                EntityPersist persist = ContainerApp.db.Persist();

                foreach (var idToma in idTomas)
                {
                    ContainerApp.db.Values("asignacion_planilla_docente").
                        Set("toma", idToma).
                        Set("planilla_docente", idPlanillaDocenteTextBox.Text).
                        Default().
                        Reset().
                        Insert(persist);
                }

                persist.Exec().RemoveCache();
            }catch (Exception ex)
            {
                new ToastContentBuilder()
                .AddText(Title)
                .AddText("ERROR: " + ex.Message)
                .Show();
            }
        }

        private void PasarTodoButton_Click(object sender, RoutedEventArgs e)
        {
            IEnumerable<object> id_tomas_aprobadas = DAO.Toma2.TomasAprobadasDePeriodoSql(calendarioAnioText.Text, calendarioSemestreText.Text).ColOfDictCache().ColOfVal<object>("id");

            ContainerApp.db.Persist().UpdateValueIds("toma", "estado_contralor", "Pasar", id_tomas_aprobadas.ToArray()).Exec().RemoveCacheDetail();
            
            LoadData();
        }
    }

    internal class Search
    {
        public string calendario__anio { get; set; } = DateTime.Now.Year.ToString();
        public int calendario__semestre { get; set; } = DateTime.Now.ToSemester();
    }



}
