using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Comision
{
    public string Id { get; set; } = null!;

    public string? Turno { get; set; }

    public string Division { get; set; } = null!;

    public string? Comentario { get; set; }

    public bool Autorizada { get; set; }

    public bool Apertura { get; set; }

    public bool Publicada { get; set; }

    public string? Observaciones { get; set; }

    public DateTime Alta { get; set; }

    public string Sede { get; set; } = null!;

    public string Modalidad { get; set; } = null!;

    public string? Planificacion { get; set; }

    public string? ComisionSiguiente { get; set; }

    public string Calendario { get; set; } = null!;

    public string? Identificacion { get; set; }

    public string? Estado { get; set; }

    public string? Configuracion { get; set; }

    public string? Pfid { get; set; }

    public virtual ICollection<AlumnoComision> AlumnoComisions { get; set; } = new List<AlumnoComision>();

    public virtual Calendario CalendarioFk { get; set; } = null!;

    public virtual ICollection<ComisionRelacionadum> ComisionRelacionadumComisionNavigations { get; set; } = new List<ComisionRelacionadum>();

    public virtual ICollection<ComisionRelacionadum> ComisionRelacionadumRelacionNavigations { get; set; } = new List<ComisionRelacionadum>();

    public virtual Comision? ComisionSiguienteNavigation { get; set; }

    public virtual ICollection<Curso> Cursos { get; set; } = new List<Curso>();

    public virtual ICollection<Comision> InverseComisionSiguienteNavigation { get; set; } = new List<Comision>();

    public virtual Modalidad ModalidadNavigation { get; set; } = null!;

    public virtual Planificacion? PlanificacionNavigation { get; set; }

    public virtual Sede SedeFk { get; set; } = null!;
}
