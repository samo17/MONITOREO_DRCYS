<?php
$titulo = 'IPRESS Consulta';
$busqueda = $busqueda ?? '';
$pagina = $pagina ?? 1;
$porPagina = $porPagina ?? 10;
$total = $total ?? 0;
$totalPaginas = $totalPaginas ?? 1;
$estado = $estado ?? '1';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
    .ipress-consulta-table-wrapper { width: 100%; overflow-x: auto; }
    .ipress-consulta-table { min-width: 1050px; }
    .ipress-consulta-table th, .ipress-consulta-table td { vertical-align: middle; }
    .ipress-consulta-table td { white-space: nowrap; }
    .ipress-consulta-pagination { width: 100%; }
    .ipress-consulta-pagination .pagination {
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 0;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0">
            <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
        </div>

        <main class="col-md-9 col-lg-10">
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1">IPRESS Consulta</h1>
                        <p class="text-muted mb-0">Consulta de establecimientos de salud. Módulo de solo lectura.</p>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="GET" action="/DRCYS/public/index.php" class="row g-3 mb-3">
    <input type="hidden" name="route" value="ipress_consulta">
    <div class="col-md-3">
        <label for="codUE" class="form-label">Unidad Ejecutora</label>
        <select name="codUE" id="codUE" class="form-select" onchange="this.form.red.value=''; this.form.microred.value=''; this.form.submit()">
            <option value="">Todas</option>
            <?php foreach (($unidadesEjecutoras ?? []) as $ueItem): ?>
                <option value="<?= e($ueItem['COD_UE']) ?>" <?= ($codUE ?? '') === (string)$ueItem['COD_UE'] ? 'selected' : '' ?>><?= e($ueItem['UE']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label for="red" class="form-label">Red</label>
        <select name="red" id="red" class="form-select" <?= empty($codUE) ? 'disabled' : '' ?> onchange="this.form.microred.value=''; this.form.submit()">
            <option value="">Todas</option>
            <?php foreach (($redes ?? []) as $redItem): ?>
                <option value="<?= e($redItem['RED']) ?>" <?= ($red ?? '') === (string)$redItem['RED'] ? 'selected' : '' ?>><?= e($redItem['RED']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label for="microred" class="form-label">Microred</label>
        <select name="microred" id="microred" class="form-select" <?= empty($red) ? 'disabled' : '' ?> onchange="this.form.submit()">
            <option value="">Todas</option>
            <?php foreach (($microredes ?? []) as $microItem): ?>
                <option value="<?= e($microItem['MICRORED']) ?>" <?= ($microred ?? '') === (string)$microItem['MICRORED'] ? 'selected' : '' ?>><?= e($microItem['MICRORED']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label for="busqueda" class="form-label">IPRESS</label>
        <input type="text" name="busqueda" id="busqueda" class="form-control" placeholder="Buscar por RENAES o nombre" value="<?= e($busqueda ?? '') ?>">
    </div>
    <div class="col-md-3">
        <label for="estado" class="form-label">Estado</label>
        <select name="estado" id="estado" class="form-select">
            <option value="1" <?= ($estado ?? '1') === '1' ? 'selected' : '' ?>>Activos</option>
            <option value="0" <?= ($estado ?? '1') === '0' ? 'selected' : '' ?>>Inactivos</option>
            <option value="todos" <?= ($estado ?? '1') === 'todos' ? 'selected' : '' ?>>Todos</option>
        </select>
    </div>
    <div class="col-md-9 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="<?= url('ipress_consulta') ?>" class="btn btn-secondary">Limpiar</a>
        <a href="<?= url('ipress_consulta/exportar') ?>&busqueda=<?= urlencode($busqueda ?? '') ?>&estado=<?= urlencode($estado ?? '1') ?>&codUE=<?= urlencode($codUE ?? '') ?>&red=<?= urlencode($red ?? '') ?>&microred=<?= urlencode($microred ?? '') ?>" class="btn btn-success">Excel</a>
    </div>
</form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>
                            <?php if ($estado === '1') echo 'IPRESS activas';
                            elseif ($estado === '0') echo 'IPRESS inactivas';
                            else echo 'Todas las IPRESS'; ?>
                        </strong>
                        <span class="badge bg-primary"><?= number_format($total) ?> registros</span>
                    </div>
                    <div class="card-body">
                        <div class="ipress-consulta-table-wrapper">
                            <table class="table table-striped table-hover align-middle ipress-consulta-table">
                                <thead class="table-primary">
                                    <tr>
                                        <th>RENAES</th><th>IPRESS</th><th>Tipo</th><th>Nivel</th>
                                        <th>UE</th><th>Red</th><th>Microred</th><th>Provincia</th>
                                        <th>Distrito</th><th>Estado</th><th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($ipress)): ?>
                                    <?php foreach ($ipress as $item): ?>
                                        <tr>
                                            <td><?= e($item['RENAES']) ?></td>
                                            <td><?= e($item['IPRESS_ATENC']) ?></td>
                                            <td><?= e($item['TIPO']) ?></td>
                                            <td><?= e($item['NIVEL']) ?></td>
                                            <td><?= e($item['UE']) ?></td>
                                            <td><?= e($item['RED']) ?></td>
                                            <td><?= e($item['MICRORED']) ?></td>
                                            <td><?= e($item['PROVINCIA']) ?></td>
                                            <td><?= e($item['DISTRITO']) ?></td>
                                            <td>
                                                <?php if ((int) $item['ESTADO'] === 1): ?>
                                                    <span class="badge bg-success">ACTIVO</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">INACTIVO</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= url('ipress_consulta/detalle') ?>&id=<?= (int) $item['IdEESS'] ?>"
                                                   class="btn btn-sm btn-info">Ver detalle</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="11" class="text-center text-muted py-4">No se encontraron IPRESS con los criterios seleccionados.</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($totalPaginas > 1): ?>
                            <?php
                                $paginaActual = max(1, (int) $pagina);
                                $totalPaginasActual = max(1, (int) $totalPaginas);
                                $paginasMostrar = [];

                                if ($totalPaginasActual <= 7) {
                                    for ($i = 1; $i <= $totalPaginasActual; $i++) {
                                        $paginasMostrar[] = $i;
                                    }
                                } else {
                                    $paginasMostrar[] = 1;
                                    $inicioRango = max(2, $paginaActual - 2);
                                    $finRango = min($totalPaginasActual - 1, $paginaActual + 2);

                                    if ($inicioRango > 2) {
                                        $paginasMostrar[] = '...';
                                    }

                                    for ($i = $inicioRango; $i <= $finRango; $i++) {
                                        $paginasMostrar[] = $i;
                                    }

                                    if ($finRango < $totalPaginasActual - 1) {
                                        $paginasMostrar[] = '...';
                                    }

                                    $paginasMostrar[] = $totalPaginasActual;
                                }

                                $urlPaginacion = url('ipress_consulta')
                                    . '&porPagina=' . $porPagina
                                    . '&busqueda=' . urlencode($busqueda)
                                    . '&estado=' . urlencode($estado)
                                    . '&codUE=' . urlencode($codUE)
                                    . '&red=' . urlencode($red)
                                    . '&microred=' . urlencode($microred);
                            ?>

                            <nav aria-label="Paginación de IPRESS Consulta"
                                 class="mt-3 ipress-consulta-pagination">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                                        <?php if ($paginaActual > 1): ?>
                                            <a class="page-link" href="<?= $urlPaginacion ?>&pagina=<?= $paginaActual - 1 ?>">
                                                &laquo; Anterior
                                            </a>
                                        <?php else: ?>
                                            <span class="page-link">&laquo; Anterior</span>
                                        <?php endif; ?>
                                    </li>

                                    <?php foreach ($paginasMostrar as $numeroPagina): ?>
                                        <?php if ($numeroPagina === '...'): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item <?= $numeroPagina === $paginaActual ? 'active' : '' ?>">
                                                <a class="page-link" href="<?= $urlPaginacion ?>&pagina=<?= $numeroPagina ?>">
                                                    <?= $numeroPagina ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                    <li class="page-item <?= $paginaActual >= $totalPaginasActual ? 'disabled' : '' ?>">
                                        <?php if ($paginaActual < $totalPaginasActual): ?>
                                            <a class="page-link" href="<?= $urlPaginacion ?>&pagina=<?= $paginaActual + 1 ?>">
                                                Siguiente &raquo;
                                            </a>
                                        <?php else: ?>
                                            <span class="page-link">Siguiente &raquo;</span>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
