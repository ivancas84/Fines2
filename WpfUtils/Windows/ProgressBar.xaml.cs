using SqlOrganize;
using System.Windows;

namespace WpfUtils.Windows
{
    /// <summary>
    /// Lógica de interacción para ProgressBar.xaml v1
    /// </summary>
    public partial class ProgressBar : Window
    {
        public ProgressBar(string? title = null)
        {
            if(!title.IsNoE())
                this.Title = title;

            InitializeComponent();
        }

        public void UpdateProgress(int percentage)
        {
            // Ensure the UI update runs on the UI thread
            Dispatcher.Invoke(() =>
            {
                pbLoad.Value = percentage;

                // Optionally close the window if progress is complete
                if (percentage >= 100)
                {
                    Close();
                }
            });
        }
    }
}
