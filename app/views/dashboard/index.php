<?php

$titulo = 'Dashboard';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="container-fluid">

    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0">
            <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
        </div>

        <!-- Contenido principal -->
        <main class="col-md-9 col-lg-10">

            <div class="container-fluid py-4">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h1 class="h3 mb-3">
                            Bienvenido a DRCyS
                        </h1>

                        <p class="mb-1">
                            Usuario:
                            <strong>
                                <?= e(Auth::nombreCompleto()) ?>
                            </strong>
                        </p>

                        <p class="mb-0">
                            DNI:
                            <strong>
                                <?= e(Auth::dni()) ?>
                            </strong>
                        </p>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

<?php

require_once __DIR__ . '/../layouts/footer.php';
?>