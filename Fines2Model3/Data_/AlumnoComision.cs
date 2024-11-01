using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.Sql;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SqlOrganize.DateTimeUtils;
using SqlOrganize.CollectionUtils;
using System.Collections.ObjectModel;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class AlumnoComision
    {

        public object Persist1()
        {
            var persist = Context.db.Persist();
            alumno_!.plan = comision_!.planificacion_!.plan;
            alumno_!.persona = (string)persist.Persist(alumno_!.persona_!)!;
            alumno = (string)persist.Persist(alumno_!)!;
            return persist.Persist(this);
        }

        public override string? Label
        {
            get {
                if (!_Label.IsNoE())
                    return _Label;

                return (estado ?? "?") + " " + (comision_?.Label ?? "?") + " " + (alumno_?.Label ?? "?");
            }
            set {
                Label = value;
                NotifyPropertyChanged(nameof(Label)); }
        }


        public long? _CantidadAprobadas11;

        public long? CantidadAprobadas11 {
            get { return _CantidadAprobadas11; }

            set {
                if (_CantidadAprobadas11 != value)
                {
                    _CantidadAprobadas11 = value; NotifyPropertyChanged(nameof(CantidadAprobadas11));
                }

            }
        }

        public long? _CantidadAprobadas12;

        public long? CantidadAprobadas12
        {
            get { return _CantidadAprobadas12; }

            set
            {
                if (_CantidadAprobadas12 != value)
                {
                    _CantidadAprobadas12 = value; NotifyPropertyChanged(nameof(CantidadAprobadas12));
                }

            }
        }

        public long? _CantidadAprobadas21;

        public long? CantidadAprobadas21
        {
            get { return _CantidadAprobadas21; }

            set
            {
                if (_CantidadAprobadas21 != value)
                {
                    _CantidadAprobadas21 = value; NotifyPropertyChanged(nameof(CantidadAprobadas21));
                }

            }
        }

        public long? _CantidadAprobadas22;

        public long? CantidadAprobadas22
        {
            get { return _CantidadAprobadas22; }

            set
            {
                if (_CantidadAprobadas22 != value)
                {
                    _CantidadAprobadas22 = value; NotifyPropertyChanged(nameof(CantidadAprobadas22));
                }

            }
        }

        public long? _CantidadAprobadas31;

        public long? CantidadAprobadas31
        {
            get { return _CantidadAprobadas31; }

            set
            {
                if (_CantidadAprobadas31 != value)
                {
                    _CantidadAprobadas31 = value; NotifyPropertyChanged(nameof(CantidadAprobadas31));
                }

            }
        }

        public long? _CantidadAprobadas32;

        public long? CantidadAprobadas32
        {
            get { return _CantidadAprobadas32; }

            set
            {
                if (_CantidadAprobadas32 != value)
                {
                    _CantidadAprobadas32 = value; NotifyPropertyChanged(nameof(CantidadAprobadas32));
                }

            }
        }


        public long? _CantidadAprobadasComision;

        public long? CantidadAprobadasComision
        {
            get { return _CantidadAprobadasComision; }

            set
            {
                if (_CantidadAprobadasComision != value)
                {
                    _CantidadAprobadasComision = value; NotifyPropertyChanged(nameof(CantidadAprobadas32));
                }

            }
        }


        public static void AddDataToOC__WITH_CantidadAprobadasPlanificacion(IEnumerable<Dictionary<string, object?>> source, ObservableCollection<AlumnoComision> oc)
        {
            IEnumerable<string> concats_alumno_planificacion = source!.ColOfValConcat("alumno", "comision__planificacion");

            var calificacionesAprobadasAgrupadas = CalificacionDAO.COUNT_calificacionesAprobadas__BY_Concat_alumno_planificacion__GROUP_alumno_planificacion(concats_alumno_planificacion).Cache().Dicts().DictOfDictByKeysValue("cantidad", "alumno", "comision__planificacion");


            for (var i = 0; i < source.Count(); i++)
            {
                AlumnoComision obj = Entity.CreateFromDict<AlumnoComision>(source.ElementAt(i));
                obj.Index = i;

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion))
                    obj.CantidadAprobadasComision = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion];

                oc.Add(obj);
            }
        }

        /// <summary> Procesar un conjunto de datos de alumno comision y agregarlos a un ObservableCollection </summary>
        public static void AddDataToOC__WITH_CantidadAprobadasPlan(IEnumerable<Dictionary<string, object?>> source, ObservableCollection<AlumnoComision> oc)
        {
            IEnumerable<string> concats_alumno_planCurso = source!.ColOfValConcat("alumno", "planificacion__plan");

            var calificacionesAprobadasAgrupadas = CalificacionDAO.COUNT_calificacionesAprobadas__BY_Concat_alumno_planDeCurso__GROUP_alumno_planDeCurso_anio_semestre(concats_alumno_planCurso).Cache().Dicts().DictOfDictByKeysValue("cantidad", "alumno", "planificacion__plan", "planificacion_dis1__anio", "planificacion_dis1__semestre");


            for (var i = 0; i < source.Count(); i++)
            {
                AlumnoComision obj = Entity.CreateFromDict<AlumnoComision>(source.ElementAt(i));
                obj.Index = i;

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "1"))
                    obj.CantidadAprobadas11 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "1"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "2"))
                    obj.CantidadAprobadas12 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "2"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "1"))
                    obj.CantidadAprobadas21 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "1"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "2"))
                    obj.CantidadAprobadas22 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "2"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "1"))
                    obj.CantidadAprobadas31 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "1"];

                if (calificacionesAprobadasAgrupadas.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "2"))
                    obj.CantidadAprobadas32 = (long)calificacionesAprobadasAgrupadas[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "2"];

                oc.Add(obj);
            }


        }


        /// <summary> Procesar un conjunto de datos de alumno comision y agregarlos a un ObservableCollection </summary>
        public static void AddDataToOC__WITH_Cantidades(IEnumerable<Dictionary<string, object?>> source, ObservableCollection<AlumnoComision> oc)
        {
            IEnumerable<string> concats_alumno_planCurso = source!.ColOfValConcat("alumno", "planificacion__plan");
            IEnumerable<string> concats_alumno_planificacion = source!.ColOfValConcat("alumno", "comision__planificacion");

            var calificacionesAprobadasAgrupadasPlan = CalificacionDAO.COUNT_calificacionesAprobadas__BY_Concat_alumno_planDeCurso__GROUP_alumno_planDeCurso_anio_semestre(concats_alumno_planCurso).Cache().Dicts().DictOfDictByKeysValue("cantidad", "alumno", "planificacion__plan", "planificacion_dis1__anio", "planificacion_dis1__semestre");
            var calificacionesAprobadasAgrupadasPlanificacion = CalificacionDAO.COUNT_calificacionesAprobadas__BY_Concat_alumno_planificacion__GROUP_alumno_planificacion(concats_alumno_planificacion).Cache().Dicts().DictOfDictByKeysValue("cantidad", "alumno", "comision__planificacion");


            for (var i = 0; i < source.Count(); i++)
            {
                AlumnoComision obj = Entity.CreateFromDict<AlumnoComision>(source.ElementAt(i));
                obj.Index = i;

                if (calificacionesAprobadasAgrupadasPlanificacion.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion))
                    obj.CantidadAprobadasComision = (long)calificacionesAprobadasAgrupadasPlanificacion[obj.alumno + "~" + obj.comision_.planificacion];


                if (calificacionesAprobadasAgrupadasPlan.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "1"))
                    obj.CantidadAprobadas11 = (long)calificacionesAprobadasAgrupadasPlan[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "1"];

                if (calificacionesAprobadasAgrupadasPlan.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "2"))
                    obj.CantidadAprobadas12 = (long)calificacionesAprobadasAgrupadasPlan[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "1" + "~" + "2"];

                if (calificacionesAprobadasAgrupadasPlan.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "1"))
                    obj.CantidadAprobadas21 = (long)calificacionesAprobadasAgrupadasPlan[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "1"];

                if (calificacionesAprobadasAgrupadasPlan.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "2"))
                    obj.CantidadAprobadas22 = (long)calificacionesAprobadasAgrupadasPlan[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "2" + "~" + "2"];

                if (calificacionesAprobadasAgrupadasPlan.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "1"))
                    obj.CantidadAprobadas31 = (long)calificacionesAprobadasAgrupadasPlan[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "1"];

                if (calificacionesAprobadasAgrupadasPlan.ContainsKey(obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "2"))
                    obj.CantidadAprobadas32 = (long)calificacionesAprobadasAgrupadasPlan[obj.alumno + "~" + obj.comision_.planificacion_.plan + "~" + "3" + "~" + "2"];

                oc.Add(obj);
            }


        }






    }


}
