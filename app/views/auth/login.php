<?php
$error = Session::get('error_login');
Session::remove('error_login');
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>DRCyS - Iniciar Sesión</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="/DRCYS/public/assets/css/bootstrap.min.css">

    <!-- Estilos propios -->
    <link rel="stylesheet"
          href="/DRCYS/public/assets/css/style.css">

</head>

<body>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">

    <div class="row w-100 justify-content-center">

        <div class="col-11 col-sm-8 col-md-5 col-lg-4">

            <div class="card shadow">

                <div class="card-body p-4">

                    <div class="text-center mb-4">

                        <img src="/DRCYS/public/assets/img/logo.png"
                             alt="DRCyS"
                             class="img-fluid mb-3"
                             style="max-height: 100px;">

                        <h3>DRCyS</h3>

                        <p class="text-muted">
                            Dirección de Referencias,
                            Contrareferencias y Seguros
                        </p>

                    </div>

                    <?php if ($error): ?>

                        <div class="alert alert-danger">
                            <?= e($error) ?>
                        </div>

                    <?php endif; ?>


                    <form method="POST"
                          action="/DRCYS/public/index.php?route=login/autenticar">

                        <div class="mb-3">

                            <label for="usuario"
                                   class="form-label">
                                Usuario
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="usuario"
                                   name="usuario"
                                   maxlength="50"
                                   autocomplete="username"
                                   required>

                        </div>


                        <div class="mb-3">

                            <label for="password"
                                   class="form-label">
                                Contraseña
                            </label>

                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   maxlength="100"
                                   autocomplete="current-password"
                                   required>

                        </div>


                        <div class="d-grid">

                            <button type="submit"
                                    class="btn btn-primary">
                                Iniciar sesión
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- jQuery -->
<script src="/DRCYS/public/assets/js/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="/DRCYS/public/assets/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript propio -->
<script src="/DRCYS/public/assets/js/app.js"></script>

</body>

</html>