using System.ComponentModel.DataAnnotations.Schema;

namespace FinesModel4.Models;

public partial class Calendario
{
    [NotMapped]
    public string Label { 
        get {
            var s = "";

            s += Anio.ToString() ?? "?";
            s += "-";
            s += Semestre.ToString() ?? "?";
            s += "";
            s += Descripcion ?? "?";

            return s; 
        } 
    }
}
