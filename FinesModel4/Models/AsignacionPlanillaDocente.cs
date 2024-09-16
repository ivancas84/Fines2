using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class AsignacionPlanillaDocente
{
    public string Id { get; set; } = null!;

    public string PlanillaDocente { get; set; } = null!;

    public string Toma { get; set; } = null!;

    public DateTime Insertado { get; set; }

    public string? Comentario { get; set; }

    public bool Reclamo { get; set; }

    public virtual PlanillaDocente PlanillaDocenteNavigation { get; set; } = null!;

    public virtual Toma TomaNavigation { get; set; } = null!;
}
