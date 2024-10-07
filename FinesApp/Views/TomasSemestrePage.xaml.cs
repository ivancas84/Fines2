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
using System.Net.Mail;
using System.Net;
using System.Collections.Specialized;
using SqlOrganize.ValueTypesUtils;
using Org.BouncyCastle.Utilities.Collections;
using System.Windows.Data;
using FinesApp.Contracts.Services;

namespace FinesApp.Views;

public partial class TomasSemestrePage : Page, INotifyPropertyChanged
{
    private readonly INavigationService _navigationService;

    private ObservableCollection<Calendario> ocCalendario = new();
    private ObservableCollection<Toma> ocToma = new(); //tomas activas
    private ObservableCollection<Toma> ocTomaNA = new(); //tomas no activas
    private ObservableCollection<PlanillaDocente> ocPlanillaDocente = new();




    IEnumerable<PersistContext>? persists;

    public TomasSemestrePage(INavigationService navigationService)
    {
        _navigationService = navigationService;

        InitializeComponent();

        DataContext = this;

        //ocToma.CollectionChanged += OcTomaCollectionChanged;
        dgdToma.CellEditEnding += DgdToma_CellEditEnding;
        dgdToma.ItemsSource = ocToma;

        dgdTomaNA.CellEditEnding += DgdToma_CellEditEnding;
        dgdTomaNA.ItemsSource = ocTomaNA;

        dgdResultadoProcesamiento.ItemsSource = ocData;
        dgdResultadoGenerarTomasPDF.ItemsSource = ocResultadoGenerarTomasPDF;
        dgdResultadoGenerarContralor.ItemsSource = ocResultadoGenerarContralor;
        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        CalendarioDAO.CalendariosSql().Cache().AddEntityToClearOC(ocCalendario);


        cbxPlanillaDocente.InitComboBoxConstructor(ocPlanillaDocente, "numero");
        PlanillaDocenteDAO.PlanillasSql().Cache().AddEntityToClearOC(ocPlanillaDocente);
    }

    private void DgdToma_CellEditEnding(object? sender, DataGridCellEditEndingEventArgs e)
    {
        Context.db.CellEditEnding(sender, e);

    }

