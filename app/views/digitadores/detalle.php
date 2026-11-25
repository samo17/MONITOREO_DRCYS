<?php
$titulo = 'Detalle del digitador';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

$campos = [
    'IdDIGITADOR' => 'ID del digitador',
    'NOMBRES' => 'Nombres completos',
    'DNI' => 'DNI',
    'FECHA_NACIMIENTO' => 'Fecha de nacimiento',
    'CONDICION' => 'Condición',
    'FECHA_INGRESO' => 'Fecha de ingreso',
    'TELEFONO' => 'Teléfono',
    'CORREO' => 'Correo electrónico',
    'TIPO' => 'Tipo',
    'GRUPO' => 'Grupo',
    'META_DIARIA' => 'Meta diaria',
    'IMG' => 'Imagen / fotografía'
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
                        <h1 class="h3 mb-1">Detalle del digitador</h1>
                        <p class="text-muted mb-0">Información registrada del personal digitador</p>
                    </div>
                    <a href="<?= url('digitadores') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Volver al listado
                    </a>
                </div>
                <div class="card shadow-sm mb-4">
                    <div class="card-header"><strong>Datos del digitador</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($campos as $campo => $etiqueta): ?>
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="small text-muted mb-1"><?= e($etiqueta) ?></div>
                                        <div class="fw-semibold text-break"><?= $valor($digitador[$campo] ?? null) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="<?= url('digitadores/editar') ?>&id=<?= (int)($digitador['IdDIGITADOR'] ?? 0) ?>" class="btn btn-primary">Editar</a>
                        <a href="<?= url('digitadores') ?>" class="btn btn-outline-secondary">Cerrar detalle</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
