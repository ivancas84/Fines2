using QRCoder;
using QuestPDF.Fluent;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Drawing;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Threading;
using SqlOrganize;

namespace Fines2Wpf.Windows.Alumno.ConstanciaPdf
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        #region Autocomplete v2 - alumnoComboBox
        private ObservableCollection<ConstanciaData> alumnoOC = new(); //datos consultados de la base de datos
        private DispatcherTimer alumnoTypingTimer; //timer para buscar
        #endregion Autocomplete v2

        public Window1()
        {
            InitializeComponent();

            #region Autocomplete v2 - alumnoComboBox
            alumnoComboBox.ItemsSource = alumnoOC;
            alumnoComboBox.DisplayMemberPath = "Label";
            alumnoComboBox.SelectedValuePath = "id";
            #endregion

        }


        private void GenerarConstanciaButton_Click(object sender, RoutedEventArgs e)
        {
            ConstanciaData alumno = (ConstanciaData)alumnoComboBox.SelectedItem;
            
            QRCodeGenerator qrGenerator = new QRCodeGenerator();
            QRCodeData qrCodeData = qrGenerator.CreateQrCode(alumno.url, QRCodeGenerator.ECCLevel.Q);
            QRCode qrCode = new QRCode(qrCodeData);
            Bitmap qrCodeImage = qrCode.GetGraphic(20);
            ImageConverter converter = new ImageConverter();
            alumno.qr_code = (byte[])converter.ConvertTo(qrCodeImage, typeof(byte[]));
            ConstanciaDocument document = new(alumno);
            document.GeneratePdf("C:\\Users\\ivan\\Downloads\\" + alumno.persona__numero_documento + ".pdf");

        }

        #region Métodos generales Autocomplete v.2.2
        ///<summary>Método General Autocomplete v2.2 - GotFocus</summary>
        private void ComboBox_GotFocus(object sender, RoutedEventArgs e)
        {
            (sender as ComboBox).IsDropDownOpen = true;
        }

        ///<summary>Método General Autocomplete v2.2 - SelectionChanged</summary>
        private void ComboBox_SelectionChanged(object sender, RoutedEventArgs e)
        {
            var cb = (ComboBox)sender;

            if (cb.SelectedIndex < 0)
                cb.IsDropDownOpen = true;
        }
        #endregion

        #region Metodos Autocomplete 2.2 - alumnoComboBox
        /// <summary>Autocomplete 2.2 - TextChanged</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790534457</remarks>
        private void AlumnoComboBox_TextChanged(object sender, TextChangedEventArgs e)
        {
            DispatcherTimer timer = alumnoTypingTimer;
            ComboBox cb = (sender as ComboBox)!;
            bool compare = WpfUtils.ComboBoxUtils.TextChangedCompare(cb, ((ConstanciaData)cb.SelectedItem)?.Label ?? null);
            if (compare)
                WpfUtils.ComboBoxUtils.TextChangedTimer(cb.Text, timer, new EventHandler(AlumnoHandleTypingTimerTimeout!));
        }

        /// <summary>Autocomplete 2.2 - HandleTypingTimerTimeout</summary>
        /// <remarks>https://github.com/Pericial/GAP/issues/54#issuecomment-1790603741</remarks>
        private void AlumnoHandleTypingTimerTimeout(object sender, EventArgs e)
        {
            var timer = sender as DispatcherTimer; // WPF
            if (timer == null)
                return;

            alumnoOC.Clear();
            string text = alumnoComboBox.Text;

            if (string.IsNullOrEmpty(text) || text.Length < 3) //restricciones para buscar, texto no nulo y mayor a 2 caracteres
                return;

            IEnumerable<Dictionary<string, object?>> list = DAO.Alumno.SearchLikeQuery(text).Cache().ColOfDict(); //busqueda de valores a mostrar en funcion del texto

            foreach (var item in list)
            {
                var o = item.Obj<ConstanciaData>();
                o.Label = o.persona__nombres + " " + o.persona__apellidos;
                alumnoOC.Add(o);
            }

            timer.Stop(); // The timer must be stopped! We want to act only once per keystroke.
        }
        #endregion

    }
}
