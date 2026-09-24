<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold"
           href="<?= url('dashboard') ?>">
            DRCyS
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarPrincipal"
                aria-controls="navbarPrincipal"
                aria-expanded="false"
                aria-label="Mostrar navegación">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse"
             id="navbarPrincipal">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a class="nav-link"
                       href="<?= url('dashboard') ?>">
                        Inicio
                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link"
                       href="<?= url('logout') ?>">
                        Cerrar sesión
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>