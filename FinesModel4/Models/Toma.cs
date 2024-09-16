using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Toma
{
    public string Id { get; set; } = null!;

    public DateOnly? FechaToma { get; set; }

    public string? Estado { get; set; }

    public string? Observaciones { get; set; }

    public string? Comentario { get; set; }

    public string TipoMovimiento { get; set; } = null!;

    public string? EstadoContralor { get; set; }

    public DateTime Alta { get; set; }

    public string Curso { get; set; } = null!;

    public string? Docente { get; set; }

    public string? Reemplazo { get; set; }

    public string? PlanillaDocente { get; set; }

    public bool Calificacion { get; set; }

    public bool TemasTratados { get; set; }

    public bool Asistencia { get; set; }

    public bool SinPlanillas { get; set; }

    public bool Confirmada { get; set; }

    public virtual ICollection<AsignacionPlanillaDocente> AsignacionPlanillaDocentes { get; set; } = new List<AsignacionPlanillaDocente>();

    public virtual Curso CursoNavigation { get; set; } = null!;

    public virtual Persona? DocenteNavigation { get; set; }

    public virtual PlanillaDocente? PlanillaDocenteNavigation { get; set; }

    public virtual Persona? ReemplazoNavigation { get; set; }
}
