using System;
using System.Collections.Generic;

namespace FinesModel4.Models;

public partial class Alumno
{
    public string Id { get; set; } = null!;

    public string? AnioIngreso { get; set; }

    public string? Observaciones { get; set; }

    public string Persona { get; set; } = null!;

    public string? EstadoInscripcion { get; set; }

    public DateOnly? FechaTitulacion { get; set; }

    public string? Plan { get; set; }

    public string? ResolucionInscripcion { get; set; }

    public short? AnioInscripcion { get; set; }

    public short? SemestreInscripcion { get; set; }

    public short? SemestreIngreso { get; set; }

    public string? AdeudaLegajo { get; set; }

    public string? AdeudaDeudores { get; set; }

    public string? DocumentacionInscripcion { get; set; }

    public bool? AnioInscripcionCompleto { get; set; }

    public string? EstablecimientoInscripcion { get; set; }

    public string? LibroFolio { get; set; }

    public string? Libro { get; set; }

    public string? Folio { get; set; }

    public string? Comentarios { get; set; }

    public bool TieneDni { get; set; }

    public bool TieneConstancia { get; set; }

    public bool TieneCertificado { get; set; }

    public bool PreviasCompletas { get; set; }

    public bool TienePartida { get; set; }

    public DateTime Creado { get; set; }

    public bool ConfirmadoDireccion { get; set; }

    public virtual ICollection<AlumnoComision> AlumnoComisiones { get; set; } = new List<AlumnoComision>();

    public virtual ICollection<Calificacion> Calificacions { get; set; } = new List<Calificacion>();

    public virtual ICollection<DisposicionPendiente> DisposicionPendientes { get; set; } = new List<DisposicionPendiente>();

    public virtual Persona PersonaNavigation { get; set; } = null!;

    public virtual Plan? PlanNavigation { get; set; }

    public virtual Resolucion? ResolucionInscripcionNavigation { get; set; }
}
