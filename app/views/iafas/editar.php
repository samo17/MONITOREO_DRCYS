<?php

$titulo = 'Editar IAFAS';

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
                            Editar IAFAS
                        </h1>

                        <p class="text-muted mb-0">
                            Actualización de los datos del IAFAS
                        </p>

                    </div>

                    <a
                        href="<?= url('iafas') ?>"
                        class="btn btn-secondary"
                    >
                        Volver
                    </a>

                </div>


                <!-- Formulario -->
                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Datos del IAFAS
                        </strong>

                    </div>


                    <div class="card-body">

                        <form
                            method="POST"
                            action="<?= url('iafas/actualizar') ?>"
                        >

                            <input
                                type="hidden"
                                name="IdIAFAS"
                                value="<?= (int) ($iafas['IdIAFAS'] ?? 0) ?>"
                            >


                            <div class="row">

                                <div class="col-md-2">

                                    <label
                                        class="form-label"
                                        for="IdIAFASMostrar"
                                    >
                                        ID
                                    </label>

                                    <input
                                        type="text"
                                        id="IdIAFASMostrar"
                                        class="form-control"
                                        value="<?= (int) ($iafas['IdIAFAS'] ?? 0) ?>"
                                        readonly
                                    >

                                </div>


                                <div class="col-md-8">

                                    <label
                                        for="Nombre"
                                        class="form-label"
                                    >
                                        Nombre de la IAFAS
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="Nombre"
                                        id="Nombre"
                                        class="form-control"
                                        maxlength="150"
                                        required
                                        autofocus
                                        value="<?= e($iafas['Nombre'] ?? '') ?>"
                                    >

                                    <div class="form-text">
                                        Máximo 150 caracteres.
                                    </div>

                                </div>


                                <div class="col-md-2">

                                    <label class="form-label">
                                        Estado
                                    </label>

                                    <?php if ((int) ($iafas['Estado'] ?? 0) === 1): ?>

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
                                    href="<?= url('iafas') ?>"
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