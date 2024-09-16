using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Calendario
{
    public string Id { get; set; } = null!;

    public DateOnly? Inicio { get; set; }

    public DateOnly? Fin { get; set; }

    public short Anio { get; set; }

    public short Semestre { get; set; }

    public DateTime Insertado { get; set; }

    public string? Descripcion { get; set; }

    public virtual ICollection<Comision> Comisions { get; set; } = new List<Comision>();
}
