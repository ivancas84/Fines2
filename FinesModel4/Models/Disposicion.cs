using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Disposicion
{
    public string Id { get; set; } = null!;

    public string Asignatura { get; set; } = null!;

    public string Planificacion { get; set; } = null!;

    public int? OrdenInformeCoordinacionDistrital { get; set; }

    public virtual Asignatura AsignaturaNavigation { get; set; } = null!;

    public virtual ICollection<Calificacion> Calificacions { get; set; } = new List<Calificacion>();

    public virtual ICollection<Curso> Cursos { get; set; } = new List<Curso>();

    public virtual ICollection<DisposicionPendiente> DisposicionPendientes { get; set; } = new List<DisposicionPendiente>();

    public virtual ICollection<DistribucionHorarium> DistribucionHoraria { get; set; } = new List<DistribucionHorarium>();

    public virtual Planificacion PlanificacionNavigation { get; set; } = null!;
}
