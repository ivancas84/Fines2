using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Persona
{
    public string Id { get; set; } = null!;

    public string Nombres { get; set; } = null!;

    public string? Apellidos { get; set; }

    public DateOnly? FechaNacimiento { get; set; }

    public string NumeroDocumento { get; set; } = null!;

    public string? Cuil { get; set; }

    public string? Genero { get; set; }

    public string? Apodo { get; set; }

    public string? Telefono { get; set; }

    public string? Email { get; set; }

    public string? EmailAbc { get; set; }

    public DateTime Alta { get; set; }

    public string? Domicilio { get; set; }

    public string? LugarNacimiento { get; set; }

    public bool TelefonoVerificado { get; set; }

    public bool EmailVerificado { get; set; }

    public bool InfoVerificada { get; set; }

    public string? DescripcionDomicilio { get; set; }

    public byte? Cuil1 { get; set; }

    public byte? Cuil2 { get; set; }

    public string? Departamento { get; set; }

    public string? Localidad { get; set; }

    public string? Partido { get; set; }

    public string? CodigoArea { get; set; }

    public string? Nacionalidad { get; set; }

    public byte? Sexo { get; set; }

    public byte? DiaNacimiento { get; set; }

    public byte? MesNacimiento { get; set; }

    public ushort? AnioNacimiento { get; set; }

    public virtual Alumno? Alumno { get; set; }

    public virtual ICollection<Designacion> Designacions { get; set; } = new List<Designacion>();

    public virtual ICollection<DetallePersona> DetallePersonas { get; set; } = new List<DetallePersona>();

    public virtual Domicilio? DomicilioNavigation { get; set; }

    public virtual ICollection<Email> Emails { get; set; } = new List<Email>();

    public virtual ICollection<Telefono> Telefonos { get; set; } = new List<Telefono>();

    public virtual ICollection<Toma> TomaDocenteNavigations { get; set; } = new List<Toma>();

    public virtual ICollection<Toma> TomaReemplazoNavigations { get; set; } = new List<Toma>();
}
