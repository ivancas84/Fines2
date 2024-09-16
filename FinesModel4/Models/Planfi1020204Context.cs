using System;
using System.Collections.Generic;
using Microsoft.EntityFrameworkCore;
using Pomelo.EntityFrameworkCore.MySql.Scaffolding.Internal;

namespace FinesModel4.Models;

public partial class Planfi1020204Context : DbContext
{
    public Planfi1020204Context()
    {
    }

    public Planfi1020204Context(DbContextOptions<Planfi1020204Context> options)
        : base(options)
    {
    }

    public virtual DbSet<Alumno> Alumnos { get; set; }

    public virtual DbSet<AlumnoComision> AlumnoComisions { get; set; }

    public virtual DbSet<AsignacionPlanillaDocente> AsignacionPlanillaDocentes { get; set; }

    public virtual DbSet<Asignatura> Asignaturas { get; set; }

    public virtual DbSet<Calendario> Calendarios { get; set; }

    public virtual DbSet<Calificacion> Calificacions { get; set; }

    public virtual DbSet<Cargo> Cargos { get; set; }

    public virtual DbSet<CentroEducativo> CentroEducativos { get; set; }

    public virtual DbSet<Comision> Comisiones { get; set; }

    public virtual DbSet<ComisionRelacionadum> ComisionRelacionada { get; set; }

    public virtual DbSet<Contralor> Contralors { get; set; }

    public virtual DbSet<Curso> Cursos { get; set; }

    public virtual DbSet<Designacion> Designacions { get; set; }

    public virtual DbSet<DetallePersona> DetallePersonas { get; set; }

    public virtual DbSet<Disposicion> Disposicions { get; set; }

    public virtual DbSet<DisposicionPendiente> DisposicionPendientes { get; set; }

    public virtual DbSet<DistribucionHorarium> DistribucionHoraria { get; set; }

    public virtual DbSet<Dium> Dia { get; set; }

    public virtual DbSet<Domicilio> Domicilios { get; set; }

    public virtual DbSet<Email> Emails { get; set; }

    public virtual DbSet<File> Files { get; set; }

    public virtual DbSet<Horario> Horarios { get; set; }

    public virtual DbSet<Modalidad> Modalidads { get; set; }

    public virtual DbSet<Persona> Personas { get; set; }

    public virtual DbSet<Plan> Plans { get; set; }

    public virtual DbSet<Planificacion> Planificacions { get; set; }

    public virtual DbSet<PlanillaDocente> PlanillaDocentes { get; set; }

    public virtual DbSet<Resolucion> Resolucions { get; set; }

    public virtual DbSet<Sede> Sedes { get; set; }

    public virtual DbSet<Telefono> Telefonos { get; set; }

    public virtual DbSet<TipoSede> TipoSedes { get; set; }

    public virtual DbSet<Toma> Tomas { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
#warning To protect potentially sensitive information in your connection string, you should move it out of source code. You can avoid scaffolding the connection string by using the Name= syntax to read it from configuration - see https://go.microsoft.com/fwlink/?linkid=2131148. For more guidance on storing connection strings, see https://go.microsoft.com/fwlink/?LinkId=723263.
        => optionsBuilder.UseMySql("server=localhost;database=planfi10_20204;user=root", Microsoft.EntityFrameworkCore.ServerVersion.Parse("10.4.32-mariadb"));

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder
            .UseCollation("utf8mb4_general_ci")
            .HasCharSet("utf8mb4");

        modelBuilder.Entity<Alumno>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("alumno")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.LibroFolio, "alumno_libro_folio_un").IsUnique();

            entity.HasIndex(e => e.Persona, "alumno_persona_un").IsUnique();

            entity.HasIndex(e => e.Plan, "alumno_plan_FK");

