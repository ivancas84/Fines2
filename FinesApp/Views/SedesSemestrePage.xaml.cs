﻿using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Controls;
using SqlOrganize.Sql;
using WpfUtils.Controls;
using CommunityToolkit.WinUI.Notifications;
using Org.BouncyCastle.Asn1.Ocsp;
using System.Windows;
using SqlOrganize.CollectionUtils;
using WpfUtils;

namespace FinesApp.Views;

public partial class SedesSemestrePage : Page, INotifyPropertyChanged
{

    private ObservableCollection<Data_calendario> ocCalendario = new();
    private ObservableCollection<Data_sede_r> ocSede = new();

    public SedesSemestrePage()
    {
        InitializeComponent();
        DataContext = this;

        #region cbxCalendario
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        var data = ContainerApp.db.Sql("calendario").Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, ocCalendario);
        #endregion

        dgdSedes.ItemsSource = ocSede;
    }

    private void BuscarButton_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if(cbxCalendario.SelectedIndex == -1)
                throw new Exception("Debe seleccionar calendario");

            IEnumerable<object> idSedes = ContainerApp.db.ComisionesAutorizadasDeCalendarioSql(cbxCalendario.SelectedValue).
                Cache().ColOfDict().ColOfVal<object>("sede");

            var sedeData = ContainerApp.db.Sql("sede").Where("$id IN (@0)").
                Order("$nombre ASC").Parameters(idSedes).Cache().ColOfDict();
            ContainerApp.db.ClearAndAddDataToOC(sedeData, ocSede);

            ToastExtensions.Show("La consulta devolvió " + sedeData.Count() + " registros.");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    #region INotifyPropertyChanged
    public event PropertyChangedEventHandler PropertyChanged;

    private void Set<T>(ref T storage, T value, [CallerMemberName] string propertyName = null)
    {
        if (Equals(storage, value))
        {
            return;
        }

        storage = value;
        OnPropertyChanged(propertyName);
    }

    private void OnPropertyChanged(string propertyName) => PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
    #endregion
}