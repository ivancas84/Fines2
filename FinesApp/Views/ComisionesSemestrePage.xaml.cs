﻿using SqlOrganize.Sql;
using System.ComponentModel;
using System.Reflection.PortableExecutable;
using System.Runtime.CompilerServices;
using System.Windows;
using System.Windows.Controls;
using SqlOrganize.Sql.Fines2Model3;
using System.Collections.ObjectModel;
using WpfUtils.Controls;
using SqlOrganize;
using WpfUtils;
using SqlOrganize.CollectionUtils;
using System.Collections.Specialized;

namespace FinesApp.Views;

public partial class ComisionesSemestrePage : Page, INotifyPropertyChanged
{
    private ObservableCollection<Comision> comisionOC = new();
    private ObservableCollection<Calendario> ocCalendario = new();
    private ObservableCollection<Calendario> ocCalendarioComisionesSiguientes = new();
    private ObservableCollection<Calendario> calendarioPFOC = new();


    public ComisionesSemestrePage()
    {
        InitializeComponent();
        DataContext = this;
        dgComision.ItemsSource = comisionOC;
        comisionOC.CollectionChanged += Items_CollectionChanged;
        dgdResultadoInformeGlobalPF.ItemsSource = ocResultadoInformeGlobal;

        cbxCalendario.InitComboBoxConstructor(ocCalendario);
        cbxCalendarioComisionesSiguientes.InitComboBoxConstructor(ocCalendarioComisionesSiguientes);
        cbxCalendarioInformeGlobalPF.InitComboBoxConstructor(calendarioPFOC);

        Loaded += Page_Loaded;
    }

    #region checkbox datagrid
    private void Items_CollectionChanged(object sender, NotifyCollectionChangedEventArgs e)
    {
        if (e.NewItems != null)
            foreach (Comision newItem in e.NewItems)
                newItem.PropertyChanged += Item_PropertyChanged;

        if (e.OldItems != null)
            foreach (Comision oldItem in e.OldItems)
                oldItem.PropertyChanged -= Item_PropertyChanged;
    }

    private void Item_PropertyChanged(object sender, PropertyChangedEventArgs e)
    {
        using (Context.db.CreateQueue())
        {
            Context.db.Persist().UpdateFieldIds("comision", e.PropertyName, sender.GetPropertyValue(e.PropertyName), sender.GetPropertyValue("id"));
            Context.db.ProcessQueue();
        }
    }
    #endregion

    private void Page_Loaded(object sender, RoutedEventArgs e)
    {
        CalendarioDAO.CalendariosSql().Cache().AddEntitiesToClearOC(ocCalendario);
    }

    private void LoadCalendarioComisionesSiguientes()
    {
        if (cbxCalendario.SelectedIndex < 0)
            return;

        Calendario cal = cbxCalendario.SelectedItem as Calendario;

        Context.db.Sql("calendario").Where("$anio >= @0 AND $semestre >= @1").
            Param("@0", cal.anio).Param("@1", cal.semestre).Cache().AddEntitiesToClearOC(ocCalendarioComisionesSiguientes);
    }

    private void BuscarButton_Click(object sender, RoutedEventArgs e)
    {
        var dataComisiones = ComisionDAO.ComisionesAutorizadasDeCalendarioSql(cbxCalendario.SelectedValue).Cache().Dicts();

        var idComisionesSiguientes = dataComisiones.ColOfVal<object>("comision_siguiente");
        var dictComisionesSiguientes = Context.db.Sql("comision").Cache().Ids(idComisionesSiguientes.ToArray()).DictOfDictByKey<string>("id");
        var idSedes = dataComisiones.ColOfVal<object>("sede");
        var dictReferentes = DesignacionDAO.ReferentesDeSedeQuery(idSedes).Cache().Dicts().DictOfListByKeys("sede");
        comisionOC.Clear();
        for (var i = 0; i < dataComisiones.Count(); i++)
        {
            Comision obj = Entity.CreateFromDict<Comision>(dataComisiones.ElementAt(i));

            if (dictReferentes.ContainsKey(obj.sede))
                foreach (var referente in dictReferentes[obj.sede])
                    Entity.CreateFromDict<Designacion>(referente).AddToOC(obj.sede_.Designacion_);


            if (!obj.comision_siguiente.IsNoE() && dictComisionesSiguientes.ContainsKey(obj.comision_siguiente))
                obj.comision_siguiente_ = Entity.CreateFromDict<Comision>(dictComisionesSiguientes[obj.comision_siguiente]);
            
            obj.Index = i;
            comisionOC.Add(obj);
        }
    }

