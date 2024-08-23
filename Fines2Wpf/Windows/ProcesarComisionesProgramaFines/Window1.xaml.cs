using SqlOrganize;
using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows;

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

            bool procesar_docente = false;

            foreach (var line in data.Text.Split(new string[] { Environment.NewLine }, StringSplitOptions.RemoveEmptyEntries))
            {

                if (procesar_docente) //se coloca primero el procesar docente porque esta ubicado en la linea despues del curso
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
                        if (id.IsNoE() || id.IsDbNull())
                        {
                            logs.Add("No existe docente " + cuil);
                            continue;
                        }
                        var p = ContainerApp.db.Persist().UpdateValueIds("persona", "cuil", String.Join("", cuil_), id).Exec().RemoveCache();
                        continue;
                    }

                }

                
                foreach (var dia in dias)
                {
                    if (line.Contains(dia))
                    {
                        dict["comision__pfid"] = line.Substring(0, line.IndexOf("/"));
                        dict["asignatura__codigo"] = line.Substring(line.IndexOf("/")+1,line.IndexOf(" ")-line.IndexOf("/")-1);
                        if (dict["asignatura__codigo"].ToString().Length > 5)
                            dict["asignatura__codigo"] = dict["asignatura__codigo"].ToString().Substring(0, 5).Trim();
                        dict["descripcion_horario"] = line.Substring(line.IndexOf(dia));
                        if (pfidComisiones.Contains(dict["comision__pfid"]))
                        {
                            logs.Add("Procesando comision" + dict["comision__pfid"].ToString());

                            dict["id"] = dao.IdCurso(dict["comision__pfid"].ToString()!, dict["asignatura__codigo"].ToString()!);
                            if (dict["id"].IsNoE() || dict["id"].IsDbNull())
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

                

            }

            info.Text += String.Join(@"
", logs);

        }


    }

}