            entity.HasIndex(e => e.ResolucionInscripcion, "alumno_resolucion_inscripcion_FK");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.AdeudaDeudores)
                .HasMaxLength(255)
                .HasColumnName("adeuda_deudores");
            entity.Property(e => e.AdeudaLegajo)
                .HasMaxLength(255)
                .HasColumnName("adeuda_legajo");
            entity.Property(e => e.AnioIngreso)
                .HasMaxLength(45)
                .HasColumnName("anio_ingreso");
            entity.Property(e => e.AnioInscripcion)
                .HasColumnType("smallint(1)")
                .HasColumnName("anio_inscripcion");
            entity.Property(e => e.AnioInscripcionCompleto).HasColumnName("anio_inscripcion_completo");
            entity.Property(e => e.Comentarios)
                .HasColumnType("text")
                .HasColumnName("comentarios");
            entity.Property(e => e.ConfirmadoDireccion).HasColumnName("confirmado_direccion");
            entity.Property(e => e.Creado)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("creado");
            entity.Property(e => e.DocumentacionInscripcion)
                .HasMaxLength(255)
                .HasColumnName("documentacion_inscripcion");
            entity.Property(e => e.EstablecimientoInscripcion)
                .HasMaxLength(255)
                .HasColumnName("establecimiento_inscripcion");
            entity.Property(e => e.EstadoInscripcion)
                .HasMaxLength(45)
                .HasColumnName("estado_inscripcion");
            entity.Property(e => e.FechaTitulacion).HasColumnName("fecha_titulacion");
            entity.Property(e => e.Folio)
                .HasMaxLength(45)
                .HasColumnName("folio");
            entity.Property(e => e.Libro)
                .HasMaxLength(45)
                .HasColumnName("libro");
            entity.Property(e => e.LibroFolio).HasColumnName("libro_folio");
            entity.Property(e => e.Observaciones)
                .HasColumnType("text")
                .HasColumnName("observaciones");
            entity.Property(e => e.Persona)
                .HasMaxLength(45)
                .HasColumnName("persona");
            entity.Property(e => e.Plan)
                .HasMaxLength(45)
                .HasColumnName("plan");
            entity.Property(e => e.PreviasCompletas).HasColumnName("previas_completas");
            entity.Property(e => e.ResolucionInscripcion)
                .HasMaxLength(45)
                .HasColumnName("resolucion_inscripcion");
            entity.Property(e => e.SemestreIngreso)
                .HasColumnType("smallint(1)")
                .HasColumnName("semestre_ingreso");
            entity.Property(e => e.SemestreInscripcion)
                .HasColumnType("smallint(1)")
                .HasColumnName("semestre_inscripcion");
            entity.Property(e => e.TieneCertificado).HasColumnName("tiene_certificado");
            entity.Property(e => e.TieneConstancia).HasColumnName("tiene_constancia");
            entity.Property(e => e.TieneDni).HasColumnName("tiene_dni");
            entity.Property(e => e.TienePartida).HasColumnName("tiene_partida");

            entity.HasOne(d => d.PersonaNavigation).WithOne(p => p.Alumno)
                .HasForeignKey<Alumno>(d => d.Persona)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("alumno_persona_FK");

            entity.HasOne(d => d.PlanNavigation).WithMany(p => p.Alumnos)
                .HasForeignKey(d => d.Plan)
                .HasConstraintName("alumno_plan_FK");

            entity.HasOne(d => d.ResolucionInscripcionNavigation).WithMany(p => p.Alumnos)
                .HasForeignKey(d => d.ResolucionInscripcion)
                .HasConstraintName("alumno_resolucion_inscripcion_FK");
        });

        modelBuilder.Entity<AlumnoComision>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("alumno_comision")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Alumno, "fk_alumno_comision_alumno");

            entity.HasIndex(e => e.Comision, "fk_alumno_comision_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Activo)
                .HasDefaultValueSql("'0'")
                .HasColumnName("activo");
            entity.Property(e => e.Alumno)
                .HasMaxLength(45)
                .HasColumnName("alumno");
            entity.Property(e => e.Comision)
                .HasMaxLength(45)
                .HasColumnName("comision");
            entity.Property(e => e.Creado)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("creado");
            entity.Property(e => e.Estado)
                .HasMaxLength(100)
                .HasDefaultValueSql("'Activo'")
                .HasColumnName("estado");
            entity.Property(e => e.Observaciones)
                .HasColumnType("text")
                .HasColumnName("observaciones");
            entity.Property(e => e.Pfid)
                .HasColumnType("int(10) unsigned")
                .HasColumnName("pfid");

            entity.HasOne(d => d.AlumnoNavigation).WithMany(p => p.AlumnoComisiones)
                .HasForeignKey(d => d.Alumno)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_alumno_comision_alumno");

            entity.HasOne(d => d.ComisionNavigation).WithMany(p => p.AlumnoComisions)
                .HasForeignKey(d => d.Comision)
                .HasConstraintName("fk_alumno_comision");
        });

        modelBuilder.Entity<AsignacionPlanillaDocente>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("asignacion_planilla_docente")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.PlanillaDocente, "fk_asignacion_planilla_docente_planilla_docente_idx");

            entity.HasIndex(e => e.Toma, "fk_asignacion_planilla_docente_toma_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Comentario)
                .HasMaxLength(255)
                .HasColumnName("comentario");
            entity.Property(e => e.Insertado)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("insertado");
            entity.Property(e => e.PlanillaDocente)
                .HasMaxLength(45)
                .HasColumnName("planilla_docente");
            entity.Property(e => e.Reclamo).HasColumnName("reclamo");
            entity.Property(e => e.Toma)
                .HasMaxLength(45)
                .HasColumnName("toma");

            entity.HasOne(d => d.PlanillaDocenteNavigation).WithMany(p => p.AsignacionPlanillaDocentes)
                .HasForeignKey(d => d.PlanillaDocente)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_asignacion_planilla_docente_planilla_docente");

            entity.HasOne(d => d.TomaNavigation).WithMany(p => p.AsignacionPlanillaDocentes)
                .HasForeignKey(d => d.Toma)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_asignacion_planilla_docente_toma");
        });

        modelBuilder.Entity<Asignatura>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("asignatura")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Nombre, "nombre_UNIQUE").IsUnique();

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Clasificacion)
                .HasMaxLength(45)
                .HasColumnName("clasificacion");
            entity.Property(e => e.Codigo)
                .HasMaxLength(45)
                .HasColumnName("codigo");
            entity.Property(e => e.Formacion)
                .HasMaxLength(45)
                .HasColumnName("formacion");
            entity.Property(e => e.Nombre).HasColumnName("nombre");
            entity.Property(e => e.Perfil)
                .HasMaxLength(45)
                .HasColumnName("perfil");
        });

        modelBuilder.Entity<Calendario>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("calendario")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Anio)
                .HasColumnType("year(4)")
                .HasColumnName("anio");
            entity.Property(e => e.Descripcion)
                .HasMaxLength(255)
                .HasColumnName("descripcion");
            entity.Property(e => e.Fin).HasColumnName("fin");
            entity.Property(e => e.Inicio).HasColumnName("inicio");
            entity.Property(e => e.Insertado)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("insertado");
            entity.Property(e => e.Semestre)
                .HasColumnType("smallint(6)")
                .HasColumnName("semestre");
        });

        modelBuilder.Entity<Calificacion>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("calificacion")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Alumno, "calificacion_alumno_FK");

            entity.HasIndex(e => e.Curso, "calificacion_curso_fk");

            entity.HasIndex(e => e.Disposicion, "calificacion_disposicion_FK");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Alumno)
                .HasMaxLength(45)
                .HasColumnName("alumno");
            entity.Property(e => e.Archivado).HasColumnName("archivado");
            entity.Property(e => e.Crec)
                .HasPrecision(4, 2)
                .HasColumnName("crec");
            entity.Property(e => e.Curso)
                .HasMaxLength(45)
                .HasColumnName("curso");
            entity.Property(e => e.Disposicion)
                .HasMaxLength(45)
                .HasColumnName("disposicion");
            entity.Property(e => e.Division)
                .HasMaxLength(255)
                .HasColumnName("division");
            entity.Property(e => e.Fecha).HasColumnName("fecha");
            entity.Property(e => e.Nota1)
                .HasPrecision(4, 2)
                .HasColumnName("nota1");
            entity.Property(e => e.Nota2)
                .HasPrecision(4, 2)
                .HasColumnName("nota2");
            entity.Property(e => e.Nota3)
                .HasPrecision(4, 2)
                .HasColumnName("nota3");
            entity.Property(e => e.NotaFinal)
                .HasPrecision(4, 2)
                .HasColumnName("nota_final");
            entity.Property(e => e.Observaciones)
                .HasColumnType("text")
                .HasColumnName("observaciones");
            entity.Property(e => e.PorcentajeAsistencia)
                .HasColumnType("int(3)")
                .HasColumnName("porcentaje_asistencia");

            entity.HasOne(d => d.AlumnoNavigation).WithMany(p => p.Calificacions)
                .HasForeignKey(d => d.Alumno)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("calificacion_alumno_FK");

            entity.HasOne(d => d.CursoNavigation).WithMany(p => p.Calificacions)
                .HasForeignKey(d => d.Curso)
                .HasConstraintName("calificacion_curso_fk");

            entity.HasOne(d => d.DisposicionNavigation).WithMany(p => p.Calificacions)
                .HasForeignKey(d => d.Disposicion)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("calificacion_disposicion_FK");
        });

        modelBuilder.Entity<Cargo>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("cargo")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Descripcion, "descripcion_UNIQUE").IsUnique();

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Descripcion).HasColumnName("descripcion");
        });

        modelBuilder.Entity<CentroEducativo>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("centro_educativo")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Cue, "cue_UNIQUE").IsUnique();

            entity.HasIndex(e => e.Domicilio, "fk_centro_educativo_domicilio1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Cue)
                .HasMaxLength(45)
                .HasColumnName("cue");
            entity.Property(e => e.Domicilio)
                .HasMaxLength(45)
                .HasColumnName("domicilio");
            entity.Property(e => e.Nombre)
                .HasMaxLength(255)
                .HasColumnName("nombre");
            entity.Property(e => e.Observaciones)
                .HasColumnType("text")
                .HasColumnName("observaciones");

            entity.HasOne(d => d.DomicilioNavigation).WithMany(p => p.CentroEducativos)
                .HasForeignKey(d => d.Domicilio)
                .HasConstraintName("fk_centro_educativo_domicilio1");
        });

        modelBuilder.Entity<Comision>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("comision")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Calendario, "fk_comision_calendario1_idx");

            entity.HasIndex(e => e.ComisionSiguiente, "fk_comision_comision1_idx");

            entity.HasIndex(e => e.Modalidad, "fk_comision_modalidad1_idx");

            entity.HasIndex(e => e.Planificacion, "fk_comision_planificacion1_idx");

            entity.HasIndex(e => e.Sede, "fk_comision_sede1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Alta)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("alta");
            entity.Property(e => e.Apertura).HasColumnName("apertura");
            entity.Property(e => e.Autorizada).HasColumnName("autorizada");
            entity.Property(e => e.Calendario)
                .HasMaxLength(45)
                .HasColumnName("calendario");
            entity.Property(e => e.Comentario)
                .HasColumnType("text")
                .HasColumnName("comentario");
            entity.Property(e => e.ComisionSiguiente)
                .HasMaxLength(45)
                .HasColumnName("comision_siguiente");
            entity.Property(e => e.Configuracion)
                .HasMaxLength(45)
                .HasDefaultValueSql("'Histórica'")
                .HasColumnName("configuracion");
            entity.Property(e => e.Division)
                .HasMaxLength(45)
                .HasColumnName("division");
            entity.Property(e => e.Estado)
                .HasMaxLength(45)
                .HasDefaultValueSql("'Confirma'")
                .HasColumnName("estado");
            entity.Property(e => e.Identificacion)
                .HasMaxLength(45)
                .HasColumnName("identificacion");
            entity.Property(e => e.Modalidad)
                .HasMaxLength(45)
                .HasColumnName("modalidad");
            entity.Property(e => e.Observaciones)
                .HasColumnType("text")
                .HasColumnName("observaciones");
            entity.Property(e => e.Pfid)
                .HasMaxLength(45)
                .HasColumnName("pfid");
            entity.Property(e => e.Planificacion)
                .HasMaxLength(45)
                .HasColumnName("planificacion");
            entity.Property(e => e.Publicada).HasColumnName("publicada");
            entity.Property(e => e.Sede)
                .HasMaxLength(45)
                .HasColumnName("sede");
            entity.Property(e => e.Turno)
                .HasMaxLength(45)
                .HasColumnName("turno");

            entity.HasOne(d => d.CalendarioFk).WithMany(p => p.Comisions)
                .HasForeignKey(d => d.Calendario)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_comision_calendario1");

            entity.HasOne(d => d.ComisionSiguienteNavigation).WithMany(p => p.InverseComisionSiguienteNavigation)
                .HasForeignKey(d => d.ComisionSiguiente)
                .HasConstraintName("fk_comision_comision1");

            entity.HasOne(d => d.ModalidadNavigation).WithMany(p => p.Comisions)
                .HasForeignKey(d => d.Modalidad)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_comision_modalidad1");

            entity.HasOne(d => d.PlanificacionNavigation).WithMany(p => p.Comisions)
                .HasForeignKey(d => d.Planificacion)
                .HasConstraintName("fk_comision_planificacion1");

            entity.HasOne(d => d.SedeFk).WithMany(p => p.Comisions)
                .HasForeignKey(d => d.Sede)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_comision_sede1");
        });

        modelBuilder.Entity<ComisionRelacionadum>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("comision_relacionada")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Comision, "comision_relacionada_comision_FK");

            entity.HasIndex(e => e.Relacion, "comision_relacionada_relacion_FK");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Comision)
                .HasMaxLength(45)
                .HasColumnName("comision");
            entity.Property(e => e.Relacion)
                .HasMaxLength(45)
                .HasColumnName("relacion");

            entity.HasOne(d => d.ComisionNavigation).WithMany(p => p.ComisionRelacionadumComisionNavigations)
                .HasForeignKey(d => d.Comision)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("comision_relacionada_comision_FK");

            entity.HasOne(d => d.RelacionNavigation).WithMany(p => p.ComisionRelacionadumRelacionNavigations)
                .HasForeignKey(d => d.Relacion)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("comision_relacionada_relacion_FK");
        });

        modelBuilder.Entity<Contralor>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("contralor")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.PlanillaDocente, "fk_contralor_planilla_docente1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.FechaConsejo).HasColumnName("fecha_consejo");
            entity.Property(e => e.FechaContralor).HasColumnName("fecha_contralor");
            entity.Property(e => e.Insertado)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("insertado");
            entity.Property(e => e.PlanillaDocente)
                .HasMaxLength(45)
                .HasColumnName("planilla_docente");

            entity.HasOne(d => d.PlanillaDocenteNavigation).WithMany(p => p.Contralors)
                .HasForeignKey(d => d.PlanillaDocente)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_contralor_planilla_docente1");
        });

        modelBuilder.Entity<Curso>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("curso")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Disposicion, "curso_disposicion_FK");

            entity.HasIndex(e => e.Comision, "fk_curso_comision1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Alta)
                .ValueGeneratedOnAddOrUpdate()
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("alta");
            entity.Property(e => e.Codigo)
                .HasMaxLength(45)
                .HasColumnName("codigo");
            entity.Property(e => e.Comision)
                .HasMaxLength(45)
                .HasColumnName("comision");
            entity.Property(e => e.DescripcionHorario)
                .HasMaxLength(255)
                .HasColumnName("descripcion_horario");
            entity.Property(e => e.Disposicion)
                .HasMaxLength(45)
                .HasColumnName("disposicion");
            entity.Property(e => e.HorasCatedra)
                .HasColumnType("int(11)")
                .HasColumnName("horas_catedra");
            entity.Property(e => e.Ige)
                .HasMaxLength(45)
                .HasColumnName("ige");
            entity.Property(e => e.Observaciones)
                .HasMaxLength(255)
                .HasColumnName("observaciones");

            entity.HasOne(d => d.ComisionNavigation).WithMany(p => p.Cursos)
                .HasForeignKey(d => d.Comision)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_curso_comision1");

            entity.HasOne(d => d.DisposicionNavigation).WithMany(p => p.Cursos)
                .HasForeignKey(d => d.Disposicion)
                .HasConstraintName("curso_disposicion_FK");
        });

        modelBuilder.Entity<Designacion>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("designacion")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Cargo, "fk_designacion_cargo1_idx");

            entity.HasIndex(e => e.Persona, "fk_designacion_persona1_idx");

            entity.HasIndex(e => e.Sede, "fk_designacion_sede1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Alta)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("alta");
            entity.Property(e => e.Cargo)
                .HasMaxLength(45)
                .HasColumnName("cargo");
            entity.Property(e => e.Desde).HasColumnName("desde");
            entity.Property(e => e.Hasta).HasColumnName("hasta");
            entity.Property(e => e.Persona)
                .HasMaxLength(45)
                .HasColumnName("persona");
            entity.Property(e => e.Pfid)
                .HasMaxLength(45)
                .HasColumnName("pfid");
            entity.Property(e => e.Sede)
                .HasMaxLength(45)
                .HasColumnName("sede");

            entity.HasOne(d => d.CargoNavigation).WithMany(p => p.Designacions)
                .HasForeignKey(d => d.Cargo)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_designacion_cargo1");

            entity.HasOne(d => d.PersonaNavigation).WithMany(p => p.Designacions)
                .HasForeignKey(d => d.Persona)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_designacion_persona1");

            entity.HasOne(d => d.SedeNavigation).WithMany(p => p.Designacions)
                .HasForeignKey(d => d.Sede)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_designacion_sede1");
        });

        modelBuilder.Entity<DetallePersona>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("detalle_persona")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Persona, "fk_detalle_persona_persona1_idx");

            entity.HasIndex(e => e.Archivo, "fk_info_persona_file1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Archivo)
                .HasMaxLength(45)
                .HasColumnName("archivo");
            entity.Property(e => e.Asunto)
                .HasMaxLength(255)
                .HasColumnName("asunto");
            entity.Property(e => e.Creado)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("creado");
            entity.Property(e => e.Descripcion)
                .HasColumnType("text")
                .HasColumnName("descripcion");
            entity.Property(e => e.Fecha)
                .HasDefaultValueSql("curdate()")
                .HasColumnName("fecha");
            entity.Property(e => e.Persona)
                .HasMaxLength(45)
                .HasColumnName("persona");
            entity.Property(e => e.Tipo)
                .HasMaxLength(255)
                .HasColumnName("tipo");

            entity.HasOne(d => d.ArchivoNavigation).WithMany(p => p.DetallePersonas)
                .HasForeignKey(d => d.Archivo)
                .HasConstraintName("fk_info_persona_file1");

            entity.HasOne(d => d.PersonaNavigation).WithMany(p => p.DetallePersonas)
                .HasForeignKey(d => d.Persona)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_detalle_persona_persona1");
        });

        modelBuilder.Entity<Disposicion>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("disposicion")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Asignatura, "fk_disposicion_asignatura");

            entity.HasIndex(e => e.Planificacion, "fk_disposicion_planificacion");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Asignatura)
                .HasMaxLength(45)
                .HasColumnName("asignatura");
            entity.Property(e => e.OrdenInformeCoordinacionDistrital)
                .HasColumnType("int(11)")
                .HasColumnName("orden_informe_coordinacion_distrital");
            entity.Property(e => e.Planificacion)
                .HasMaxLength(45)
                .HasColumnName("planificacion");

            entity.HasOne(d => d.AsignaturaNavigation).WithMany(p => p.Disposicions)
                .HasForeignKey(d => d.Asignatura)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_disposicion_asignatura");

            entity.HasOne(d => d.PlanificacionNavigation).WithMany(p => p.Disposicions)
                .HasForeignKey(d => d.Planificacion)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_disposicion_planificacion");
        });

        modelBuilder.Entity<DisposicionPendiente>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("disposicion_pendiente")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Alumno, "disposicion_pendiente_alumno_FK");

            entity.HasIndex(e => e.Disposicion, "disposicion_pendiente_disposicion_FK");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Alumno)
                .HasMaxLength(45)
                .HasColumnName("alumno");
            entity.Property(e => e.Disposicion)
                .HasMaxLength(45)
                .HasColumnName("disposicion");
            entity.Property(e => e.Modo)
                .HasMaxLength(45)
                .HasColumnName("modo");

            entity.HasOne(d => d.AlumnoNavigation).WithMany(p => p.DisposicionPendientes)
                .HasForeignKey(d => d.Alumno)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("disposicion_pendiente_alumno_FK");

            entity.HasOne(d => d.DisposicionNavigation).WithMany(p => p.DisposicionPendientes)
                .HasForeignKey(d => d.Disposicion)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("disposicion_pendiente_disposicion_FK");
        });

        modelBuilder.Entity<DistribucionHorarium>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("distribucion_horaria")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Disposicion, "distribucion_horaria_disposicion_FK");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Dia)
                .HasColumnType("int(11)")
                .HasColumnName("dia");
            entity.Property(e => e.Disposicion)
                .HasMaxLength(45)
                .HasColumnName("disposicion");
            entity.Property(e => e.HorasCatedra)
                .HasColumnType("int(11)")
                .HasColumnName("horas_catedra");

            entity.HasOne(d => d.DisposicionNavigation).WithMany(p => p.DistribucionHoraria)
                .HasForeignKey(d => d.Disposicion)
                .HasConstraintName("distribucion_horaria_disposicion_FK");
        });

        modelBuilder.Entity<Dium>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("dia")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Dia, "dia_UNIQUE").IsUnique();

            entity.HasIndex(e => e.Numero, "numero_UNIQUE").IsUnique();

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Dia)
                .HasMaxLength(9)
                .HasColumnName("dia");
            entity.Property(e => e.Numero)
                .HasColumnType("smallint(1)")
                .HasColumnName("numero");
        });

        modelBuilder.Entity<Domicilio>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("domicilio")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Barrio)
                .HasMaxLength(255)
                .HasColumnName("barrio");
            entity.Property(e => e.Calle)
                .HasMaxLength(45)
                .HasColumnName("calle");
            entity.Property(e => e.Departamento)
                .HasMaxLength(45)
                .HasColumnName("departamento");
            entity.Property(e => e.Entre)
                .HasMaxLength(45)
                .HasColumnName("entre");
            entity.Property(e => e.Localidad)
                .HasMaxLength(255)
                .HasColumnName("localidad");
            entity.Property(e => e.Numero)
                .HasMaxLength(45)
                .HasColumnName("numero");
            entity.Property(e => e.Piso)
                .HasMaxLength(45)
                .HasColumnName("piso");
        });

        modelBuilder.Entity<Email>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("email")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Persona, "fk_email_persona1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Eliminado)
                .HasColumnType("timestamp")
                .HasColumnName("eliminado");
            entity.Property(e => e.Email1)
                .HasMaxLength(255)
                .HasColumnName("email");
            entity.Property(e => e.Insertado)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("insertado");
            entity.Property(e => e.Persona)
                .HasMaxLength(45)
                .HasColumnName("persona");
            entity.Property(e => e.Verificado).HasColumnName("verificado");

            entity.HasOne(d => d.PersonaNavigation).WithMany(p => p.Emails)
                .HasForeignKey(d => d.Persona)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_email_persona1");
        });

        modelBuilder.Entity<File>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("file")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Content)
                .HasMaxLength(255)
                .HasColumnName("content");
            entity.Property(e => e.Created)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("created");
            entity.Property(e => e.Name)
                .HasMaxLength(255)
                .HasColumnName("name");
            entity.Property(e => e.Size)
                .HasColumnType("int(10) unsigned")
                .HasColumnName("size");
            entity.Property(e => e.Type)
                .HasMaxLength(255)
                .HasColumnName("type");
        });

        modelBuilder.Entity<Horario>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("horario")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Curso, "fk_horario_curso1_idx");

            entity.HasIndex(e => e.Dia, "fk_horario_dia1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Curso)
                .HasMaxLength(45)
                .HasColumnName("curso");
            entity.Property(e => e.Dia)
                .HasMaxLength(45)
                .HasColumnName("dia");
            entity.Property(e => e.HoraFin)
                .HasColumnType("time")
                .HasColumnName("hora_fin");
            entity.Property(e => e.HoraInicio)
                .HasColumnType("time")
                .HasColumnName("hora_inicio");

            entity.HasOne(d => d.CursoNavigation).WithMany(p => p.Horarios)
                .HasForeignKey(d => d.Curso)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_horario_curso1");

            entity.HasOne(d => d.DiaNavigation).WithMany(p => p.Horarios)
                .HasForeignKey(d => d.Dia)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_horario_dia1");
        });

        modelBuilder.Entity<Modalidad>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("modalidad")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Nombre, "nombre_UNIQUE").IsUnique();

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Nombre)
                .HasMaxLength(45)
                .HasColumnName("nombre");
            entity.Property(e => e.Pfid)
                .HasMaxLength(45)
                .HasColumnName("pfid");
        });

        modelBuilder.Entity<Persona>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("persona")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Cuil, "cuil_UNIQUE").IsUnique();

            entity.HasIndex(e => e.EmailAbc, "email_abc").IsUnique();

            entity.HasIndex(e => e.Domicilio, "fk_persona_domicilio1_idx");

            entity.HasIndex(e => e.NumeroDocumento, "numero_documento_UNIQUE").IsUnique();

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Alta)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("alta");
            entity.Property(e => e.AnioNacimiento)
                .HasColumnType("smallint(5) unsigned")
                .HasColumnName("anio_nacimiento");
            entity.Property(e => e.Apellidos)
                .HasMaxLength(255)
                .HasColumnName("apellidos");
            entity.Property(e => e.Apodo)
                .HasMaxLength(255)
                .HasColumnName("apodo");
            entity.Property(e => e.CodigoArea)
                .HasMaxLength(45)
                .HasColumnName("codigo_area");
            entity.Property(e => e.Cuil)
                .HasMaxLength(45)
                .HasColumnName("cuil");
            entity.Property(e => e.Cuil1)
                .HasColumnType("tinyint(3) unsigned")
                .HasColumnName("cuil1");
            entity.Property(e => e.Cuil2)
                .HasColumnType("tinyint(3) unsigned")
                .HasColumnName("cuil2");
            entity.Property(e => e.Departamento)
                .HasMaxLength(45)
                .HasColumnName("departamento");
            entity.Property(e => e.DescripcionDomicilio)
                .HasMaxLength(255)
                .HasColumnName("descripcion_domicilio");
            entity.Property(e => e.DiaNacimiento)
                .HasColumnType("tinyint(3) unsigned")
                .HasColumnName("dia_nacimiento");
            entity.Property(e => e.Domicilio)
                .HasMaxLength(45)
                .HasColumnName("domicilio");
            entity.Property(e => e.Email)
                .HasMaxLength(255)
                .HasColumnName("email");
            entity.Property(e => e.EmailAbc).HasColumnName("email_abc");
            entity.Property(e => e.EmailVerificado).HasColumnName("email_verificado");
            entity.Property(e => e.FechaNacimiento).HasColumnName("fecha_nacimiento");
            entity.Property(e => e.Genero)
                .HasMaxLength(45)
                .HasColumnName("genero");
            entity.Property(e => e.InfoVerificada).HasColumnName("info_verificada");
            entity.Property(e => e.Localidad)
                .HasMaxLength(100)
                .HasColumnName("localidad");
            entity.Property(e => e.LugarNacimiento)
                .HasMaxLength(255)
                .HasColumnName("lugar_nacimiento");
            entity.Property(e => e.MesNacimiento)
                .HasColumnType("tinyint(3) unsigned")
                .HasColumnName("mes_nacimiento");
            entity.Property(e => e.Nacionalidad)
                .HasMaxLength(100)
                .HasColumnName("nacionalidad");
            entity.Property(e => e.Nombres)
                .HasMaxLength(255)
                .HasColumnName("nombres");
            entity.Property(e => e.NumeroDocumento)
                .HasMaxLength(45)
                .HasColumnName("numero_documento");
            entity.Property(e => e.Partido)
                .HasMaxLength(100)
                .HasColumnName("partido");
            entity.Property(e => e.Sexo)
                .HasColumnType("tinyint(3) unsigned")
                .HasColumnName("sexo");
            entity.Property(e => e.Telefono)
                .HasMaxLength(255)
                .HasColumnName("telefono");
            entity.Property(e => e.TelefonoVerificado).HasColumnName("telefono_verificado");

            entity.HasOne(d => d.DomicilioNavigation).WithMany(p => p.Personas)
                .HasForeignKey(d => d.Domicilio)
                .HasConstraintName("fk_persona_domicilio1");
        });

        modelBuilder.Entity<Plan>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("plan")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.DistribucionHoraria)
                .HasMaxLength(45)
                .HasColumnName("distribucion_horaria");
            entity.Property(e => e.Orientacion)
                .HasMaxLength(45)
                .HasColumnName("orientacion");
            entity.Property(e => e.Pfid)
                .HasMaxLength(45)
                .HasColumnName("pfid");
            entity.Property(e => e.Resolucion)
                .HasMaxLength(45)
                .HasColumnName("resolucion");
        });

        modelBuilder.Entity<Planificacion>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("planificacion")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Plan, "fk_planificacion_plan1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Anio)
                .HasMaxLength(45)
                .HasColumnName("anio");
            entity.Property(e => e.Pfid)
                .HasMaxLength(45)
                .HasColumnName("pfid");
            entity.Property(e => e.Plan)
                .HasMaxLength(45)
                .HasColumnName("plan");
            entity.Property(e => e.Semestre)
                .HasMaxLength(45)
                .HasColumnName("semestre");

            entity.HasOne(d => d.PlanNavigation).WithMany(p => p.Planificacions)
                .HasForeignKey(d => d.Plan)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_planificacion_plan1");
        });

        modelBuilder.Entity<PlanillaDocente>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("planilla_docente")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.FechaConsejo).HasColumnName("fecha_consejo");
            entity.Property(e => e.FechaContralor).HasColumnName("fecha_contralor");
            entity.Property(e => e.Insertado)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("insertado");
            entity.Property(e => e.Numero)
                .HasMaxLength(255)
                .HasColumnName("numero");
            entity.Property(e => e.Observaciones)
                .HasColumnType("text")
                .HasColumnName("observaciones");
        });

        modelBuilder.Entity<Resolucion>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("resolucion")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Anio)
                .HasColumnType("year(4)")
                .HasColumnName("anio");
            entity.Property(e => e.Numero)
                .HasMaxLength(255)
                .HasColumnName("numero");
            entity.Property(e => e.Tipo)
                .HasMaxLength(255)
                .HasColumnName("tipo");
        });

        modelBuilder.Entity<Sede>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("sede")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.CentroEducativo, "fk_sede_centro_educativo1_idx");

            entity.HasIndex(e => e.Domicilio, "fk_sede_domicilio1_idx");

            entity.HasIndex(e => e.Organizacion, "fk_sede_sede1");

            entity.HasIndex(e => e.TipoSede, "fk_sede_tipo_sede1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Alta)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("alta");
            entity.Property(e => e.Baja)
                .HasColumnType("timestamp")
                .HasColumnName("baja");
            entity.Property(e => e.CentroEducativo)
                .HasMaxLength(45)
                .HasColumnName("centro_educativo");
            entity.Property(e => e.Domicilio)
                .HasMaxLength(45)
                .HasColumnName("domicilio");
            entity.Property(e => e.FechaTraspaso).HasColumnName("fecha_traspaso");
            entity.Property(e => e.Nombre)
                .HasMaxLength(255)
                .HasColumnName("nombre");
            entity.Property(e => e.Numero)
                .HasMaxLength(45)
                .HasColumnName("numero");
            entity.Property(e => e.Observaciones)
                .HasColumnType("text")
                .HasColumnName("observaciones");
            entity.Property(e => e.Organizacion)
                .HasMaxLength(45)
                .HasColumnName("organizacion");
            entity.Property(e => e.Pfid)
                .HasMaxLength(45)
                .HasColumnName("pfid");
            entity.Property(e => e.PfidOrganizacion)
                .HasMaxLength(45)
                .HasColumnName("pfid_organizacion");
            entity.Property(e => e.TipoSede)
                .HasMaxLength(45)
                .HasColumnName("tipo_sede");

            entity.HasOne(d => d.CentroEducativoNavigation).WithMany(p => p.Sedes)
                .HasForeignKey(d => d.CentroEducativo)
                .HasConstraintName("fk_sede_centro_educativo1");

            entity.HasOne(d => d.DomicilioNavigation).WithMany(p => p.Sedes)
                .HasForeignKey(d => d.Domicilio)
                .HasConstraintName("fk_sede_domicilio1");

            entity.HasOne(d => d.OrganizacionNavigation).WithMany(p => p.InverseOrganizacionNavigation)
                .HasForeignKey(d => d.Organizacion)
                .HasConstraintName("fk_sede_sede1");

            entity.HasOne(d => d.TipoSedeNavigation).WithMany(p => p.Sedes)
                .HasForeignKey(d => d.TipoSede)
                .HasConstraintName("fk_sede_tipo_sede1");
        });

        modelBuilder.Entity<Telefono>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("telefono")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Persona, "fk_telefono_persona1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Eliminado)
                .HasColumnType("timestamp")
                .HasColumnName("eliminado");
            entity.Property(e => e.Insertado)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("insertado");
            entity.Property(e => e.Numero)
                .HasMaxLength(255)
                .HasColumnName("numero");
            entity.Property(e => e.Persona)
                .HasMaxLength(45)
                .HasColumnName("persona");
            entity.Property(e => e.Prefijo)
                .HasMaxLength(45)
                .HasColumnName("prefijo");
            entity.Property(e => e.Tipo)
                .HasMaxLength(45)
                .HasColumnName("tipo");

            entity.HasOne(d => d.PersonaNavigation).WithMany(p => p.Telefonos)
                .HasForeignKey(d => d.Persona)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_telefono_persona1");
        });

        modelBuilder.Entity<TipoSede>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("tipo_sede")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Descripcion, "descripcion_UNIQUE").IsUnique();

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Descripcion).HasColumnName("descripcion");
        });

        modelBuilder.Entity<Toma>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PRIMARY");

            entity
                .ToTable("toma")
                .HasCharSet("utf8")
                .UseCollation("utf8_spanish_ci");

            entity.HasIndex(e => e.Curso, "fk_toma_curso1_idx");

            entity.HasIndex(e => e.Docente, "fk_toma_persona1_idx");

            entity.HasIndex(e => e.Reemplazo, "fk_toma_persona2_idx");

            entity.HasIndex(e => e.PlanillaDocente, "fk_toma_planilla_docente1_idx");

            entity.Property(e => e.Id)
                .HasMaxLength(45)
                .HasColumnName("id");
            entity.Property(e => e.Alta)
                .HasDefaultValueSql("current_timestamp()")
                .HasColumnType("timestamp")
                .HasColumnName("alta");
            entity.Property(e => e.Asistencia).HasColumnName("asistencia");
            entity.Property(e => e.Calificacion).HasColumnName("calificacion");
            entity.Property(e => e.Comentario)
                .HasMaxLength(45)
                .HasColumnName("comentario");
            entity.Property(e => e.Confirmada).HasColumnName("confirmada");
            entity.Property(e => e.Curso)
                .HasMaxLength(45)
                .HasColumnName("curso");
            entity.Property(e => e.Docente)
                .HasMaxLength(45)
                .HasColumnName("docente");
            entity.Property(e => e.Estado)
                .HasMaxLength(45)
                .HasColumnName("estado");
            entity.Property(e => e.EstadoContralor)
                .HasMaxLength(45)
                .HasColumnName("estado_contralor");
            entity.Property(e => e.FechaToma).HasColumnName("fecha_toma");
            entity.Property(e => e.Observaciones)
                .HasColumnType("text")
                .HasColumnName("observaciones");
            entity.Property(e => e.PlanillaDocente)
                .HasMaxLength(45)
                .HasColumnName("planilla_docente");
            entity.Property(e => e.Reemplazo)
                .HasMaxLength(45)
                .HasColumnName("reemplazo");
            entity.Property(e => e.SinPlanillas).HasColumnName("sin_planillas");
            entity.Property(e => e.TemasTratados).HasColumnName("temas_tratados");
            entity.Property(e => e.TipoMovimiento)
                .HasMaxLength(45)
                .HasColumnName("tipo_movimiento");

            entity.HasOne(d => d.CursoNavigation).WithMany(p => p.Tomas)
                .HasForeignKey(d => d.Curso)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("fk_toma_curso1");

            entity.HasOne(d => d.DocenteNavigation).WithMany(p => p.TomaDocenteNavigations)
                .HasForeignKey(d => d.Docente)
                .HasConstraintName("fk_toma_persona1");

            entity.HasOne(d => d.PlanillaDocenteNavigation).WithMany(p => p.Tomas)
                .HasForeignKey(d => d.PlanillaDocente)
                .HasConstraintName("fk_toma_planilla_docente1");

            entity.HasOne(d => d.ReemplazoNavigation).WithMany(p => p.TomaReemplazoNavigations)
                .HasForeignKey(d => d.Reemplazo)
                .HasConstraintName("fk_toma_persona2");
        });

        OnModelCreatingPartial(modelBuilder);
    }

    partial void OnModelCreatingPartial(ModelBuilder modelBuilder);
}
