using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Planificacion
{
    public string Id { get; set; } = null!;

    public string Anio { get; set; } = null!;

    public string Semestre { get; set; } = null!;

    public string Plan { get; set; } = null!;

    public string? Pfid { get; set; }

    public virtual ICollection<Comision> Comisions { get; set; } = new List<Comision>();

    public virtual ICollection<Disposicion> Disposicions { get; set; } = new List<Disposicion>();

    public virtual Plan PlanNavigation { get; set; } = null!;
}
