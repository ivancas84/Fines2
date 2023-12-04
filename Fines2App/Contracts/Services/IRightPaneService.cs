using System.Windows.Controls;

using MahApps.Metro.Controls;

namespace Fines2App.Contracts.Services;

public interface IRightPaneService
{
    event EventHandler PaneOpened;

    event EventHandler PaneClosed;

    void OpenInRightPane(Type pageType, object parameter = null);

    void Initialize(Frame rightPaneFrame, SplitView splitView);

    void CleanUp();
}
