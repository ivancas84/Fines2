using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Diagnostics;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Threading;
using WpfUtils;
using WpfUtils.Controls;

namespace FinesApp.Views;

public partial class InformeComisionPage : Page, INotifyPropertyChanged
{

    #region Autocomplete v3 - comision
    private ObservableCollection<Comision> comisionOC = new(); //datos consultados de la base de datos
    private DispatcherTimer comisionTypingTimer; //timer para buscar
    #endregion

    private ObservableCollection<Curso> cursoOC = new(); //datos consultados de la base de datos

    private ObservableCollection<AsignacionConAsignaturasItem> asignacionOC = new(); //datos consultados de la base de datos

    public InformeComisionPage()
    {
        InitializeComponent();
        DataContext = this;

        #region Autocomplete v3 - comision
        cbxComision.InitComboBoxConstructor(comisionOC);
        comisionTypingTimer = new DispatcherTimer { Interval = TimeSpan.FromMilliseconds(300) };
        comisionTypingTimer.Tick += ComisionTimerTick;
        #endregion

        cursoDataGrid.ItemsSource = cursoOC;
        asignacionDataGrid.ItemsSource = asignacionOC;

        #region tab registro alumnos
        dgdInfoPersist.ItemsSource = ocDataPersist;
        #endregion

    }

    #region Autocomplete v3 - organismo
    private void ComisionTimerTick(object sender, EventArgs e)
    {
        try
        {
            (string? text, TextBox? textBox, int? textBoxPos) = cbxComision.SetTimerTickInitializeItem<Comision>(comisionTypingTimer);
            if (text == null)
                return;

            var list = ComisionDAO.Comisiones__By_Search(text);

            list.AddEntitiesToClearOC(comisionOC);

            cbxComision.SetTimerTickFinalize(textBox!, text, (int)textBoxPos!);
        }
        catch (Exception ex)
        {
            ToastExtensions.ShowExceptionMessageWithFileNameAndLineNumber(ex);
        }
    }

    private void CbxComision_KeyUp(object sender, KeyEventArgs e)
    {
        comisionTypingTimer.SetKeyUp(e);
    }

    private void CbxComision_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        var cb = (ComboBox)sender;

