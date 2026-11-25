<?php
$titulo = 'Detalle de ocurrencia';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

$campos = [
    'IdOcurrencia' => 'ID de ocurrencia',
    'FechaRegistro' => 'Fecha de registro',
    'NumeroDni' => 'DNI del paciente',
    'NombreAsegurado' => 'Nombre del asegurado',
    'IAFAS' => 'IAFAS de procedencia',
    'IdAfiliados' => 'ID del afiliado SIS',
    'NroFormato' => 'N.º de formato',
    'FECHA_PROBABLE_PARTO' => 'Fecha probable de parto',
    'GESTANTE' => 'Gestante',
    'EMERGENCIAS' => 'Emergencias',
    'PRESENCIAL' => 'Presencial',
    'TiposOcurrencia' => 'Tipos de ocurrencia',
    'IPRESS_ATENC' => 'IPRESS',
    'DISTRITO' => 'Distrito',
    'COMUNIDAD' => 'Comunidad',
    'DIRECCION' => 'Dirección',
    'REFERENCIA' => 'Referencia',
    'OBSERVACIONES' => 'Observaciones',
    'PERSONA_RESPONSABLE' => 'Persona responsable',
    'DNI_RESPONSABLE' => 'DNI del responsable',
    'FONO_RESPONSABLE' => 'Teléfono del responsable',
    'DNI_RESPONSABLE_AFILIACION' => 'DNI responsable de afiliación',
    'NombresResponsableAfiliacion' => 'Nombres del responsable de afiliación',
    'ApellidosResponsableAfiliacion' => 'Apellidos del responsable de afiliación',
    'Sexo' => 'Sexo del asegurado',
    'FechaNacimiento' => 'Fecha de nacimiento',
    'EessAdscrito' => 'EESS adscrito',
    'Provincia' => 'Provincia del asegurado'
];
$valor = static function ($dato): string {
    $dato = trim((string) ($dato ?? ''));
    return $dato !== '' ? e($dato) : '<span class="text-muted">No registrado</span>';
};
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0">
            <?php require __DIR__ . '/../layouts/sidebar.php'; ?>
        </div>
        <main class="col-md-9 col-lg-10">
            <div class="container-fluid py-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                    <div>
                        <h1 class="h3 mb-1">Detalle de ocurrencia</h1>
                        <p class="text-muted mb-0">Consulta de la información registrada en la Central de Afiliación</p>
                    </div>
                    <a href="<?= url('ocurrenciasca') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Volver al listado
                    </a>
                </div>
                <div class="card shadow-sm mb-4">
                    <div class="card-header"><strong>Información de la ocurrencia</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($campos as $campo => $etiqueta): ?>
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="small text-muted mb-1"><?= e($etiqueta) ?></div>
                                        <div class="fw-semibold text-break"><?= $valor($ocurrencia[$campo] ?? null) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="<?= url('ocurrenciasca') ?>" class="btn btn-primary">Cerrar detalle</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
