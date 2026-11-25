
<?php
require_once __DIR__ . '/../../core/Auth.php';

$tieneAdministracion =
    Auth::tienePermiso('USUARIOS') ||
    Auth::tienePermiso('IPRESS') ||
    Auth::tienePermiso('DIGITADORES')||
    Auth::tienePermiso('TIPOS_OCURRENCIA') ||
    Auth::tienePermiso('IAFAS') ||
    Auth::tienePermiso('MAESTROS');

$tieneAfiliacion =
Auth::tienePermiso('AFILIADOS') ||
Auth::tienePermiso('OCURRENCIAS_CA');

$tieneConsultas =
    Auth::tienePermiso('IPRESS_CONSULTA') ||
    Auth::tienePermiso('AFILIADOS_CONSULTA');

$tieneAtencion =       
    Auth::tienePermiso('REFERENCIAS') ||
    Auth::tienePermiso('PEDIDO_FUA') ||
    Auth::tienePermiso('SEPELIO');

$tieneServicios =
    Auth::tienePermiso('MONITOREO') ||
    Auth::tienePermiso('TELEMEDICINA');
?>

<aside class="sidebar bg-dark text-white">
    <div class="sidebar-header p-3 border-bottom">
        <h5 class="mb-0">Menú principal</h5>
    </div>

    <nav class="nav flex-column p-2">

        <!-- Dashboard -->
        <a class="nav-link text-white"
           href="<?= url('dashboard') ?>">
            Dashboard
        </a>

        <!-- Administración -->
        <?php if ($tieneAdministracion): ?>
            <button class="nav-link text-white text-start w-100 border-0 bg-transparent
                           d-flex justify-content-between align-items-center"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#menuAdministracion"
                    aria-expanded="false"
                    aria-controls="menuAdministracion">
                Administración
                <span class="flecha-menu">▸</span>
            </button>

            <div class="collapse ps-3" id="menuAdministracion">
                <?php if (Auth::tienePermiso('USUARIOS')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('usuarios') ?>">Usuarios</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('IPRESS')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('ipress') ?>">IPRESS</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('DIGITADORES')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('digitadores') ?>">Digitadores</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('IAFAS')): ?>
                    <a class="nav-link text-white" href="<?= url('iafas') ?>">IAFAS</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('TIPOS_OCURRENCIA')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('tiposocurrencia') ?>">Tipos de Ocurrencia</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('MAESTROS')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('maestros') ?>">Maestros - Afiliados</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('AFILIADOS')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('afiliados') ?>">Afiliados</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Afiliación -->
        <?php if ($tieneAfiliacion): ?>
            <button class="nav-link text-white text-start w-100 border-0 bg-transparent
                           d-flex justify-content-between align-items-center"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#menuAfiliacion"
                    aria-expanded="false"
                    aria-controls="menuAfiliacion">
                Central de Afiliación
                <span class="flecha-menu">▸</span>
            </button>

            <div class="collapse ps-3" id="menuAfiliacion">
                

                <?php if (Auth::tienePermiso('OCURRENCIAS_CA')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('ocurrenciasca') ?>">Ocurrencias CA</a>
                <?php endif; ?>
                
            </div>
        <?php endif; ?>

        <!-- Consultas -->
        <?php if ($tieneConsultas): ?>
            <button class="nav-link text-white text-start w-100 border-0 bg-transparent
                           d-flex justify-content-between align-items-center"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#menuConsultas"
                    aria-expanded="false"
                    aria-controls="menuConsultas">
                Consultas
                <span class="flecha-menu">▸</span>
            </button>

            <div class="collapse ps-3" id="menuConsultas">
                <?php if (Auth::tienePermiso('IPRESS_CONSULTA')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('ipress_consulta') ?>">Consulta IPRESS</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('AFILIADOS_CONSULTA')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('afiliados_consulta') ?>">Consulta Afiliados</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Atención -->
        <?php if ($tieneAtencion): ?>
            <button class="nav-link text-white text-start w-100 border-0 bg-transparent
                           d-flex justify-content-between align-items-center"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#menuAtencion"
                    aria-expanded="false"
                    aria-controls="menuAtencion">
                Atención
                <span class="flecha-menu">▸</span>
            </button>

            <div class="collapse ps-3" id="menuAtencion">
                

                

                <?php if (Auth::tienePermiso('REFERENCIAS')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('referencias') ?>">Referencias</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('PEDIDO_FUA')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('pedidofua') ?>">Pedido FUA</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('SEPELIO')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('sepelio') ?>">Sepelio</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Monitoreo y servicios -->
        <?php if ($tieneServicios): ?>
            <button class="nav-link text-white text-start w-100 border-0 bg-transparent
                           d-flex justify-content-between align-items-center"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#menuServicios"
                    aria-expanded="false"
                    aria-controls="menuServicios">
                Monitoreo y servicios
                <span class="flecha-menu">▸</span>
            </button>

            <div class="collapse ps-3" id="menuServicios">
                <?php if (Auth::tienePermiso('MONITOREO')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('monitoreo') ?>">Monitoreo</a>
                <?php endif; ?>

                <?php if (Auth::tienePermiso('TELEMEDICINA')): ?>
                    <a class="nav-link text-white"
                       href="<?= url('telemedicina') ?>">Telemedicina</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </nav>
</aside>

<style>
.sidebar .nav-link {
    color: #fff !important;
    text-decoration: none;
    border-radius: 4px;
    padding: 10px 12px;
}

.sidebar .nav-link:hover {
    background-color: #62676a;
    color: #fff !important;
}

.sidebar button.nav-link {
    font-size: 1rem;
    cursor: pointer;
}

.sidebar .collapse .nav-link {
    padding-left: 16px;
}

.sidebar .flecha-menu {
    transition: transform 0.2s ease;
}

.sidebar button[aria-expanded="true"] .flecha-menu {
    transform: rotate(90deg);
}
</style>
