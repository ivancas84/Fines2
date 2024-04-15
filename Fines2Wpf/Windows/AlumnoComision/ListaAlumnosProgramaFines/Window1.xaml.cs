using Fines2Wpf.Data;
using System;
using System.Collections.ObjectModel;
using System.Windows;
using Utils;

namespace Fines2Wpf.Windows.AlumnoComision.ListaAlumnosProgramaFines
{
    /// <summary>
    /// Generar lista de alumnos para copiar y pegar en el script js del page manipulator y cargar alumnos en programafines
    /// </summary>
    public partial class Window1 : Window
    {

        DAO.AlumnoComision alumnoComisionDAO = new();

        ObservableCollection<AlumnoProgramaFinesData> alumnosOC = new();

        public Window1()
        {
            InitializeComponent();
            alumnosDataGrid.ItemsSource = alumnosOC;

            var alumnoComisionData = alumnoComisionDAO.AsignacionesActivasDeComisionesAutorizadasPorSemestreQuery("2024", "1").ColOfDictCache();
            foreach(var ac in alumnoComisionData)
            {
                var acObj = ac.Obj<Data_alumno_comision_r>();
                
                AlumnoProgramaFinesData apf = new();
                
                apf.dni_cargar = acObj.persona__numero_documento;
                apf.apellido = acObj.persona__apellidos;
                apf.nombre = acObj.persona__nombres;

                if (acObj.persona__genero.IsNullOrEmptyOrDbNull() || acObj.persona__genero!.ToLower().Contains("f"))
                    apf.sexo = "2"; //Femenino
                else
                    apf.sexo = "1"; //Masculino

                if (!acObj.persona__fecha_nacimiento.IsNullOrEmptyOrDbNull())
                {
                    var fechaNacimiento = (DateTime)acObj.persona__fecha_nacimiento!;
                    apf.dia_nac = fechaNacimiento.Day.ToString();
                    apf.mes_nac = fechaNacimiento.Month.ToString();
                    apf.ano_nac = fechaNacimiento.Year.ToString();
                }

                if (!acObj.persona__cuil.IsNullOrEmptyOrDbNull() && acObj.persona__cuil.Length == 11)
                {
                    apf.cuil1 = acObj.persona__cuil.Substring(0, 2);
                    apf.cuil2 = acObj.persona__cuil.Substring(10, 1);
                }

                apf.subcategory = acObj.comision__pfid;

                alumnosOC.Add(apf);

            }


            jsonTextBox.Text = Newtonsoft.Json.JsonConvert.SerializeObject(alumnosOC);


        }
    }
}
