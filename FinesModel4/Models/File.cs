using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class File
{
    public string Id { get; set; } = null!;

    public string Name { get; set; } = null!;

    public string Type { get; set; } = null!;

    public string Content { get; set; } = null!;

    public uint Size { get; set; }

    public DateTime Created { get; set; }

    public virtual ICollection<DetallePersona> DetallePersonas { get; set; } = new List<DetallePersona>();
}
