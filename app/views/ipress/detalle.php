<?php

$titulo = 'Detalle de IPRESS';

$ipress = $ipress ?? null;

if (!$ipress) {
    die('No se encontraron datos de la IPRESS.');
}

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-3 col-lg-2 p-0">
            <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
        </div>

        <main class="col-md-9 col-lg-10">

            <div class="container-fluid py-4">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h1 class="h3 mb-1">Detalle de IPRESS</h1>
                        <p class="text-muted mb-0">
                            Información completa de la IPRESS seleccionada
                        </p>
                    </div>

                    <a href="<?= url('ipress') ?>"
                       class="btn btn-secondary">
                        Volver
                    </a>

                </div>

                <div class="card shadow-sm">

                    <div class="card-header bg-primary text-white">
                        <strong>Información de la IPRESS</strong>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    RENAES
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['RENAES']) ?>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-bold">
                                    Nombre de IPRESS
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['IPRESS_ATENC']) ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Tipo
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['TIPO']) ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Nivel
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['NIVEL']) ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Clasificación
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['CLAS_NOCLAS']) ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Código PP.DD
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['COD_PPDD'] ?: '-') ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Código UE
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['COD_UE']) ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Unidad Ejecutora
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['UE']) ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Red
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['RED']) ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Microred
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['MICRORED']) ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Provincia
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['PROVINCIA']) ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Distrito
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['DISTRITO']) ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Grupo
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['GRUPO']) ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Estado
                                </label>

                                <div class="form-control bg-light">
                                    <?php if ((int) $ipress['ESTADO'] === 1): ?>
                                        <span class="badge bg-success">
                                            ACTIVO
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            INACTIVO
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Latitud geográfica
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['LATITUD_GEOGRAFICA'] ?: '-') ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Longitud geográfica
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['LONGITUD_GEOGRAFICA'] ?: '-') ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Coordenada Este
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['COORDENADA_ESTE'] ?: '-') ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Coordenada Norte
                                </label>

                                <div class="form-control bg-light">
                                    <?= e($ipress['COORDENADA_NORTE'] ?: '-') ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="card-footer text-end">

                        <a href="<?= url('ipress') ?>"
                           class="btn btn-secondary">
                            Volver al listado
                        </a>

                    </div>

                </div>

            </div>

        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>