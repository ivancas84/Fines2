﻿using CommunityToolkit.WinUI.Notifications;

using FinesApp.Contracts.Services;

using Windows.UI.Notifications;

namespace FinesApp.Services;

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