    private void btnGenerarComisionesSiguientes_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            if (cbxCalendario.SelectedIndex < 0 || cbxCalendarioComisionesSiguientes.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            using (Context.db.CreateQueue())
            {
                (cbxCalendario.SelectedItem as Calendario).GenerarComisionesSemestreSiguiente((string)cbxCalendarioComisionesSiguientes.SelectedValue);
                Context.db.ProcessQueue();
            }

            ToastExtensions.Show("Comisiones agregadas");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnGenerarCursosComisiones_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            using (Context.db.CreateQueue())
            {
                foreach (var comObj in comisionOC)
                    comObj.GenerarCursos();
                Context.db.ProcessQueue();
            }

            ToastExtensions.Show("Se han generado los cursos");

        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }


    #region Pestaña Procesar Informe Global PF
    ObservableCollection<Entity> ocResultadoInformeGlobal = new();

    private void btnProcesarInformeGlobalPF_Click(object sender, RoutedEventArgs e)
    {
        var cq = Context.db.CreateQueue();
        
        try
        {
            if (cbxCalendarioInformeGlobalPF.SelectedIndex < 0)
                throw new Exception("Verificar formulario");

            var calendarioObj = (Calendario)cbxCalendarioInformeGlobalPF.SelectedItem;
            
            calendarioObj.PersistComisionesPf(tbxInformeGlobalPF.Text);
            
            // TODO mostrar directamente el calendario?
            /*ocResultadoInformeGlobal.Clear();
            for (var i = 0; i < persists.Count(); i++)
            {
                Entity obj = new();
                obj.Index = i;
                obj.Label = persists.ElementAt(i).logging.ToString();
                ocResultadoInformeGlobal.Add(obj);

            }*/
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    private void btnGuardarInformeGlobalPF_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            Context.db.ProcessQueue();
            ToastExtensions.Show("Registro realizado");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }

    #endregion

    #region InotifyPropertyChanged

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

    private void cbxCalendario_SelectionChanged(object sender, SelectionChangedEventArgs e)
    {
        LoadCalendarioComisionesSiguientes();
    }

    private void BtnTransferirAlumnosActivos_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            Context.db.CreateQueue();

            if (cbxCalendario.SelectedIndex < 0)
                return;

            Calendario cal = cbxCalendario.SelectedItem as Calendario;

            var ocAsignacion = AsignacionDAO.AsignacionesActivas__WITH_ComisionSiguiente__BY_idCalendario(cal.id).Cache().Entities<AlumnoComision>();

            if (!ocAsignacion.Any())
                return;

            PersistContext persist = Context.db.Persist();

            int cantidad = 0;

            foreach (var asignacion in ocAsignacion)
            {
                var nuevaAsignacion = new AlumnoComision();
                nuevaAsignacion.Set("comision", asignacion.comision_.comision_siguiente);
                nuevaAsignacion.Set("alumno", asignacion.alumno);
                nuevaAsignacion.Set("estado", "Activo");
                persist.InsertIfNotExists(nuevaAsignacion);
                cantidad++;
            }

            ToastExtensions.Show("Se han transferido " +  cantidad + " de asignaciones.");
        } catch (Exception ex)
        {
            ex.ToastException();
        }




    }

    private void BtnCambiarEstadoAlumnos_Click(object sender, RoutedEventArgs e)
    {
        try
        {
            Context.db.CreateQueue();

            if (cbxCalendario.SelectedIndex < 0)
                return;

            Calendario cal = cbxCalendario.SelectedItem as Calendario;

            var ocAsignacion = AsignacionDAO.AsignacionesActivas__WITH_ComisionSiguiente__BY_idCalendario(cal.id).Cache().Entities<AlumnoComision>();

            if (!ocAsignacion.Any())
                return;

            PersistContext persist = Context.db.Persist();

            int cantidad = 0;

            foreach (var asignacion in ocAsignacion)
            {
                var nuevaAsignacion = new AlumnoComision();
                nuevaAsignacion.Set("comision", asignacion.comision_.comision_siguiente);
                nuevaAsignacion.Set("alumno", asignacion.alumno);
                nuevaAsignacion.Set("estado", "Activo");
                persist.InsertIfNotExists(nuevaAsignacion);
                cantidad++;
            }

            ToastExtensions.Show("Se han transferido " + cantidad + " de asignaciones.");
        }
        catch (Exception ex)
        {
            ex.ToastException();
        }
    }
}
