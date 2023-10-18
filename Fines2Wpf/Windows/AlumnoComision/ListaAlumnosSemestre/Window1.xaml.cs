﻿using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
using Utils;
using Fines2Wpf.Data;
using System.Windows.Controls;
using System.Windows.Data;
using SqlOrganize;
using WpfUtils;

namespace Fines2Wpf.Windows.AlumnoComision.ListaAlumnosSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private DAO.Sede sedeDAO = new();
        private SqlOrganize.DAO dao = new(ContainerApp.db);
        public ObservableCollection<Asignacion> asignacionData = new();
        public Data_alumno_comision_r search = new(DataInitMode.Null);
        
        public Window1()
        {
            InitializeComponent();

            estadoCombo.SelectedValuePath = "Key";
            estadoCombo.DisplayMemberPath = "Value";
            estadoCombo.Items.Add(new KeyValuePair<string?, string>(null, "(Todos)"));
            estadoCombo.Items.Add(new KeyValuePair<string, string>("Activo", "Activo"));
            estadoCombo.Items.Add(new KeyValuePair<string, string>("No activo", "No activo"));
            estadoCombo.Items.Add(new KeyValuePair<string, string>("Mesa", "Mesa"));

            search.calendario__anio = 2023;
            search.calendario__semestre = 2;
            search.estado = "Activo";

            asignacionGrid.CellEditEnding += AsignacionGrid_CellEditEnding;

            asignacionGrid.ItemsSource = asignacionData;
            Loaded += Window1_Loaded;
        }



        public void LoadAsignaciones()
        {
            var data = dao.Search("alumno_comision", search);
            asignacionData.Clear();
            foreach (var d in data)
            {
                var v = (Values.AlumnoComision)ContainerApp.db.Values("alumno_comision").Values(d);
                var o = d.Obj<Asignacion>();
                o.comision__label = v.ValuesTree("comision")?.ToString() ?? "";
                asignacionData.Add(o);
            }
        }

        private void Window1_Loaded(object sender, RoutedEventArgs e)
        {
            LoadAsignaciones();
        }

        private void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            LoadAsignaciones();
        }


        private void AsignacionGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            if (e.EditAction != DataGridEditAction.Commit)
                return;

            var result = e.GetKeyAndValue();
            string key = result.key;
            object? value = result.value;

            if (key.IsNullOrEmpty())
                return;

            IDictionary<string, object?> source = e.Row.DataContext.Dict();
            string? fieldId = null;
            string mainEntityName = "alumno_comision", entityName = "alumno_comision", fieldName = key;
            if (key.Contains("__"))
                (fieldId, fieldName, entityName) = ContainerApp.db.KeyDeconstruction(entityName, key);

            bool continueWhile;
            bool reload = false;
            do
            {
                continueWhile = (fieldId == null) ? false : true;
                EntityValues v = ContainerApp.db.Values(entityName, fieldId).Set(source);
                if (!v.GetOrNull(fieldName).IsNullOrEmptyOrDbNull() && v.values[fieldName]!.Equals(value))
                {
                    if (reload)
                        LoadAsignaciones(); //debe recargarse para visualizar los cambios realizados en otras iteraciones
                    //Dada una relacion a : b, si se modifica b correspondiente a a.b, se deberan actualizar todas las filas para reflejar a.b correctamente.

                    break;
                }

                v.Sset(fieldName, value);
                IDictionary<string, object>? row = dao.RowByUniqueFieldOrValues(fieldName, v);
                if (!row.IsNullOrEmpty()) //con el nuevo valor ingresados se obtuvo un nuevo campo unico, no se realiza persistencia y se cambian los valores para reflejar el nuevo valor consultado
                {
                    v.Clear().Set(row!);
                    (e.Row.Item as Asignacion).CopyValues<Asignacion>(v.Get().Obj<Asignacion>(), sourceNotNull: true);
                }
                else //con el nuevo valor ingresados no se obtuvo un nuevo campo unico, se realiza persistencia (insertar o modificar) del nuevo valor
                {
                    if (!v.Check())
                    {
                        (e.Row.Item as Asignacion).CopyValues<Asignacion>(v.Get().Obj<Asignacion>(), sourceNotNull: true);
                        break;
                    }

                    dao.Persist(v);
                }

                if(fieldId != null)
                    (fieldId, fieldName, entityName, value) = v.ParentVariables(mainEntityName);

                reload = true;
            }
            while (continueWhile);
        }
    }

    public class SedeViewModel
    {
        public ObservableCollection<Data_sede> Sedes()
        {
            ObservableCollection<Data_sede> r = new ObservableCollection<Data_sede>();
            var data = ContainerApp.db.Query("sede").Size(100).ColOfDictCache();
            r.Clear();
            r.AddRange(data);
            return r;
        }
    }
}
