using CommunityToolkit.WinUI.Notifications;
using Org.BouncyCastle.Asn1.Ocsp;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Threading;
using Utils;

namespace Fines2App.Views;

public partial class TransferirPersonaPage : Page, INotifyPropertyChanged
{

    private ObservableCollection<Data_per> personaOC = new(); //datos consultados de la base de datos
    private DispatcherTimer personaTypingTimer; //timer para buscar


    public TransferirPersonaPage()
    {
        InitializeComponent();
        DataContext = this;


        personaComboBox.ItemsSource = personaOC;
        personaComboBox.DisplayMemberPath = "Label";
        personaComboBox.SelectedValuePath = "id";
    }

    private void TransferirButton_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            causaOC.Clear();

            var request = (Requests.Causas_BuscarDatosRequest)searchGroupBox.DataContext;
            CausaDatos[] data = causaDAO.BuscarWS(request);
            if (data.IsNullOrEmptyOrDbNull())
                new ToastContentBuilder()
                    .AddText("Búsqueda de Causas del WS")
                    .AddText("La consulta no arrojó resultados")
                    .Show();
            else
                new ToastContentBuilder()
                    .AddText("Búsqueda de Causas del WS")
                    .AddText("La consulta devolvió " + data.Count() + " registros.")
                    .Show();

            causaOC.AddRange(data);
        }
        catch (Exception ex)
        {
            new ToastContentBuilder()
                .AddText("Búsqueda de Causas del WS")
                .AddText(ex.Message)
                .Show();
        }
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
