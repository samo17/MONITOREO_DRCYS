<?php

$titulo = 'Nuevo Tipo de Ocurrencia';

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
                            Nuevo Tipo de Ocurrencia
                        </h1>

                        <p class="text-muted mb-0">
                            Registro de un nuevo tipo de ocurrencia
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
                            action="<?= url('tiposocurrencia/guardar') ?>"
                        >

                            <div class="row">

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
                                    >

                                    <div class="form-text">
                                        Máximo 150 caracteres.
                                    </div>

                                </div>

                            </div>


                            <hr class="my-4">


                            <div class="d-flex gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Guardar
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