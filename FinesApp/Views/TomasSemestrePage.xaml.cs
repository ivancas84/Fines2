using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using WpfUtils;
using System.Collections.ObjectModel;
using WpfUtils.Controls;
using SqlOrganize;
using System.Drawing;
using QRCoder;
using QuestPDF.Infrastructure;
using System.Globalization;
using QuestPDF.Fluent;
using Microsoft.Extensions.Hosting;
using System.Net.Mail;
using System.Net;
using CommunityToolkit.WinUI.Notifications;
using static QRCoder.PayloadGenerator;

namespace FinesApp.Views;

public partial class TomasSemestrePage : Page, INotifyPropertyChanged
{

    private ObservableCollection<Data_calendario> ocCalendario = new();
    private ObservableCollection<Data_toma_r> ocToma = new();
   
    IEnumerable<EntityPersist>? persists;

    public TomasSemestrePage()
    {
        InitializeComponent();

        DataContext = this;

        dgdToma.ItemsSource = ocToma;
        dgdResultadoProcesamiento.ItemsSource = ocData;
        dgdResultadoGenerarTomasPDF.ItemsSource = ocResultadoGenerarTomasPDF;
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        cbxCalendario2.InitComboBoxConstructor(ocCalendario);
        var data = ContainerApp.db.Sql("calendario").Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(data, ocCalendario);
    }

    private void cbxCalendario_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        if (cbxCalendario.SelectedIndex < 0)
            return;

