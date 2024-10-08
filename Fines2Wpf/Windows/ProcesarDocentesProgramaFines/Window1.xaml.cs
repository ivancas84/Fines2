﻿using Newtonsoft.Json;
using SqlOrganize;
using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows;

namespace Fines2Wpf.Windows.ProcesarDocentesProgramaFines
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        DAO dao = new ();
        List<string> logs = new();

        public Window1()
        {
            InitializeComponent();
        }

        private void ProcesarButton_Click(object sender, RoutedEventArgs e)
        {
            ProcesarDocentes();
        }

        private void ProcesarDocentes()
        {
            var pfidComisiones = dao.PfidComisiones();
            var docentes = JsonConvert.DeserializeObject<List<Docente>>(data.Text)!;
            logs.Add("Cantidad de docentes a procesar" + docentes.Count.ToString());

            foreach (Docente docente in docentes)
            {

                #region insertar o actualizar docente (se insertan o actualizan todos)
                var d = docente.Dict();
                if (!d["anio_nacimiento"].IsNoE() && !d["mes_nacimiento"].IsNoE() && !d["dia_nacimiento"].IsNoE())
                    d["fecha_nacimiento"] = new DateTime((int)d["anio_nacimiento"], (int)d["mes_nacimiento"], (int)d["dia_nacimiento"]);
                EntityVal vPersona = ContainerApp.db.Values("persona").Set(d).Reset();
                var row = dao.RowByEntityUnique("persona", vPersona.Values());
                if (row != null) {
                    EntityVal vPersonaAux = ContainerApp.db.Values("persona").Set(row);
                    CompareParams cp = new()
                    {
                        Data = vPersona.Values()
                    };
                    IDictionary<string, object> valuesToUpdate = vPersonaAux.Compare(cp);
                    vPersonaAux.SetNotNull(valuesToUpdate);
                    vPersona = vPersonaAux;
                    vPersona.Reset();
                    if (!valuesToUpdate.IsNoE())
                    {
                        try {
                            var p = ContainerApp.db.Persist().Update(vPersona).Exec().RemoveCache();
                        } catch (Exception exception)
                        {
                            logs.Add("No se pudo agregar el docente " + exception.Message);

                        }
                    }
                } else
                {
                    vPersona.Default().Reset();
                    var p = ContainerApp.db.Persist().Insert(vPersona).Exec().RemoveCache();
                }
                #endregion

                #region insertar o actualizar cargo
                foreach (var cargo in docente.cargos)
                {
                    if (pfidComisiones.ToList().Contains(cargo["comision"]))
                    {
                        object idCurso = dao.IdCurso(cargo["comision"], cargo["codigo"]);
                        if (idCurso.IsNoE())
                        {
                            logs.Add("No existe curso " + cargo["comision"] + " " + cargo["codigo"]);
                            continue;

                        }

                        IDictionary<string, object> rowTomaActiva = dao.TomaActiva(idCurso);
                        if(rowTomaActiva != null)
                        {
                            if (!rowTomaActiva["docente"].Equals(vPersona.Get("id")))
                                logs.Add("Existe una toma activa con otro docente en " + cargo["comision"] + " " + cargo["codigo"]);
                            else 
                                logs.Add("La toma ya se encuentra cargada " + cargo["comision"] + " " + cargo["codigo"]);
                        }
                        else
                        {
                            EntityVal vToma = ContainerApp.db.Values("toma").
                                Set("curso", idCurso).
                                Set("docente", vPersona.Get("id")).
                                Set("estado", "Aprobada").
                                Set("estado_contralor", "Pendiente").
                                Set("tipo_movimiento", "AI").
                                Set("fecha_toma",new DateTime(2024,03,11));
                            vToma.Default().Reset();
                            var p = ContainerApp.db.Persist().Insert(vToma).Exec().RemoveCache();
                        }

                    }

                   
                }
                #endregion
            }
            info.Text += String.Join(@"
",logs);
        }


    }

    internal class Docente
    {
        public string numero_documento { set; get; }
        public string nombres { set; get; }
        public string apellidos { set; get; }
        public string? descripcion_domicilio { set; get; }
        public string? telefono { set; get; }
        public string? email_abc { set; get; }
        public int? dia_nacimiento { set; get; }
        public int? mes_nacimiento { set; get; }
        public int? anio_nacimiento { set; get; }
        public List<Dictionary<string, string>> cargos { set; get; }
    }
}
