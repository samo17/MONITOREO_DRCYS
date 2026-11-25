<?php

$titulo = 'Ocurrencias CA';

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
                            Ocurrencias - Central de Afiliación
                        </h1>

                        <p class="text-muted mb-0">
                            Registro y seguimiento de ocurrencias de afiliación
                        </p>

                    </div>

                    <a
                        href="<?= url('ocurrenciasca/crear') ?>"
                        class="btn btn-primary"
                    >
                        Nueva ocurrencia
                    </a>

                </div>


                <!-- Tabla -->
                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Ocurrencias registradas
                        </strong>

                    </div>


                    <div class="card-body">

                        <!-- Filtros -->
                        <form
                            method="GET"
                            action="index.php"
                            class="row g-3 mb-4"
                        >

                            <input
                                type="hidden"
                                name="route"
                                value="ocurrenciasca"
                            >


                            <!-- Búsqueda general -->
                            <div class="col-md-6">

                                <label
                                    for="buscar"
                                    class="form-label"
                                >
                                    Buscar
                                </label>

                                <input
                                    type="text"
                                    name="buscar"
                                    id="buscar"
                                    class="form-control"
                                    placeholder="DNI, nombre, Nro. Formato, comunidad o responsable..."
                                    value="<?= e($buscar ?? '') ?>"
                                >

                            </div>


                            <!-- Responsable de afiliación -->
                            <div class="col-md-6">

                                <label
                                    for="dni_responsable_afiliacion"
                                    class="form-label"
                                >
                                    Responsable de afiliación
                                </label>

                                <select
                                    name="dni_responsable_afiliacion"
                                    id="dni_responsable_afiliacion"
                                    class="form-select"
                                >

                                    <option value="">
                                        Todos los responsables
                                    </option>

                                    <?php foreach (($responsables ?? []) as $responsable): ?>

                                        <?php

                                        $dniResponsable =
                                            $responsable['DNI'] ?? '';

                                        $nombreResponsable =
                                            trim(
                                                ($responsable['Nombres'] ?? '') .
                                                ' ' .
                                                ($responsable['Apellidos'] ?? '')
                                            );

                                        ?>

                                        <option
                                            value="<?= e($dniResponsable) ?>"
                                            <?= (
                                                ($dniResponsableAfiliacion ?? '') ===
                                                $dniResponsable
                                            )
                                                ? 'selected'
                                                : ''
                                            ?>
                                        >
                                            <?= e($nombreResponsable) ?>
                                            - DNI:
                                            <?= e($dniResponsable) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <!-- Unidad Ejecutora -->
                            <div class="col-md-6">

                                <label
                                    for="cod_ue"
                                    class="form-label"
                                >
                                    Unidad Ejecutora
                                </label>

                                <select
                                    name="cod_ue"
                                    id="cod_ue"
                                    class="form-select"
                                >

                                    <option value="">
                                        Todas las unidades ejecutoras
                                    </option>

                                    <?php foreach (($unidadesEjecutoras ?? []) as $unidad): ?>

                                        <?php
                                        $codigoUE = $unidad['COD_UE'] ?? '';
                                        $nombreUE = $unidad['UE'] ?? '';
                                        ?>

                                        <option
                                            value="<?= e($codigoUE) ?>"
                                            <?= (
                                                ($codUE ?? '') === $codigoUE
                                            )
                                                ? 'selected'
                                                : ''
                                            ?>
                                        >
                                            <?= e($codigoUE) ?> - <?= e($nombreUE) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <!-- Fecha inicio -->
                            <div class="col-md-3">

                                <label
                                    for="fecha_inicio"
                                    class="form-label"
                                >
                                    Fecha inicio
                                </label>

                                <input
                                    type="date"
                                    name="fecha_inicio"
                                    id="fecha_inicio"
                                    class="form-control"
                                    value="<?= e($fechaInicio ?? '') ?>"
                                >

                            </div>


                            <!-- Fecha fin -->
                            <div class="col-md-3">

                                <label
                                    for="fecha_fin"
                                    class="form-label"
                                >
                                    Fecha fin
                                </label>

                                <input
                                    type="date"
                                    name="fecha_fin"
                                    id="fecha_fin"
                                    class="form-control"
                                    value="<?= e($fechaFin ?? '') ?>"
                                >

                            </div>


                            <!-- Botones -->
                            <div class="col-md-6 d-flex align-items-end gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Buscar
                                </button>


                                <a
                                    href="<?= url('ocurrenciasca') ?>"
                                    class="btn btn-secondary"
                                >
                                    Limpiar
                                </a>


                                <a
                                    href="index.php?route=ocurrenciasca/exportar-excel&buscar=<?= urlencode($buscar ?? '') ?>&dni_responsable_afiliacion=<?= urlencode($dniResponsableAfiliacion ?? '') ?>&cod_ue=<?= urlencode($codUE ?? '') ?>&fecha_inicio=<?= urlencode($fechaInicio ?? '') ?>&fecha_fin=<?= urlencode($fechaFin ?? '') ?>"
                                    class="btn btn-success"
                                >
                                    Descargar Excel
                                </a>

                            </div>

                        </form>


                        <!-- Tabla -->
                        <div class="table-responsive">

                            <table class="table table-striped table-hover align-middle">

                                <thead class="table-primary">

                                    <tr>

                                        <th>
                                            ID
                                        </th>

                                        <th>
                                            DNI Paciente
                                        </th>

                                        <th>
                                            Asegurado
                                        </th>

                                        <th>
                                            IAFAS
                                        </th>

                                        <th>
                                            Nro. Formato
                                        </th>

                                        <th>
                                            F.P.P.
                                        </th>

                                        <th>
                                            Comunidad
                                        </th>

                                        <th>
                                            IPRESS
                                        </th>

                                        <th>
                                            Responsable
                                        </th>

                                        <th>
                                            Responsable Afiliación
                                        </th>

                                        <th>
                                            Fecha Registro
                                        </th>

                                        <th class="text-center">
                                            Acciones
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php if (!empty($ocurrencias)): ?>

                                        <?php foreach ($ocurrencias as $ocurrencia): ?>

                                            <?php

                                            $nombreResponsableAfiliacion =
                                                trim(
                                                    ($ocurrencia['NombresResponsableAfiliacion'] ?? '') .
                                                    ' ' .
                                                    ($ocurrencia['ApellidosResponsableAfiliacion'] ?? '')
                                                );

                                            ?>

                                            <tr>

                                                <td>
                                                    <?= e($ocurrencia['IdOcurrencia']) ?>
                                                </td>

                                                <td>
                                                    <?= e($ocurrencia['DNI_PACIENTE']) ?>
                                                </td>

                                                <td>
                                                    <?= e($ocurrencia['NombreAsegurado'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($ocurrencia['IAFAS'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($ocurrencia['NroFormato'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($ocurrencia['FECHA_PROBABLE_PARTO'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($ocurrencia['COMUNIDAD'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($ocurrencia['IPRESS_ATENC'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($ocurrencia['PERSONA_RESPONSABLE'] ?? '') ?>
                                                </td>

                                                <td>

                                                    <?= e($nombreResponsableAfiliacion) ?>

                                                    <?php if (!empty($ocurrencia['DNIUsuarioResponsable'])): ?>

                                                        <br>

                                                        <small class="text-muted">
                                                            DNI:
                                                            <?= e($ocurrencia['DNIUsuarioResponsable']) ?>
                                                        </small>

                                                    <?php endif; ?>

                                                </td>

                                                <td>
                                                    <?= e($ocurrencia['FechaRegistro'] ?? '') ?>
                                                </td>

                                                <td class="text-center">

                                                    <a
                                                        href="<?= url('ocurrenciasca/detalle') ?>&id=<?= (int) $ocurrencia['IdOcurrencia'] ?>"
                                                        class="btn btn-sm btn-info me-1"
                                                    >
                                                        Ver
                                                    </a>

                                                    <a
                                                        href="<?= url('ocurrenciasca/editar') ?>&id=<?= (int) $ocurrencia['IdOcurrencia'] ?>"
                                                        class="btn btn-sm btn-primary"
                                                    >
                                                        Editar
                                                    </a>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>

                                            <td
                                                colspan="11"
                                                class="text-center text-muted"
                                            >
                                                No existen ocurrencias registradas.
                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>


                        <!-- Paginación -->
                        <?php if (($totalPaginas ?? 1) > 1): ?>

                            <nav
                                aria-label="Paginación de ocurrencias"
                                class="mt-3"
                            >

                                <ul class="pagination justify-content-center">


                                    <?php if (($pagina ?? 1) > 1): ?>

                                        <li class="page-item">

                                            <a
                                                class="page-link"
                                                href="index.php?route=ocurrenciasca&buscar=<?= urlencode($buscar ?? '') ?>&dni_responsable_afiliacion=<?= urlencode($dniResponsableAfiliacion ?? '') ?>&cod_ue=<?= urlencode($codUE ?? '') ?>&fecha_inicio=<?= urlencode($fechaInicio ?? '') ?>&fecha_fin=<?= urlencode($fechaFin ?? '') ?>&pagina=<?= (($pagina ?? 1) - 1) ?>"
                                            >
                                                &laquo; Anterior
                                            </a>

                                        </li>

                                    <?php else: ?>

                                        <li class="page-item disabled">

                                            <span class="page-link">
                                                &laquo; Anterior
                                            </span>

                                        </li>

                                    <?php endif; ?>


                                    <?php for ($i = 1; $i <= ($totalPaginas ?? 1); $i++): ?>

                                        <li
                                            class="page-item <?= $i === ($pagina ?? 1) ? 'active' : '' ?>"
                                        >

                                            <a
                                                class="page-link"
                                                href="index.php?route=ocurrenciasca&buscar=<?= urlencode($buscar ?? '') ?>&dni_responsable_afiliacion=<?= urlencode($dniResponsableAfiliacion ?? '') ?>&cod_ue=<?= urlencode($codUE ?? '') ?>&fecha_inicio=<?= urlencode($fechaInicio ?? '') ?>&fecha_fin=<?= urlencode($fechaFin ?? '') ?>&pagina=<?= $i ?>"
                                            >
                                                <?= $i ?>
                                            </a>

                                        </li>

                                    <?php endfor; ?>


                                    <?php if (($pagina ?? 1) < ($totalPaginas ?? 1)): ?>

                                        <li class="page-item">

                                            <a
                                                class="page-link"
                                                href="index.php?route=ocurrenciasca&buscar=<?= urlencode($buscar ?? '') ?>&dni_responsable_afiliacion=<?= urlencode($dniResponsableAfiliacion ?? '') ?>&cod_ue=<?= urlencode($codUE ?? '') ?>&fecha_inicio=<?= urlencode($fechaInicio ?? '') ?>&fecha_fin=<?= urlencode($fechaFin ?? '') ?>&pagina=<?= (($pagina ?? 1) + 1) ?>"
                                            >
                                                Siguiente &raquo;
                                            </a>

                                        </li>

                                    <?php else: ?>

                                        <li class="page-item disabled">

                                            <span class="page-link">
                                                Siguiente &raquo;
                                            </span>

                                        </li>

                                    <?php endif; ?>

                                </ul>

                            </nav>

                        <?php endif; ?>


                        <div class="text-muted text-center small">

                            Mostrando
                            <?= count($ocurrencias ?? []) ?>
                            de
                            <?= (int) ($totalOcurrencias ?? 0) ?>
                            ocurrencias

                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>


<?php

require_once __DIR__ . '/../layouts/footer.php';

?>