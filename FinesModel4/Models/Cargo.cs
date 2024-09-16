using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Cargo
{
    public string Id { get; set; } = null!;

    public string Descripcion { get; set; } = null!;

    public virtual ICollection<Designacion> Designacions { get; set; } = new List<Designacion>();
}
