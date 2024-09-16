using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class DisposicionPendiente
{
    public string Id { get; set; } = null!;

    public string Disposicion { get; set; } = null!;

    public string Alumno { get; set; } = null!;

    public string? Modo { get; set; }

    public virtual Alumno AlumnoNavigation { get; set; } = null!;

    public virtual Disposicion DisposicionNavigation { get; set; } = null!;
}
