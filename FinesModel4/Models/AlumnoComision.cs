using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class AlumnoComision
{
    public string Id { get; set; } = null!;

    public DateTime Creado { get; set; }

    public bool? Activo { get; set; }

    public string? Observaciones { get; set; }

    public string? Comision { get; set; }

    public string Alumno { get; set; } = null!;

    public string? Estado { get; set; }

    public uint? Pfid { get; set; }

    public virtual Alumno AlumnoNavigation { get; set; } = null!;

    public virtual Comision? ComisionNavigation { get; set; }
}
