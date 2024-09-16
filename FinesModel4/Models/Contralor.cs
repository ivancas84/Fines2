using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Contralor
{
    public string Id { get; set; } = null!;

    public DateOnly? FechaContralor { get; set; }

    public DateOnly? FechaConsejo { get; set; }

    public DateTime Insertado { get; set; }

    public string PlanillaDocente { get; set; } = null!;

    public virtual PlanillaDocente PlanillaDocenteNavigation { get; set; } = null!;
}
