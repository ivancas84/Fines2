using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
using SqlOrganize;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;

namespace Fines2Wpf.Windows.Informe.CantidadesPorComision
{
    /// <summary>
    /// Lógica de interacción para Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {

        ObservableCollection<ItemEdad> informeEdadOC = new();

        ObservableCollection<ItemGenero> informeGeneroOC = new();

        public Window1()
        {

            InitializeComponent();
            informeEdadDataGrid.ItemsSource = informeEdadOC;
            informeGeneroDataGrid.ItemsSource = informeGeneroOC;


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
                
                Group("$comision-pfid, $planificacion-anio, $planificacion-semestre, $plan-orientacion, edad").
                Where("$calendario-anio = @0 AND $calendario-semestre = @1 AND $comision-autorizada").
                Size(0).
                Parameters(ContainerApp.config.anio, ContainerApp.config.semestre).ColOfDict();




            informeEdadOC.Clear();
            informeEdadOC.AddRange(data);

            data = ContainerApp.db.Sql("alumno_comision").Select(@"COUNT(*) as cantidad
").
                Group("$comision-pfid, $planificacion-anio, $planificacion-semestre, $plan-orientacion, $persona-genero").
                Where("$calendario-anio = @0 AND $calendario-semestre = @1 AND $comision-autorizada").
                Size(0).

                Parameters(ContainerApp.config.anio, ContainerApp.config.semestre).ColOfDict();

            informeGeneroOC.Clear();
            informeGeneroOC.AddRange(data);
        }
    }
}
