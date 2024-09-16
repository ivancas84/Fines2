using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Horario
{
    public string Id { get; set; } = null!;

    public TimeOnly HoraInicio { get; set; }

    public TimeOnly HoraFin { get; set; }

    public string Curso { get; set; } = null!;

    public string Dia { get; set; } = null!;

    public virtual Curso CursoNavigation { get; set; } = null!;

    public virtual Dium DiaNavigation { get; set; } = null!;
}
