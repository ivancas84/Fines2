using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Email
{
    public string Id { get; set; } = null!;

    public string Email1 { get; set; } = null!;

    public bool Verificado { get; set; }

    public DateTime Insertado { get; set; }

    public DateTime? Eliminado { get; set; }

    public string Persona { get; set; } = null!;

    public virtual Persona PersonaNavigation { get; set; } = null!;
}
