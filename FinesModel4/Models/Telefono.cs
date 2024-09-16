using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Telefono
{
    public string Id { get; set; } = null!;

    public string? Tipo { get; set; }

    public string? Prefijo { get; set; }

    public string Numero { get; set; } = null!;

    public DateTime Insertado { get; set; }

    public DateTime? Eliminado { get; set; }

    public string Persona { get; set; } = null!;

    public virtual Persona PersonaNavigation { get; set; } = null!;
}
