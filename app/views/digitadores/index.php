<?php

$titulo = 'Digitadores';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

?>

<style>
    .digitadores-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .digitadores-table {
        min-width: 1100px;
    }

    .digitadores-table th,
    .digitadores-table td {
        vertical-align: middle;
    }

    .digitadores-table td {
        white-space: nowrap;
    }

    .digitadores-actions {
        white-space: nowrap;
    }

    .digitadores-pagination {
        width: 100%;
    }

    .digitadores-pagination .pagination {
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
                            Gestión de Digitadores
                        </h1>

                        <p class="text-muted mb-0">
                            Administración de digitadores del sistema DRCyS
                        </p>

                    </div>


                    <div class="d-flex gap-2">

                        <a
                            href="<?= url('digitadores/exportar') ?>&buscar=<?= urlencode($buscar ?? '') ?>&condicion=<?= urlencode($condicion ?? '') ?>&tipo=<?= urlencode($tipo ?? '') ?>&grupo=<?= urlencode($grupo ?? '') ?>"
                            class="btn btn-success"
                        >
                            Exportar Excel
                        </a>


                        <a
                            href="<?= url('digitadores/crear') ?>"
                            class="btn btn-primary"
                        >
                            Nuevo digitador
                        </a>

                    </div>

                </div>


                <!-- Tabla -->
                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Digitadores registrados
                        </strong>

                    </div>


                    <div class="card-body">

                        <!-- Filtros -->
                        <form
                            method="GET"
                            action="index.php"
                            class="row g-2 mb-3"
                        >

                            <input
                                type="hidden"
                                name="route"
                                value="digitadores"
                            >


                            <!-- Buscar -->
                            <div class="col-md-4">

                                <input
                                    type="text"
                                    name="buscar"
                                    class="form-control"
                                    placeholder="Buscar por nombres o DNI..."
                                    value="<?= e($buscar ?? '') ?>"
                                >

                            </div>


                            <!-- Condición -->
                            <div class="col-md-2">

                                <select
                                    name="condicion"
                                    class="form-select"
                                >

                                    <option value="">
                                        Todas las condiciones
                                    </option>

                                    <?php foreach (($condiciones ?? []) as $item): ?>

                                        <option
                                            value="<?= e($item) ?>"
                                            <?= ($condicion ?? '') === $item ? 'selected' : '' ?>
                                        >
                                            <?= e($item) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <!-- Tipo -->
                            <div class="col-md-2">

                                <select
                                    name="tipo"
                                    class="form-select"
                                >

                                    <option value="">
                                        Todos los tipos
                                    </option>

                                    <?php foreach (($tipos ?? []) as $item): ?>

                                        <option
                                            value="<?= e($item) ?>"
                                            <?= ($tipo ?? '') === $item ? 'selected' : '' ?>
                                        >
                                            <?= e($item) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <!-- Grupo -->
                            <div class="col-md-2">

                                <select
                                    name="grupo"
                                    class="form-select"
                                >

                                    <option value="">
                                        Todos los grupos
                                    </option>

                                    <?php foreach (($grupos ?? []) as $item): ?>

                                        <option
                                            value="<?= e($item) ?>"
                                            <?= ($grupo ?? '') === $item ? 'selected' : '' ?>
                                        >
                                            <?= e($item) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <!-- Buscar -->
                            <div class="col-auto">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Buscar
                                </button>

                            </div>


                            <!-- Limpiar -->
                            <div class="col-auto">

                                <a
                                    href="<?= url('digitadores') ?>"
                                    class="btn btn-secondary"
                                >
                                    Limpiar
                                </a>

                            </div>

                        </form>


                        <!-- Tabla -->
                        <div class="digitadores-table-wrapper">

                            <table class="table table-striped table-hover align-middle digitadores-table">

                                <thead class="table-primary">

                                    <tr>

                                        <th>ID</th>

                                        <th>Nombres</th>

                                        <th>DNI</th>

                                        <th>Fecha nacimiento</th>

                                        <th>Condición</th>

                                        <th>Fecha ingreso</th>

                                        <th>Teléfono</th>

                                        <th>Correo</th>

                                        <th>Tipo</th>

                                        <th>Grupo</th>

                                        <th>Meta diaria</th>

                                        <th class="text-center">
                                            Acciones
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php if (!empty($digitadores)): ?>

                                        <?php foreach ($digitadores as $digitador): ?>

                                            <tr>

                                                <td>
                                                    <?= (int) ($digitador['IdDIGITADOR'] ?? 0) ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['NOMBRES'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['DNI'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['FECHA_NACIMIENTO'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['CONDICION'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['FECHA_INGRESO'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['TELEFONO'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['CORREO'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['TIPO'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['GRUPO'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($digitador['META_DIARIA'] ?? '') ?>
                                                </td>


                                                <!-- Acciones -->
                                                <td class="text-center digitadores-actions">

                                                    <a
                                                        href="<?= url('digitadores/detalle') ?>&id=<?= (int) ($digitador['IdDIGITADOR'] ?? 0) ?>"
                                                        class="btn btn-sm btn-info"
                                                    >
                                                        Ver
                                                    </a>


                                                    <a
                                                        href="<?= url('digitadores/editar') ?>&id=<?= (int) ($digitador['IdDIGITADOR'] ?? 0) ?>"
                                                        class="btn btn-sm btn-primary"
                                                    >
                                                        Editar
                                                    </a>


                                                    <form
                                                        method="POST"
                                                        action="<?= url('digitadores/eliminar') ?>"
                                                        class="d-inline"
                                                        onsubmit="return confirm('¿Está seguro de eliminar este digitador?');"
                                                    >

                                                        <input
                                                            type="hidden"
                                                            name="IdDIGITADOR"
                                                            value="<?= (int) ($digitador['IdDIGITADOR'] ?? 0) ?>"
                                                        >


                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-danger"
                                                        >
                                                            Eliminar
                                                        </button>

                                                    </form>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>

                                            <td
                                                colspan="12"
                                                class="text-center text-muted"
                                            >
                                                No existen digitadores registrados.
                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>


                        <!-- Paginación -->
                        <?php
                        $paginaActual = max(1, (int) ($pagina ?? 1));
                        $totalPaginasActual = max(1, (int) ($totalPaginas ?? 1));

                        $paginasMostrar = [];

                        if ($totalPaginasActual <= 7) {
                            for ($i = 1; $i <= $totalPaginasActual; $i++) {
                                $paginasMostrar[] = $i;
                            }
                        } else {
                            $paginasMostrar[] = 1;
                            $inicio = max(2, $paginaActual - 2);
                            $fin = min($totalPaginasActual - 1, $paginaActual + 2);

                            if ($inicio > 2) {
                                $paginasMostrar[] = '...';
                            }

                            for ($i = $inicio; $i <= $fin; $i++) {
                                $paginasMostrar[] = $i;
                            }

                            if ($fin < $totalPaginasActual - 1) {
                                $paginasMostrar[] = '...';
                            }

                            $paginasMostrar[] = $totalPaginasActual;
                        }
                        ?>

                        <?php if ($totalPaginasActual > 1): ?>

                            <nav
                                aria-label="Paginación de digitadores"
                                class="mt-3 digitadores-pagination"
                            >

                                <ul class="pagination justify-content-center">

                                    <!-- Anterior -->
                                    <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">

                                        <?php if ($paginaActual > 1): ?>

                                            <a
                                                class="page-link"
                                                href="index.php?route=digitadores&buscar=<?= urlencode($buscar ?? '') ?>&condicion=<?= urlencode($condicion ?? '') ?>&tipo=<?= urlencode($tipo ?? '') ?>&grupo=<?= urlencode($grupo ?? '') ?>&pagina=<?= $paginaActual - 1 ?>"
                                            >
                                                &laquo; Anterior
                                            </a>

                                        <?php else: ?>

                                            <span class="page-link">
                                                &laquo; Anterior
                                            </span>

                                        <?php endif; ?>

                                    </li>

                                    <!-- Números -->
                                    <?php foreach ($paginasMostrar as $numeroPagina): ?>

                                        <?php if ($numeroPagina === '...'): ?>

                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>

                                        <?php else: ?>

                                            <li class="page-item <?= $numeroPagina === $paginaActual ? 'active' : '' ?>">
                                                <a
                                                    class="page-link"
                                                    href="index.php?route=digitadores&buscar=<?= urlencode($buscar ?? '') ?>&condicion=<?= urlencode($condicion ?? '') ?>&tipo=<?= urlencode($tipo ?? '') ?>&grupo=<?= urlencode($grupo ?? '') ?>&pagina=<?= $numeroPagina ?>"
                                                >
                                                    <?= $numeroPagina ?>
                                                </a>
                                            </li>

                                        <?php endif; ?>

                                    <?php endforeach; ?>

                                    <!-- Siguiente -->
                                    <li class="page-item <?= $paginaActual >= $totalPaginasActual ? 'disabled' : '' ?>">

                                        <?php if ($paginaActual < $totalPaginasActual): ?>

                                            <a
                                                class="page-link"
                                                href="index.php?route=digitadores&buscar=<?= urlencode($buscar ?? '') ?>&condicion=<?= urlencode($condicion ?? '') ?>&tipo=<?= urlencode($tipo ?? '') ?>&grupo=<?= urlencode($grupo ?? '') ?>&pagina=<?= $paginaActual + 1 ?>"
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
                        <div class="text-muted text-center small">

                            Mostrando
                            <?= count($digitadores ?? []) ?>
                            de
                            <?= (int) ($totalDigitadores ?? 0) ?>
                            digitadores

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