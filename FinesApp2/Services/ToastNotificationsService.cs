using CommunityToolkit.WinUI.Notifications;

using FinesApp2.Contracts.Services;

using Windows.UI.Notifications;

namespace FinesApp2.Services;

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
