using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Sede
{
    public string Id { get; set; } = null!;

    public string Numero { get; set; } = null!;

    public string Nombre { get; set; } = null!;

    public string? Observaciones { get; set; }

    public DateTime Alta { get; set; }

    public DateTime? Baja { get; set; }

    public string? Domicilio { get; set; }

    public string? TipoSede { get; set; }

    public string? CentroEducativo { get; set; }

    public DateOnly? FechaTraspaso { get; set; }

    public string? Organizacion { get; set; }

    public string? Pfid { get; set; }

    public string? PfidOrganizacion { get; set; }

    public virtual CentroEducativo? CentroEducativoNavigation { get; set; }

    public virtual ICollection<Comision> Comisions { get; set; } = new List<Comision>();

    public virtual ICollection<Designacion> Designacions { get; set; } = new List<Designacion>();

    public virtual Domicilio? DomicilioNavigation { get; set; }

    public virtual ICollection<Sede> InverseOrganizacionNavigation { get; set; } = new List<Sede>();

    public virtual Sede? OrganizacionNavigation { get; set; }

    public virtual TipoSede? TipoSedeNavigation { get; set; }
}
