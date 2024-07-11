using SqlOrganize.Sql.Fines2Model3;
using System.Net;
using System.Net.Mail;

namespace Fines2Wpf.Windows.EnviarEmailToma
{
    internal class Email : SmtpClient
    {

        Data_toma_r Model;
        public string? Subject = null;
        public string? Body = null;
        public string? To = null;
        public string? Bcc = null;
        public string? Attachment = null;

        public Email(Data_toma_r model) : base() {
            Host = ContainerApp.config.emailDocenteHost;
            Port = 587;
            Credentials = new NetworkCredential(ContainerApp.config.emailDocenteUser, ContainerApp.config.emailDocentePassword);
            EnableSsl = true;
            Model = model;
            Attachment = $"C:\\Users\\ivan\\Downloads\\{Model.comision__pfid}_{Model.asignatura__codigo}_{Model.docente__numero_documento}.pdf";
            To = Model.docente__email_abc;
            //Attachment = @"C:\Users\ivan\Downloads\10077_WQQ_36936393.pdf";
            //To = "icastaneda@abc.gob.ar";
            Bcc = ContainerApp.config.emailDocenteBcc;
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
            mailMessage.To.Add(To!);
            mailMessage.Bcc.Add(Bcc!);

            Send(mailMessage);
        }


    }
}
