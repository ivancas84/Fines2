using CommunityToolkit.WinUI.Notifications;
using Fines2Model3.Data;
using QRCoder;
using QuestPDF.Fluent;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Drawing;
using System.IO;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Threading;
using Utils;
using SqlOrganize;
using System.Text;

namespace Fines2Wpf.Windows.Alumno.ConstanciaAlumnoRegularPdf
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        private string downloadPath = System.IO.Path.Combine(Directory.GetCurrentDirectory(), ContainerApp.config.downloadPath);

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

            #region urlComboBox
            urlComboBox.SelectedValuePath = "Key";
            urlComboBox.DisplayMemberPath = "Value";
            urlComboBox.Items.Add(new KeyValuePair<bool, string>(true, "Sí"));
            urlComboBox.Items.Add(new KeyValuePair<bool, string>(false, "No"));
            urlComboBox.SelectedIndex = 0;
            #endregion
        }


        private void GenerarConstanciaButton_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                ConstanciaData alumno = (ConstanciaData)alumnoComboBox.SelectedItem;


                int anio = DateTime.Now.Year;
                short semester = DateTime.Now.ToSemester();

                Data_alumno_comision_r asignacionActiva = DAO.AlumnoComision2.
                AsignacionActivaDeAlumnoAnioSemestreQuery(alumno.id, anio, semester).
                DictCache().
                Obj<Data_alumno_comision_r>();

                if (asignacionActiva.IsNullOrEmptyOrDbNull())
                    throw new Exception("No existe asignación activa! Verificar las comisiones del alumno!");
                
                alumno.anio_constancia = asignacionActiva.planificacion__anio!;

                alumno.resolucion_constancia = asignacionActiva.plan__resolucion!;

                alumno.orientacion_constancia = asignacionActiva.plan__orientacion;
                
                if (!observacionesTextBox.Text.IsNullOrEmptyOrDbNull())
                    alumno.observaciones_constancia = observacionesTextBox.Text;

                if ((bool)urlComboBox.SelectedValue)
                    GenerarPedido(alumno);

                QRCodeGenerator qrGenerator = new QRCodeGenerator();
                QRCodeData qrCodeData = qrGenerator.CreateQrCode(alumno.url, QRCodeGenerator.ECCLevel.Q);
                QRCode qrCode = new QRCode(qrCodeData);
                Bitmap qrCodeImage = qrCode.GetGraphic(20);
                ImageConverter converter = new ImageConverter();
                alumno.qr_code = (byte[])converter.ConvertTo(qrCodeImage, typeof(byte[]));
                ConstanciaDocument document = new(alumno);
                document.GeneratePdf(downloadPath + alumno.persona__numero_documento + ".pdf");
            } catch (Exception ex)
            {
                new ToastContentBuilder()
                   .AddText(Title)
                   .AddText("ERROR: " + ex.Message)
                   .Show();
            }
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

            IEnumerable<Dictionary<string, object?>> list = DAO.Alumno.SearchLikeQuery(text).ColOfDictCache(); //busqueda de valores a mostrar en funcion del texto

            foreach (var item in list)
            {
                var o = item.Obj<ConstanciaData>();
                o.Label = o.persona__nombres + " " + o.persona__apellidos;
                alumnoOC.Add(o);
            }

            timer.Stop(); // The timer must be stopped! We want to act only once per keystroke.
        }
        #endregion


        private void GenerarPedido(ConstanciaData alumno)
        {
            StringBuilder threads_body = new StringBuilder();
            threads_body.Append("La Dirección de la escuela CENS 462 de La Plata, hace constar por la presente que ");
            threads_body.Append(alumno.persona__apellidos!.ToUpper());
            threads_body.Append(", ");
            threads_body.Append(alumno.persona__nombres!.ToTitleCase());
            threads_body.Append(" DNI N° ");
            threads_body.Append(alumno.persona__numero_documento);
            threads_body.Append(" es alumno regular de ");
            threads_body.Append(alumno.anio_constancia.ToOrdinalSpanish().ToUpper());
            threads_body.Append(" año Programa Fines 2 Trayecto Secundario con orientación en ");
            threads_body.Append(alumno.orientacion_constancia);
            threads_body.Append(" resolución ");
            threads_body.Append(alumno.resolucion_constancia);
            if (!alumno.observaciones_constancia.IsNullOrEmptyOrDbNull())
            {
                threads_body.Append(" - "); // Add line break
                threads_body.Append(alumno.observaciones_constancia); // Add line break

            }

            EntityValues ticketsValues = ContainerApp.dbPedidos.Values("wpwt_psmsc_tickets").Default().
               Set("subject", " Constancia de alumno regular : " + alumno.persona__apellidos!.ToUpper() + ", " + alumno.persona__nombres!.ToTitleCase()).
               Set("status", 4). //cerado
               Set("category", 10). //constancia
               Set("cust_24", alumno.persona__numero_documento).
               //Set("cust_27", alumno.persona__telefono).
               Set("cust_28", "Válido por 30 días").
               Set("assigned_agent", "").Reset();

            EntityValues threadsValues = ContainerApp.dbPedidos.Values("wpwt_psmsc_threads").Default().
                Set("ticket", ticketsValues.Get("id")).
                Set("body", threads_body.ToString()).Reset();

            if (!ticketsValues.Check() && !threadsValues.Check())
            {
                throw new Exception("El chequeo de valores es incorrecto");
            }

            EntityPersist persist = ContainerApp.dbPedidos.Persist();

            persist.Insert(ticketsValues)
                .Insert(threadsValues)
                .Exec()
                .RemoveCache();


            var id = ticketsValues.Get("id").ToString() ;
            var authCode = ticketsValues.Get("auth_code").ToString();
            alumno.url = "https://planfines2.com.ar/wp/pedidos/?wpsc-section=ticket-list&ticket-id=" + id + "&auth-code=" + authCode;
        }
    }


}