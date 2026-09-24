<?php

$titulo = 'Nuevo usuario';

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

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h1 class="h3 mb-1">
                            Nuevo usuario
                        </h1>

                        <p class="text-muted mb-0">
                            Registrar un nuevo usuario en el sistema DRCyS
                        </p>

                    </div>

                    <a href="<?= url('usuarios') ?>"
                       class="btn btn-secondary">

                        Volver

                    </a>

                </div>

                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Datos del usuario
                        </strong>

                    </div>

                    <div class="card-body">

                        <form method="POST"
                              action="<?= url('usuarios/guardar') ?>">

                            <div class="row">

                                <!-- Usuario -->
                                <div class="col-md-6 mb-3">

                                    <label for="Usuario"
                                           class="form-label">
                                        Usuario
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="Usuario"
                                           name="Usuario"
                                           maxlength="50"
                                           required>

                                </div>

                                <!-- Contraseña -->
                                <div class="col-md-6 mb-3">

                                    <label for="Password"
                                           class="form-label">
                                        Contraseña
                                    </label>

                                    <input type="password"
                                           class="form-control"
                                           id="Password"
                                           name="Password"
                                           maxlength="100"
                                           required>

                                </div>

                                <!-- Nombres -->
                                <div class="col-md-6 mb-3">

                                    <label for="Nombres"
                                           class="form-label">
                                        Nombres
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="Nombres"
                                           name="Nombres"
                                           maxlength="150"
                                           required>

                                </div>

                                <!-- Apellidos -->
                                <div class="col-md-6 mb-3">

                                    <label for="Apellidos"
                                           class="form-label">
                                        Apellidos
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="Apellidos"
                                           name="Apellidos"
                                           maxlength="150"
                                           required>

                                </div>

                                <!-- DNI -->
                                <div class="col-md-4 mb-3">

                                    <label for="DNI"
                                           class="form-label">
                                        DNI
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="DNI"
                                           name="DNI"
                                           maxlength="8"
                                           pattern="[0-9]{8}"
                                           required>

                                </div>

                                <!-- Correo -->
                                <div class="col-md-4 mb-3">

                                    <label for="Correo"
                                           class="form-label">
                                        Correo electrónico
                                    </label>

                                    <input type="email"
                                           class="form-control"
                                           id="Correo"
                                           name="Correo"
                                           maxlength="150">

                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-4 mb-3">

                                    <label for="Telefono"
                                           class="form-label">
                                        Teléfono
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="Telefono"
                                           name="Telefono"
                                           maxlength="20">

                                </div>

                            </div>
                            <!-- Permisos por módulo -->
                            <div class="mb-4">

                                <h5 class="mb-3">
                                    Permisos de acceso
                                </h5>

                                <p class="text-muted">
                                    Seleccione los módulos que este usuario podrá utilizar.
                                </p>

                                <div class="row">

                                    <?php if (!empty($permisos)): ?>

                                        <?php foreach ($permisos as $permiso): ?>

                                            <div class="col-md-6 col-lg-4 mb-3">

                                                <div class="form-check">

                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="permisos[]"
                                                        value="<?= e($permiso['IdPermiso']) ?>"
                                                        id="permiso_<?= e($permiso['IdPermiso']) ?>"
                                                    >

                                                    <label
                                                        class="form-check-label"
                                                        for="permiso_<?= e($permiso['IdPermiso']) ?>"
                                                    >

                                                        <strong>
                                                            <?= e($permiso['Nombre']) ?>
                                                        </strong>

                                                        <?php if (!empty($permiso['Descripcion'])): ?>

                                                            <br>

                                                            <small class="text-muted">
                                                                <?= e($permiso['Descripcion']) ?>
                                                            </small>

                                                        <?php endif; ?>

                                                    </label>

                                                </div>

                                            </div>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <div class="col-12">

                                            <div class="alert alert-warning">
                                                No existen permisos activos configurados.
                                            </div>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>
                            <hr>

                            <div class="d-flex justify-content-end gap-2">
                        
                                <a href="<?= url('usuarios') ?>"
                                class="btn btn-secondary">

                                    Cancelar

                                </a>

                                <button type="submit"
                                        class="btn btn-primary">

                                    Guardar usuario

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

<?php

require_once __DIR__ . '/../layouts/footer.php';

?>