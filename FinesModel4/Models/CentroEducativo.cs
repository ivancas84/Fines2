using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class CentroEducativo
{
    public string Id { get; set; } = null!;

    public string Nombre { get; set; } = null!;

    public string? Cue { get; set; }

    public string? Domicilio { get; set; }

    public string? Observaciones { get; set; }

    public virtual Domicilio? DomicilioNavigation { get; set; }

    public virtual ICollection<Sede> Sedes { get; set; } = new List<Sede>();
}
