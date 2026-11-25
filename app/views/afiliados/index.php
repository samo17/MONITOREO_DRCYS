<?php

$titulo = 'Afiliados';

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
                <div class="mb-4">

                    <h1 class="h3 mb-1">
                        Afiliados
                    </h1>

                    <p class="text-muted mb-0">
                        Consulta del maestro de afiliados
                    </p>

                </div>

                
                <?php
                $mensajeSuccess = \Session::getFlash('success');
                $mensajeError = \Session::getFlash('error');
                ?>

                <?php if ($mensajeSuccess): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= e($mensajeSuccess) ?>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Cerrar"
                        ></button>
                    </div>
                <?php endif; ?>

                <?php if ($mensajeError): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= e($mensajeError) ?>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Cerrar"
                        ></button>
                    </div>
                <?php endif; ?>


                <!-- Filtros -->
                <div class="card shadow-sm mb-4">

                    <div class="card-body">

                        <form
                            method="GET"
                            action="index.php"
                        >

                            <input
                                type="hidden"
                                name="route"
                                value="afiliados"
                            >

                            <div class="row g-3">

                                <!-- Unidad Ejecutora -->
                                <div class="col-md-3">

                                    <label
                                        for="ue"
                                        class="form-label"
                                    >
                                        Unidad Ejecutora
                                    </label>

                                    <select
                                        name="ue"
                                        id="ue"
                                        class="form-select"
                                    >

                                        <option value="">
                                            Todas
                                        </option>

                                        <?php foreach (
                                            ($unidadesEjecutoras ?? [])
                                            as $unidad
                                        ): ?>

                                            <option
                                                value="<?= e($unidad['COD_UE'] ?? '') ?>"
                                                <?= ($ue ?? '') === ($unidad['COD_UE'] ?? '') ? 'selected' : '' ?>
                                            >
                                                <?= e($unidad['COD_UE'] ?? '') ?>
                                                -
                                                <?= e($unidad['UE'] ?? '') ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>


                                <!-- Red -->
                                <div class="col-md-3">

                                    <label
                                        for="red"
                                        class="form-label"
                                    >
                                        Red
                                    </label>

                                    <select
                                        name="red"
                                        id="red"
                                        class="form-select"
                                    >

                                        <option value="">
                                            Todas
                                        </option>

                                        <?php foreach (
                                            ($redes ?? [])
                                            as $itemRed
                                        ): ?>

                                            <option
                                                value="<?= e($itemRed['RED'] ?? '') ?>"
                                                <?= ($red ?? '') === ($itemRed['RED'] ?? '') ? 'selected' : '' ?>
                                            >
                                                <?= e($itemRed['RED'] ?? '') ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>


                                <!-- Microred -->
                                <div class="col-md-3">

                                    <label
                                        for="microred"
                                        class="form-label"
                                    >
                                        Microred
                                    </label>

                                    <select
                                        name="microred"
                                        id="microred"
                                        class="form-select"
                                    >

                                        <option value="">
                                            Todas
                                        </option>

                                        <?php foreach (
                                            ($microredes ?? [])
                                            as $itemMicrored
                                        ): ?>

                                            <option
                                                value="<?= e($itemMicrored['MICRORED'] ?? '') ?>"
                                                <?= ($microred ?? '') === ($itemMicrored['MICRORED'] ?? '') ? 'selected' : '' ?>
                                            >
                                                <?= e($itemMicrored['MICRORED'] ?? '') ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>


                                <!-- IPRESS -->
                                <div class="col-md-3">

                                    <label
                                        for="buscar_ipress"
                                        class="form-label"
                                    >
                                        IPRESS
                                    </label>

                                    <div class="position-relative">

                                        <input
                                            type="text"
                                            id="buscar_ipress"
                                            class="form-control"
                                            autocomplete="off"
                                            placeholder="Buscar por RENAES o nombre"
                                            value="<?= e($ipressSeleccionada ?? '') ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="id_eess"
                                            id="id_eess"
                                            value="<?= e($idEess ?? '') ?>"
                                        >

                                        <div
                                            id="resultados_ipress"
                                            class="list-group position-absolute w-100 shadow-sm"
                                            style="z-index: 1050; display:none; max-height:250px; overflow-y:auto;"
                                        ></div>

                                    </div>

                                    <small class="text-muted">
                                        Escriba RENAES o nombre de IPRESS.
                                    </small>

                                </div>


                                <!-- Buscar -->
                                <div class="col-md-8">

                                    <label
                                        for="buscar"
                                        class="form-label"
                                    >
                                        Buscar por DNI o nombre
                                    </label>

                                    <input
                                        type="text"
                                        name="buscar"
                                        id="buscar"
                                        class="form-control"
                                        placeholder="Ingrese DNI o nombre del asegurado"
                                        value="<?= e($buscar ?? '') ?>"
                                    >

                                </div>


                                <!-- Botones -->
                                <div class="col-md-4 d-flex align-items-end gap-2 flex-wrap">

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Buscar
                                    </button>

                                    <a
                                        href="index.php?route=afiliados"
                                        class="btn btn-secondary"
                                    >
                                        Limpiar
                                    </a>

                                    <!-- Exportar Excel -->
                                    <a
                                        href="index.php?route=afiliados/exportar&formato=excel&buscar=<?= urlencode($buscar ?? '') ?>&ue=<?= urlencode($ue ?? '') ?>&red=<?= urlencode($red ?? '') ?>&microred=<?= urlencode($microred ?? '') ?>&id_eess=<?= urlencode($idEess ?? '') ?>"
                                        class="btn btn-success"
                                    >
                                        Exportar Excel
                                    </a>

                                    <!-- Exportar CSV -->
                                    <a
                                        href="index.php?route=afiliados/exportar&formato=csv&buscar=<?= urlencode($buscar ?? '') ?>&ue=<?= urlencode($ue ?? '') ?>&red=<?= urlencode($red ?? '') ?>&microred=<?= urlencode($microred ?? '') ?>&id_eess=<?= urlencode($idEess ?? '') ?>"
                                        class="btn btn-outline-success"
                                    >
                                        Exportar CSV
                                    </a>

                                    
                                    <button
                                        type="submit"
                                        form="formSincronizarComparando"
                                        class="btn btn-success"
                                    >
                                        Sincronizar comparando
                                    </button>

                                    <?php $puedeSincronizarDesdeCero = ((int) ($totalGlobalAfiliados ?? 0) === 0); ?>
                                    <button
                                        type="submit"
                                        form="formSincronizarDesdeCero"
                                        class="btn btn-outline-danger"
                                        <?= $puedeSincronizarDesdeCero ? '' : 'disabled' ?>
                                        title="<?= $puedeSincronizarDesdeCero
                                            ? 'La tabla afiliados está vacía; puede realizar la carga inicial.'
                                            : 'Deshabilitado: la tabla afiliados ya contiene registros.' ?>"
                                    >
                                        Sincronizar desde cero
                                    </button>


                                    <!-- Total -->
                                    <div class="alert alert-danger d-inline-block ms-2 mb-0 py-2 px-3">
                                        <strong>Afiliados:</strong>
                                        <?= number_format($totalAfiliados ?? 0) ?>
                                    </div>

                                </div>

                            </div>

                        </form>

                        <!-- Formularios independientes para evitar formularios anidados -->
                        <form
                            id="formSincronizarComparando"
                            method="POST"
                            action="index.php?route=afiliados/sincronizar"
                            onsubmit="return confirm('¿Ejecutar la sincronización comparativa? Se insertarán registros nuevos y se actualizarán coincidencias según las reglas existentes.');"
                        >
                            <input type="hidden" name="modo_sincronizacion" value="comparar">
                        </form>

                        <form
                            id="formSincronizarDesdeCero"
                            method="POST"
                            action="index.php?route=afiliados/sincronizar"
                            onsubmit="return confirm('¿Iniciar la carga desde cero? Solo funciona si afiliados está vacía. Insertará directamente desde m_afiliados por lotes, sin comparar registros.');"
                        >
                            <input type="hidden" name="modo_sincronizacion" value="desde_cero">
                        </form>

                    </div>

                </div>


                <!-- Tabla -->
                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Afiliados registrados
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-primary">

                                    <tr>

                                        <th>
                                            ID
                                        </th>

                                        <th>
                                            DNI
                                        </th>

                                        <th>
                                            N.º Formato
                                        </th>

                                        <th>
                                            Nombre del Asegurado
                                        </th>

                                        <th>
                                            Sexo
                                        </th>

                                        <th>
                                            Fecha Nacimiento
                                        </th>

                                        <th>
                                            Estado
                                        </th>

                                        <th>
                                            EESS Adscrito
                                        </th>

                                        <th class="text-center">
                                            Acción
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php if (empty($afiliados)): ?>

                                        <tr>

                                            <td
                                                colspan="9"
                                                class="text-center text-muted py-4"
                                            >
                                                No se encontraron afiliados.
                                            </td>

                                        </tr>

                                    <?php else: ?>

                                        <?php foreach (
                                            $afiliados
                                            as $afiliado
                                        ): ?>

                                            <tr>

                                                <td>
                                                    <?= (int) ($afiliado['IdAfiliados'] ?? 0) ?>
                                                </td>

                                                <td>
                                                    <?= e($afiliado['NumeroDni'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($afiliado['NroFormato'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($afiliado['NombreAsegurado'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($afiliado['Sexo'] ?? '') ?>
                                                </td>

                                                <td>
                                                    <?= e($afiliado['FechaNacimiento'] ?? '') ?>
                                                </td>

                                                <td>

                                                    <?php
                                                    $estado =
                                                        strtoupper(
                                                            trim(
                                                                $afiliado['Estado'] ?? ''
                                                            )
                                                        );
                                                    ?>

                                                    <?php if (
                                                        $estado === 'ACTIVO'
                                                    ): ?>

                                                        <span class="badge bg-success">
                                                            Activo
                                                        </span>

                                                    <?php else: ?>

                                                        <span class="badge bg-secondary">
                                                            <?= e(
                                                                $afiliado['Estado'] ?? ''
                                                            ) ?>
                                                        </span>

                                                    <?php endif; ?>

                                                </td>

                                                <td>
                                                    <?= e(
                                                        $afiliado['EessAdscrito'] ?? ''
                                                    ) ?>
                                                </td>

                                                <td class="text-center">

                                                    <a
                                                        href="index.php?route=afiliados/detalle&id=<?= (int) ($afiliado['IdAfiliados'] ?? 0) ?>"
                                                        class="btn btn-sm btn-primary"
                                                    >
                                                        Ver
                                                    </a>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>


                        <!-- Paginación -->
                        <?php
                        $paginaActual = max(1, (int) ($pagina ?? 1));
                        $totalPaginasActual = max(1, (int) ($totalPaginas ?? 1));
                        $paginasMostrar = [];

                        if ($totalPaginasActual <= 7) {
                            for ($i = 1; $i <= $totalPaginasActual; $i++) {
                                $paginasMostrar[] = $i;
                            }
                        } else {
                            $paginasMostrar[] = 1;
                            $inicio = max(2, $paginaActual - 2);
                            $fin = min($totalPaginasActual - 1, $paginaActual + 2);

                            if ($inicio > 2) {
                                $paginasMostrar[] = '...';
                            }

                            for ($i = $inicio; $i <= $fin; $i++) {
                                $paginasMostrar[] = $i;
                            }

                            if ($fin < $totalPaginasActual - 1) {
                                $paginasMostrar[] = '...';
                            }

                            $paginasMostrar[] = $totalPaginasActual;
                        }
                        ?>

                        <?php if ($totalPaginasActual > 1): ?>

                            <nav
                                aria-label="Paginación de afiliados"
                                class="mt-3"
                            >

                                <ul class="pagination justify-content-center">

                                    <!-- Anterior -->
                                    <li
                                        class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>"
                                    >

                                        <?php if ($paginaActual > 1): ?>

                                            <a
                                                class="page-link"
                                                href="index.php?route=afiliados&buscar=<?= urlencode($buscar ?? '') ?>&ue=<?= urlencode($ue ?? '') ?>&red=<?= urlencode($red ?? '') ?>&microred=<?= urlencode($microred ?? '') ?>&id_eess=<?= urlencode($idEess ?? '') ?>&pagina=<?= $paginaActual - 1 ?>"
                                            >
                                                &laquo; Anterior
                                            </a>

                                        <?php else: ?>

                                            <span class="page-link">
                                                &laquo; Anterior
                                            </span>

                                        <?php endif; ?>

                                    </li>

                                    <!-- Números -->
                                    <?php foreach ($paginasMostrar as $numeroPagina): ?>

                                        <?php if ($numeroPagina === '...'): ?>

                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>

                                        <?php else: ?>

                                            <li
                                                class="page-item <?= $numeroPagina === $paginaActual ? 'active' : '' ?>"
                                            >

                                                <a
                                                    class="page-link"
                                                    href="index.php?route=afiliados&buscar=<?= urlencode($buscar ?? '') ?>&ue=<?= urlencode($ue ?? '') ?>&red=<?= urlencode($red ?? '') ?>&microred=<?= urlencode($microred ?? '') ?>&id_eess=<?= urlencode($idEess ?? '') ?>&pagina=<?= $numeroPagina ?>"
                                                >
                                                    <?= $numeroPagina ?>
                                                </a>

                                            </li>

                                        <?php endif; ?>

                                    <?php endforeach; ?>

                                    <!-- Siguiente -->
                                    <li
                                        class="page-item <?= $paginaActual >= $totalPaginasActual ? 'disabled' : '' ?>"
                                    >

                                        <?php if ($paginaActual < $totalPaginasActual): ?>

                                            <a
                                                class="page-link"
                                                href="index.php?route=afiliados&buscar=<?= urlencode($buscar ?? '') ?>&ue=<?= urlencode($ue ?? '') ?>&red=<?= urlencode($red ?? '') ?>&microred=<?= urlencode($microred ?? '') ?>&id_eess=<?= urlencode($idEess ?? '') ?>&pagina=<?= $paginaActual + 1 ?>"
                                            >
                                                Siguiente &raquo;
                                            </a>

                                        <?php else: ?>

                                            <span class="page-link">
                                                Siguiente &raquo;
                                            </span>

                                        <?php endif; ?>

                                    </li>

                                </ul>

                            </nav>

                        <?php endif; ?>

                        <!-- Resumen -->
                        <div class="text-muted text-center small mt-2">

                            Mostrando
                            <?= count($afiliados ?? []) ?>
                            de
                            <?= (int) ($totalAfiliados ?? 0) ?>
                            registros

                        </div>
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


<script>

document.addEventListener('DOMContentLoaded', function () {

    const inputIpress = document.getElementById('buscar_ipress');
    const inputIdEess = document.getElementById('id_eess');

    const selectUE = document.getElementById('ue');
    const selectRed = document.getElementById('red');
    const selectMicrored = document.getElementById('microred');

    const resultados = document.getElementById('resultados_ipress');

    if (
        !inputIpress ||
        !inputIdEess ||
        !selectUE ||
        !selectRed ||
        !selectMicrored ||
        !resultados
    ) {
        return;
    }


    let temporizador = null;


    /*
    |--------------------------------------------------------------------------
    | Cargar REDES según Unidad Ejecutora
    |--------------------------------------------------------------------------
    */
    function cargarRedes(codUE, redSeleccionada = '') {

        selectRed.innerHTML =
            '<option value="">Todas</option>';

        selectMicrored.innerHTML =
            '<option value="">Todas</option>';

        if (codUE === '') {
            return Promise.resolve();
        }

        return fetch(
            '../ajax/ipress.php?accion=redes&codUE=' +
            encodeURIComponent(codUE)
        )
        .then(function (respuesta) {

            if (!respuesta.ok) {
                throw new Error('No fue posible obtener las redes.');
            }

            return respuesta.json();

        })
        .then(function (datos) {

            if (!Array.isArray(datos)) {
                return;
            }

            datos.forEach(function (item) {

                const opcion =
                    document.createElement('option');

                opcion.value =
                    item.RED ?? '';

                opcion.textContent =
                    item.RED ?? '';

                if (
                    redSeleccionada !== '' &&
                    opcion.value === redSeleccionada
                ) {
                    opcion.selected = true;
                }

                selectRed.appendChild(opcion);

            });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Cargar MICROREDES según UE + RED
    |--------------------------------------------------------------------------
    */
    function cargarMicroredes(
        codUE,
        red,
        microredSeleccionada = ''
    ) {

        selectMicrored.innerHTML =
            '<option value="">Todas</option>';

        if (
            codUE === '' ||
            red === ''
        ) {
            return Promise.resolve();
        }

        const parametros = new URLSearchParams();

        parametros.append(
            'accion',
            'microredes'
        );

        parametros.append(
            'codUE',
            codUE
        );

        parametros.append(
            'red',
            red
        );

        return fetch(
            '../ajax/ipress.php?' +
            parametros.toString()
        )
        .then(function (respuesta) {

            if (!respuesta.ok) {
                throw new Error(
                    'No fue posible obtener las microredes.'
                );
            }

            return respuesta.json();

        })
        .then(function (datos) {

            if (!Array.isArray(datos)) {
                return;
            }

            datos.forEach(function (item) {

                const opcion =
                    document.createElement('option');

                opcion.value =
                    item.MICRORED ?? '';

                opcion.textContent =
                    item.MICRORED ?? '';

                if (
                    microredSeleccionada !== '' &&
                    opcion.value === microredSeleccionada
                ) {
                    opcion.selected = true;
                }

                selectMicrored.appendChild(opcion);

            });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | UE → RED → MICRORED
    |--------------------------------------------------------------------------
    */
    selectUE.addEventListener('change', function () {

        const codUE = this.value;

        /*
        | Al cambiar UE se limpia la IPRESS seleccionada
        */
        inputIpress.value = '';
        inputIdEess.value = '';

        cargarRedes(codUE)
            .catch(function () {

                selectRed.innerHTML =
                    '<option value="">Todas</option>';

                selectMicrored.innerHTML =
                    '<option value="">Todas</option>';

            });

    });


    /*
    |--------------------------------------------------------------------------
    | RED → MICRORED
    |--------------------------------------------------------------------------
    */
    selectRed.addEventListener('change', function () {

        const codUE = selectUE.value;
        const red = this.value;

        /*
        | Al cambiar Red se limpia la IPRESS seleccionada
        */
        inputIpress.value = '';
        inputIdEess.value = '';

        cargarMicroredes(codUE, red)
            .catch(function () {

                selectMicrored.innerHTML =
                    '<option value="">Todas</option>';

            });

    });


    /*
    |--------------------------------------------------------------------------
    | MICRORED
    |--------------------------------------------------------------------------
    */
    selectMicrored.addEventListener('change', function () {

        /*
        | La selección de Microred mantiene
        | UE y Red seleccionadas.
        |
        | Se limpia solamente la IPRESS seleccionada.
        */
        inputIpress.value = '';
        inputIdEess.value = '';

    });


    /*
    |--------------------------------------------------------------------------
    | Buscar IPRESS por RENAES o nombre
    |--------------------------------------------------------------------------
    */
    inputIpress.addEventListener('input', function () {

        const buscar = this.value.trim();

        inputIdEess.value = '';

        clearTimeout(temporizador);

        if (buscar.length < 2) {

            resultados.innerHTML = '';
            resultados.style.display = 'none';

            return;
        }


        temporizador = setTimeout(function () {

            const ue =
                selectUE.value;

            const red =
                selectRed.value;

            const microred =
                selectMicrored.value;


            const parametros =
                new URLSearchParams();

            parametros.append(
                'accion',
                'buscar'
            );

            parametros.append(
                'buscar',
                buscar
            );


            /*
            | Si existen filtros seleccionados,
            | la búsqueda de IPRESS se limita
            | a esa combinación.
            */
            if (ue !== '') {

                parametros.append(
                    'codUE',
                    ue
                );

            }

            if (red !== '') {

                parametros.append(
                    'red',
                    red
                );

            }

            if (microred !== '') {

                parametros.append(
                    'microred',
                    microred
                );

            }


            const url =
                '../ajax/ipress.php?' +
                parametros.toString();


            fetch(url)

                .then(function (respuesta) {

                    if (!respuesta.ok) {

                        throw new Error(
                            'Error en la búsqueda'
                        );

                    }

                    return respuesta.json();

                })

                .then(function (datos) {

                    resultados.innerHTML = '';


                    if (
                        !Array.isArray(datos) ||
                        datos.length === 0
                    ) {

                        resultados.innerHTML =
                            '<div class="list-group-item text-muted">' +
                            'No se encontraron IPRESS.' +
                            '</div>';

                        resultados.style.display =
                            'block';

                        return;

                    }


                    datos.forEach(function (item) {

                        const boton =
                            document.createElement('button');

                        boton.type = 'button';

                        boton.className =
                            'list-group-item list-group-item-action';


                        boton.innerHTML =
                            '<strong>' +
                            escapeHtml(
                                item.RENAES ?? ''
                            ) +
                            '</strong> - ' +
                            escapeHtml(
                                item.IPRESS_ATENC ?? ''
                            );


                        boton.addEventListener(
                            'click',
                            function () {

                                seleccionarIpress(item);

                            }
                        );


                        resultados.appendChild(
                            boton
                        );

                    });


                    resultados.style.display =
                        'block';

                })

                .catch(function () {

                    resultados.innerHTML =
                        '<div class="list-group-item text-danger">' +
                        'No fue posible buscar las IPRESS.' +
                        '</div>';

                    resultados.style.display =
                        'block';

                });

        }, 300);

    });


    /*
    |--------------------------------------------------------------------------
    | Seleccionar IPRESS
    |--------------------------------------------------------------------------
    | Al seleccionar una IPRESS:
    |
    | IPRESS → obtiene COD_UE, UE, RED, MICRORED
    |        → selecciona UE
    |        → carga RED
    |        → selecciona RED
    |        → carga MICRORED
    |        → selecciona MICRORED
    |--------------------------------------------------------------------------
    */
    function seleccionarIpress(item) {

        const idEess =
            item.IdEESS ?? '';

        if (idEess === '') {
            return;
        }


        inputIdEess.value =
            idEess;

        inputIpress.value =
            (item.RENAES ?? '') +
            ' - ' +
            (item.IPRESS_ATENC ?? '');


        resultados.innerHTML = '';
        resultados.style.display = 'none';


        /*
        | Si la búsqueda ya devolvió los datos completos,
        | podemos utilizarlos directamente.
        */
        const codUE =
            item.COD_UE ?? '';

        const red =
            item.RED ?? '';

        const microred =
            item.MICRORED ?? '';


        /*
        |--------------------------------------------------------------------------
        | Obtener nuevamente la IPRESS por IdEESS
        |--------------------------------------------------------------------------
        | Esto garantiza que la información utilizada
        | para vincular los selectores provenga de la
        | nueva acción accion=obtener.
        |--------------------------------------------------------------------------
        */
        fetch(
            '../ajax/ipress.php?accion=obtener&idEESS=' +
            encodeURIComponent(idEess)
        )

        .then(function (respuesta) {

            if (!respuesta.ok) {

                throw new Error(
                    'No fue posible obtener la IPRESS.'
                );

            }

            return respuesta.json();

        })

        .then(function (ipress) {

            if (
                !ipress ||
                !ipress.IdEESS
            ) {

                throw new Error(
                    'IPRESS no encontrada.'
                );

            }


            const codigoUE =
                ipress.COD_UE ?? '';

            const nombreRed =
                ipress.RED ?? '';

            const nombreMicrored =
                ipress.MICRORED ?? '';


            /*
            |--------------------------------------------------------------------------
            | 1. Seleccionar Unidad Ejecutora
            |--------------------------------------------------------------------------
            */
            selectUE.value =
                codigoUE;


            /*
            |--------------------------------------------------------------------------
            | 2. Cargar Red correspondiente
            |--------------------------------------------------------------------------
            */
            return cargarRedes(
                codigoUE,
                nombreRed
            )
            .then(function () {

                /*
                |--------------------------------------------------------------------------
                | 3. Seleccionar Red
                |--------------------------------------------------------------------------
                */
                selectRed.value =
                    nombreRed;


                /*
                |--------------------------------------------------------------------------
                | 4. Cargar Microred correspondiente
                |--------------------------------------------------------------------------
                */
                return cargarMicroredes(
                    codigoUE,
                    nombreRed,
                    nombreMicrored
                );

            })
            .then(function () {

                /*
                |--------------------------------------------------------------------------
                | 5. Seleccionar Microred
                |--------------------------------------------------------------------------
                */
                selectMicrored.value =
                    nombreMicrored;

            });

        })

        .catch(function (error) {

            console.error(
                'Error al vincular la IPRESS:',
                error
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Cerrar resultados al hacer clic fuera
    |--------------------------------------------------------------------------
    */
    document.addEventListener(
        'click',
        function (evento) {

            if (
                !inputIpress.contains(
                    evento.target
                ) &&
                !resultados.contains(
                    evento.target
                )
            ) {

                resultados.style.display =
                    'none';

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Seguridad para mostrar texto de IPRESS
    |--------------------------------------------------------------------------
    */
    function escapeHtml(texto) {

        const div =
            document.createElement('div');

        div.textContent =
            texto;

        return div.innerHTML;

    }

});

</script>