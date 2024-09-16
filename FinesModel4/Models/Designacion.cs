using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Designacion
{
    public string Id { get; set; } = null!;

    public DateOnly? Desde { get; set; }

    public DateOnly? Hasta { get; set; }

    public string Cargo { get; set; } = null!;

    public string Sede { get; set; } = null!;

    public string Persona { get; set; } = null!;

    public DateTime Alta { get; set; }

    public string? Pfid { get; set; }

    public virtual Cargo CargoNavigation { get; set; } = null!;

    public virtual Persona PersonaNavigation { get; set; } = null!;

    public virtual Sede SedeNavigation { get; set; } = null!;
}
