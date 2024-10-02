#nullable enable
using CommunityToolkit.WinUI.Notifications;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using System.Collections.ObjectModel;
using System.Windows.Controls;
using System.Windows.Data;

namespace WpfUtils.Controls
{
    //Comportamiento general para DataGrid
    public static class DataGridUtils
    {

        /// <summary>Obtener key and value, en el procesamiento de columnas</summary>
        /// <remarks>No utilizado, se deja como referencia</remarks>
        public static (string key, object? value) GetKeyAndValue(this DataGridCellEditEndingEventArgs e)
        {
            string key = "";
            object? value = null;

            var columnCh = e.Column as DataGridCheckBoxColumn; //los campos checkbox habitualmente se procesan de forma independiente porque al modificar hay que apretar enter o quitar el foco y resulta confuso.
            if (columnCh != null)
            {
                key = ((Binding)columnCh.Binding).Path.Path;
                value = (e.EditingElement as CheckBox)?.IsChecked ?? false; // Extract the checkbox value
                return (key, value);
            }

            var columnCo = e.Column as DataGridComboBoxColumn;
            if (columnCo != null)
            {
                key = ((Binding)columnCo.SelectedValueBinding).Path.Path; //column's binding
                value = (e.EditingElement as ComboBox)!.SelectedValue;
                return (key, value);
            }

            var column = e.Column as DataGridBoundColumn;
            if (column != null)
            {
                key = ((Binding)column.Binding).Path.Path; //column's binding (using System.Windows.Data)
                value = (e.EditingElement as TextBox)!.Text;
                return (key, value);
            }

            return (key, value);
        }

      

        /// <summary>Codigo general para eliminar una fila en un datagrid v2</summary>        
        public static void DeleteRowFromDataGrid<T>(this Db db, string entityName, ObservableCollection<T> oc, T data, string title = "")
        {
            try
            {
                if (!data.GetPropertyValue("id").IsNoE())
                {
                    using (db.CreateQueue())
                    {
                        db.Persist().DeleteIds(entityName, data.GetPropertyValue("id")!);
                        db.ProcessQueue();
                    }
                }
                oc.Remove(data);
            }
            catch (Exception ex)
            {
                ToastExtensions.ShowExceptionMessageWithFileNameAndLineNumber(ex, title);
            }
        }

        public static void SaveRowFromDataGrid(this Db db, Entity data, string title = "")
        {
            try
            {
                data.Persist();
                new ToastContentBuilder()
                 .AddText(title)
                 .AddText("Registro realizado")
                 .Show();
            }
            catch (Exception ex)
            {
                ToastExtensions.ShowExceptionMessageWithFileNameAndLineNumber(ex, title);
            }
        }

        /// <summary>
        /// Metodo general para editar
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        public static void CellEditEnding(this Db db, object? sender, DataGridCellEditEndingEventArgs e)
        {
            (string key, object? value) = e.GetKeyAndValue();

            db.UpdateKeyAndValue(sender, e, key, value);
        }

        public static void UpdateKeyAndValue(this Db db, object? sender, DataGridCellEditEndingEventArgs e, string key, object? value)
        {
            DataGrid grid = sender as DataGrid;

            // Get the item (object) being edited
            var editedObject = e.Row.Item;

            if (!key.IsNoE())
            {
                var keys = key.Split(".");

                for (var i = 0; i < (keys.Count() - 1); i++)
                    editedObject = editedObject.GetPropertyValue<object>(keys[i]);

                using (db.CreateQueue())
                {
                    (editedObject as Entity).UpdateFieldValue(keys.Last(), value);
                    db.ProcessQueue();
                }
            }
        }
    }
}
