using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Dium
{
    public string Id { get; set; } = null!;

    public short Numero { get; set; }

    public string Dia { get; set; } = null!;

    public virtual ICollection<Horario> Horarios { get; set; } = new List<Horario>();
}
