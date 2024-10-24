﻿using FinesApp.Contracts.Views;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using SqlOrganize;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Controls;

namespace FinesApp.Views;

public partial class AdministrarAlumnoPage : Page, INotifyPropertyChanged, INavigationAware
{
    public AdministrarAlumnoPage()
    {
        InitializeComponent();
        DataContext = this;
    }

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

    #region INavigationAware
    public void OnNavigatedTo(object parameter)
    {
        if (!parameter.IsNoE())
        {
            gbxPrincipal.DataContext = Entity.CreateFromId<Alumno>(parameter);
        }

        else
            gbxPrincipal.DataContext = new Alumno();
    }

    public void OnNavigatedFrom()
    {
        //throw new NotImplementedException();
    }
    #endregion

    private void BtnGuardarAlumno_Click(object sender, System.Windows.RoutedEventArgs e)
    {

    }
}
