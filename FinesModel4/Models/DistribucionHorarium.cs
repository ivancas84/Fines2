using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class DistribucionHorarium
{
    public string Id { get; set; } = null!;

    public int HorasCatedra { get; set; }

    public int Dia { get; set; }

    public string? Disposicion { get; set; }

    public virtual Disposicion? DisposicionNavigation { get; set; }
}
