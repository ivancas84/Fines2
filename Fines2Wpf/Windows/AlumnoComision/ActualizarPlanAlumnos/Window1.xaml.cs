using System.Linq;
using System.Windows;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;


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
        
            var comisiones = ContainerApp.db.ComisionesAutorizadasDePeriodoSql("2024", "1").Cache().Dicts();
            bool persist_ = false;
            var persist = ContainerApp.db.Persist();
            foreach (var comision in comisiones)
            {
                var idAlumnos = DAO.AlumnoComision2.AsignacionesDeComisionSinPlanQuery(comision["id"]!, comision["planificacion__plan"]!).Cache().Dicts().ColOfVal<object>("alumno");
                if (idAlumnos.IsNoE()) continue;
                persist_ = true;
                persist.UpdateValueIds("alumno", "plan", comision["planificacion__plan"], idAlumnos.ToArray());
            }
            if(persist_)
                   persist.Exec().RemoveCache();
        }
    }
}
