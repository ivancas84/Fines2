using System;
using System.Collections.Generic;
using System.Collections.Immutable;
using System.Drawing;
using System.Globalization;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Documents;
using Org.BouncyCastle.Crypto.Modes.Gcm;
using QRCoder;
using QuestPDF.Fluent;
using QuestPDF.Helpers;
using QuestPDF.Infrastructure;
using Utils;

namespace Fines2Wpf.Windows.Alumno.ConstanciaAlumnoRegularPdf
{

    internal class ConstanciaDocument : IDocument
    {
        private string imagePath = Path.Combine(Directory.GetCurrentDirectory(), ContainerApp.config.imagePath);

        public ConstanciaData Model;
        public DocumentMetadata GetMetadata() => DocumentMetadata.Default;
        public DocumentSettings GetSettings() => DocumentSettings.Default;

        TextInfo textInfo = new CultureInfo("es-AR", false).TextInfo;

        public ConstanciaDocument(ConstanciaData model)
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

        void ComposeHeader(IContainer container)
        {
            container.Row(row =>
            {
                row.RelativeItem(3).Height(75).AlignBottom().Column(column =>
                {
                    column.Item().Image(imagePath + "logo.jpg").FitArea();
                });

                row.RelativeItem().AlignRight().Column(column =>
                {
                    column.Item().Image(Model.qr_code).FitArea();
                }); 
            });
        }

        void ComposeContent(IContainer container)
        {
            var titleStyle = TextStyle.Default.FontSize(20).SemiBold();
            var contentStyle = TextStyle.Default.LineHeight(1.5f).FontSize(12);
            var subtitleStyle = TextStyle.Default.FontSize(14).SemiBold();

            container.Column(column =>
            {
                column.Spacing(20);
                column.Item().AlignCenter().PaddingTop(20).Text("CONSTANCIA DE ALUMNO REGULAR").Style(titleStyle);
                column.Item().AlignLeft().PaddingTop(20).PaddingLeft(20).PaddingRight(20).
                Text(text =>
                {
                    
                    text.Justify();
                    text.Span("    La Dirección de la Escuela de Educación CENS N° 462 de La Plata, hace constar por la presente que: ").Style(contentStyle);
                    text.Span($" {Model.persona__apellidos!.ToUpper() }, {Model.persona__nombres!.ToTitleCase() }     ").Underline().SemiBold().Style(contentStyle);
                    text.Span(" DNI N° ").Style(contentStyle);
                    text.Span($" {Model.persona__numero_documento } ").Underline().SemiBold().Style(contentStyle);
                    text.Span(" es alumno regular de ").Style(contentStyle);
                    text.Span($" {Model.anio_constancia.ToOrdinalSpanish().ToUpper()} ").Underline().SemiBold().Style(contentStyle);
                    text.Span(" año ").Style(contentStyle);
                    text.Span(" Programa Fines 2 Trayecto Secundario ").Underline().SemiBold().Style(contentStyle);
                    text.Span(" con orientación en ").Style(contentStyle);
                    text.Span($" {Model.orientacion_constancia} ").Underline().SemiBold().Style(contentStyle);
                    text.Span(" resolución ").Style(contentStyle);
                    text.Span($" {Model.resolucion_constancia} ").Underline().SemiBold().Style(contentStyle);
                });
                column.Item().AlignLeft().PaddingLeft(20).PaddingRight(20).Text(text =>
                {
                    text.Justify();
                    text.Span("Se extiende el presente a pedido del interesado en La Plata el día ").Style(contentStyle);
                    text.Span($" {DateTime.Now.Day.ToString()} de {DateTime.Now.ToString("MMMM")} de {DateTime.Now.Year.ToString()} ").Underline().SemiBold().Style(contentStyle);
                    text.Span(" para ser presentado ante ").Style(contentStyle);
                    text.Span($" quien corresponda ").Underline().SemiBold().Style(contentStyle);
                });
                column.Item().AlignLeft().PaddingLeft(20).PaddingRight(20).Text(text =>
                {
                    text.Justify();
                    text.Span("Se extiende el presente a pedido del interesado en La Plata el día ").Style(contentStyle);
                    text.Span($" {DateTime.Now.Day.ToString() } de {DateTime.Now.ToString("MMMM")} de {DateTime.Now.Year.ToString()} ").Underline().SemiBold().Style(contentStyle);
                    text.Span(" para ser presentado ante ").Style(contentStyle);
                    text.Span($" quien corresponda ").Underline().SemiBold().Style(contentStyle);
                });
                    
                //column.Item().Element(ComposeTableDocente);
                //column.Item().Text("Datos del cargo").Style(subtitleStyle);
                //column.Item().Element(ComposeTableCargo);
            });
        }

        void ComposeFooter(IContainer container)
        {

            container.Layers(layers =>
            {
                layers.PrimaryLayer().Row(row =>
                {
                    row.RelativeItem(2).AlignRight().PaddingRight(60).Column(column =>
                    {
                        column.Item().Image(imagePath + "sello_cens.png").FitArea();
                    });

                    row.RelativeItem().AlignRight().AlignMiddle().Column(column =>
                    {
                        column.Item().Image(imagePath + "firma_director.png").FitArea();
                    });
                });
            });
            /*
            container.(row =>
            {
                row.ConstantItem(100).AlignCenter().Column(column =>
                {
                    column.Item().Image("C:\\projects\\SqlOrganize\\Fines2Wpf\\Images\\sello_cens.png").FitArea();
                });

                row.ConstantItem(100).AlignRight().Column(column =>
                {
                    column.Item().Image("C:\\projects\\SqlOrganize\\Fines2Wpf\\Images\\firma_director.png").FitArea();
                });
            });*/
        }


        static IContainer BlockHeader(IContainer container)
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

        static IContainer BlockContent(IContainer container)
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
}