    private void cbxCalendario_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        LoadTomas();
    }

    private void LoadTomas()
    {
        if(cbxCalendario.SelectedIndex < 0)
        {
            ocToma.Clear();
            return;
        }

        TomaDAO.TomasAprobadasDeCalendarioSql(cbxCalendario.SelectedValue).Cache().AddEntityToClearOC(ocToma);
        TomaDAO.TomasNoAprobadasDeCalendarioSql(cbxCalendario.SelectedValue).Cache().AddEntityToClearOC(ocTomaNA);
    }

    #region Pestaña principal
    private void EmailTomaButton_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            var button = (e.OriginalSource as Button);
            var toma = (Toma)button.DataContext;

            EmailToma email = new EmailToma(toma);
            email.Send();
            ToastExtensions.Show("Email de toma enviado");
        } catch(Exception ex) {
            ex.ToastException();        
        }
    }

    private void GenerarTomaButton_Click(object sender, RoutedEventArgs e)
    {
        ///falta qr, ver el codigo para generar todas las tomas y coipar
        /*var button = (e.OriginalSource as Button);
        var toma = (TomaQrItem)button.DataContext;
        QRCodeGenerator qrGenerator = new QRCodeGenerator();
        QRCodeData qrCodeData = qrGenerator.CreateQrCode(Context.config.urlValidarToma + toma.id, QRCodeGenerator.ECCLevel.Q);
        QRCode qrCode = new QRCode(qrCodeData);
        Bitmap qrCodeImage = qrCode.GetGraphic(20);
        ImageConverter converter = new ImageConverter();
        toma.qr_code = (byte[])converter.ConvertTo(qrCodeImage, typeof(byte[]));
        ConstanciaDocument document = new(toma);
        document.GeneratePdf(Context.config.downloadPath + toma.comision__pfid + "_" + toma.asignatura__codigo + "_" + toma.docente_.numero_documento + ".pdf");
        */
    }

    private void EliminarTomaButton_Click(object sender, RoutedEventArgs e)
    {
        var button = (e.OriginalSource as Button);
        var toma = (Toma)button.DataContext;
        try
        {
            using (Context.db.CreateQueue())
            {
                Context.db.Persist().DeleteIds("toma", toma.id!);
                Context.db.ProcessQueue();
                LoadTomas();
            }
            ToastExtensions.Show("Toma eliminada");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }
    #endregion

    #region Tab Procesar Docentes PF (XLSX y HTML)
    ObservableCollection<Entity> ocData = new();

    private void btnProcesarDocentesPF_Click(object sender, RoutedEventArgs e)
    {

        try
        {
            Context.db.CreateQueue();
            if (cbxCalendario.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            var calendarioObj = (Calendario)cbxCalendario.SelectedItem;
            calendarioObj.PersistTomasPf(tbxDocentesPF.Text);
            ocData.Clear();
            for (var i = 0; i < persists.Count(); i++)
            {
                Entity obj = new();
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
            Context.db.ProcessQueue();
            ToastExtensions.Show("Registro realizado");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnProcesarDocentesPfHtml_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            Context.db.CreateQueue();
            if (cbxCalendario.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            var calendarioObj = (Calendario)cbxCalendario.SelectedItem;
            calendarioObj.PersistTomasPfHtml(tbxDocentesPF.Text);
            ocData.Clear();
            for (var i = 0; i < persists.Count(); i++)
            {
                Entity obj = new();
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
    private ObservableCollection<Toma> ocResultadoGenerarTomasPDF = new();
    private void btnGenerarTomasPDF_Click(object sender, RoutedEventArgs e)
    {
        QRCodeGenerator qrGenerator = new QRCodeGenerator();

        IEnumerable<Dictionary<string, object?>> tomasData = TomaDAO.TomasAprobadasDeCalendarioSql(cbxCalendario.SelectedValue).Cache().Dicts();
        
        ocResultadoGenerarTomasPDF.Clear();
        for(var i = 0; i < tomasData.Count(); i++)
        {
            TomaQrItem toma = Entity.CreateFromDict<TomaQrItem>(tomasData.ElementAt(i));
            toma.Index = 0;
            try
            {
                QRCodeData qrCodeData = qrGenerator.CreateQrCode(Context.config.urlValidarToma + toma.id, QRCodeGenerator.ECCLevel.Q);
                QRCode qrCode = new QRCode(qrCodeData);
                Bitmap qrCodeImage = qrCode.GetGraphic(20);
                ImageConverter converter = new ImageConverter();
                toma.qr_code = (byte[])converter.ConvertTo(qrCodeImage, typeof(byte[]));
                ConstanciaDocument document = new(toma);
                document.GeneratePdf(Context.config.downloadPath + toma.curso_.comision_.pfid + "_" + toma.curso_.disposicion_.asignatura_.codigo + "_" + toma.docente_.numero_documento + ".pdf");
                toma.Msg = "Toma generada correctamente";
            } catch (Exception ex)
            {
                toma.Msg = ex.Message;

            }
            ocResultadoGenerarTomasPDF.Add(toma);
        }
    }
    #endregion;

    #region Tab Generar Contralor
    private ObservableCollection<TomaContralorItem> ocResultadoGenerarContralor = new();

    private void btnGenerarContralor_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (cbxCalendario.SelectedIndex < 0)
                throw new Exception("No existe calendario seleccionado");
            var tomas = TomaDAO.TomasPasarSinPlanillaDocenteDeCalendario(cbxCalendario.SelectedValue).Cache().Dicts();

            ocResultadoGenerarContralor.Clear();
            foreach (var item in tomas)
            {
                TomaContralorItem tomaObj = Entity.CreateFromDict<TomaContralorItem>(item);
                tomaObj.docente_.numero_documento = tomaObj.docente_.numero_documento;
                tomaObj.docente_.Label = tomaObj.docente_.apellidos!.ToUpper() + " " + tomaObj.docente_.nombres!.ToTitleCase();
                tomaObj.curso_.disposicion_.planificacion_.plan_.Label = tomaObj.curso_.disposicion_.planificacion_.plan_.orientacion!.Acronym();

                if (tomaObj.curso_.comision_.turno.IsNoE())
                    tomaObj.curso_.disposicion_.planificacion_.Label = "V";
                else
                    tomaObj.curso_.disposicion_.planificacion_.Label = tomaObj.curso_.comision_.turno!.Acronym();


                if (tomaObj.docente_.cuil1.IsNoE())
                    tomaObj.docente_.cuil1 = Convert.ToByte(tomaObj.docente_.cuil.Substring(0, 2));

                if (tomaObj.docente_.cuil2.IsNoE())
                    tomaObj.docente_.cuil2 = Convert.ToByte(tomaObj.docente_.cuil.Substring(10, 1));

                ocResultadoGenerarContralor.Add(tomaObj);
            }
        } catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnAsignarPlanillaDocente_Click(object sender, RoutedEventArgs e)
    {
        try

        {
            if (cbxCalendario.SelectedIndex < 0)
                throw new Exception("Calendario no seleccionado");
            if (cbxPlanillaDocente.SelectedIndex < 0)
                throw new Exception("Planilla no seleccionada");


            var idTomas = TomaDAO.IdTomasPasarSinPlanillaDocenteDeCalendario(cbxCalendario.SelectedValue);

            using (var queue = Context.db.CreateQueue())
            {
                var persist = Context.db.Persist();
                foreach(var id in idTomas) { 
                    AsignacionPlanillaDocente asignacion = new();
                    asignacion.toma = (string)id;
                    asignacion.planilla_docente = (string)cbxPlanillaDocente.SelectedValue;
                    persist.Persist(asignacion);
                }

                Context.db.ProcessQueue();
                ToastExtensions.Show("Se han asignado las planillas docentes");
            }




        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }
    #endregion
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
                    column.Item().Image(Context.config.imagePath + "logo.jpg").FitArea();
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
                        column.Item().Image(Context.config.imagePath + "sello_cens.png").FitArea();
                    });

                    row.RelativeItem().AlignRight().AlignMiddle().Column(column =>
                    {
                        column.Item().Image(Context.config.imagePath + "firma_director.png").FitArea();
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
                table.Cell().Row(1).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.docente_.apellidos!.ToUpper() + ", " + textInfo.ToTitleCase(Model.docente_.nombres));

                table.Cell().Row(2).Column(1).Element(BlockHeader).Text("CUIL").Bold();
                table.Cell().Row(2).Column(2).Element(BlockContent).Text(Model.docente_.cuil);

                table.Cell().Row(2).Column(3).Element(BlockHeader).Text("Fecha de Nacimiento:").Bold();

                if (Model.docente_.fecha_nacimiento.IsNoE())
                    table.Cell().Row(2).Column(4).Element(BlockContent).Text("");
                else
                    table.Cell().Row(2).Column(4).Element(BlockContent).Text(((DateTime)Model.docente_.fecha_nacimiento!).ToString("dd/MM/yyyy"));

                table.Cell().Row(3).Column(1).Element(BlockHeader).Text("Email").Bold();
                table.Cell().Row(3).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.docente_.email_abc);

                table.Cell().Row(4).Column(1).Element(BlockHeader).Text("Domicilio").Bold();
                table.Cell().Row(4).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.docente_.descripcion_domicilio);

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
                table.Cell().Row(1).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.curso_.comision_.sede_.nombre);

                table.Cell().Row(1).Column(5).Element(BlockHeader).Text("Comisión").Bold();
                table.Cell().Row(1).Column(6).Element(BlockContent).Text(Model.curso_.comision_.pfid);

                table.Cell().Row(2).Column(1).Element(BlockHeader).Text("Domicilio").Bold();
                table.Cell().Row(2).Column(2).ColumnSpan(5).Element(BlockContent).Text(Model.curso_.comision_.sede_.domicilio_.calle + " e/ " + Model.curso_.comision_.sede_.domicilio_.entre + " N° " + Model.curso_.comision_.sede_.domicilio_.numero + " " + Model.curso_.comision_.sede_.domicilio_.localidad);

                table.Cell().Row(3).Column(1).Element(BlockHeader).Text("Horario").Bold();
                table.Cell().Row(3).Column(2).ColumnSpan(5).Element(BlockContent).Text(Model.curso_.descripcion_horario);

                table.Cell().Row(4).Column(1).Element(BlockHeader).Text("Fecha Toma").Bold();
                table.Cell().Row(4).Column(2).ColumnSpan(2).Element(BlockContent).Text(((DateTime)Model.curso_.comision_.calendario_.inicio!).ToString("dd/MM/yyyy"));

                table.Cell().Row(4).Column(4).Element(BlockHeader).Text("Fecha Fin").Bold();
                table.Cell().Row(4).Column(5).ColumnSpan(2).Element(BlockContent).Text(((DateTime)Model.curso_.comision_.calendario_.fin).ToString("dd/MM/yyyy"));

                table.Cell().Row(5).Column(1).Element(BlockHeader).Text("Asignatura").Bold();
                table.Cell().Row(5).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.curso_.disposicion_.asignatura_.nombre + " " + Model.curso_.disposicion_.asignatura_.codigo);

                table.Cell().Row(5).Column(5).Element(BlockHeader).Text("Hs Cát").Bold();
                table.Cell().Row(5).Column(6).Element(BlockContent).Text(Model.curso_.horas_catedra.ToString());

                table.Cell().Row(6).Column(1).Element(BlockHeader).Text("Resolución").Bold();
                table.Cell().Row(6).Column(2).ColumnSpan(5).Element(BlockContent).Text(Model.curso_.disposicion_.planificacion_.plan_.resolucion);


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

        Toma Model;
        public string? Subject = null;
        public string? Body = null;
        List<string>? To = new();
        public string? Bcc = null;
        public string? Attachment = null;

        public EmailToma(Toma model) : base()
        {
            Host = Context.config.emailDocenteHost;
            Port = 587;
            Credentials = new NetworkCredential(Context.config.emailDocenteUser, Context.config.emailDocentePassword);
            EnableSsl = true;
            Model = model;
            Attachment = $"{Context.config.downloadPath}{Model.curso_.comision_.pfid}_{Model.curso_.disposicion_.asignatura_.codigo}_{Model.docente_.numero_documento}.pdf";
            if (Model.docente_.email_abc.IsNoE() && Model.docente_.email.IsNoE())
                throw new Exception("Emails no definidos");
            if (!Model.docente_.email_abc.IsNoE())
                To.Add(Model.docente_.email_abc);
            if(!Model.docente_.email.IsNoE())
                To.Add(Model.docente_.email);
            //Attachment = @"C:\Users\ivan\Downloads\10077_WQQ_36936393.pdf";
            //To = "icastaneda@abc.gob.ar";
            Bcc = Context.config.emailDocenteBcc.Replace(" ", "");
            Subject = $"Toma de posesión: {Model.curso_.comision_.pfid} {Model.curso_.disposicion_.asignatura_.nombre}";
            Body = $@"
<p>Hola {Model.docente_.nombres} {Model.docente_.apellidos}, usted ha recibido este email porque fue designado/a en la asignatura <strong>{Model.curso_.disposicion_.asignatura_.nombre}</strong> de sede {Model.curso_.comision_.sede_.nombre}</p>
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
                From = new MailAddress(Context.config.emailDocenteFromAddress),
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

    internal class TomaContralorItem : Toma
    {
        public string cupof
        {
            get { return "S/N"; }
        }

        public string rev
        {
            get { return "P"; }
        }

        public string funcion
        {
            get { return "PF"; }
        }

        public string dia_desde
        {
            get { return "12"; }
        }

        public string mes_desde
        {
            get { return "08"; }
        }

        public string anio_desde
        {
            get { return "24"; }
        }

        public string dia_hasta
        {
            get { return "13"; }
        }

        public string mes_hasta
        {
            get { return "12"; }
        }

        public string anio_hasta
        {
            get { return "24"; }
        }




    }

    private void btnAdministrarToma_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            var button = (e.OriginalSource as Button);
            var toma = (Toma)button.DataContext;

            _navigationService.NavigateTo(typeof(AdministrarTomaPage), toma.id);



        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    
}

public class EstadoContralorData
{
    public ObservableCollection<string> EstadosContralor()
    {
        ObservableCollection<string> responseOC = new ObservableCollection<string>();
        responseOC.Clear();
        responseOC.Add("Pasar");
        responseOC.Add("No pasar");
        responseOC.Add("Pendiente");
        responseOC.Add("Modificar");
        return responseOC;
    }
}