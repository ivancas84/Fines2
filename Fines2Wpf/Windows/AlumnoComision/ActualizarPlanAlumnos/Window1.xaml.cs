using System;
using System.Collections.Generic;
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

namespace Fines2Wpf.Windows.AlumnoComision.ActualizarPlanAlumnos
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        Fines2Wpf.DAO.AlumnoComision asignacionDAO = new();
        Fines2Wpf.DAO.Comision comisionDAO = new();

        List<string> logs = new();
        public Window1()
        {
            InitializeComponent();
        
            var comisiones = comisionDAO.ComisionesAutorizadasPorSemestre("2023", "2");
            bool persist_ = false;
            var persist = ContainerApp.db.Persist();
            foreach (var comision in comisiones)
            {
                var idAlumnos = asignacionDAO.IdAlumnosConPlanDiferenteDeComision(comision["id"], comision["planificacion-plan"]);
                if (idAlumnos.IsNullOrEmpty()) continue;
                persist_ = true;
                persist.UpdateValueIds("alumno", "plan", comision["planificacion-plan"], idAlumnos.ToArray());
            }
            if(persist_)
                   persist.Transaction().RemoveCache();
        }
    }
}
