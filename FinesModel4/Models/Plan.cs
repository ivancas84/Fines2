using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Plan
{
    public string Id { get; set; } = null!;

    public string Orientacion { get; set; } = null!;

    public string? Resolucion { get; set; }

    public string? DistribucionHoraria { get; set; }

    public string? Pfid { get; set; }

    public virtual ICollection<Alumno> Alumnos { get; set; } = new List<Alumno>();

    public virtual ICollection<Planificacion> Planificacions { get; set; } = new List<Planificacion>();
}
