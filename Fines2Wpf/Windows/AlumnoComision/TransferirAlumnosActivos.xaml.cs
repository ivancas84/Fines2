﻿using SqlOrganize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;
using Utils;
using Fines2Wpf.Windows.Curso.ListaCursoSemestreSinTomasAprobadas;
using Fines2Wpf.Windows.ProcesarDocentesProgramaFines;

namespace Fines2Wpf.Windows.AlumnoComision
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class TransferirAlumnosActivos : Window
    {
        Fines2Wpf.DAO.Comision comisionDAO = new();
        Fines2Wpf.DAO.AlumnoComision alumnoComisionDAO = new();
        private ObservableCollection<Model> data = new();
            

        public TransferirAlumnosActivos()
        {
            InitializeComponent();
            dataGrid.ItemsSource = data;

            var idsComisiones = comisionDAO.IdsComisionesAutorizadasConSiguientePorSemestre("2023", "1");
            var alumnosComisiones = alumnoComisionDAO.AsignacionesActivasPorComisiones(idsComisiones);
            var idsComisionesSiguientes = alumnosComisiones.ColOfVal<object>("comision-comision_siguiente");
            var idsComisionesSiguientes_ = idsComisionesSiguientes.GroupBy(x => x.ToString()).Select(x => x.First()).ToList();
            var comisionesSiguientesAgrupadasPorId = comisionDAO.ComisionesPorIds(idsComisionesSiguientes_).DictOfDictByKey<object>("id");
            data.Clear();
            
            foreach (var ac in alumnosComisiones)
            {
                var cs = comisionesSiguientesAgrupadasPorId[ac["comision-comision_siguiente"]];
                ac["comision_siguiente-numero"] = cs["sede-numero"].ToString() + cs["division"].ToString() + cs["planificacion-anio"].ToString() + cs["planificacion-semestre"].ToString();

                EntityValues v = ContainerApp.db.Values("alumno_comision");
                v.Set("comision", ac["comision-comision_siguiente"]).
                    Set("alumno", ac["alumno"]).
                    Set("estado", "Activo");
                               
                ContainerApp.db.Persist("alumno_comision").Persist(v).Exec();

                data.Add(ac.Obj<Model>());
            }


        }

        public class Model
        {
            public string id { get; set; }
            public string comision_siguiente__numero { get; set; }
            public string persona__nombres { get; set; }
            public string persona__apellidos { get; set; }
            public string estado { get; set; }
        }

    }
}
