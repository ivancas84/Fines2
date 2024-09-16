using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class TipoSede
{
    public string Id { get; set; } = null!;

    public string Descripcion { get; set; } = null!;

    public virtual ICollection<Sede> Sedes { get; set; } = new List<Sede>();
}
