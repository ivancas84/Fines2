using Fines2Model3.Data;
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
using SqlOrganize;

namespace Fines2Wpf.Windows.Informe.CantidadesPorComision
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        ObservableCollection<Item> informeOC = new();

        public Window1()
        {

            InitializeComponent();
            informeDataGrid.ItemsSource = informeOC;


            Data_alumno_comision_r search = new();
            search.calendario__anio = 2024;
            search.calendario__semestre = 1;

            var data = ContainerApp.db.Sql("alumno_comision").Select(@"
CASE
    WHEN fecha_nacimiento IS NULL THEN '18?'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) = 18 THEN '18'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) = 19 THEN '19'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) = 20 THEN '20'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) = 21 THEN '21'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) = 22 THEN '22'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) = 23 THEN '23'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) = 24 THEN '24'    
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) BETWEEN 25 AND 29 THEN '25-29'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) BETWEEN 30 AND 34 THEN '30-34'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) BETWEEN 35 AND 39 THEN '35-39'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) BETWEEN 40 AND 44 THEN '40-44'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) BETWEEN 45 AND 49 THEN '45-49'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) BETWEEN 50 AND 54 THEN '50-54'
    WHEN TIMESTAMPDIFF(YEAR, $persona-fecha_nacimiento, CURDATE()) >= 55 THEN '55+' 
END AS edad, count(*) as cantidad
").
                
                Group("$planificacion-anio, $planificacion-semestre, $plan-orientacion, edad").
                Where("$calendario-anio = @0 AND $calendario-semestre = @1").
                Parameters(ContainerApp.config.anio, ContainerApp.config.semestre).ColOfDict();

            informeOC.Clear();
            informeOC.AddRange(data);   
        }
    }
}
