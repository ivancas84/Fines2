using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Domicilio
{
    public string Id { get; set; } = null!;

    public string Calle { get; set; } = null!;

    public string? Entre { get; set; }

    public string Numero { get; set; } = null!;

    public string? Piso { get; set; }

    public string? Departamento { get; set; }

    public string? Barrio { get; set; }

    public string Localidad { get; set; } = null!;

    public virtual ICollection<CentroEducativo> CentroEducativos { get; set; } = new List<CentroEducativo>();

    public virtual ICollection<Persona> Personas { get; set; } = new List<Persona>();

    public virtual ICollection<Sede> Sedes { get; set; } = new List<Sede>();
}
