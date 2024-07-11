using System;
using System.Collections.Generic;
using System.Collections.Immutable;
using System.Drawing;
using System.Globalization;
using QuestPDF.Fluent;
using QuestPDF.Infrastructure;
using SqlOrganize.ValueTypesUtils;

namespace Fines2Wpf.Windows.Alumno.ConstanciaPdf
{

    internal class ConstanciaDocument : IDocument
    {
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
                    column.Item().Image("C:\\projects\\Fines2\\Fines2Wpf\\Images\\logo.jpg").FitArea();
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
            var subtitleStyle = TextStyle.Default.FontSize(14).SemiBold();

            container.Column(column =>
            {
                column.Spacing(20);
                column.Item().AlignCenter().PaddingTop(20).Text(Model.titulo_constancia).Style(titleStyle);
                column.Item().AlignLeft().Text(text =>
                {
                    text.Span("La dirección de la Escuela de Educación CENS N° 462 de La Plata, hace constar por la presente que: ");
                    text.Span($"   {Model.persona__apellidos!.ToUpper() }, {Model.persona__nombres!.ToTitleCase() }  ").Underline().SemiBold();
                    text.Span(" DNI N° ");
                    text.Span($"   {Model.persona__numero_documento }  ").Underline().SemiBold();
                    text.Span(" es alumno regular de ");

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
                    row.RelativeItem(2).AlignRight().AlignBottom().PaddingRight(60).Column(column =>
                    {
                        column.Item().Image("C:\\projects\\Fines2\\Fines2Wpf\\Images\\sello_cens.png").FitArea();
                    });

                    row.RelativeItem().AlignRight().AlignMiddle().Column(column =>
                    {
                        column.Item().Image("C:\\projects\\Fines2\\Fines2Wpf\\Images\\firma_director.png").FitArea();
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