        var tomaData = ContainerApp.db.TomasAprobadasDeCalendarioSql(cbxCalendario.SelectedValue).Cache().ColOfDict();
        ContainerApp.db.ClearAndAddDataToOC(tomaData, ocToma);
    }

    #region Pestaña principal
    private void EmailTomaButton_Click(object sender, RoutedEventArgs e)
    {
        var button = (e.OriginalSource as Button);
        var toma = (Data_toma_r)button.DataContext;
        if (toma.docente__email_abc.IsNoE())
        {
            new ToastContentBuilder()
            .AddText("Email abc no definido")
            .Show();
            return;
        }
        EmailToma email = new EmailToma(toma);
        email.Send();
        new ToastContentBuilder()
        .AddText("Email de toma enviado")
        .Show();
    }

    private void GenerarTomaButton_Click(object sender, RoutedEventArgs e)
    {
        ///falta qr, ver el codigo para generar todas las tomas y coipar
        /*var button = (e.OriginalSource as Button);
        var toma = (TomaQrItem)button.DataContext;
        QRCodeGenerator qrGenerator = new QRCodeGenerator();
        QRCodeData qrCodeData = qrGenerator.CreateQrCode(ContainerApp.config.urlValidarToma + toma.id, QRCodeGenerator.ECCLevel.Q);
        QRCode qrCode = new QRCode(qrCodeData);
        Bitmap qrCodeImage = qrCode.GetGraphic(20);
        ImageConverter converter = new ImageConverter();
        toma.qr_code = (byte[])converter.ConvertTo(qrCodeImage, typeof(byte[]));
        ConstanciaDocument document = new(toma);
        document.GeneratePdf(ContainerApp.config.downloadPath + toma.comision__pfid + "_" + toma.asignatura__codigo + "_" + toma.docente__numero_documento + ".pdf");
        */
    }

    private void EliminarTomaButton_Click(object sender, RoutedEventArgs e)
    {
        var button = (e.OriginalSource as Button);
        var toma = (Data_toma_r)button.DataContext;
        try
        {
            ContainerApp.db.Persist().DeleteIds("toma", toma.id!).Exec().RemoveCache();
            //LoadData(); falta recargar
            new ToastContentBuilder()
                    .AddText(Title)
                    .AddText("Toma eliminada")
                    .Show();
        }
        catch (Exception ex)
        {
            new ToastContentBuilder()
                .AddText(Title)
                .AddText("ERROR: " + ex.Message)
                .Show();

        }
    }
    #endregion

    #region Pestaña Procesar Docentes PF
    ObservableCollection<Data> ocData = new();

    private void btnProcesarDocentesPF_Click(object sender, RoutedEventArgs e)
    {

        try
        {
            if (cbxCalendario2.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            var calendarioObj = (Data_calendario)cbxCalendario2.SelectedItem;
            persists = ContainerApp.db.PersistTomasPf(calendarioObj, tbxDocentesPF.Text);
            ocData.Clear();
            for (var i = 0; i < persists.Count(); i++)
            {
                Data obj = new();
                obj.Index = i;
                obj.Label = persists.ElementAt(i).logging.ToString();
                ocData.Add(obj);

            }
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnGuardarDocentesPF_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (persists.IsNoE())
                throw new Exception("No hay nada para persistir");

            persists.Transaction().RemoveCache();
            persists = null;
            ToastExtensions.Show("Registro realizado");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }
    #endregion

    #region INotifyPropertyChanged
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


    #endregion

    #region Tab Generar Tomas Pdf
    private ObservableCollection<Data> ocResultadoGenerarTomasPDF = new();
    private void btnGenerarTomasPDF_Click(object sender, RoutedEventArgs e)
    {
        QRCodeGenerator qrGenerator = new QRCodeGenerator();

        IEnumerable<Dictionary<string, object?>> tomasData = ContainerApp.db.TomasAprobadasDeCalendarioSql(cbxCalendario.SelectedValue).Cache().ColOfDict();
        
        ocResultadoGenerarTomasPDF.Clear();
        for(var i = 0; i < tomasData.Count(); i++)
        {
            var obj = new Data();
            obj.Index = 0;
            try
            {
                TomaQrItem toma = ContainerApp.db.ToData<TomaQrItem>(tomasData.ElementAt(i));
                obj.Label = toma.Label;
                QRCodeData qrCodeData = qrGenerator.CreateQrCode(ContainerApp.config.urlValidarToma + toma.id, QRCodeGenerator.ECCLevel.Q);
                QRCode qrCode = new QRCode(qrCodeData);
                Bitmap qrCodeImage = qrCode.GetGraphic(20);
                ImageConverter converter = new ImageConverter();
                toma.qr_code = (byte[])converter.ConvertTo(qrCodeImage, typeof(byte[]));
                ConstanciaDocument document = new(toma);
                document.GeneratePdf(ContainerApp.config.downloadPath + toma.comision__pfid + "_" + toma.asignatura__codigo + "_" + toma.docente__numero_documento + ".pdf");
                obj.Msg = "Toma generada correctamente";
            } catch (Exception ex)
            {
                obj.Msg = ex.Message;

            }
            ocResultadoGenerarTomasPDF.Add(obj);
        }
    }
    #endregion;

    internal class ConstanciaDocument : IDocument
    {
        public TomaQrItem Model;
        public DocumentMetadata GetMetadata() => DocumentMetadata.Default;
        public DocumentSettings GetSettings() => DocumentSettings.Default;

        TextInfo textInfo = new CultureInfo("es-AR", false).TextInfo;

        public ConstanciaDocument(TomaQrItem model)
        {
            Model = model;
        }

        public void Compose(IDocumentContainer container)
        {

            container
                .Page(page =>
                {
                    page.Margin(50);

                    page.Header().Height(80).Element(ComposeHeader);
                    page.Content().Element(ComposeContent);
                    page.Footer().Height(115).Element(ComposeFooter);
                });

        }

        private void ComposeHeader(QuestPDF.Infrastructure.IContainer container)
        {
            container.Row(row =>
            {
                row.RelativeItem(3).Height(75).AlignBottom().Column(column =>
                {
                    column.Item().Image(ContainerApp.config.imagePath + "logo.jpg").FitArea();
                });

                row.RelativeItem().AlignRight().Column(column =>
                {
                    column.Item().Image(Model.qr_code).FitArea();
                });
            });
        }

        void ComposeContent(QuestPDF.Infrastructure.IContainer container)
        {
            var titleStyle = TextStyle.Default.FontSize(20).SemiBold();
            var subtitleStyle = TextStyle.Default.FontSize(14).SemiBold();

            container.Column(column =>
            {
                column.Spacing(20);
                column.Item().AlignCenter().PaddingTop(20).Text("Toma de Posesión CENS 462").Style(titleStyle);
                column.Item().AlignLeft().Text("La dirección del CENS 462 procede a realizar la toma de posesión docente bajo el siguiente detalle:");
                column.Item().Text("Datos del Docente").Style(subtitleStyle);
                column.Item().Element(ComposeTableDocente);
                column.Item().Text("Datos del cargo").Style(subtitleStyle);
                column.Item().Element(ComposeTableCargo);
            });
        }

        void ComposeFooter(QuestPDF.Infrastructure.IContainer container)
        {

            string baseDirectory = AppDomain.CurrentDomain.BaseDirectory;

            container.Layers(layers =>
            {
                layers.PrimaryLayer().Row(row =>
                {
                    row.RelativeItem(2).AlignRight().AlignBottom().PaddingRight(60).Column(column =>
                    {
                        column.Item().Image(ContainerApp.config.imagePath + "sello_cens.png").FitArea();
                    });

                    row.RelativeItem().AlignRight().AlignMiddle().Column(column =>
                    {
                        column.Item().Image(ContainerApp.config.imagePath + "firma_director.png").FitArea();
                    });
                });
            });
        }

        void ComposeTableDocente(QuestPDF.Infrastructure.IContainer container)
        {

            var tableStyle = TextStyle.Default.FontSize(10);

            container.Table(table =>
            {
                table.ColumnsDefinition(columns =>
                {
                    columns.RelativeColumn();
                    columns.RelativeColumn();
                    columns.RelativeColumn();
                    columns.RelativeColumn();

                });
                // step 2
                table.Cell().Row(1).Column(1).Element(BlockHeader).Text("Nombre").Bold();
                table.Cell().Row(1).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.docente__apellidos!.ToUpper() + ", " + textInfo.ToTitleCase(Model.docente__nombres));

                table.Cell().Row(2).Column(1).Element(BlockHeader).Text("CUIL").Bold();
                table.Cell().Row(2).Column(2).Element(BlockContent).Text(Model.docente__cuil);

                table.Cell().Row(2).Column(3).Element(BlockHeader).Text("Fecha de Nacimiento:").Bold();

                if (Model.docente__fecha_nacimiento.IsNoE())
                    table.Cell().Row(2).Column(4).Element(BlockContent).Text("");
                else
                    table.Cell().Row(2).Column(4).Element(BlockContent).Text(((DateTime)Model.docente__fecha_nacimiento!).ToString("dd/MM/yyyy"));

                table.Cell().Row(3).Column(1).Element(BlockHeader).Text("Email").Bold();
                table.Cell().Row(3).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.docente__email_abc);

                table.Cell().Row(4).Column(1).Element(BlockHeader).Text("Domicilio").Bold();
                table.Cell().Row(4).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.docente__descripcion_domicilio);

            });
        }

        void ComposeTableCargo(QuestPDF.Infrastructure.IContainer container)
        {
            container.Table(table =>
            {
                table.ColumnsDefinition(columns =>
                {
                    columns.RelativeColumn();
                    columns.RelativeColumn();
                    columns.RelativeColumn();
                    columns.RelativeColumn();
                    columns.RelativeColumn();
                    columns.RelativeColumn();


                });
                // step 2
                table.Cell().Row(1).Column(1).Element(BlockHeader).Text("Sede").Bold();
                table.Cell().Row(1).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.sede__nombre);

                table.Cell().Row(1).Column(5).Element(BlockHeader).Text("Comisión").Bold();
                table.Cell().Row(1).Column(6).Element(BlockContent).Text(Model.comision__pfid);

                table.Cell().Row(2).Column(1).Element(BlockHeader).Text("Domicilio").Bold();
                table.Cell().Row(2).Column(2).ColumnSpan(5).Element(BlockContent).Text(Model.domicilio__calle + " e/ " + Model.domicilio__entre + " N° " + Model.domicilio__numero + " " + Model.domicilio__localidad);

                table.Cell().Row(3).Column(1).Element(BlockHeader).Text("Horario").Bold();
                table.Cell().Row(3).Column(2).ColumnSpan(5).Element(BlockContent).Text(Model.curso__descripcion_horario);

                table.Cell().Row(4).Column(1).Element(BlockHeader).Text("Fecha Toma").Bold();
                table.Cell().Row(4).Column(2).ColumnSpan(2).Element(BlockContent).Text(((DateTime)Model.calendario__inicio!).ToString("dd/MM/yyyy"));

                table.Cell().Row(4).Column(4).Element(BlockHeader).Text("Fecha Fin").Bold();
                table.Cell().Row(4).Column(5).ColumnSpan(2).Element(BlockContent).Text(((DateTime)Model.calendario__fin).ToString("dd/MM/yyyy"));

                table.Cell().Row(5).Column(1).Element(BlockHeader).Text("Asignatura").Bold();
                table.Cell().Row(5).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.asignatura__nombre + " " + Model.asignatura__codigo);

                table.Cell().Row(5).Column(5).Element(BlockHeader).Text("Hs Cát").Bold();
                table.Cell().Row(5).Column(6).Element(BlockContent).Text(Model.curso__horas_catedra.ToString());

                table.Cell().Row(6).Column(1).Element(BlockHeader).Text("Resolución").Bold();
                table.Cell().Row(6).Column(2).ColumnSpan(5).Element(BlockContent).Text(Model.plan__resolucion);


            });
        }

        static QuestPDF.Infrastructure.IContainer BlockHeader(QuestPDF.Infrastructure.IContainer container)
        {
            return container
                .Border(1)
                .DefaultTextStyle(TextStyle.Default.FontSize(10))
                .ShowOnce()
                .Padding(2)
                .Height(25)
                .AlignCenter()
                .AlignMiddle();

        }

        static QuestPDF.Infrastructure.IContainer BlockContent(QuestPDF.Infrastructure.IContainer container)
        {
            return container
                .Border(1)
                .DefaultTextStyle(TextStyle.Default.FontSize(9))
                .ShowOnce()
                .Padding(2)
                .Height(25)
                .AlignMiddle();

        }
    }

    internal class EmailToma : SmtpClient
    {

        Data_toma_r Model;
        public string? Subject = null;
        public string? Body = null;
        List<string>? To = new();
        public string? Bcc = null;
        public string? Attachment = null;

        public EmailToma(Data_toma_r model) : base()
        {
            Host = ContainerApp.config.emailDocenteHost;
            Port = 587;
            Credentials = new NetworkCredential(ContainerApp.config.emailDocenteUser, ContainerApp.config.emailDocentePassword);
            EnableSsl = true;
            Model = model;
            Attachment = $"{ContainerApp.config.downloadPath}{Model.comision__pfid}_{Model.asignatura__codigo}_{Model.docente__numero_documento}.pdf";
            if (!Model.docente__email_abc.IsNoE())
                To.Add(Model.docente__email_abc);
            if(!Model.docente__email.IsNoE())
                To.Add(Model.docente__email);
            //Attachment = @"C:\Users\ivan\Downloads\10077_WQQ_36936393.pdf";
            //To = "icastaneda@abc.gob.ar";
            Bcc = ContainerApp.config.emailDocenteBcc.Replace(" ", "");
            Subject = $"Toma de posesión: {Model.comision__pfid} {Model.asignatura__nombre}";
            Body = $@"
<p>Hola {Model.docente__nombres} {Model.docente__apellidos}, usted ha recibido este email porque fue designado/a en la asignatura <strong>{Model.asignatura__nombre}</strong> de sede {Model.sede__nombre}</p>
<p><strong>Para confirmar su toma de posesión, necesitamos que responda este email indicando que la información del documento adjunto es correcta.</strong></p>
<p>Se recuerda que al aceptar su toma de posesión, usted se compromete a:</p>
  <ul>
    <li>Completar las planillas de finalización en tiempo y forma.</li>
    <li>Participar de las mesas de examen cuando se lo requiera.</li>
    <li>Atender a la brevedad cualquier solicitud indicada por el CENS.</li>
  </ul>
</p>
<p><strong>Para cualquier duda comuníquese vía mensaje o audio de WhatsApp al número 2216713326</strong></p>
<br>
Saluda a Usted muy atentamente:
<br>
Equipo de Coordinadores del Plan Fines 2 CENS 462
<br><a href=""https://planfines2.com.ar"">https://planfines2.com.ar</a>";
            //DeliveryMethod = SmtpDeliveryMethod.Network}
        }

        public void Send()
        {
            var mailMessage = new MailMessage
            {
                From = new MailAddress(ContainerApp.config.emailDocenteFromAddress),
                Subject = Subject!,
                Body = Body!,
                IsBodyHtml = true,
            };

            var attachment = new Attachment(Attachment!);
            mailMessage.Attachments.Add(attachment);
            foreach(string to in To)
                mailMessage.To.Add(to!);
            mailMessage.Bcc.Add(Bcc!);

            Send(mailMessage);
        }


    }


}
