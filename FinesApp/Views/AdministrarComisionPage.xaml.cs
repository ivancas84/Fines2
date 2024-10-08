﻿using FinesApp.Contracts.Services;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Controls;

namespace FinesApp.Views;

public partial class AdministrarComisionPage : Page, INotifyPropertyChanged
{

    private readonly INavigationService _navigationService;

    public AdministrarComisionPage(INavigationService navigationService)
    {
        _navigationService = navigationService;
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
}
