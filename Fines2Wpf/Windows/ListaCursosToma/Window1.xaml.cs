using Fines2Wpf.Data;
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

namespace Fines2Wpf.Windows.ListaCursosToma
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        private ObservableCollection<Data_curso_r> cursoOC = new()
            ;
        public Window1()
        {
            InitializeComponent();
            DAO.Curso cursoDAO = new();
            var cursosData = cursoDAO.CursosAutorizadosSemestre("2024", "1");
            cursoDataGrid.ItemsSource = cursoOC;
            cursoOC.Clear();
            foreach (var cursoData in cursosData)
            {
                Data_curso_r cursoObj = cursoData.Obj<Data_curso_r>();
                cursoObj.planificacion__Label = cursoObj.planificacion__anio + "/" + cursoObj.planificacion__semestre;
                cursoObj.asignatura__nombre = cursoObj.asignatura__nombre + " " + cursoObj.asignatura__codigo;
                string barrio = cursoObj.domicilio__barrio ?? "";
                cursoObj.domicilio__Label = "CALLE " + cursoObj.domicilio_cen__calle + " e/ " + cursoObj.domicilio__entre + " N° " + cursoObj.domicilio__numero + " " + barrio + " " + cursoObj.domicilio_cen__localidad;
                cursoOC.Add(cursoObj);
                cursoObj.Label = "https://planfines2.com.ar/wp/toma-de-posesion?curso=" + cursoObj.comision__pfid + "-" + cursoObj.asignatura__codigo;

            }
        }
    }
}
