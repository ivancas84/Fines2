using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Calificacion
{
    public string Id { get; set; } = null!;

    public decimal? Nota1 { get; set; }

    public decimal? Nota2 { get; set; }

    public decimal? Nota3 { get; set; }

    public decimal? NotaFinal { get; set; }

    public decimal? Crec { get; set; }

    public string? Curso { get; set; }

    public int? PorcentajeAsistencia { get; set; }

    public string? Observaciones { get; set; }

    public string? Division { get; set; }

    public string Alumno { get; set; } = null!;

    public string Disposicion { get; set; } = null!;

    public DateOnly? Fecha { get; set; }

    public bool Archivado { get; set; }

    public virtual Alumno AlumnoNavigation { get; set; } = null!;

    public virtual Curso? CursoNavigation { get; set; }

    public virtual Disposicion DisposicionNavigation { get; set; } = null!;
}