        if (cb.SelectedIndex > -1)
        {
            var obj = (Comision)cb.SelectedItem;
            Buscar(obj);
        }
    }

    #endregion

    private void Buscar(Comision comision)
    {
        try
        {
            var cursosData = CursoDAO.Cursos__By_IdComision(comision.id);
            var tomaData = TomaDAO.TomasAprobadas__By_IdsComisiones(comision.id);
            //cursosData.MergeByKeys(tomaData, "id", "curso", "toma_");
            cursosData.AddEntitiesToClearOC(cursoOC);

            //var idsAsignaturas = cursosData.ColOfVal<string>("asignatura-id").ToList();

            //var disposicionesData = Context.db.Sql("disposicion").Equal("$planificacion", comision.planificacion).Cache().Dicts();
            //var idsDisposiciones = disposicionesData.ColOfVal<string>("id").ToList();


            var asignacionesData = AsignacionDAO.AsignacionesDeComisionesSql(comision.id).Cache().Dicts();

            if (asignacionesData.Any()) { 
                var idAlumnos = asignacionesData.ColOfVal<object>("alumno").ToArray();

                var calificacionesData = CalificacionDAO.CalificacionesAprobadasPorAlumnoDePlanificacionSql(comision.planificacion, idAlumnos).Cache().Dicts().DictOfListByKeys("alumno");


                asignacionOC.Clear();
                foreach (var asi in asignacionesData)
                {
                    var itemObj = Entity.CreateFromDict<AsignacionConAsignaturasItem>(asi);
                    if (calificacionesData.ContainsKey(itemObj.alumno))
                    {
                        foreach (var cal in calificacionesData[itemObj.alumno])
                        {
                            itemObj.cantidad_aprobadas++;
                            var calificacionObj = Entity.CreateFromDict<Calificacion>(cal);

                            int index = idsAsignaturas.IndexOf(calificacionObj.disposicion_.asignatura_.id);
                            switch (index)
                            {
                                case 0: 
                                    itemObj.asignatura0 = calificacionObj.nota_aprobada; 
                                break;

                                case 1:
                                    itemObj.asignatura1 = calificacionObj.nota_aprobada;  
                                break;

                                case 2:
                                    itemObj.asignatura2 = calificacionObj.nota_aprobada;
                                    break;

                                case 3:
                                    itemObj.asignatura3 = calificacionObj.nota_aprobada;
                                    break;

                                case 4:
                                    itemObj.asignatura4 = calificacionObj.nota_aprobada;
                                    break;
                            }

                        }
                    }
                    asignacionOC.Add(itemObj);
                }
                //Context.db.AddEntitiesToClearOC(data, alumnos3OC);
            }

        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void WhatsAppLink_Click(object sender, RoutedEventArgs e)
    {

        var data = ((Hyperlink)e.OriginalSource).DataContext as Curso;

        var hyperlink = sender as Hyperlink;
        string phoneNumber = hyperlink?.Tag.ToString();

        if (!string.IsNullOrEmpty(phoneNumber))
        {
            string message = "Hola " + data.toma_activa_.docente_.Label + " quería hacerte una consulta acerca de la asignatura " + data.disposicion_.asignatura_.nombre + " de comision " + data.comision_.pfid;
            string whatsappUrl = $"https://web.whatsapp.com/send?phone={phoneNumber}&text={Uri.EscapeDataString(message)}";

            Process.Start(new ProcessStartInfo(whatsappUrl) { UseShellExecute = true });
        }
    }



    #region tab registro alumnos
    /*private IEnumerable<PersistContext> persists;
    private ObservableCollection<Entity> ocDataPersist = new();
    */
    private void btnProcesarAlumnos_Click(object sender, RoutedEventArgs e)
    {
        /*try
        {
            Context.db.CreateQueue();
            //TODO NO SE QUE TENGO QUE PROCESAR ACA
            //Context.db.PersistAsignacionesComisionText(cbxComision.SelectedValue, tbxAlumnos.Text);
            Context.db.ProcessQueue();
            //TODO NO SE QUE TENGO QUE MOSTRAR ACA

            /*foreach(var p in persists)
            {
                var resultObj = Context.db.Data<Entity>();
                resultObj.Label = p.logging.ToString();
                ocDataPersist.Add(resultObj);
            }
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }*/
    }

    private void btnGuardarAlumnos_Click(object sender, RoutedEventArgs e)
    {
        /*try
        {
            Context.db.ProcessQueue();
            ToastExtensions.Show("Se han registrado las asignaciones");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }*/
    }
    #endregion


    #region INotifyPropertyChanged
    public event PropertyChangedEventHandler PropertyChanged;

    private void Set<T>(ref T storage, T value, [CallerMemberName] string propertyName = null)
    {
        if (Equals(storage, value))
        {
            return;
        }

        storage = value;
        OnPropertyChanged(propertyName);
    }

    private void OnPropertyChanged(string propertyName) => PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
    #endregion

    private void btnCambiarEstado_Click(object sender, RoutedEventArgs e)
    {
        /*try
        {
            using (Context.db.CreateQueue())
            {
                if (!asignacionOC.Any())
                    throw new Exception("La lista de alumnos esta vacía");

                PersistContext persist = Context.db.Persist();

                foreach (AsignacionConAsignaturasItem asiObj in asignacionOC)
                {
                    string estado = (asiObj.cantidad_aprobadas < 3) ? "No activo" : "Activo";
                    persist.UpdateFieldIds("alumno_comision", "estado", estado, asiObj.id);
                }
                Context.db.ProcessQueue();
            } 

            Buscar((Comision)cbxComision.SelectedItem);
            ToastExtensions.Show("Se ha cambiado el estado de los alumnos");
        } catch (Exception ex) { 
            ex.ToastException();
        }*/
    }

    private void btnTransferirAlumnos_Click(object sender, RoutedEventArgs e)
    {
        try {
            using (Context.db.CreateQueue())
            {

                if (!asignacionOC.Any())
                    throw new Exception("La lista de alumnos esta vacía");

                var comObj = (Comision)cbxComision.SelectedItem;

                if (comObj.IsNoE())
                    throw new Exception("No se encuentra seleccionada ninguna comision");


                if (comObj.comision_siguiente.IsNoE())
                    throw new Exception("No se encuentra definida la comision siguiente");

                var idAlumnosExistentes = AsignacionDAO.AsignacionesDeComisionesSql(comObj.comision_siguiente).Cache().Dicts().DictOfDictByKeysValue("id", "alumno");


                PersistContext persist = Context.db.Persist();

                foreach (AsignacionConAsignaturasItem asiObj in asignacionOC)
                {
                    asiObj.Msg = "";
                    if (!asiObj.estado.Equals("Activo"))
                    {
                        if (idAlumnosExistentes.ContainsKey(asiObj.alumno))
                        {
                            persist.DeleteIds("alumno_comision", idAlumnosExistentes[asiObj.alumno]);
                            asiObj.Msg = "Eliminado de la comision siguiente";
                        }

                        continue;


                    }

                    if (idAlumnosExistentes.ContainsKey(asiObj.alumno))
                    {
                        asiObj.Msg = "Ya existe en la comision siguiente";
                        continue;
                    }

                    var asignacion = new AlumnoComision();
                    asignacion.comision = comObj.comision_siguiente;
                    asignacion.alumno = asiObj.alumno;
                    asignacion.estado = "Activo";
                    persist.InsertIfNotExists(asignacion);
                    asiObj.Msg = "Alumno transferido";
                }

                Context.db.ProcessQueue();
            }
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnGenerarCursos_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (cbxComision.SelectedIndex < 0)
                throw new Exception("No existe comision seleccionada");

            var comision = ((Comision)cbxComision.SelectedItem);

            using (Context.db.CreateQueue())
            {
                comision.GenerarCursos();
                Context.db.ProcessQueue();
                ToastExtensions.Show("Se han generado los cursos");
            }

        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }
}
