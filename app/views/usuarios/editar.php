<?php

$titulo = 'Editar usuario';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

$permisosAsignados = [];

foreach (($permisosUsuario ?? []) as $permisoUsuario) {
    $permisosAsignados[] = (int) $permisoUsuario['IdPermiso'];
}

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
                            Editar usuario
                        </h1>

                        <p class="text-muted mb-0">
                            Actualización de datos y permisos del usuario
                        </p>

                    </div>

                    <a href="<?= url('usuarios') ?>"
                       class="btn btn-secondary">
                        Volver
                    </a>

                </div>


                <div class="card shadow-sm">

                    <div class="card-body">

                        <form method="POST"
                              action="<?= url('usuarios/actualizar') ?>">

                            <input type="hidden"
                                   name="IdUsuario"
                                   value="<?= (int) ($usuario['IdUsuario'] ?? 0) ?>">


                            <div class="row g-3">

                                <!-- Usuario -->
                                <div class="col-md-6">

                                    <label for="Usuario"
                                           class="form-label">
                                        Usuario *
                                    </label>

                                    <input type="text"
                                           name="Usuario"
                                           id="Usuario"
                                           class="form-control"
                                           maxlength="50"
                                           required
                                           value="<?= e($usuario['Usuario'] ?? '') ?>">

                                </div>


                                <!-- DNI -->
                                <div class="col-md-6">

                                    <label for="DNI"
                                           class="form-label">
                                        DNI *
                                    </label>

                                    <input type="text"
                                           name="DNI"
                                           id="DNI"
                                           class="form-control"
                                           maxlength="8"
                                           pattern="[0-9]{8}"
                                           required
                                           value="<?= e($usuario['DNI'] ?? '') ?>">

                                </div>


                                <!-- Nombres -->
                                <div class="col-md-6">

                                    <label for="Nombres"
                                           class="form-label">
                                        Nombres *
                                    </label>

                                    <input type="text"
                                           name="Nombres"
                                           id="Nombres"
                                           class="form-control"
                                           maxlength="150"
                                           required
                                           value="<?= e($usuario['Nombres'] ?? '') ?>">

                                </div>


                                <!-- Apellidos -->
                                <div class="col-md-6">

                                    <label for="Apellidos"
                                           class="form-label">
                                        Apellidos *
                                    </label>

                                    <input type="text"
                                           name="Apellidos"
                                           id="Apellidos"
                                           class="form-control"
                                           maxlength="150"
                                           required
                                           value="<?= e($usuario['Apellidos'] ?? '') ?>">

                                </div>


                                <!-- Correo -->
                                <div class="col-md-6">

                                    <label for="Correo"
                                           class="form-label">
                                        Correo
                                    </label>

                                    <input type="email"
                                           name="Correo"
                                           id="Correo"
                                           class="form-control"
                                           maxlength="150"
                                           value="<?= e($usuario['Correo'] ?? '') ?>">

                                </div>


                                <!-- Teléfono -->
                                <div class="col-md-6">

                                    <label for="Telefono"
                                           class="form-label">
                                        Teléfono
                                    </label>

                                    <input type="text"
                                           name="Telefono"
                                           id="Telefono"
                                           class="form-control"
                                           maxlength="20"
                                           value="<?= e($usuario['Telefono'] ?? '') ?>">

                                </div>


                                <!-- Contraseña -->
                                <div class="col-md-6">

                                    <label for="Password"
                                           class="form-label">
                                        Nueva contraseña
                                    </label>

                                    <input type="password"
                                           name="Password"
                                           id="Password"
                                           class="form-control"
                                           maxlength="255">

                                    <small class="text-muted">
                                        Dejar vacío para conservar la contraseña actual.
                                    </small>

                                </div>


                                <!-- Estado -->
                                <div class="col-md-6">

                                    <label for="Estado"
                                           class="form-label">
                                        Estado *
                                    </label>

                                    <select name="Estado"
                                            id="Estado"
                                            class="form-select"
                                            required>

                                       <option value="1"
                                            <?= ((int) ($usuario['Estado'] ?? 0) === 1) ? 'selected' : '' ?>>
                                            Activo
                                        </option>

                                        <option value="0"
                                            <?= ((int) ($usuario['Estado'] ?? 0) === 0) ? 'selected' : '' ?>>
                                            Inactivo
                                        </option>

                                    </select>

                                </div>

                            </div>


                            <hr class="my-4">


                            <!-- Permisos -->
                            <h5 class="mb-3">
                                Permisos del usuario
                            </h5>

                            <div class="row g-3">

                                <?php foreach (($permisos ?? []) as $permiso): ?>

                                    <?php
                                    $idPermiso = (int) $permiso['IdPermiso'];
                                    $marcado = in_array(
                                        $idPermiso,
                                        $permisosAsignados,
                                        true
                                    );
                                    ?>

                                    <div class="col-md-4">

                                        <div class="form-check">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="<?= $idPermiso ?>"
                                                id="permiso_<?= $idPermiso ?>"
                                                <?= $marcado ? 'checked' : '' ?>
                                            >

                                            <label
                                                class="form-check-label"
                                                for="permiso_<?= $idPermiso ?>">

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

                            </div>


                            <hr class="my-4">


                            <div class="d-flex justify-content-end gap-2">

                                <a href="<?= url('usuarios') ?>"
                                class="btn btn-secondary">
                                    Cancelar
                                </a>

                                <button type="submit"
                                        class="btn btn-primary">
                                    Guardar cambios
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