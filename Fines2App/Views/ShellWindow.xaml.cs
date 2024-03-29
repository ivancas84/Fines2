﻿using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;

using Fines2App.Contracts.Services;
using Fines2App.Contracts.Views;

using MahApps.Metro.Controls;

namespace Fines2App.Views;

public partial class ShellWindow : MetroWindow, IShellWindow, INotifyPropertyChanged
{
    private readonly INavigationService _navigationService;
    private readonly IRightPaneService _rightPaneService;
    private bool _canGoBack;

    public bool CanGoBack
    {
        get { return _canGoBack; }
        set { Set(ref _canGoBack, value); }
    }

    public ShellWindow(INavigationService navigationService, IRightPaneService rightPaneService)
    {
        _navigationService = navigationService;
        _navigationService.Navigated += OnNavigated;
        _rightPaneService = rightPaneService;
        InitializeComponent();
        DataContext = this;
    }

    public Frame GetNavigationFrame()
        => shellFrame;

    public Frame GetRightPaneFrame()
        => rightPaneFrame;

    public void ShowWindow()
        => Show();

    public void CloseWindow()
        => Close();

    public SplitView GetSplitView()
        => splitView;

    private void OnLoaded(object sender, RoutedEventArgs e)
    {
    }

    private void OnUnloaded(object sender, RoutedEventArgs e)
    {
        _rightPaneService.CleanUp();
    }

    private void OnGoBack(object sender, RoutedEventArgs e)
    {
        _navigationService.GoBack();
    }

    private void OnNavigated(object sender, Type pageType)
    {
        CanGoBack = _navigationService.CanGoBack;
    }

    private void OnMenuFileExit(object sender, RoutedEventArgs e)
        => Application.Current.Shutdown();

    private void OnMenuViewsMain(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(MainPage), null, true);

    private void OnMenuFileSettings(object sender, RoutedEventArgs e)
        => _rightPaneService.OpenInRightPane(typeof(SettingsPage));

    private void OnMenuViewsListaComisiones(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(ListaComisionesPage), null, true);

    private void OnMenuViewsTransferirPersona(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(TransferirPersonaPage), null, true);

    private void OnMenuViewsAnalizarAlumnosComision(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(AnalizarAlumnosComisionPage), null, true);

    private void OnMenuViewsDesactivarAlumnosNoCalificados(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(DesactivarAlumnosNoCalificadosPage), null, true);

    private void OnMenuViewsListaAlumnosSemestre(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(ListaAlumnosSemestrePage), null, true);

    public event PropertyChangedEventHandler PropertyChanged;

    private void Set<T>(ref T storage, T value, [CallerMemberName]string propertyName = null)
    {
        if (Equals(storage, value))
        {
            return;
        }

        storage = value;
        OnPropertyChanged(propertyName);
    }

    private void OnPropertyChanged(string propertyName) => PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
}
