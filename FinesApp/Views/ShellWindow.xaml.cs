﻿using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;

using FinesApp.Contracts.Services;
using FinesApp.Contracts.Views;

using MahApps.Metro.Controls;

namespace FinesApp.Views;

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

    private void OnMenuViewsProcesarRegistroAlumnos(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(ProcesarRegistroAlumnosPage), null, true);

    private void OnMenuViewsProcesarPlanillaCalificaciones(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(ProcesarPlanillaCalificacionesPage), null, true);

    private void OnMenuViewsTransferirAlumno(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(TransferirAlumnoPage), null, true);

    private void OnMenuViewsInformeComision(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(InformeComisionPage), null, true);

    private void OnMenuViewsTomasSemestre(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(TomasSemestrePage), null, true);

    private void OnMenuViewsComisionesSemestre(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(ComisionesSemestrePage), null, true);

    private void OnMenuViewsCursosSemestre(object sender, RoutedEventArgs e)
        => _navigationService.NavigateTo(typeof(CursosSemestrePage), null, true);

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
