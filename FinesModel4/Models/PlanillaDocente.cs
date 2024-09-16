using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class PlanillaDocente
{
    public string Id { get; set; } = null!;

    public string Numero { get; set; } = null!;

    public DateTime Insertado { get; set; }

    public DateOnly? FechaContralor { get; set; }

    public DateOnly? FechaConsejo { get; set; }

    public string? Observaciones { get; set; }

    public virtual ICollection<AsignacionPlanillaDocente> AsignacionPlanillaDocentes { get; set; } = new List<AsignacionPlanillaDocente>();

    public virtual ICollection<Contralor> Contralors { get; set; } = new List<Contralor>();

    public virtual ICollection<Toma> Tomas { get; set; } = new List<Toma>();
}
