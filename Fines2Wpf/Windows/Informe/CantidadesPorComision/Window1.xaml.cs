using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Windows;
using SqlOrganize;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using SqlOrganize.CollectionUtils;

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
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) = 18 THEN '18'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) = 19 THEN '19'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) = 20 THEN '20'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) = 21 THEN '21'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) = 22 THEN '22'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) = 23 THEN '23'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) = 24 THEN '24'    
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) BETWEEN 25 AND 29 THEN '25__29'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) BETWEEN 30 AND 34 THEN '30__34'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) BETWEEN 35 AND 39 THEN '35__39'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) BETWEEN 40 AND 44 THEN '40__44'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) BETWEEN 45 AND 49 THEN '45__49'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) BETWEEN 50 AND 54 THEN '50__54'
    WHEN TIMESTAMPDIFF(YEAR, $persona__fecha_nacimiento, CURDATE()) >= 55 THEN '55+' 
END AS edad, count(*) as cantidad
").

                Group("$comision__pfid, $planificacion__anio, $planificacion__semestre, $plan__orientacion, edad").
                Where("$calendario__anio = @0 AND $calendario__semestre = @1 AND $comision__autorizada").
                Size(0).
                Param("@0", ContainerApp.config.anio).Param("@1", ContainerApp.config.semestre).Dicts();


            ContainerApp.db.ClearAndAddDataToOC(data, informeEdadOC);

            data = ContainerApp.db.Sql("alumno_comision").Select(@"COUNT(*) as cantidad
").
                Group("$comision__pfid, $planificacion__anio, $planificacion__semestre, $plan__orientacion, $persona__genero").
                Where("$calendario__anio = @0 AND $calendario__semestre = @1 AND $comision__autorizada").
                Size(0).

                Param("@0", ContainerApp.config.anio).Param("@1", ContainerApp.config.semestre).Dicts();

            ContainerApp.db.ClearAndAddDataToOC(data, informeGeneroOC);
        }
    }
}
