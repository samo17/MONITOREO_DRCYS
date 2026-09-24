<?php

require_once __DIR__ . '/../../core/Auth.php';

?>

<aside class="sidebar bg-dark text-white">

    <div class="sidebar-header p-3 border-bottom">

        <h5 class="mb-0">
            Menú principal
        </h5>

    </div>

    <nav class="nav flex-column p-2">

        <!-- Dashboard -->
        <a class="nav-link text-white"
           href="<?= url('dashboard') ?>">

            Dashboard

        </a>


        <!-- Administración -->
        <?php if (Auth::tienePermiso('USUARIOS')): ?>

            <a class="nav-link text-white"
               href="<?= url('usuarios') ?>">

                Usuarios

            </a>

        <?php endif; ?>


        <?php if (Auth::tienePermiso('IPRESS')): ?>

            <a class="nav-link text-white"
               href="<?= url('ipress') ?>">

                IPRESS

            </a>

        <?php endif; ?>


        <?php if (Auth::tienePermiso('DIGITADORES')): ?>

            <a class="nav-link text-white"
               href="<?= url('digitadores') ?>">

                Digitadores

            </a>

        <?php endif; ?>


        <?php if (Auth::tienePermiso('AFILIADOS')): ?>

            <a class="nav-link text-white"
               href="<?= url('afiliados') ?>">

                Afiliados

            </a>

        <?php endif; ?>


        <!-- Atención -->
        <?php
        $tieneAtencion =
            Auth::tienePermiso('OCURRENCIAS_CA') ||
            Auth::tienePermiso('REFERENCIAS') ||
            Auth::tienePermiso('PEDIDO_FUA') ||
            Auth::tienePermiso('SEPELIO');
        ?>

        <?php if ($tieneAtencion): ?>

            <div class="sidebar-section text-uppercase small px-3 mt-3 mb-1">

                Atención

            </div>

        <?php endif; ?>


        <?php if (Auth::tienePermiso('OCURRENCIAS_CA')): ?>

            <a class="nav-link text-white"
               href="<?= url('ocurrenciasca') ?>">

                Ocurrencias CA

            </a>

        <?php endif; ?>

        <?php if (Auth::tienePermiso('TIPOS_OCURRENCIA')): ?>

            <a class="nav-link text-white"
            href="<?= url('tiposocurrencia') ?>">
                Tipos de Ocurrencia
            </a>

        <?php endif; ?>

        <?php if (Auth::tienePermiso('REFERENCIAS')): ?>

            <a class="nav-link text-white"
               href="<?= url('referencias') ?>">

                Referencias

            </a>

        <?php endif; ?>


        <?php if (Auth::tienePermiso('PEDIDO_FUA')): ?>

            <a class="nav-link text-white"
               href="<?= url('pedidofua') ?>">

                Pedido FUA

            </a>

        <?php endif; ?>


        <?php if (Auth::tienePermiso('SEPELIO')): ?>

            <a class="nav-link text-white"
               href="<?= url('sepelio') ?>">

                Sepelio

            </a>

        <?php endif; ?>


        <!-- Monitoreo -->
        <?php
        $tieneMonitoreo =
            Auth::tienePermiso('MONITOREO') ||
            Auth::tienePermiso('TELEMEDICINA');
        ?>

        <?php if ($tieneMonitoreo): ?>

            <div class="sidebar-section text-uppercase small px-3 mt-3 mb-1">

                Monitoreo

            </div>

        <?php endif; ?>


        <?php if (Auth::tienePermiso('MONITOREO')): ?>

            <a class="nav-link text-white"
               href="<?= url('monitoreo') ?>">

                Monitoreo

            </a>

        <?php endif; ?>


        <?php if (Auth::tienePermiso('TELEMEDICINA')): ?>

            <a class="nav-link text-white"
               href="<?= url('telemedicina') ?>">

                Telemedicina

            </a>

        <?php endif; ?>

    </nav>

</aside>