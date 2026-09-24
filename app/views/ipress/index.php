<?php

$titulo = 'IPRESS';
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
    .ipress-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .ipress-table {
        min-width: 1100px;
    }

    .ipress-table th,
    .ipress-table td {
        vertical-align: middle;
    }

    .ipress-table td {
        white-space: nowrap;
    }

    .ipress-actions {
        white-space: nowrap;
    }

    .ipress-pagination {
        width: 100%;
    }

    .ipress-pagination .pagination {
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 0;
    }
</style>


<div class="container-fluid">

    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0">

            <?php
            require_once __DIR__ . '/../layouts/sidebar.php';
            ?>

        </div>


        <!-- Contenido principal -->
        <main class="col-md-9 col-lg-10">

            <div class="container-fluid py-4">

                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h1 class="h3 mb-1">
                            IPRESS
                        </h1>

                        <p class="text-muted mb-0">
                            Registro de Instituciones Prestadoras de Servicios de Salud
                        </p>

                    </div>


                    <div>

                        <a
                            href="<?= url('ipress/crear') ?>"
                            class="btn btn-primary"
                        >
                            + Crear IPRESS
                        </a>

                    </div>

                </div>


                <!-- Filtros -->
                <div class="card shadow-sm mb-4">

                    <div class="card-body">

                        <form
                            method="GET"
                            action="/DRCYS/public/index.php"
                            class="row g-2 mb-3"
                        >

                            <input
                                type="hidden"
                                name="route"
                                value="ipress"
                            >


                            <div class="col-md-5">

                                <label
                                    for="busqueda"
                                    class="form-label"
                                >
                                    Buscar IPRESS
                                </label>

                                <input
                                    type="text"
                                    name="busqueda"
                                    id="busqueda"
                                    class="form-control"
                                    placeholder="Nombre de IPRESS o RENAES"
                                    value="<?= e($busqueda ?? '') ?>"
                                >

                            </div>


                            <div class="col-md-3">

                                <label
                                    for="estado"
                                    class="form-label"
                                >
                                    Estado
                                </label>

                                <select
                                    name="estado"
                                    id="estado"
                                    class="form-select"
                                >

                                    <option
                                        value="1"
                                        <?= (($estado ?? '1') === '1') ? 'selected' : '' ?>
                                    >
                                        Activos
                                    </option>


                                    <option
                                        value="0"
                                        <?= (($estado ?? '1') === '0') ? 'selected' : '' ?>
                                    >
                                        Inactivos
                                    </option>


                                    <option
                                        value="todos"
                                        <?= (($estado ?? '1') === 'todos') ? 'selected' : '' ?>
                                    >
                                        Todos
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-4 d-flex align-items-end gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Buscar
                                </button>


                                <a
                                    href="<?= url('ipress') ?>"
                                    class="btn btn-secondary"
                                >
                                    Limpiar
                                </a>


                                <a
                                    href="<?= url('ipress/exportar') ?>&busqueda=<?= urlencode($busqueda ?? '') ?>"
                                    class="btn btn-success"
                                >
                                    Excel
                                </a>

                            </div>

                        </form>

                    </div>

                </div>


                <!-- Tabla -->
                <div class="card shadow-sm">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <strong>

                            <?php

                            if (($estado ?? '1') === '1') {

                                echo 'IPRESS activas';

                            } elseif (($estado ?? '1') === '0') {

                                echo 'IPRESS inactivas';

                            } else {

                                echo 'Todas las IPRESS';

                            }

                            ?>

                        </strong>


                        <span class="badge bg-primary">

                            <?= number_format($total) ?>

                            registros

                        </span>

                    </div>


                    <div class="card-body">

                        <div class="ipress-table-wrapper">

                            <table class="table table-striped table-hover align-middle ipress-table">

                                <thead class="table-primary">

                                    <tr>

                                        <th>RENAES</th>
                                        <th>IPRESS</th>
                                        <th>Tipo</th>
                                        <th>Nivel</th>
                                        <th>UE</th>
                                        <th>Red</th>
                                        <th>Microred</th>
                                        <th>Provincia</th>
                                        <th>Distrito</th>
                                        <th>Estado</th>
                                        <th class="text-center">
                                            Acciones
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php if (!empty($ipress)): ?>

                                        <?php foreach ($ipress as $item): ?>

                                            <tr>

                                                <td>
                                                    <?= e($item['RENAES']) ?>
                                                </td>

                                                <td>
                                                    <?= e($item['IPRESS_ATENC']) ?>
                                                </td>

                                                <td>
                                                    <?= e($item['TIPO']) ?>
                                                </td>

                                                <td>
                                                    <?= e($item['NIVEL']) ?>
                                                </td>

                                                <td>
                                                    <?= e($item['UE']) ?>
                                                </td>

                                                <td>
                                                    <?= e($item['RED']) ?>
                                                </td>

                                                <td>
                                                    <?= e($item['MICRORED']) ?>
                                                </td>

                                                <td>
                                                    <?= e($item['PROVINCIA']) ?>
                                                </td>

                                                <td>
                                                    <?= e($item['DISTRITO']) ?>
                                                </td>

                                                <td>

                                                    <?php if ((int) $item['ESTADO'] === 1): ?>

                                                        <span class="badge bg-success">
                                                            ACTIVO
                                                        </span>

                                                    <?php else: ?>

                                                        <span class="badge bg-secondary">
                                                            INACTIVO
                                                        </span>

                                                    <?php endif; ?>

                                                </td>


                                                <td class="text-center ipress-actions">

                                                    <a
                                                        href="<?= url('ipress/detalle') ?>&id=<?= (int) $item['IdEESS'] ?>"
                                                        class="btn btn-sm btn-info"
                                                    >
                                                        Ver
                                                    </a>


                                                    <a
                                                        href="<?= url('ipress/editar') ?>&id=<?= (int) $item['IdEESS'] ?>"
                                                        class="btn btn-sm btn-primary"
                                                    >
                                                        Editar
                                                    </a>


                                                    <?php if ((int) $item['ESTADO'] === 1): ?>

                                                        <form
                                                            method="POST"
                                                            action="<?= url('ipress/cambiar-estado') ?>"
                                                            class="d-inline"
                                                            onsubmit="return confirm('¿Está seguro de inactivar esta IPRESS?');"
                                                        >

                                                            <input
                                                                type="hidden"
                                                                name="IdEESS"
                                                                value="<?= (int) $item['IdEESS'] ?>"
                                                            >

                                                            <input
                                                                type="hidden"
                                                                name="ESTADO"
                                                                value="0"
                                                            >

                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-warning"
                                                            >
                                                                Inactivar
                                                            </button>

                                                        </form>

                                                    <?php else: ?>

                                                        <form
                                                            method="POST"
                                                            action="<?= url('ipress/cambiar-estado') ?>"
                                                            class="d-inline"
                                                            onsubmit="return confirm('¿Está seguro de activar esta IPRESS?');"
                                                        >

                                                            <input
                                                                type="hidden"
                                                                name="IdEESS"
                                                                value="<?= (int) $item['IdEESS'] ?>"
                                                            >

                                                            <input
                                                                type="hidden"
                                                                name="ESTADO"
                                                                value="1"
                                                            >

                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-success"
                                                            >
                                                                Activar
                                                            </button>

                                                        </form>

                                                    <?php endif; ?>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>

                                            <td
                                                colspan="11"
                                                class="text-center text-muted py-4"
                                            >

                                                <?php

                                                if (($estado ?? '1') === '1') {

                                                    echo 'No se encontraron IPRESS activas.';

                                                } elseif (($estado ?? '1') === '0') {

                                                    echo 'No se encontraron IPRESS inactivas.';

                                                } else {

                                                    echo 'No se encontraron IPRESS.';

                                                }

                                                ?>

                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>


                        <!-- Información de registros -->
                        <?php

                        $inicio = $total > 0
                            ? (($pagina - 1) * $porPagina) + 1
                            : 0;

                        $fin = min(
                            $pagina * $porPagina,
                            $total
                        );

                        $paginaActual = max(
                            1,
                            (int) $pagina
                        );

                        $totalPaginasActual = max(
                            1,
                            (int) $totalPaginas
                        );

                        $paginasMostrar = [];

                        if ($totalPaginasActual <= 7) {

                            for (
                                $i = 1;
                                $i <= $totalPaginasActual;
                                $i++
                            ) {
                                $paginasMostrar[] = $i;
                            }

                        } else {

                            $paginasMostrar[] = 1;

                            $inicioRango = max(
                                2,
                                $paginaActual - 2
                            );

                            $finRango = min(
                                $totalPaginasActual - 1,
                                $paginaActual + 2
                            );

                            if ($inicioRango > 2) {
                                $paginasMostrar[] = '...';
                            }

                            for (
                                $i = $inicioRango;
                                $i <= $finRango;
                                $i++
                            ) {
                                $paginasMostrar[] = $i;
                            }

                            if (
                                $finRango <
                                $totalPaginasActual - 1
                            ) {
                                $paginasMostrar[] = '...';
                            }

                            $paginasMostrar[] =
                                $totalPaginasActual;
                        }

                        ?>

                        <!-- Paginación -->
                        <?php if ($totalPaginasActual > 1): ?>

                            <nav
                                aria-label="Paginación de IPRESS"
                                class="mt-3 ipress-pagination"
                            >

                                <ul class="pagination justify-content-center">

                                    <!-- Anterior -->
                                    <li
                                        class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>"
                                    >

                                        <?php if ($paginaActual > 1): ?>

                                            <a
                                                class="page-link"
                                                href="<?= url('ipress') ?>&pagina=<?= $paginaActual - 1 ?>&porPagina=<?= $porPagina ?>&busqueda=<?= urlencode($busqueda) ?>&estado=<?= urlencode($estado) ?>"
                                            >
                                                &laquo; Anterior
                                            </a>

                                        <?php else: ?>

                                            <span class="page-link">
                                                &laquo; Anterior
                                            </span>

                                        <?php endif; ?>

                                    </li>


                                    <!-- Números de página -->
                                    <?php foreach ($paginasMostrar as $numeroPagina): ?>

                                        <?php if ($numeroPagina === '...'): ?>

                                            <li class="page-item disabled">

                                                <span class="page-link">
                                                    ...
                                                </span>

                                            </li>

                                        <?php else: ?>

                                            <li
                                                class="page-item <?= $numeroPagina === $paginaActual ? 'active' : '' ?>"
                                            >

                                                <a
                                                    class="page-link"
                                                    href="<?= url('ipress') ?>&pagina=<?= $numeroPagina ?>&porPagina=<?= $porPagina ?>&busqueda=<?= urlencode($busqueda) ?>&estado=<?= urlencode($estado) ?>"
                                                >
                                                    <?= $numeroPagina ?>
                                                </a>

                                            </li>

                                        <?php endif; ?>

                                    <?php endforeach; ?>


                                    <!-- Siguiente -->
                                    <li
                                        class="page-item <?= $paginaActual >= $totalPaginasActual ? 'disabled' : '' ?>"
                                    >

                                        <?php if ($paginaActual < $totalPaginasActual): ?>

                                            <a
                                                class="page-link"
                                                href="<?= url('ipress') ?>&pagina=<?= $paginaActual + 1 ?>&porPagina=<?= $porPagina ?>&busqueda=<?= urlencode($busqueda) ?>&estado=<?= urlencode($estado) ?>"
                                            >
                                                Siguiente &raquo;
                                            </a>

                                        <?php else: ?>

                                            <span class="page-link">
                                                Siguiente &raquo;
                                            </span>

                                        <?php endif; ?>

                                    </li>

                                </ul>

                            </nav>

                        <?php endif; ?>


                        <!-- Resumen -->
                        <div class="text-muted text-center small mt-2">

                            Mostrando
                            <?= $inicio ?>
                            -
                            <?= $fin ?>
                            de
                            <?= number_format($total) ?>
                            IPRESS

                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>


<?php

require_once __DIR__ . '/../layouts/footer.php';

?>