using MySqlX.XDevAPI.Relational;
using SqlOrganize;
using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Security.Cryptography;
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
using Fines2Wpf.Windows.Comision;
using Fines2Wpf.Windows.ListaCursos;

namespace Fines2Wpf.Windows.ProcesarComisionesProgramaFines
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        DAO dao = new();
        List<string> logs = new();
        List<string> dias = new() { "Lunes", "Martes", "Miercoles", "Jueves", "Viernes" };

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
            Dictionary<string, object> dict = new Dictionary<string, object>();
            foreach (var line in data.Text.Split(new string[] { Environment.NewLine }, StringSplitOptions.RemoveEmptyEntries))
            {
                bool procesar_docente = false;

                
                foreach (var dia in dias)
                {
                    if (line.Contains(dia))
                    {
                        dict["comision__pfid"] = line.Substring(0, line.IndexOf("/"));
                        dict["asignatura__codigo"] = line.Substring(line.IndexOf("/")+1,line.IndexOf(" ")-line.IndexOf("/")-1);
                        dict["descripcion_horario"] = line.Substring(line.IndexOf(dia));
                        if (pfidComisiones.Contains(dict["comision__pfid"]))
                        {
                            logs.Add("Procesando comision" + dict["comision__pfid"].ToString());

                            dict["id"] = dao.IdCurso(dict["comision__pfid"].ToString()!, dict["asignatura__codigo"].ToString()!);
                            if (dict["id"].IsNullOrEmpty() || dict["id"].IsDbNull())
                            {
                                logs.Add("No existe curso " + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString());
                                break;
                            }
                            var p = ContainerApp.db.Persist().UpdateValueIds("curso", "descripcion_horario", dict["descripcion_horario"].ToString()!, dict["id"]!).Exec().RemoveCache();
                            procesar_docente = true;
                        }
                        break;

                    }
                }

                if (procesar_docente)
                {
                    if (line.Contains("*"))
                    {
                        logs.Add("Docente sin designar en curso " + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString());
                        procesar_docente = false;
                        continue;
                    }
                    else if (!line.Contains("-"))
                    {
                        logs.Add("Salto de línea, en curso " + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString());
                        continue;
                    }
                    else
                    {
                        logs.Add("Procesando docente de curso" + dict["comision__pfid"].ToString() + " " + dict["asignatura__codigo"].ToString());
                        procesar_docente = false;
                        string cuil = line.Substring(line.IndexOf("-") - 2, 13);
                        string[] cuil_ = cuil.Split("-");
                        object? id = dao.IdPersona(cuil_[1]);
                        if (id.IsNullOrEmpty() || id.IsDbNull())
                        {
                            logs.Add("No existe docente " + cuil);
                            continue;
                        }
                        List<object> ids = new List<object>() { id };
                        var p = ContainerApp.db.Persist().UpdateValueIds("persona", "cuil", String.Join("", cuil_), ids).Exec().RemoveCache();
                        continue;
                    }

                }

            }

            info.Text += String.Join(@"
", logs);

        }


    }

}
