<div class="page-header">
    <div>
        <h1>Constancias</h1>
        <p class="text-secondary mb-0">Seleccioná el tipo de constancia que querés generar.</p>
    </div>
</div>

<section class="constancia-cards">
    <a class="action-card" href="<?= e(url('/constancias/alumno-regular/nueva')) ?>">
        <strong>Alumno regular</strong>
        <span>Constancia de alumno/a regular con modalidad, orientación y resolución.</span>
    </a>
    <a class="action-card" href="<?= e(url('/constancias/titulo-tramite/nueva')) ?>">
        <strong>Título en trámite</strong>
        <span>Constancia de certificado analítico de estudios completo en trámite.</span>
    </a>
    <a class="action-card" href="<?= e(url('/constancias/vacante/nueva')) ?>">
        <strong>Vacante</strong>
        <span>Constancia de vacante para continuar estudios en el establecimiento.</span>
    </a>
    <a class="action-card" href="<?= e(url('/constancias/pase/nueva')) ?>">
        <strong>Pase</strong>
        <span>Constancia de pase con materias aprobadas y desaprobadas pegadas desde Excel.</span>
    </a>
    <a class="action-card" href="<?= e(url('/constancias/general/nueva')) ?>">
        <strong>General</strong>
        <span>Constancia general con texto libre.</span>
    </a>
</section>
