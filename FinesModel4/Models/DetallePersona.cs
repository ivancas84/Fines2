using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class DetallePersona
{
    public string Id { get; set; } = null!;

    public string Descripcion { get; set; } = null!;

    public string? Archivo { get; set; }

    public DateTime Creado { get; set; }

    public string Persona { get; set; } = null!;

    public DateOnly? Fecha { get; set; }

    public string? Tipo { get; set; }

    public string? Asunto { get; set; }

    public virtual File? ArchivoNavigation { get; set; }

    public virtual Persona PersonaNavigation { get; set; } = null!;
}
