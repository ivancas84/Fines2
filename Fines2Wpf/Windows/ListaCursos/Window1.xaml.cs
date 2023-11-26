using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using Utils;
using Fines2Wpf.Model;
using ToastNotifications;
using ToastNotifications.Lifetime;
using ToastNotifications.Position;
using ToastNotifications.Messages;

using System.Linq;
using CommunityToolkit.WinUI.Notifications;

namespace Fines2Wpf.Windows.ListaCursos
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        Search search = new();
        Fines2Wpf.DAO.Curso cursoDAO = new();
        private ObservableCollection<Data_curso_r> cursoData = new();

        private Notifier notifier = new Notifier(cfg =>
        {
            cfg.PositionProvider = new WindowPositionProvider(
                parentWindow: Application.Current.MainWindow,
                corner: Corner.BottomRight,
                offsetX: 10,
                offsetY: 10);

            cfg.LifetimeSupervisor = new TimeAndCountBasedLifetimeSupervisor(
                notificationLifetime: TimeSpan.FromSeconds(3),
                maximumNotificationCount: MaximumNotificationCount.FromCount(5));

            cfg.Dispatcher = Application.Current.Dispatcher;
        });

        public Window1()
        {
            InitializeComponent();
            DataContext = search;
            cursoGrid.ItemsSource = cursoData;
            this.Loaded += MainWindow_Loaded;
            cursoGrid.CellEditEnding += CursoGrid_CellEditEnding!;
        }

        private void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        private void LoadData()
        {
            cursoData.Clear();
            try
            {
                IEnumerable<Dictionary<string, object>> list = cursoDAO.CursosSemestre(search.calendario__anio, search.calendario__semestre);
                cursoData.AddRange(list.ColOfObj<Data_curso_r>());
            }
            catch (Exception ex)
            {
                notifier.ShowError(ex.Message);
            }

        }
        private void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            LoadData();
        }

        private void CursoGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            if (e.EditAction == DataGridEditAction.Commit)
            {
                var column = e.Column as DataGridBoundColumn;
                if (column != null)
                {

                    string key = ((Binding)column.Binding).Path.Path; //column's binding
                    object value = (e.EditingElement as TextBox)!.Text;
                    Dictionary<string, object> source = (Dictionary<string, object>)((Data_curso_r)e.Row.DataContext).Dict();
                    string? fieldId = null;
                    string mainEntityName = "curso";
                    string entityName = "curso";
                    string fieldName = key;

                    if (key.Contains("__"))
                    {
                        int i = key.IndexOf("__");
                        fieldId = key.Substring(0, i);
                        entityName = ContainerApp.db.Entity(entityName!).relations[fieldId].refEntityName;
                        fieldName = key.Substring(i + "__".Length);
                    }

                    bool continueWhile;
                    bool reload = false;
                    do
                    {
                        continueWhile = (fieldId == null) ? false : true;
                        EntityValues v = ContainerApp.db.Values(entityName, fieldId).Set(source);
                        if (!v.values[fieldName].IsNullOrEmpty() && v.values[fieldName].Equals(value))
                        {
                            if (reload)
                                LoadData(); //debe recargarse para visualizar los cambios realizados en otras iteraciones.
                            break;
                        }

                        v.Sset(fieldName, value);
                        IDictionary<string, object> ? row;

                        //en caso de que el campo editado sea unico, se consultan sus valores
                        if (ContainerApp.db.Field(entityName, fieldName).IsUnique())
                            row = ContainerApp.dao.RowByFieldValue(entityName, fieldName, value);
                        else
                            row = ContainerApp.dao.RowByUniqueWithoutIdIfExists(entityName, v.values);

                        if (!row.IsNullOrEmpty())
                        {
                            v = ContainerApp.db.Values(entityName).Set(row!);
                            v.fieldId = fieldId;
                        }
                        else
                        {
                            if (!v.Check())
                            {
                                (e.Row.Item as Data_curso_r).CopyValues<Data_curso_r>(v.Get().Obj<Data_curso_r>(),sourceNotNull:true);
                                break;
                            }

                            if (v.Get(ContainerApp.config.id).IsNullOrEmpty())
                            {
                                v.Default().Reset();
                                var p = ContainerApp.db.Persist(entityName).Insert(v.values).Exec().RemoveCache();
                            }
                            else
                            {
                                v.Reset();
                                var p = ContainerApp.db.Persist(entityName).Update(v.values).Exec().RemoveCache();
                            }
                        }

                        (e.Row.Item as Data_curso_r).CopyValues<Data_curso_r>(v.Get().Obj<Data_curso_r>(),sourceNotNull:true);

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

        private void AgregarCalificaciones_Click(object sender, RoutedEventArgs e)
        {
            var button = (e.OriginalSource as Button);
            var data = (Data_curso_r)button.DataContext;
            Calificacion.CargarCalificacionesCurso.Window1 win = new(data.id!);
            win.WindowState = WindowState.Maximized;
            win.Show();

        }
    }

    internal class Search
    {
        public string calendario__anio { get; set; } = DateTime.Now.Year.ToString();
        public int calendario__semestre { get; set; } = DateTime.Now.ToSemester();
    }


}
