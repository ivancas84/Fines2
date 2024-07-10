using System.Windows;
using System.Windows.Controls;

using FinesApp.Contracts.Views;

using MahApps.Metro.Controls;

namespace FinesApp.Views;

public partial class ShellDialogWindow : MetroWindow, IShellDialogWindow
{
    public ShellDialogWindow()
    {
        InitializeComponent();
        DataContext = this;
    }

    public Frame GetDialogFrame()
        => dialogFrame;

    private void OnCloseClick(object sender, RoutedEventArgs e)
    {
        DialogResult = true;
        Close();
    }
}
