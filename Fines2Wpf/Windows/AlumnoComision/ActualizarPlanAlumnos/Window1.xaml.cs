using System.Linq;
using System.Windows;
using Fines2Wpf.DAO;
using SqlOrganize;
using Utils;

namespace Fines2Wpf.Windows.AlumnoComision.ActualizarPlanAlumnos
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        public Window1()
        {
            InitializeComponent();
        
            var comisiones = ContainerApp.db.ComisionesAutorizadasDePeriodoSql("2024", "1").Cache().ColOfDict();
            bool persist_ = false;
            var persist = ContainerApp.db.Persist();
            foreach (var comision in comisiones)
            {
                var idAlumnos = DAO.AlumnoComision2.AsignacionesDeComisionSinPlanQuery(comision["id"]!, comision["planificacion-plan"]!).Cache().ColOfDict().ColOfVal<object>("alumno");
                if (idAlumnos.IsNullOrEmpty()) continue;
                persist_ = true;
                persist.UpdateValueIds("alumno", "plan", comision["planificacion-plan"], idAlumnos.ToArray());
            }
            if(persist_)
                   persist.Exec().RemoveCache();
        }
    }
}
