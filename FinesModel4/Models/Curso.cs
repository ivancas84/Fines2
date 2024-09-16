using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Curso
{
    public string Id { get; set; } = null!;

    public int HorasCatedra { get; set; }

    public string? Ige { get; set; }

    public string Comision { get; set; } = null!;

    public DateTime Alta { get; set; }

    public string? DescripcionHorario { get; set; }

    public string? Codigo { get; set; }

    public string? Disposicion { get; set; }

    public string? Observaciones { get; set; }

    public virtual ICollection<Calificacion> Calificacions { get; set; } = new List<Calificacion>();

    public virtual Comision ComisionNavigation { get; set; } = null!;

    public virtual Disposicion? DisposicionNavigation { get; set; }

    public virtual ICollection<Horario> Horarios { get; set; } = new List<Horario>();

    public virtual ICollection<Toma> Tomas { get; set; } = new List<Toma>();
}
