<?php

$titulo = 'Detalle del afiliado - Consulta';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

$campos = [
    'IdAfiliados' => 'ID del afiliado',
    'IdSiasis' => 'ID SIA SIS',
    'TipoTabla' => 'Tipo de tabla',
    'NroFormato' => 'N.º de formato',
    'Correlativo' => 'Correlativo',
    'TipoDocumento' => 'Tipo de documento',
    'NumeroDni' => 'DNI',
    'NombreAsegurado' => 'Nombre del asegurado',
    'Sexo' => 'Sexo',
    'FechaNacimiento' => 'Fecha de nacimiento',
    'Estado' => 'Estado',
    'FechaFormato' => 'Fecha de formato',
    'FechaBaja' => 'Fecha de baja',
    'EessAdscrito' => 'EESS adscrito',
    'Provincia' => 'Provincia',
    'Distrito' => 'Distrito',
    'GrupoPoblacional' => 'Grupo poblacional',
    'MotivoBaja' => 'Motivo de baja',
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
                        <h1 class="h3 mb-1">Detalle del afiliado</h1>
                        <p class="text-muted mb-0">Información registrada en el maestro de afiliados</p>
                    </div>
                    <a href="index.php?route=afiliados_consulta" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Volver al listado
                    </a>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <strong>Datos del asegurado</strong>
                        <?php if (strcasecmp(trim((string) ($afiliado['Estado'] ?? '')), 'Activo') === 0): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?= e($afiliado['Estado'] ?? 'Sin estado') ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($campos as $campo => $etiqueta): ?>
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="small text-muted mb-1"><?= e($etiqueta) ?></div>
                                        <div class="fw-semibold text-break"><?= $valor($afiliado[$campo] ?? null) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="index.php?route=afiliados_consulta" class="btn btn-primary">Cerrar detalle</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
