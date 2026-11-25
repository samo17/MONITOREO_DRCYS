<?php
$titulo = 'Maestros - Afiliados';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

$totalActual = $totalActual ?? null;
$resultado = $resultado ?? null;
$mensaje = $mensaje ?? null;
$tipoMensaje = $tipoMensaje ?? 'info';
$nombreArchivo = $nombreArchivo ?? '';

$escapar = static function ($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
};
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0">
            <?php require __DIR__ . '/../layouts/sidebar.php'; ?>
        </div>

        <main class="col-md-9 col-lg-10">
            <div class="container-fluid py-4">
                <div class="mb-4">
                    <h1 class="h3 mb-1">Maestros / Afiliados</h1>
                    <p class="text-muted mb-0">Carga directa del archivo CSV a la tabla maestra independiente.</p>
                </div>

                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?= $escapar($tipoMensaje) ?>" role="alert">
                        <?= $escapar($mensaje) ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-info">
                    <strong>Importante:</strong>
                    el archivo debe contener exactamente 17 columnas, incluido el encabezado.
                    El campo <code>IdAfiliados</code> se genera automáticamente y no debe venir en el CSV.
                    El sistema valida el archivo completo antes de reemplazar los datos de <code>m_afiliados</code>.
                    No modifica las tablas operativas <code>afiliados</code> ni <code>maestro_afiliados</code>.
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small">Registros actualmente en m_afiliados</div>
                                <div class="display-6">
                                    <?= $totalActual === null ? '—' : number_format((int) $totalActual) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (is_array($resultado)): ?>
                        <div class="col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="text-muted small">Registros cargados en esta operación</div>
                                    <div class="display-6">
                                        <?= number_format((int) ($resultado['totalCargados'] ?? 0)) ?>
                                    </div>
                                    <div class="small text-muted">
                                        Leídos: <?= number_format((int) ($resultado['totalLeidos'] ?? 0)) ?> ·
                                        Duplicados omitidos: <?= number_format((int) ($resultado['duplicadosOmitidos'] ?? 0)) ?> ·
                                        Advertencias: <?= number_format((int) ($resultado['errores'] ?? 0)) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header">
                        <strong>Seleccionar archivo CSV</strong>
                    </div>
                    <div class="card-body">
                        <form method="POST"
                              action="<?= $escapar(url('maestros/cargar')) ?>"
                              enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="archivo_csv" class="form-label">Archivo CSV</label>
                                <input type="file"
                                       name="archivo_csv"
                                       id="archivo_csv"
                                       class="form-control"
                                       accept=".csv,text/csv"
                                       required>
                                <div class="form-text">
                                    Selecciona el archivo una sola vez. El sistema validará las 17 columnas y realizará la carga directa.
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit"
                                        class="btn btn-primary"
                                        onclick="return confirm('La carga reemplazará los registros actuales de m_afiliados únicamente si el CSV es válido. Las tablas operativas no serán modificadas. ¿Deseas continuar?');">
                                    Cargar CSV
                                </button>

                                <a href="<?= $escapar(url('maestros')) ?>"
                                   class="btn btn-outline-secondary">
                                    Volver
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
