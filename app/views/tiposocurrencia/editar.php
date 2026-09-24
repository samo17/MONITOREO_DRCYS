<?php

$titulo = 'Editar Tipo de Ocurrencia';

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
                            Editar Tipo de Ocurrencia
                        </h1>

                        <p class="text-muted mb-0">
                            Actualización de los datos del tipo de ocurrencia
                        </p>

                    </div>

                    <a
                        href="<?= url('tiposocurrencia') ?>"
                        class="btn btn-secondary"
                    >
                        Volver
                    </a>

                </div>


                <!-- Formulario -->
                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Datos del tipo de ocurrencia
                        </strong>

                    </div>


                    <div class="card-body">

                        <form
                            method="POST"
                            action="<?= url('tiposocurrencia/actualizar') ?>"
                        >

                            <input
                                type="hidden"
                                name="IdTipoOcurrencia"
                                value="<?= (int) ($tipo['IdTipoOcurrencia'] ?? 0) ?>"
                            >


                            <div class="row">

                                <div class="col-md-2">

                                    <label
                                        class="form-label"
                                        for="IdTipoOcurrenciaMostrar"
                                    >
                                        ID
                                    </label>

                                    <input
                                        type="text"
                                        id="IdTipoOcurrenciaMostrar"
                                        class="form-control"
                                        value="<?= (int) ($tipo['IdTipoOcurrencia'] ?? 0) ?>"
                                        readonly
                                    >

                                </div>


                                <div class="col-md-8">

                                    <label
                                        for="Descripcion"
                                        class="form-label"
                                    >
                                        Descripción
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="Descripcion"
                                        id="Descripcion"
                                        class="form-control"
                                        maxlength="150"
                                        required
                                        autofocus
                                        value="<?= e($tipo['Descripcion'] ?? '') ?>"
                                    >

                                    <div class="form-text">
                                        Máximo 150 caracteres.
                                    </div>

                                </div>


                                <div class="col-md-2">

                                    <label class="form-label">
                                        Estado
                                    </label>

                                    <?php if ((int) ($tipo['Estado'] ?? 0) === 1): ?>

                                        <div>
                                            <span class="badge bg-success fs-6">
                                                Activo
                                            </span>
                                        </div>

                                    <?php else: ?>

                                        <div>
                                            <span class="badge bg-danger fs-6">
                                                Inactivo
                                            </span>
                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <hr class="my-4">


                            <div class="d-flex gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Actualizar
                                </button>

                                <a
                                    href="<?= url('tiposocurrencia') ?>"
                                    class="btn btn-secondary"
                                >
                                    Cancelar
                                </a>

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