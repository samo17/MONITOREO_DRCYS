<?php

$titulo = 'Usuarios';

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
                            Gestión de Usuarios
                        </h1>

                        <p class="text-muted mb-0">
                            Administración de usuarios del sistema DRCyS
                        </p>

                    </div>

                   <a href="<?= url('usuarios/crear') ?>"
   class="btn btn-primary">

    Nuevo usuario

</a>

                </div>

                <!-- Tabla -->
                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Usuarios registrados
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <form method="GET" action="index.php" class="row g-2 mb-3">

                                <input type="hidden"
                                    name="route"
                                    value="usuarios">

                                <div class="col-md-6">

                                    <input type="text"
                                        name="buscar"
                                        class="form-control"
                                        placeholder="Buscar por usuario, nombres, apellidos o DNI..."
                                        value="<?= e($buscar ?? '') ?>">

                                </div>

                                <div class="col-md-3">

                                    <select name="estado"
                                            class="form-select">

                                        <option value=""
                                            <?= ($estadoFiltro ?? '') === '' ? 'selected' : '' ?>>
                                            Todos
                                        </option>

                                        <option value="1"
                                            <?= ($estadoFiltro ?? '') === '1' ? 'selected' : '' ?>>
                                            Activos
                                        </option>

                                        <option value="0"
                                            <?= ($estadoFiltro ?? '') === '0' ? 'selected' : '' ?>>
                                            Inactivos
                                        </option>

                                    </select>

                                </div>

                                <div class="col-auto">

                                    <button type="submit"
                                            class="btn btn-primary">
                                        Buscar
                                    </button>

                                </div>

                                <div class="col-auto">

                                    <a href="<?= url('usuarios') ?>"
                                    class="btn btn-secondary">
                                        Limpiar
                                    </a>

                                </div>

                            </form>

                            <table class="table table-striped table-hover align-middle">

                                <thead class="table-primary">

                                    <tr>

                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>DNI</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                        <th>Último acceso</th>
                                        <th class="text-center">Acciones</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php if (!empty($usuarios)): ?>

                                        <?php foreach ($usuarios as $usuario): ?>

                                            <tr>

                                                <td>
                                                    <?= e($usuario['IdUsuario']) ?>
                                                </td>

                                                <td>
                                                    <?= e($usuario['Usuario']) ?>
                                                </td>

                                                <td>
                                                    <?= e($usuario['Nombres']) ?>
                                                </td>

                                                <td>
                                                    <?= e($usuario['Apellidos']) ?>
                                                </td>

                                                <td>
                                                    <?= e($usuario['DNI']) ?>
                                                </td>

                                                <td>
                                                    <?= e($usuario['Correo']) ?>
                                                </td>

                                                <td>
                                                    <?= e($usuario['Telefono']) ?>
                                                </td>

                                                <td>

                                                    <?php if ((int) $usuario['Estado'] === 1): ?>

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
                                                    <?= e($usuario['UltimoAcceso'] ?? '') ?>
                                                </td>

                                                <td class="text-center">

                                                    <a href="<?= url('usuarios/editar') ?>&id=<?= (int) $usuario['IdUsuario'] ?>"
                                                    class="btn btn-sm btn-primary">
                                                        Editar
                                                    </a>

                                                    <?php if ((int) $usuario['Estado'] === 1): ?>

                                                        <form method="POST"
                                                            action="<?= url('usuarios/cambiar-estado') ?>"
                                                            class="d-inline"
                                                            onsubmit="return confirm('¿Está seguro de inactivar este usuario?');">

                                                            <input type="hidden"
                                                                name="IdUsuario"
                                                                value="<?= (int) $usuario['IdUsuario'] ?>">

                                                            <input type="hidden"
                                                                name="Estado"
                                                                value="0">

                                                            <button type="submit"
                                                                    class="btn btn-sm btn-danger">
                                                                Inactivar
                                                            </button>

                                                        </form>

                                                    <?php else: ?>

                                                        <form method="POST"
                                                            action="<?= url('usuarios/cambiar-estado') ?>"
                                                            class="d-inline"
                                                            onsubmit="return confirm('¿Está seguro de activar este usuario?');">

                                                            <input type="hidden"
                                                                name="IdUsuario"
                                                                value="<?= (int) $usuario['IdUsuario'] ?>">

                                                            <input type="hidden"
                                                                name="Estado"
                                                                value="1">

                                                            <button type="submit"
                                                                    class="btn btn-sm btn-success">
                                                                Activar
                                                            </button>

                                                        </form>

                                                    <?php endif; ?>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>

                                            <td colspan="10"
                                                class="text-center text-muted">

                                                No existen usuarios registrados.

                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                            <?php
                            $paginaActual = max(1, (int) ($pagina ?? 1));
                            $totalPaginasActual = max(1, (int) ($totalPaginas ?? 1));

                            $paginasMostrar = [];

                            if ($totalPaginasActual <= 7) {
                                $paginasMostrar = range(1, $totalPaginasActual);
                            } else {
                                $paginasMostrar[] = 1;

                                if ($paginaActual > 4) {
                                    $paginasMostrar[] = '...';
                                }

                                $inicio = max(2, $paginaActual - 1);
                                $fin = min($totalPaginasActual - 1, $paginaActual + 1);

                                for ($i = $inicio; $i <= $fin; $i++) {
                                    $paginasMostrar[] = $i;
                                }

                                if ($paginaActual < $totalPaginasActual - 3) {
                                    $paginasMostrar[] = '...';
                                }

                                $paginasMostrar[] = $totalPaginasActual;
                            }
                            ?>

                            <?php if ($totalPaginasActual > 1): ?>
                                <nav aria-label="Paginación de usuarios" class="mt-3">
                                    <ul class="pagination justify-content-center">

                                        <?php if ($paginaActual > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                href="index.php?route=usuarios&buscar=<?= urlencode($buscar ?? '') ?>&estado=<?= urlencode($estadoFiltro ?? '') ?>&pagina=<?= $paginaActual - 1 ?>">
                                                    &laquo; Anterior
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">&laquo; Anterior</span>
                                            </li>
                                        <?php endif; ?>

                                        <?php foreach ($paginasMostrar as $numero): ?>
                                            <?php if ($numero === '...'): ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">…</span>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item <?= $numero === $paginaActual ? 'active' : '' ?>">
                                                    <a class="page-link"
                                                    href="index.php?route=usuarios&buscar=<?= urlencode($buscar ?? '') ?>&estado=<?= urlencode($estadoFiltro ?? '') ?>&pagina=<?= $numero ?>">
                                                        <?= $numero ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>

                                        <?php if ($paginaActual < $totalPaginasActual): ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                href="index.php?route=usuarios&buscar=<?= urlencode($buscar ?? '') ?>&estado=<?= urlencode($estadoFiltro ?? '') ?>&pagina=<?= $paginaActual + 1 ?>">
                                                    Siguiente &raquo;
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">Siguiente &raquo;</span>
                                            </li>
                                        <?php endif; ?>

                                    </ul>
                                </nav>
                            <?php endif; ?>

                            <div class="text-muted text-center small">
                                Mostrando <?= count($usuarios ?? []) ?> de <?= (int) ($totalUsuarios ?? 0) ?> usuarios
                            </div>

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