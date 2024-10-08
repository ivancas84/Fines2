﻿using CommunityToolkit.WinUI.Notifications;
using Fines2Wpf.Windows.EnviarEmailToma;
using QRCoder;
using SqlOrganize;
using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Drawing;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using QuestPDF.Fluent;
using System.Linq;
using WpfUtils;
using System.Windows.Media;
using SqlOrganize.CollectionUtils;
using SqlOrganize.ValueTypesUtils;
using SqlOrganize.Sql.Fines2Model3;

namespace Fines2Wpf.Windows.ListaTomas
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        Search search = new();
        QRCodeGenerator qrGenerator = new QRCodeGenerator();

        private ObservableCollection<TomaPosesionPdf.ConstanciaData> tomasAprobadasOC = new();
        private ObservableCollection<TomaPosesionPdf.ConstanciaData> tomasParticularesOC = new();
        private ObservableCollection<TomaPosesionPdf.ConstanciaData> tomasRenunciadasOC = new();
        private ObservableCollection<TomaContralor> tomasContralorOC = new();


        public Window1()
        {
            InitializeComponent();
            DataContext = search;
            tomasAprobadasDataGrid.ItemsSource = tomasAprobadasOC;
            tomasRenunciadasDataGrid.ItemsSource = tomasRenunciadasOC;
            tomasParticularesDataGrid.ItemsSource = tomasParticularesOC;
            tomasContralorDataGrid.ItemsSource = tomasContralorOC;
            this.Loaded += MainWindow_Loaded;
            tomasAprobadasDataGrid.CellEditEnding += TomaDataGrid_CellEditEnding!;
            tomasRenunciadasDataGrid.CellEditEnding += TomaDataGrid_CellEditEnding!;
            tomasParticularesDataGrid.CellEditEnding += TomaDataGrid_CellEditEnding!;

        }

        private void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        private void LoadData()
        {
            tomasAprobadasOC.Clear();
            tomasAprobadasOC.AddRange(
                DAO.Toma2.TomasAprobadasSinModificarDePeriodoSql(search.calendario__anio, search.calendario__semestre).
                Cache().Dicts().
                Objs<TomaPosesionPdf.ConstanciaData>()
            );

            tomasRenunciadasOC.Clear();
            tomasRenunciadasOC.AddRange(
                DAO.Toma2.TomasRenunciaBajaSinModificarDePeriodoSql(search.calendario__anio, search.calendario__semestre).
                Cache().Dicts().
                Objs<TomaPosesionPdf.ConstanciaData>()
            );

            tomasParticularesOC.Clear();
            tomasParticularesOC.AddRange(
                DAO.Toma2.TomasParticularesDePeriodoSql(search.calendario__anio, search.calendario__semestre).
                Cache().Dicts().
                Objs<TomaPosesionPdf.ConstanciaData>()
            );


            IEnumerable<object> idTomas = DAO.Toma2.IdTomasPasarSinPlanillaDocenteDePeriodo(search.calendario__anio, search.calendario__semestre);
            if (idTomas.Any())
            {
                var tomasContralorData = ContainerApp.db.Sql("toma").Size(0)
                .Where(@"
                    $id IN ( @0 ) 
                ")
                .Param("@0", idTomas).Cache().Dicts();
    
                 tomasContralorOC.Clear();

                foreach (var item in tomasContralorData)
                {
                    var tomaObj = item.Obj<TomaContralor>();

                    tomaObj.docente__cuil = tomaObj.docente__cuil;

                    tomaObj.dia_desde = ((DateTime)tomaObj.fecha_toma!).ToString("dd");
                    tomaObj.mes_desde = ((DateTime)tomaObj.fecha_toma!).ToString("MM");
                    tomaObj.anio_desde = ((DateTime)tomaObj.fecha_toma!).ToString("yyyy");

                    tomaObj.docente__Label = tomaObj.docente__apellidos!.ToUpper() + " " + tomaObj.docente__nombres!.ToTitleCase();
                    tomasContralorOC.Add(tomaObj);
                    tomaObj.plan__Label = tomaObj.plan__orientacion!.Acronym();

                    if (tomaObj.comision__turno.IsNoE())
                        tomaObj.planificacion__Label = "V";
                    else
                        tomaObj.planificacion__Label = tomaObj.comision__turno!.Acronym();

                }
            }


        }
        private void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        /// <summary>Edición de celdas (no boolean) - CellEditEnding v1</summary>
        private void TomaDataGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            try
            {
                string key = "";
                object? value = null;
                if (e.Column is DataGridCheckBoxColumn)
                    return;

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

                if (key.IsNoE())
                    return;

                var reload = ContainerApp.db.DataGridCellEditEndingEventArgs_CellEditEnding<Data_toma_r>(e, "toma", key, value);
                if (reload)
                    LoadData(); //debe recargarse para visualizar los cambios realizados en otras iteraciones
                                //Dada una relacion a : b, si se modifica b correspondiente a a.b, se deberan actualizar todas las filas
            } catch (Exception ex)
            {
                new ToastContentBuilder()
                .AddText("ERROR: " + ex.Message)
                .Show();
            }
        }


        /// <summary>DataGrid Add Button v3 > Seccion</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/68</remarks>
        private void AgregarToma_Click(object sender, RoutedEventArgs e)
        {
            var data = ContainerApp.db.Data<TomaPosesionPdf.ConstanciaData>();
            //var someDataRelated = (Data_related)someGroupBox.DataContext; 
            //data.data_related = someDataRelated.id;
            tomasAprobadasOC.Add(data);
        }

        /// <summary>DataGrid Save Button v3 > Seccion</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/68</remarks>
        private void GuardarToma_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var data = (TomaPosesionPdf.ConstanciaData)button.DataContext;
            ContainerApp.db.SaveRowFromDataGrid("seccion", data, Title);
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

                    EntityVal v = ContainerApp.db.Values(entityName, fieldId).Set(source);
                    v.Sset(fieldName, value);

                    if (v.Check())
                        v.Persist();

                    DataGridRow row = DataGridRow.GetRowContainingElement(cell);
                    (row.Item as Data_toma_r).CopyValues<Data_toma_r>(v.Get().Obj<Data_toma_r>(),sourceNotNull:true);

                    if(!fieldId.IsNoE())
                        LoadData(); //debe recargarse para visualizar los cambios realizados en otras iteraciones
                }
            }
        }

        private void EmailTomaButton_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var toma = (Data_toma_r)button.DataContext;
            if (toma.docente__email_abc.IsNoE())
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
                var idTomas = DAO.Toma2.IdTomasPasarSinPlanillaDocenteDePeriodo(calendarioAnioText.Text, calendarioSemestreText.Text);

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
            IEnumerable<object> id_tomas_aprobadas = DAO.Toma2.TomasPasarDePeriodoSql(calendarioAnioText.Text, calendarioSemestreText.Text).Cache().Dicts().ColOfVal<object>("id");

            ContainerApp.db.Persist().UpdateValueIds("toma", "estado_contralor", "Pasar", id_tomas_aprobadas.ToArray()).Exec().RemoveCacheDetail();
            
            LoadData();
        }
    }

    internal class Search
    {
        public string calendario__anio { get; set; } = "2024";
        public int calendario__semestre { get; set; } = 1;
    }



}
