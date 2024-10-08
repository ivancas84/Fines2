﻿using System;
using System.Collections.Generic;
using System.Collections.Immutable;
using System.Drawing;
using System.Globalization;
using QuestPDF.Fluent;
using QuestPDF.Infrastructure;
using SqlOrganize;

namespace Fines2Wpf.Windows.TomaPosesionPdf
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
                column.Item().AlignCenter().PaddingTop(20).Text("Toma de Posesión CENS 462").Style(titleStyle);
                column.Item().AlignLeft().Text("La dirección del CENS 462 procede a realizar la toma de posesión docente bajo el siguiente detalle:");
                column.Item().Text("Datos del Docente").Style(subtitleStyle);
                column.Item().Element(ComposeTableDocente);
                column.Item().Text("Datos del cargo").Style(subtitleStyle);
                column.Item().Element(ComposeTableCargo);
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

        void ComposeTableDocente(IContainer container)
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

                if(Model.docente__fecha_nacimiento.IsNoE())
                    table.Cell().Row(2).Column(4).Element(BlockContent).Text("");
                else
                    table.Cell().Row(2).Column(4).Element(BlockContent).Text(((DateTime)Model.docente__fecha_nacimiento!).ToString("dd/MM/yyyy"));

                table.Cell().Row(3).Column(1).Element(BlockHeader).Text("Email").Bold();
                table.Cell().Row(3).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.docente__email_abc);

                table.Cell().Row(4).Column(1).Element(BlockHeader).Text("Domicilio").Bold();
                table.Cell().Row(4).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.docente__descripcion_domicilio);

            });
        }

        void ComposeTableCargo(IContainer container)
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
                table.Cell().Row(2).Column(2).ColumnSpan(5).Element(BlockContent).Text(Model.domicilio__calle + " e/ " +  Model.domicilio__entre + " N° " + Model.domicilio__numero + " " + Model.domicilio__localidad);

                table.Cell().Row(3).Column(1).Element(BlockHeader).Text("Horario").Bold();
                table.Cell().Row(3).Column(2).ColumnSpan(5).Element(BlockContent).Text(Model.curso__descripcion_horario);

                table.Cell().Row(4).Column(1).Element(BlockHeader).Text("Fecha Toma").Bold();
                table.Cell().Row(4).Column(2).ColumnSpan(2).Element(BlockContent).Text(((DateTime)Model.fecha_toma!).ToString("dd/MM/yyyy"));

                table.Cell().Row(4).Column(4).Element(BlockHeader).Text("Fecha Fin").Bold();
                table.Cell().Row(4).Column(5).ColumnSpan(2).Element(BlockContent).Text("12/07/2024");

                table.Cell().Row(5).Column(1).Element(BlockHeader).Text("Asignatura").Bold();
                table.Cell().Row(5).Column(2).ColumnSpan(3).Element(BlockContent).Text(Model.asignatura__nombre + " " + Model.asignatura__codigo);

                table.Cell().Row(5).Column(5).Element(BlockHeader).Text("Hs Cát").Bold();
                table.Cell().Row(5).Column(6).Element(BlockContent).Text(Model.curso__horas_catedra.ToString());

                table.Cell().Row(6).Column(1).Element(BlockHeader).Text("Resolución").Bold();
                table.Cell().Row(6).Column(2).ColumnSpan(5).Element(BlockContent).Text(Model.plan__resolucion);


            });
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
