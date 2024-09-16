using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Resolucion
{
    public string Id { get; set; } = null!;

    public string Numero { get; set; } = null!;

    public short? Anio { get; set; }

    public string? Tipo { get; set; }

    public virtual ICollection<Alumno> Alumnos { get; set; } = new List<Alumno>();
}
