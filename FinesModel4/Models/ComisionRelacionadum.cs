using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class ComisionRelacionadum
{
    public string Id { get; set; } = null!;

    public string Comision { get; set; } = null!;

    public string Relacion { get; set; } = null!;

    public virtual Comision ComisionNavigation { get; set; } = null!;

    public virtual Comision RelacionNavigation { get; set; } = null!;
}
