using CommunityToolkit.WinUI.Notifications;
using System.Diagnostics;

namespace WpfUtils
{
    public static class ToastExtensions
    {
        /// <summary>Toast exception with fileName and lineNumber v1</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/92#issuecomment-1988339359</remarks>
        public static void ShowExceptionMessageWithFileNameAndLineNumber(Exception ex, string title = "")
        {
            #region Toast exception with fileName and lineNumber v1
            var st = new StackTrace(ex, true); // Get the top stack frame
            var frame = st.GetFrame(0); // Get the line number from the stack frame
            var lineNumber = frame.GetFileLineNumber();
            string fileName = frame.GetFileName();
            new ToastContentBuilder()
                .AddText(title)
                .AddText("ERROR " + fileName + "-" + lineNumber.ToString() + ": " + ex.Message)
            .Show();
            #endregion
        }

        /// <summary>Toast exception with fileName and lineNumber v1</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/92#issuecomment-1988339359</remarks>
        public static void ShowError(string message, string title = "")
        {
            new ToastContentBuilder()
                .AddText(title)
                .AddText("ERROR: " + message)
            .Show();
        }

        public static void Show(string message, string title = "")
        {
            new ToastContentBuilder()
                .AddText(title)
                .AddText(message)
            .Show();
        }

        public static void ToastException(this Exception ex, string? title = "")
        {
            new ToastContentBuilder()
                .AddText(title)
                .AddText("ERROR: " + ex.Message)
            .Show();
        }

        public static void ToastExceptionDetail(this Exception ex, string? title = "")
        {
            var st = new StackTrace(ex, true); // Get the top stack frame
            var frame = st.GetFrame(0); // Get the line number from the stack frame
            var lineNumber = frame.GetFileLineNumber();
            string fileName = frame.GetFileName();
            new ToastContentBuilder()
                .AddText(title)
                .AddText("ERROR " + fileName + "-" + lineNumber.ToString() + ": " + ex.Message)
            .Show();
        }
    }
}
