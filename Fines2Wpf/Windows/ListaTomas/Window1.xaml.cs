using CommunityToolkit.WinUI.Notifications;
using Fines2Wpf.Data;
using Fines2Wpf.Windows.EnviarEmailToma;
using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using Utils;

namespace Fines2Wpf.Windows.ListaTomas
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        Search search = new();
        Fines2Wpf.DAO.Toma tomaDAO = new();
        
        private ObservableCollection<Data_toma_r> tomaData = new();

        public Window1()
        {
            InitializeComponent();
            DataContext = search;
            tomaGrid.ItemsSource = tomaData;
            this.Loaded += MainWindow_Loaded;
            tomaGrid.CellEditEnding += TomaGrid_CellEditEnding!;
        }

        private void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        private void LoadData()
        {
            IEnumerable<Dictionary<string, object>> list = tomaDAO.TomasSemestre(search.calendario__anio, search.calendario__semestre);
            tomaData.Clear();
            tomaData.AddRange(list.ColOfObj<Data_toma_r>());
        }
        private void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        private void TomaGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            if (e.EditAction == DataGridEditAction.Commit)
            {
                var column = e.Column as DataGridBoundColumn;
                if (column != null)
                {
                    List<string> ignore = new List<string>() { "confirmada", "alumno__tiene_constancia", "alumno__tiene_dni", "alumno__tiene_partida", "alumno__tiene_certificado" };
                    string key = ((Binding)column.Binding).Path.Path; //column's binding
                    if (ignore.Contains(key)) return;
                    object value = (e.EditingElement as TextBox)!.Text;
                    Dictionary<string, object> source = (Dictionary<string, object>)((Data_toma_r)e.Row.DataContext).Dict();
                    string? fieldId = null;
                    string mainEntityName = "toma", entityName = "toma", fieldName = key;

                    if (key.Contains("__"))
                        (fieldId, fieldName, entityName) = ContainerApp.db.KeyDeconstruction(entityName, key);

                    bool continueWhile;
                    bool reload = false;
                    do
                    {
                        continueWhile = (fieldId == null) ? false : true;
                        EntityValues v = ContainerApp.db.Values(entityName, fieldId).Set(source);
                        if (!v.GetOrNull(fieldName).IsNullOrEmpty() && v.Get(fieldName).Equals(value))
                        {
                            if (reload)
                                LoadData(); //debe recargarse para visualizar los cambios realizados en otras iteraciones.
                            break;
                        }

                        v.Sset(fieldName, value);
                        IDictionary<string, object>? row;

                        row = ContainerApp.dao.RowByUniqueFieldOrValues(fieldName, v);

                        if (!row.IsNullOrEmpty())
                        {
                            v = ContainerApp.db.Values(entityName).Set(row!);
                            v.fieldId = fieldId;
                        }
                        else
                        {
                            if (!v.Check())
                            {
                                (e.Row.Item as Data_toma_r).CopyValues<Data_toma_r>(v.Get().Obj<Data_toma_r>(),sourceNotNull:true);
                                break;
                            }

                            ContainerApp.dao.Persist(v);
                        }

                        (e.Row.Item as Data_toma_r).CopyValues<Data_toma_r>(v.Get().Obj<Data_toma_r>(), sourceNotNull: true);

                        if (fieldId != null)
                        {
                            string? parentId = ContainerApp.db.Entity(mainEntityName).relations[fieldId].parentId;
                            if (parentId != null)
                            {
                                var parentFieldName = ContainerApp.db.Entity(mainEntityName).relations[fieldId].fieldName;
                                value = v.Get()[fieldId + "-" + ContainerApp.db.Entity(mainEntityName).relations[fieldId].refFieldName];
                                fieldId = parentId;
                                fieldName = parentFieldName;
                                entityName = ContainerApp.db.Entity(mainEntityName).relations[parentId].refEntityName;

                            }
                            else
                            {
                                entityName = mainEntityName;
                                value = v.Get()[fieldId + "-" + ContainerApp.db.Entity(mainEntityName).relations[fieldId].refFieldName];
                                fieldName = ContainerApp.db.Entity(mainEntityName).relations[fieldId].fieldName;
                                fieldId = null;
                            }
                        }
                        reload = true;
                    }
                    while (continueWhile);
                }
            }
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
    }

    internal class Search
    {
        public string calendario__anio { get; set; } = DateTime.Now.Year.ToString();
        public int calendario__semestre { get; set; } = DateTime.Now.ToSemester();
    }



}
