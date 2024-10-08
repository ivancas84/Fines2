﻿using SqlOrganize;
using SqlOrganize.DateTimeUtils;
using System;
using System.Collections.Generic;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;

namespace Fines2Wpf.Forms.ListaSedesSemestre
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        ComisionSearch comisionSearch = new();

        private Fines2Wpf.Forms.ListaSedesSemestre.DAO.Comision comisionDAO = new();

        public Window1()
        {
            InitializeComponent();

            DataContext = comisionSearch;

            this.autorizadaCombo.SelectedValuePath = "Key";
            this.autorizadaCombo.DisplayMemberPath = "Value";
            this.autorizadaCombo.Items.Add(new KeyValuePair<bool?, string>(null, "(Todos)"));
            this.autorizadaCombo.Items.Add(new KeyValuePair<bool, string>(true, "Sí"));
            this.autorizadaCombo.Items.Add(new KeyValuePair<bool, string>(false, "No"));

            sedeGrid.CellEditEnding += SedeGrid_CellEditEnding;

            ComisionSearch();
        }

        private void ComisionSearch()
        {
            IEnumerable<Dictionary<string, object>> list = comisionDAO.Search(comisionSearch);
            sedeGrid.ItemsSource = list.Objs<Sede>();
        }

        private void BuscarButton_Click(object sender, RoutedEventArgs e)
        {
            ComisionSearch();
        }

        private void SedeGrid_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            if (e.EditAction == DataGridEditAction.Commit)
            {
                var column = e.Column as DataGridBoundColumn;
                if (column != null)
                {
                    string key = ((Binding)column.Binding).Path.Path; //column's binding
                    Dictionary<string, object> source = (Dictionary<string, object>)((Sede)e.Row.DataContext).Dict();
                    string value = (e.EditingElement as TextBox)!.Text;
                    comisionDAO.UpdateValueRel(key, value, source);
                }
            }
        }
    }

    internal class ComisionSearch
    {
        public string calendario__anio { get; set; } = DateTime.Now.Year.ToString();
        public int calendario__semestre { get; set; } = DateTime.Now.ToSemester();
        public bool? autorizada { get; set; } = true;
    }


    internal class Sede
    {

        public string sede___Id { get; set; }
        public string sede__numero { get; set; }
        public string sede__nombre { get; set; }
        public string sede__pfid { get; set; }
        public string sede__pfid_organizacion { get; set; }

        public string domicilio___Id { get; set; }
        public string domicilio__calle { get; set; }
        public string domicilio__numero { get; set; }
        public string domicilio__entre { get; set; }
        public string domicilio__localidad { get; set; }
        public string domicilio__barrio { get; set; }

    }
}
