using Microsoft.Win32;
using System.Diagnostics;
using System.IO;
using System.Windows;
using System.Windows.Documents;

namespace WpfUtils.File
{
    public static class Utils
    {

        public static void OpenFile_Click(object sender, RoutedEventArgs e)
        {
            var link = (Hyperlink)sender;
            string filePath = link.Tag.ToString();
            try
            {
                var psi = new ProcessStartInfo(filePath)
                {
                    UseShellExecute = true
                };

                Process.Start(psi);
            }
            catch (Exception ex)
            {
                ToastExtensions.ShowExceptionMessageWithFileNameAndLineNumber(ex);
            }
        }

        public static void UploadFiles(string destinationDirectory)
        {
            OpenFileDialog openFileDialog = new OpenFileDialog
            {
                Multiselect = true
            };
            if (openFileDialog.ShowDialog() == true)
            {

                foreach (string selectedFilePath in openFileDialog.FileNames)
                {
                    string fileName = Path.GetFileName(selectedFilePath);
                    string destinationFilePath = Path.Combine(destinationDirectory, fileName);

                    try
                    {
                        System.IO.File.Copy(selectedFilePath, destinationFilePath, true);
                    }
                    catch (IOException ioEx)
                    {
                        ToastExtensions.ShowError(ioEx.Message, "Error al subir archivos");
                    }
                }
                ToastExtensions.Show("Carga de archivos finalizada", "Archivos Nota");
            }
        }
    }
}
