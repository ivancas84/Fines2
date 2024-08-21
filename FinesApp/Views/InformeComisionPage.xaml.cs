using CommunityToolkit.WinUI.Notifications;
using Fines2Model3.Item;
using Org.BouncyCastle.Asn1.Ocsp;
using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.ValueTypesUtils;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Data;
using System.Diagnostics;
using System.Net;
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
    private ObservableCollection<Data_comision_r> comisionOC = new(); //datos consultados de la base de datos
    private DispatcherTimer comisionTypingTimer; //timer para buscar
    #endregion

    private ObservableCollection<CursoConTomaItem> cursoOC = new(); //datos consultados de la base de datos

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
            (string? text, TextBox? textBox, int? textBoxPos) = cbxComision.SetTimerTickInitializeItem<Data_comision_r>(comisionTypingTimer);
            if (text == null)
                return;

            var list = ContainerApp.db.BusquedaAproximadaComision(text).ColOfDict();

            ContainerApp.db.ClearAndAddDataToOC(list, comisionOC);

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
            var obj = (Data_comision)cb.SelectedItem;
            Buscar(obj);
        }
    }

    #endregion

    private void Buscar(Data_comision comision)
    {
        try
        {
            var cursosData = ContainerApp.db.Sql("curso").Equal("$comision", comision.id).Cache().ColOfDict();
            var tomaData = ContainerApp.db.TomaAprobadaDeComisionQuery(comision.id).Cache().ColOfDict();
            cursosData.MergeByKeys(tomaData, "id", "curso", "toma_");
            ContainerApp.db.ClearAndAddDataToOC(cursosData, cursoOC);

            var idsAsignaturas = cursosData.ColOfVal<string>("asignatura-id").ToList();

            //var disposicionesData = ContainerApp.db.Sql("disposicion").Equal("$planificacion", comision.planificacion).Cache().ColOfDict();
            //var idsDisposiciones = disposicionesData.ColOfVal<string>("id").ToList();


            var asignacionesData = ContainerApp.db.AsignacionesDeComisionesSql(comision.id).Cache().ColOfDict();

            var idAlumnos = asignacionesData.ColOfVal<object>("alumno").ToArray();

            var calificacionesData = ContainerApp.db.CalificacionesAprobadasPorAlumnoDePlanificacionSql(comision.planificacion, idAlumnos).Cache().ColOfDict().DictOfListByKeys("alumno");



            asignacionOC.Clear();
            foreach (var asi in asignacionesData)
            {
                var itemObj = ContainerApp.db.ToData<AsignacionConAsignaturasItem>(asi);
                if (calificacionesData.ContainsKey(itemObj.alumno))
                {
                    foreach (var cal in calificacionesData[itemObj.alumno])
                    {
                        itemObj.cantidad_aprobadas++;
                        var calificacionObj = ContainerApp.db.ToData<Data_calificacion_r>(cal);

                        int index = idsAsignaturas.IndexOf(calificacionObj.asignatura_dis__id);
                        switch (index)
                        {
                            case 0: 
                                itemObj.asignatura0 = CalificacionValues.GetNotaAprobada(calificacionObj.nota_final, calificacionObj.crec); 
                            break;

                            case 1:
                                itemObj.asignatura1 = CalificacionValues.GetNotaAprobada(calificacionObj.nota_final, calificacionObj.crec);  
                            break;

                            case 2:
                                itemObj.asignatura2 = CalificacionValues.GetNotaAprobada(calificacionObj.nota_final, calificacionObj.crec);
                                break;

                            case 3:
                                itemObj.asignatura3 = CalificacionValues.GetNotaAprobada(calificacionObj.nota_final, calificacionObj.crec);
                                break;

                            case 4:
                                itemObj.asignatura4 = CalificacionValues.GetNotaAprobada(calificacionObj.nota_final, calificacionObj.crec);
                                break;
                        }

                    }
                }
                asignacionOC.Add(itemObj);
            }
            //ContainerApp.db.ClearAndAddDataToOC(data, alumnos3OC);
        
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void WhatsAppLink_Click(object sender, RoutedEventArgs e)
    {

        var data = ((Hyperlink)e.OriginalSource).DataContext as CursoConTomaItem;

        var hyperlink = sender as Hyperlink;
        string phoneNumber = hyperlink?.Tag.ToString();

        if (!string.IsNullOrEmpty(phoneNumber))
        {
            string message = "Hola " + data.toma_docente__nombres + " quería hacerte una consulta acerca de la asignatura " + data.asignatura__nombre + " de comision " + data.comision__pfid;
            string whatsappUrl = $"https://web.whatsapp.com/send?phone={phoneNumber}&text={Uri.EscapeDataString(message)}";

            Process.Start(new ProcessStartInfo(whatsappUrl) { UseShellExecute = true });
        }
    }



    #region tab registro alumnos
    private IEnumerable<EntityPersist> persists;
    private ObservableCollection<Data> ocDataPersist = new();

    private void btnProcesarAlumnos_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            persists = ContainerApp.db.PersistAsignacionesComisionText(cbxComision.SelectedValue, tbxAlumnos.Text);
            foreach(var p in persists)
            {
                var resultObj = ContainerApp.db.Data<Data>();
                resultObj.Label = p.logging.ToString();
                ocDataPersist.Add(resultObj);
            }
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnGuardarAlumnos_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (!persists.Any())
                throw new Exception("No existen datos a persistir");

            persists.Transaction().RemoveCache();
            ToastExtensions.Show("Se han registrado las asignaciones");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
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
        try
        {

            if (!asignacionOC.Any())
                throw new Exception("La lista de alumnos esta vacía");

            EntityPersist persist = ContainerApp.db.Persist();

            foreach (AsignacionConAsignaturasItem asiObj in asignacionOC)
            {
                string estado = (asiObj.cantidad_aprobadas < 3) ? "No activo" : "Activo"; 
                persist.UpdateValueIds("alumno_comision", "estado", estado, asiObj.id);
            }

            persist.Transaction().RemoveCache();
            Buscar((Data_comision)cbxComision.SelectedItem);
            ToastExtensions.Show("Se ha cambiado el estado de los alumnos");
        } catch (Exception ex) { 
            ex.ToastException();
        }
    }

    private void btnTransferirAlumnos_Click(object sender, RoutedEventArgs e)
    {
        try { 
            if (!asignacionOC.Any())
                throw new Exception("La lista de alumnos esta vacía");

            var comObj = (Data_comision)cbxComision.SelectedItem;

            if (comObj.IsNoE())
                throw new Exception("No se encuentra seleccionada ninguna comision");


            if(comObj.comision_siguiente.IsNoE())
                throw new Exception("No se encuentra definida la comision siguiente");

            var idAlumnosExistentes = ContainerApp.db.AsignacionesDeComisionesSql(comObj.comision_siguiente).Cache().ColOfDict().DictOfDictByKeysValue("id","alumno");


            EntityPersist persist = ContainerApp.db.Persist();

            foreach (AsignacionConAsignaturasItem asiObj in asignacionOC)
            {
                asiObj.Msg = "";
                if (!asiObj.estado.Equals("Activo"))
                {
                    if (idAlumnosExistentes.ContainsKey(asiObj.alumno)) { 
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

                

                ContainerApp.db.Values("alumno_comision").
                    Set("comision", comObj.comision_siguiente).
                    Set("alumno", asiObj.alumno).
                    Set("estado", "Activo").InsertIfNotExists(persist);

                asiObj.Msg = "Alumno transferido";
            }

            persist.Transaction().RemoveCache();

        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }
}
