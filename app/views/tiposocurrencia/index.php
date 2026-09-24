<?php

$titulo = 'Tipos de Ocurrencia';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

?>

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
                            Tipos de Ocurrencia
                        </h1>

                        <p class="text-muted mb-0">
                            Administración de los tipos de ocurrencia del módulo Ocurrencias CA
                        </p>

                    </div>

                    <a
                        href="<?= url('tiposocurrencia/crear') ?>"
                        class="btn btn-primary"
                    >
                        Nuevo tipo de ocurrencia
                    </a>

                </div>


                <!-- Tabla -->
                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Tipos de ocurrencia registrados
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
                                value="tiposocurrencia"
                            >


                            <div class="col-md-6">

                                <input
                                    type="text"
                                    name="buscar"
                                    class="form-control"
                                    placeholder="Buscar por descripción..."
                                    value="<?= e($buscar ?? '') ?>"
                                >

                            </div>


                            <div class="col-md-3">

                                <select
                                    name="estado"
                                    class="form-select"
                                >

                                    <option
                                        value=""
                                        <?= ($estadoFiltro ?? '') === '' ? 'selected' : '' ?>
                                    >
                                        Todos
                                    </option>

                                    <option
                                        value="1"
                                        <?= ($estadoFiltro ?? '') === '1' ? 'selected' : '' ?>
                                    >
                                        Activos
                                    </option>

                                    <option
                                        value="0"
                                        <?= ($estadoFiltro ?? '') === '0' ? 'selected' : '' ?>
                                    >
                                        Inactivos
                                    </option>

                                </select>

                            </div>


                            <div class="col-auto">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Buscar
                                </button>

                            </div>


                            <div class="col-auto">

                                <a
                                    href="<?= url('tiposocurrencia') ?>"
                                    class="btn btn-secondary"
                                >
                                    Limpiar
                                </a>

                            </div>

                        </form>


                        <!-- Tabla -->
                        <div class="table-responsive">

                            <table class="table table-striped table-hover align-middle">

                                <thead class="table-primary">

                                    <tr>

                                        <th>
                                            ID
                                        </th>

                                        <th>
                                            Descripción
                                        </th>

                                        <th>
                                            Estado
                                        </th>

                                        <th>
                                            Fecha de Registro
                                        </th>

                                        <th class="text-center">
                                            Acciones
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php if (!empty($tipos)): ?>

                                        <?php foreach ($tipos as $tipo): ?>

                                            <tr>

                                                <td>
                                                    <?= e($tipo['IdTipoOcurrencia']) ?>
                                                </td>


                                                <td>
                                                    <?= e($tipo['Descripcion']) ?>
                                                </td>


                                                <td>

                                                    <?php if ((int) $tipo['Estado'] === 1): ?>

                                                        <span class="badge bg-success">
                                                            Activo
                                                        </span>

                                                    <?php else: ?>

                                                        <span class="badge bg-danger">
                                                            Inactivo
                                                        </span>

                                                    <?php endif; ?>

                                                </td>


                                                <td>
                                                    <?= e($tipo['FechaRegistro'] ?? '') ?>
                                                </td>


                                                <td class="text-center">

                                                    <a
                                                        href="<?= url('tiposocurrencia/editar') ?>&id=<?= (int) $tipo['IdTipoOcurrencia'] ?>"
                                                        class="btn btn-sm btn-primary"
                                                    >
                                                        Editar
                                                    </a>


                                                    <?php if ((int) $tipo['Estado'] === 1): ?>

                                                        <form
                                                            method="POST"
                                                            action="<?= url('tiposocurrencia/cambiar-estado') ?>"
                                                            class="d-inline"
                                                            onsubmit="return confirm('¿Está seguro de inactivar este tipo de ocurrencia?');"
                                                        >

                                                            <input
                                                                type="hidden"
                                                                name="IdTipoOcurrencia"
                                                                value="<?= (int) $tipo['IdTipoOcurrencia'] ?>"
                                                            >

                                                            <input
                                                                type="hidden"
                                                                name="Estado"
                                                                value="0"
                                                            >

                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-danger"
                                                            >
                                                                Inactivar
                                                            </button>

                                                        </form>


                                                    <?php else: ?>

                                                        <form
                                                            method="POST"
                                                            action="<?= url('tiposocurrencia/cambiar-estado') ?>"
                                                            class="d-inline"
                                                            onsubmit="return confirm('¿Está seguro de activar este tipo de ocurrencia?');"
                                                        >

                                                            <input
                                                                type="hidden"
                                                                name="IdTipoOcurrencia"
                                                                value="<?= (int) $tipo['IdTipoOcurrencia'] ?>"
                                                            >

                                                            <input
                                                                type="hidden"
                                                                name="Estado"
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
                                                colspan="5"
                                                class="text-center text-muted"
                                            >
                                                No existen tipos de ocurrencia registrados.
                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>


                        <!-- Paginación -->
                        <?php if (($totalPaginas ?? 1) > 1): ?>

                            <nav
                                aria-label="Paginación de tipos de ocurrencia"
                                class="mt-3"
                            >

                                <ul class="pagination justify-content-center">


                                    <!-- Anterior -->
                                    <?php if (($pagina ?? 1) > 1): ?>

                                        <li class="page-item">

                                            <a
                                                class="page-link"
                                                href="index.php?route=tiposocurrencia&buscar=<?= urlencode($buscar ?? '') ?>&estado=<?= urlencode($estadoFiltro ?? '') ?>&pagina=<?= (($pagina ?? 1) - 1) ?>"
                                            >
                                                &laquo; Anterior
                                            </a>

                                        </li>

                                    <?php else: ?>

                                        <li class="page-item disabled">

                                            <span class="page-link">
                                                &laquo; Anterior
                                            </span>

                                        </li>

                                    <?php endif; ?>


                                    <!-- Números -->
                                    <?php for ($i = 1; $i <= ($totalPaginas ?? 1); $i++): ?>

                                        <li
                                            class="page-item <?= $i === ($pagina ?? 1) ? 'active' : '' ?>"
                                        >

                                            <a
                                                class="page-link"
                                                href="index.php?route=tiposocurrencia&buscar=<?= urlencode($buscar ?? '') ?>&estado=<?= urlencode($estadoFiltro ?? '') ?>&pagina=<?= $i ?>"
                                            >
                                                <?= $i ?>
                                            </a>

                                        </li>

                                    <?php endfor; ?>


                                    <!-- Siguiente -->
                                    <?php if (($pagina ?? 1) < ($totalPaginas ?? 1)): ?>

                                        <li class="page-item">

                                            <a
                                                class="page-link"
                                                href="index.php?route=tiposocurrencia&buscar=<?= urlencode($buscar ?? '') ?>&estado=<?= urlencode($estadoFiltro ?? '') ?>&pagina=<?= (($pagina ?? 1) + 1) ?>"
                                            >
                                                Siguiente &raquo;
                                            </a>

                                        </li>

                                    <?php else: ?>

                                        <li class="page-item disabled">

                                            <span class="page-link">
                                                Siguiente &raquo;
                                            </span>

                                        </li>

                                    <?php endif; ?>


                                </ul>

                            </nav>

                        <?php endif; ?>


                        <!-- Resumen -->
                        <div class="text-muted text-center small">

                            Mostrando
                            <?= count($tipos ?? []) ?>
                            de
                            <?= (int) ($totalTipos ?? 0) ?>
                            tipos de ocurrencia

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