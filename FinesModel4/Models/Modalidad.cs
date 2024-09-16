using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Modalidad
{
    public string Id { get; set; } = null!;

    public string Nombre { get; set; } = null!;

    public string? Pfid { get; set; }

    public virtual ICollection<Comision> Comisions { get; set; } = new List<Comision>();
}
