using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Asignatura
{
    public string Id { get; set; } = null!;

    public string Nombre { get; set; } = null!;

    public string? Formacion { get; set; }

    public string? Clasificacion { get; set; }

    public string? Codigo { get; set; }

    public string? Perfil { get; set; }

    public virtual ICollection<Disposicion> Disposicions { get; set; } = new List<Disposicion>();
}
