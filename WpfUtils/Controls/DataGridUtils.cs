#nullable enable
using SqlOrganize;
using SqlOrganize.Sql;
using SqlOrganize.ValueTypesUtils;
using System.Collections.ObjectModel;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;

namespace WpfUtils.Controls
{
    //Comportamiento general para DataGrid
    public static class DataGridUtils
    {
        #region CellEditEnding
        /// <summary>
        /// Metodo general para editar
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        public static void DgdCellEditEnding(this Db db, object? sender, DataGridCellEditEndingEventArgs e)
        {
            (string key, object? value) = e.DgdGetKeyAndValue();

            db.DgdUpdateKeyAndValue(sender, e, key, value);
        }

        public static void DgdUpdateKeyAndValue(this Db db, object? sender, DataGridCellEditEndingEventArgs e, string key, object? value)
        {
            DataGrid grid = (sender as DataGrid)!;

            // Get the item (object) being edited
            var editedObject = e.Row.Item;

            if (!key.IsNoE())
            {
                var keys = key.Split(".");

                for (var i = 0; i < (keys.Count() - 1); i++)
                    editedObject = editedObject!.GetPropertyValue<object>(keys[i]);

                using (db.CreateQueue())
                {
                    (editedObject as Entity)!.UpdateFieldValue(keys.Last(), value);
                    db.ProcessQueue();
                }
            }
        }

        /// <summary>Obtener key and value, en el procesamiento de columnas</summary>
        /// <remarks>No utilizado, se deja como referencia</remarks>
        public static (string key, object? value) DgdGetKeyAndValue(this DataGridCellEditEndingEventArgs e)
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
        #endregion

        #region botones Eliminar, Guardar y Agregar
        public static void DgdDeleteRow<T>(this ObservableCollection<T> oc, object sender) where T : Entity
        {
            if (MessageBox.Show("¿Está seguro que desea eliminar?",
                "Eliminar",
                MessageBoxButton.YesNo,
                MessageBoxImage.Question) == MessageBoxResult.Yes)
            {
                try
                {
                    if (sender is FrameworkElement element && element.DataContext is Entity entity)
                    {
                        entity.DeleteFromOC(oc);
                        ToastExtensions.Show("Registro eliminado: " + entity.entityName.ToTitleCase());
                    }
                }
                catch (Exception ex)
                {
                    ex.ToastException();
                }
            }
        }

        public static void DgdAddRow<T>(this ObservableCollection<T> oc) where T : Entity, new()
        {
            var entity = new T();
            oc.Add(entity);
        }

        public static void DgdPersistRow(this DataGrid dgd, object sender)
        {
            try
            {
                dgd.CommitEdit(DataGridEditingUnit.Row, true);

                if (sender is FrameworkElement element && element.DataContext is Entity entity)
                {
                    entity.Persist();
                    ToastExtensions.Show("Registro agregado: " + entity.entityName.ToTitleCase());
                }
            }
            catch (Exception ex)
            {
                ex.ToastException();
            }
        }
        #endregion


    }
}
