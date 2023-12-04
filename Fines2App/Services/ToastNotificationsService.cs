using CommunityToolkit.WinUI.Notifications;

using Fines2App.Contracts.Services;

using Windows.UI.Notifications;

namespace Fines2App.Services;

public partial class ToastNotificationsService : IToastNotificationsService
{
    public ToastNotificationsService()
    {
    }

    public void ShowToastNotification(ToastNotification toastNotification)
    {
        ToastNotificationManagerCompat.CreateToastNotifier().Show(toastNotification);
    }
}
