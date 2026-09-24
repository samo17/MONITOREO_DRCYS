<?php

$titulo = 'Nuevo digitador';

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
                        <h1 class="h3 mb-1">Nuevo digitador</h1>
                        <p class="text-muted mb-0">
                            Registro de un nuevo digitador en el sistema DRCyS
                        </p>
                    </div>

                    <div>
                        <a href="<?= url('digitadores') ?>" class="btn btn-secondary">
                            Volver
                        </a>
                    </div>

                </div>

                <!-- Formulario -->
                <div class="card shadow-sm">

                    <div class="card-header">
                        <strong>Datos del digitador<br><small class="text-muted">Los datos de texto se registran en MAYÚSCULAS; el correo se registra en minúsculas.</small></strong>
                    </div>

                    <div class="card-body">

                        <form method="POST" action="<?= url('digitadores/guardar') ?>">

                            <!-- DATOS PERSONALES -->
                            <div class="row">

                                <div class="col-md-8 mb-3">
                                    <label for="NOMBRES" class="form-label">
                                        Nombres
                                    </label>
                                    <input
                                        type="text"
                                        name="NOMBRES" oninput="this.value = this.value.toUpperCase()"
                                        id="NOMBRES"
                                        class="form-control"
                                        maxlength="250"
                                        required
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="DNI" class="form-label">
                                        DNI
                                    </label>
                                    <input
                                        type="text"
                                        name="DNI" oninput="this.value = this.value.toUpperCase()"
                                        id="DNI"
                                        class="form-control"
                                        maxlength="8"
                                        inputmode="numeric"
                                        required
                                        autocomplete="off"
                                    >
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label for="FECHA_NACIMIENTO" class="form-label">
                                        Fecha de nacimiento
                                    </label>
                                    <input
                                        type="date"
                                        name="FECHA_NACIMIENTO"
                                        id="FECHA_NACIMIENTO"
                                        class="form-control"
                                    >
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="CONDICION" class="form-label">
                                        Condición
                                    </label>
                                    <select
                                        name="CONDICION"
                                        id="CONDICION"
                                        class="form-select"
                                    >
                                        <option value="">Seleccione una condición</option>
                                        <option value="ACLAS">ACLAS</option>
                                        <option value="C.A.S.">C.A.S.</option>
                                        <option value="LOCADOR (A)">LOCADOR (A)</option>
                                        <option value="NOMBRADO (A)">NOMBRADO (A)</option>
                                        <option value="T.P.">T.P.</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="FECHA_INGRESO" class="form-label">
                                        Fecha de ingreso
                                    </label>
                                    <input
                                        type="date"
                                        name="FECHA_INGRESO"
                                        id="FECHA_INGRESO"
                                        class="form-control"
                                    >
                                </div>

                            </div>

                            <hr class="my-4">

                            <!-- DATOS DE CONTACTO -->
                            <h5 class="mb-3">Datos de contacto</h5>

                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label for="TELEFONO" class="form-label">
                                        Teléfono
                                    </label>
                                    <input
                                        type="text"
                                        name="TELEFONO" oninput="this.value = this.value.toUpperCase()"
                                        id="TELEFONO"
                                        class="form-control"
                                        maxlength="30"
                                        inputmode="tel"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label for="CORREO" class="form-label">
                                        Correo electrónico
                                    </label>
                                    <input
                                        type="email"
                                        name="CORREO" oninput="this.value = this.value.toLowerCase()"
                                        id="CORREO"
                                        class="form-control"
                                        maxlength="150"
                                        autocomplete="off"
                                    >
                                </div>

                            </div>

                            <hr class="my-4">

                            <!-- DATOS DEL DIGITADOR -->
                            <h5 class="mb-3">Datos del digitador</h5>

                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label for="TIPO" class="form-label">
                                        Tipo
                                    </label>
                                    <select
                                        name="TIPO" oninput="this.value = this.value.toUpperCase()"
                                        id="TIPO"
                                        class="form-select"
                                    >
                                        <option value="">Seleccione un tipo</option>
                                        <option value="DIGITADOR">DIGITADOR</option>
                                        <option value="FUA_ADMIN">FUA_ADMIN</option>
                                        <option value="NO DIGITA">NO DIGITA</option>
                                        <option value="PIAS">PIAS</option>
                                        <option value="VOLANTE DRCYS100">VOLANTE DRCYS100</option>
                                        <option value="VOLANTE DRCYS100C">VOLANTE DRCYS100C</option>
                                        <option value="VOLANTE DRCYS200">VOLANTE DRCYS200</option>
                                        <option value="VOLANTE DRCYS250">VOLANTE DRCYS250</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="GRUPO" class="form-label">
                                        Grupo
                                    </label>
                                    <select
                                        name="GRUPO"
                                        id="GRUPO"
                                        class="form-select"
                                    >
                                        <option value="">Seleccione un grupo</option>
                                        <option value="DE PLANTA">DE PLANTA</option>
                                        <option value="VOLANTE">VOLANTE</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="META_DIARIA" class="form-label">
                                        Meta diaria
                                    </label>
                                    <select
                                        name="META_DIARIA"
                                        id="META_DIARIA"
                                        class="form-select"
                                    >
                                        <option value="">Seleccione una meta</option>
                                        <option value="0">0</option>
                                        <option value="100">100</option>
                                        <option value="200">200</option>
                                        <option value="250">250</option>
                                    </select>
                                </div>

                            </div>

                            <!-- IMAGEN -->
                            <div class="row">

                                <div class="col-md-8 mb-3">
                                    <label for="IMG" class="form-label">
                                        Imagen
                                    </label>
                                    <input
                                        type="text"
                                        name="IMG" oninput="this.value = this.value.toUpperCase()"
                                        id="IMG"
                                        class="form-control"
                                        maxlength="255"
                                        autocomplete="off"
                                    >
                                    <div class="form-text">
                                        Ruta o referencia de la imagen del digitador.
                                    </div>
                                </div>

                            </div>

                            <hr class="my-4">

                            <!-- BOTONES -->
                            <div class="d-flex justify-content-end gap-2">

                                <a href="<?= url('digitadores') ?>" class="btn btn-secondary">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    Guardar digitador
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
