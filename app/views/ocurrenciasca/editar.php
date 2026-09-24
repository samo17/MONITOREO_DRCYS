<?php

$titulo = 'Editar Ocurrencia CA';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

/*
 * ============================================================
 * EDAD INFORMATIVA
 * ============================================================
 */

$edadInformativa = '';

if (!empty($ocurrencia['FechaNacimiento'])) {

    try {

        $fechaNacimiento = new DateTime(
            $ocurrencia['FechaNacimiento']
        );

        $hoy = new DateTime();

        if ($fechaNacimiento <= $hoy) {

            $edadInformativa = $fechaNacimiento->diff($hoy)->y;

        }

    } catch (Exception $e) {

        $edadInformativa = '';

    }

}


/*
 * ============================================================
 * DATOS EXISTENTES DEL NRO. FORMATO
 *
 * Formato:
 * 240-Lote-Codigo
 *
 * Ejemplo:
 * 240-2-80271731
 * ============================================================
 */

$nroFormatoActual =
    trim(
        (string) (
            $ocurrencia['NroFormato']
            ?? ''
        )
    );

$nroFormatoLoteActual = '';
$nroFormatoCodigoActual = '';

if ($nroFormatoActual !== '') {

    $partesFormato =
        explode(
            '-',
            $nroFormatoActual,
            3
        );

    if (count($partesFormato) === 3) {

        if ($partesFormato[0] === '240') {

            $nroFormatoLoteActual =
                trim($partesFormato[1]);

            $nroFormatoCodigoActual =
                trim($partesFormato[2]);

        }

    }

}


/*
 * ============================================================
 * DATOS EXISTENTES
 * ============================================================
 */

$gestanteActual =
    strtoupper(
        trim(
            (string) (
                $ocurrencia['GESTANTE']
                ?? ''
            )
        )
    );

$emergenciasActual =
    strtoupper(
        trim(
            (string) (
                $ocurrencia['EMERGENCIAS']
                ?? ''
            )
        )
    );

$presencialActual =
    strtoupper(
        trim(
            (string) (
                $ocurrencia['PRESENCIAL']
                ?? ''
            )
        )
    );

$distritoActual =
    trim(
        (string) (
            $ocurrencia['DISTRITO']
            ?? $ocurrencia['Distrito']
            ?? ''
        )
    );

$provinciaActual =
    trim(
        (string) (
            $ocurrencia['PROVINCIA']
            ?? $ocurrencia['Provincia']
            ?? ''
        )
    );

?>

<div class="container-fluid">

    <div class="row">

        <!-- =========================================================
             SIDEBAR
             ========================================================= -->

        <div class="col-md-3 col-lg-2 p-0">

            <?php
            require_once __DIR__ . '/../layouts/sidebar.php';
            ?>

        </div>


        <!-- =========================================================
             CONTENIDO PRINCIPAL
             ========================================================= -->

        <main class="col-md-9 col-lg-10">

            <div class="container-fluid py-4">

                <!-- =================================================
                     ENCABEZADO
                     ================================================= -->

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h1 class="h3 mb-1">
                            Editar Ocurrencia CA
                        </h1>

                        <p class="text-muted mb-0">
                            Modificación de los datos de la ocurrencia
                        </p>

                    </div>

                    <div>

                        <a
                            href="<?= url('ocurrenciasca') ?>"
                            class="btn btn-secondary"
                        >
                            Volver
                        </a>

                    </div>

                </div>


                <!-- =================================================
                     FORMULARIO
                     ================================================= -->

                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Datos de la ocurrencia
                        </strong>

                    </div>


                    <div class="card-body">

                        <form
                            method="POST"
                            action="<?= url('ocurrenciasca/actualizar') ?>"
                        >

                            <!-- =================================================
                                 CAMPOS OCULTOS
                                 ================================================= -->

                            <input
                                type="hidden"
                                name="IdOcurrencia"
                                value="<?= (int) ($ocurrencia['IdOcurrencia'] ?? 0) ?>"
                            >

                            <input
                                type="hidden"
                                name="IdAfiliados"
                                id="IdAfiliados"
                                value="<?= (int) ($ocurrencia['IdAfiliados'] ?? 0) ?>"
                            >

                            <input
                                type="hidden"
                                name="NroFormato"
                                id="NroFormato"
                                value="<?= e($nroFormatoActual) ?>"
                            >


                            <!-- =================================================
                                 DATOS DEL AFILIADO
                                 ================================================= -->

                            <h5 class="mb-3">
                                Datos del afiliado
                            </h5>


                            <div class="row">

                                <!-- DNI -->

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="DNI_PACIENTE"
                                        class="form-label"
                                    >
                                        DNI
                                    </label>

                                    <input
                                        type="text"
                                        name="DNI_PACIENTE"
                                        id="DNI_PACIENTE"
                                        class="form-control"
                                        value="<?= e($ocurrencia['DNI_PACIENTE'] ?? '') ?>"
                                        readonly
                                    >

                                </div>


                                <!-- NOMBRE -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="NombreAsegurado"
                                        class="form-label"
                                    >
                                        Nombre del asegurado
                                    </label>

                                    <input
                                        type="text"
                                        id="NombreAsegurado"
                                        class="form-control"
                                        value="<?= e($ocurrencia['NombreAsegurado'] ?? '') ?>"
                                        readonly
                                    >

                                </div>


                                <!-- SEXO -->

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="Sexo"
                                        class="form-label"
                                    >
                                        Sexo
                                    </label>

                                    <input
                                        type="text"
                                        id="Sexo"
                                        class="form-control"
                                        value="<?= e($ocurrencia['Sexo'] ?? '') ?>"
                                        readonly
                                    >

                                </div>

                            </div>


                            <div class="row">

                                <!-- FECHA NACIMIENTO -->

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="FechaNacimiento"
                                        class="form-label"
                                    >
                                        Fecha de nacimiento
                                    </label>

                                    <input
                                        type="date"
                                        id="FechaNacimiento"
                                        class="form-control"
                                        value="<?= e($ocurrencia['FechaNacimiento'] ?? '') ?>"
                                        readonly
                                    >

                                </div>


                                <!-- EDAD -->

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="Edad"
                                        class="form-label"
                                    >
                                        Edad
                                    </label>

                                    <input
                                        type="text"
                                        id="Edad"
                                        class="form-control"
                                        value="<?= $edadInformativa !== '' ? e($edadInformativa) : '' ?>"
                                        readonly
                                    >

                                </div>


                                <!-- EESS ANTERIOR -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="EessAdscrito"
                                        class="form-label"
                                    >
                                        EESS Anterior
                                    </label>

                                    <input
                                        type="text"
                                        id="EessAdscrito"
                                        class="form-control"
                                        value="<?= e($ocurrencia['EessAdscrito'] ?? '') ?>"
                                        readonly
                                    >

                                    <div class="form-text">
                                        Obtenido automáticamente del EESS de adscripción del afiliado.
                                    </div>

                                </div>

                            </div>


                            <div class="row">

                                <!-- GESTANTE -->

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="GESTANTE"
                                        class="form-label"
                                    >
                                        Gestante
                                    </label>

                                    <select
                                        name="GESTANTE"
                                        id="GESTANTE"
                                        class="form-select"
                                        disabled
                                    >

                                        <option value="">
                                            Seleccione
                                        </option>

                                        <option
                                            value="SI"
                                            <?= $gestanteActual === 'SI' ? 'selected' : '' ?>
                                        >
                                            SI
                                        </option>

                                        <option
                                            value="NO"
                                            <?= $gestanteActual === 'NO' ? 'selected' : '' ?>
                                        >
                                            NO
                                        </option>

                                    </select>

                                </div>


                                <!-- FPP -->

                                <div class="col-md-5 mb-3">

                                    <label
                                        for="FECHA_PROBABLE_PARTO"
                                        class="form-label"
                                    >
                                        Fecha probable de parto
                                    </label>

                                    <input
                                        type="date"
                                        name="FECHA_PROBABLE_PARTO"
                                        id="FECHA_PROBABLE_PARTO"
                                        class="form-control"
                                        value="<?= e($ocurrencia['FECHA_PROBABLE_PARTO'] ?? '') ?>"
                                        disabled
                                        autocomplete="off"
                                    >

                                    <div class="form-text">
                                        Solo se habilita si Sexo = F,
                                        Edad &gt; 9 y Gestante = SI.
                                    </div>

                                </div>

                            </div>


                            <hr class="my-4">


                            <!-- =================================================
                                 DATOS DE LA OCURRENCIA
                                 ================================================= -->

                            <h5 class="mb-3">
                                Datos de la ocurrencia
                            </h5>


                            <!-- =================================================
                                 NRO FORMATO / EMERGENCIA / PRESENCIAL
                                 ================================================= -->

                            <div class="row">

                                <!-- NRO FORMATO -->

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="NroFormatoCodigo"
                                        class="form-label"
                                    >
                                        Nro. Formato
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            240
                                        </span>


                                        <select
                                            id="NroFormatoLote"
                                            class="form-select"
                                            style="max-width: 90px;"
                                        >

                                            <option value="">
                                                Lote
                                            </option>

                                            <option
                                                value="2"
                                                <?= $nroFormatoLoteActual === '2' ? 'selected' : '' ?>
                                            >
                                                2
                                            </option>

                                            <option
                                                value="E"
                                                <?= $nroFormatoLoteActual === 'E' ? 'selected' : '' ?>
                                            >
                                                E
                                            </option>

                                            <option
                                                value="3"
                                                <?= $nroFormatoLoteActual === '3' ? 'selected' : '' ?>
                                            >
                                                3
                                            </option>

                                            <option
                                                value="8"
                                                <?= $nroFormatoLoteActual === '8' ? 'selected' : '' ?>
                                            >
                                                8
                                            </option>

                                            <option
                                                value="R"
                                                <?= $nroFormatoLoteActual === 'R' ? 'selected' : '' ?>
                                            >
                                                R
                                            </option>

                                            <option
                                                value="9"
                                                <?= $nroFormatoLoteActual === '9' ? 'selected' : '' ?>
                                            >
                                                9
                                            </option>

                                        </select>


                                        <input
                                            type="text"
                                            id="NroFormatoCodigo"
                                            class="form-control"
                                            maxlength="10"
                                            placeholder="Código"
                                            value="<?= e($nroFormatoCodigoActual) ?>"
                                        >

                                    </div>

                                    <div class="form-text">
                                        Ejemplo: 240-2-80271731
                                    </div>

                                </div>


                                <!-- EMERGENCIAS -->

                                <div class="col-md-2 mb-3">

                                    <label
                                        for="EMERGENCIAS"
                                        class="form-label"
                                    >
                                        Emergencias
                                    </label>

                                    <select
                                        name="EMERGENCIAS"
                                        id="EMERGENCIAS"
                                        class="form-select"
                                    >

                                        <option value="">
                                            Seleccione
                                        </option>

                                        <option
                                            value="SI"
                                            <?= $emergenciasActual === 'SI' ? 'selected' : '' ?>
                                        >
                                            SI
                                        </option>

                                        <option
                                            value="NO"
                                            <?= $emergenciasActual === 'NO' ? 'selected' : '' ?>
                                        >
                                            NO
                                        </option>

                                    </select>

                                </div>


                                <!-- PRESENCIAL -->

                                <div class="col-md-2 mb-3">

                                    <label
                                        for="PRESENCIAL"
                                        class="form-label"
                                    >
                                        Presencial
                                    </label>

                                    <select
                                        name="PRESENCIAL"
                                        id="PRESENCIAL"
                                        class="form-select"
                                    >

                                        <option value="">
                                            Seleccione
                                        </option>

                                        <option
                                            value="SI"
                                            <?= $presencialActual === 'SI' ? 'selected' : '' ?>
                                        >
                                            SI
                                        </option>

                                        <option
                                            value="NO"
                                            <?= $presencialActual === 'NO' ? 'selected' : '' ?>
                                        >
                                            NO
                                        </option>

                                    </select>

                                </div>

                            </div>


                            <!-- =================================================
                                 IPRESS
                                 ================================================= -->

                            <div class="row">

                                <div class="col-md-8 mb-3">

                                    <label
                                        for="IPRESS_BUSCAR"
                                        class="form-label"
                                    >
                                        IPRESS
                                    </label>

                                    <div class="position-relative">

                                        <input
                                            type="text"
                                            id="IPRESS_BUSCAR"
                                            class="form-control"
                                            value="<?= e($ocurrencia['IPRESS_ATENC'] ?? '') ?>"
                                            placeholder="Buscar por RENAES o nombre de IPRESS"
                                            autocomplete="off"
                                        >

                                        <div
                                            id="resultadoIpress"
                                            class="list-group position-absolute w-100"
                                            style="
                                                z-index:1050;
                                                max-height:250px;
                                                overflow-y:auto;
                                            "
                                        ></div>

                                    </div>


                                    <input
                                        type="hidden"
                                        name="IdEESS"
                                        id="IdEESS"
                                        value="<?= (int) ($ocurrencia['IdEESS'] ?? 0) ?>"
                                    >

                                </div>

                            </div>


                            <!-- =================================================
                                 DATOS DE LA IPRESS
                                 ================================================= -->

                            <div class="row">

                                <!-- UNIDAD EJECUTORA -->

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="UE"
                                        class="form-label"
                                    >
                                        Unidad Ejecutora
                                    </label>

                                    <input
                                        type="text"
                                        name="UE"
                                        id="UE"
                                        class="form-control"
                                        value="<?= e($ocurrencia['UE'] ?? '') ?>"
                                        readonly
                                    >

                                </div>


                                <!-- RED -->

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="RED"
                                        class="form-label"
                                    >
                                        Red
                                    </label>

                                    <input
                                        type="text"
                                        name="RED"
                                        id="RED"
                                        class="form-control"
                                        value="<?= e($ocurrencia['RED'] ?? '') ?>"
                                        readonly
                                    >

                                </div>


                                <!-- MICRORED -->

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="MICRORED"
                                        class="form-label"
                                    >
                                        Microred
                                    </label>

                                    <input
                                        type="text"
                                        name="MICRORED"
                                        id="MICRORED"
                                        class="form-control"
                                        value="<?= e($ocurrencia['MICRORED'] ?? '') ?>"
                                        readonly
                                    >

                                </div>


                                <!-- PROVINCIA -->

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="PROVINCIA"
                                        class="form-label"
                                    >
                                        Provincia
                                    </label>

                                    <input
                                        type="text"
                                        name="PROVINCIA"
                                        id="PROVINCIA"
                                        class="form-control"
                                        value="<?= e($provinciaActual) ?>"
                                        readonly
                                    >

                                </div>


                                <!-- DISTRITO -->

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="DISTRITO"
                                        class="form-label"
                                    >
                                        Distrito
                                    </label>

                                    <select
                                        name="DISTRITO"
                                        id="DISTRITO"
                                        class="form-select"
                                        data-actual="<?= e($distritoActual) ?>"
                                    >

                                        <option value="">
                                            Seleccione un Distrito
                                        </option>

                                        <?php if ($distritoActual !== ''): ?>

                                            <option
                                                value="<?= e($distritoActual) ?>"
                                                selected
                                            >
                                                <?= e($distritoActual) ?>
                                            </option>

                                        <?php endif; ?>

                                    </select>

                                    <div class="form-text">
                                        Distritos obtenidos de las IPRESS de Loreto.
                                    </div>

                                </div>

                            </div>


                            <!-- =================================================
                                 COMUNIDAD / DIRECCION / REFERENCIA
                                 ================================================= -->

                            <div class="row">

                                <!-- COMUNIDAD -->

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="COMUNIDAD"
                                        class="form-label"
                                    >
                                        Comunidad
                                    </label>

                                    <input
                                        type="text"
                                        name="COMUNIDAD"
                                        id="COMUNIDAD"
                                        class="form-control"
                                        maxlength="150"
                                        value="<?= e($ocurrencia['COMUNIDAD'] ?? '') ?>"
                                    >

                                </div>


                                <!-- DIRECCION -->

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="DIRECCION"
                                        class="form-label"
                                    >
                                        Dirección
                                    </label>

                                    <input
                                        type="text"
                                        name="DIRECCION"
                                        id="DIRECCION"
                                        class="form-control"
                                        maxlength="250"
                                        value="<?= e($ocurrencia['DIRECCION'] ?? '') ?>"
                                    >

                                </div>


                                <!-- REFERENCIA -->

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="REFERENCIA"
                                        class="form-label"
                                    >
                                        Referencia
                                    </label>

                                    <input
                                        type="text"
                                        name="REFERENCIA"
                                        id="REFERENCIA"
                                        class="form-control"
                                        maxlength="250"
                                        value="<?= e($ocurrencia['REFERENCIA'] ?? '') ?>"
                                    >

                                </div>

                            </div>


                            <!-- =================================================
                                 OBSERVACIONES
                                 ================================================= -->

                            <div class="row">

                                <div class="col-md-12 mb-3">

                                    <label
                                        for="OBSERVACIONES"
                                        class="form-label"
                                    >
                                        Observaciones
                                    </label>

                                    <textarea
                                        name="OBSERVACIONES"
                                        id="OBSERVACIONES"
                                        class="form-control"
                                        rows="3"
                                    ><?= e($ocurrencia['OBSERVACIONES'] ?? '') ?></textarea>

                                </div>

                            </div>


                            <!-- =================================================
                                 RESPONSABLE
                                 ================================================= -->

                            <div class="row">

                                <!-- PERSONA RESPONSABLE -->

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="PERSONA_RESPONSABLE"
                                        class="form-label"
                                    >
                                        Persona responsable
                                    </label>

                                    <input
                                        type="text"
                                        name="PERSONA_RESPONSABLE"
                                        id="PERSONA_RESPONSABLE"
                                        class="form-control"
                                        maxlength="150"
                                        value="<?= e($ocurrencia['PERSONA_RESPONSABLE'] ?? '') ?>"
                                    >

                                </div>


                                <!-- DNI RESPONSABLE -->

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="DNI_RESPONSABLE"
                                        class="form-label"
                                    >
                                        DNI responsable
                                    </label>

                                    <input
                                        type="text"
                                        name="DNI_RESPONSABLE"
                                        id="DNI_RESPONSABLE"
                                        class="form-control"
                                        maxlength="8"
                                        value="<?= e($ocurrencia['DNI_RESPONSABLE'] ?? '') ?>"
                                    >

                                </div>


                                <!-- TELEFONO RESPONSABLE -->

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="FONO_RESPONSABLE"
                                        class="form-label"
                                    >
                                        Teléfono responsable
                                    </label>

                                    <input
                                        type="text"
                                        name="FONO_RESPONSABLE"
                                        id="FONO_RESPONSABLE"
                                        class="form-control"
                                        maxlength="15"
                                        value="<?= e($ocurrencia['FONO_RESPONSABLE'] ?? '') ?>"
                                    >

                                </div>

                            </div>


                            <!-- =================================================
                                 TIPOS DE OCURRENCIA
                                 ================================================= -->

                            <div class="row">

                                <div class="col-md-12 mb-3">

                                    <div class="form-label">
                                    Tipos de ocurrencia
                                </div>

                                    <?php

                                    $idsTiposSeleccionados = array_map(
                                        'intval',
                                        $tiposSeleccionados ?? []
                                    );

                                    ?>

                                    <div
                                        id="tiposOcurrenciaEdicion"
                                        class="border rounded p-3"
                                    >

                                        <?php if (!empty($tipos)): ?>

                                            <div class="row">

                                                <?php foreach ($tipos as $tipo): ?>

                                                    <?php

                                                    $idTipo = (int) (
                                                        $tipo['IdTipoOcurrencia'] ?? 0
                                                    );

                                                    $seleccionado = in_array(
                                        $idTipo,
                                        $idsTiposSeleccionados,
                                        true
                                    );
                                                

                                    ?>

                <div class="col-md-6 mb-2">

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="tiposOcurrencia[]"
                            value="<?= $idTipo ?>"
                            id="tipoOcurrencia_<?= $idTipo ?>"
                            <?= $seleccionado ? 'checked' : '' ?>
                        >

                        <label
                            class="form-check-label"
                            for="tipoOcurrencia_<?= $idTipo ?>"
                        >
                            <?= e($tipo['Descripcion'] ?? '') ?>
                        </label>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="text-muted">
            No hay tipos de ocurrencia disponibles.
        </div>

    <?php endif; ?>

</div>

                                </div>

                            </div>


                            <!-- =================================================
                                 BOTONES
                                 ================================================= -->

                            <div class="d-flex justify-content-end gap-2 mt-4">

                                <a
                                    href="<?= url('ocurrenciasca') ?>"
                                    class="btn btn-secondary"
                                >
                                    Cancelar
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Actualizar ocurrencia
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>


<script>

/* =========================================================
   CONTROL DE GESTANTE Y FECHA PROBABLE DE PARTO
   ========================================================= */

function controlarDatosGestante()
{

    const sexoElement =
        document.getElementById('Sexo');

    const edadElement =
        document.getElementById('Edad');

    const gestanteElement =
        document.getElementById('GESTANTE');

    const fechaPartoElement =
        document.getElementById(
            'FECHA_PROBABLE_PARTO'
        );


    if (
        !sexoElement ||
        !gestanteElement ||
        !fechaPartoElement
    ) {

        return;

    }


    const sexo =
        String(
            sexoElement.value || ''
        )
        .trim()
        .toUpperCase();


    if (sexo === 'M') {

        gestanteElement.value = '';

        gestanteElement.disabled = true;

        fechaPartoElement.value = '';

        fechaPartoElement.disabled = true;

        return;

    }


    if (sexo === 'F') {

        gestanteElement.disabled = false;

    } else {

        gestanteElement.value = '';

        gestanteElement.disabled = true;

        fechaPartoElement.value = '';

        fechaPartoElement.disabled = true;

        return;

    }


    const gestante =
        String(
            gestanteElement.value || ''
        )
        .trim()
        .toUpperCase();


    if (gestante !== 'SI') {

        fechaPartoElement.value = '';

        fechaPartoElement.disabled = true;

        return;

    }


    const edadTexto =
        edadElement
            ? String(
                edadElement.value || ''
            )
            : '';


    const coincidencia =
        edadTexto.match(/\d+/);


    const edad =
        coincidencia
            ? parseInt(
                coincidencia[0],
                10
            )
            : null;


    if (
        sexo === 'F' &&
        edad !== null &&
        edad > 9 &&
        gestante === 'SI'
    ) {

        fechaPartoElement.disabled = false;

    } else {

        fechaPartoElement.value = '';

        fechaPartoElement.disabled = true;

    }

}


/* =========================================================
   FUNCIONES IPRESS
   ========================================================= */

function limpiarDatosIpress()
{

    const ue =
        document.getElementById('UE');

    const red =
        document.getElementById('RED');

    const microred =
        document.getElementById('MICRORED');

    const provincia =
        document.getElementById('PROVINCIA');

    const distrito =
        document.getElementById('DISTRITO');


    if (ue) {
        ue.value = '';
    }

    if (red) {
        red.value = '';
    }

    if (microred) {
        microred.value = '';
    }

    if (provincia) {
        provincia.value = '';
    }

    if (distrito) {

        distrito.innerHTML = `
            <option value="">
                Seleccione un Distrito
            </option>
        `;

    }

}


/* =========================================================
   CARGAR DATOS DE IPRESS
   ========================================================= */

function cargarDatosIpress(idEess)
{

    idEess =
        String(idEess || '').trim();


    if (idEess === '') {

        limpiarDatosIpress();

        return;

    }


    fetch(
        '/DRCYS/ajax/ipress.php?accion=obtener&idEESS=' +
        encodeURIComponent(idEess),
        {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        }
    )
    .then(function (respuesta) {

        if (!respuesta.ok) {

            throw new Error(
                'HTTP ' + respuesta.status
            );

        }

        return respuesta.json();

    })
    .then(function (datos) {

        if (
            !datos ||
            Array.isArray(datos)
        ) {

            limpiarDatosIpress();

            return;

        }


        const ue =
            document.getElementById('UE');

        const red =
            document.getElementById('RED');

        const microred =
            document.getElementById('MICRORED');

        const provincia =
            document.getElementById('PROVINCIA');


        if (ue) {

            ue.value =
                datos.UE ?? '';

        }

        if (red) {

            red.value =
                datos.RED ?? '';

        }

        if (microred) {

            microred.value =
                datos.MICRORED ?? '';

        }

        if (provincia) {

            provincia.value =
                datos.PROVINCIA ?? '';

        }


        cargarDistritos(
            datos.PROVINCIA ?? ''
        );

    })
    .catch(function (error) {

        console.error(
            'Error al obtener los datos de la IPRESS:',
            error
        );

        limpiarDatosIpress();

    });

}


/* =========================================================
   CARGAR DISTRITOS
   ========================================================= */

function cargarDistritos(provincia)
{

    const distrito =
        document.getElementById('DISTRITO');


    if (!distrito) {
        return;
    }


    provincia =
        String(provincia || '').trim();


    const distritoActual =
        distrito.dataset.actual || '';


    distrito.innerHTML = `
        <option value="">
            Cargando distritos...
        </option>
    `;


    if (provincia === '') {

        distrito.innerHTML = `
            <option value="">
                Seleccione un Distrito
            </option>
        `;

        return;

    }


    fetch(
        '/DRCYS/ajax/ipress.php?accion=distritos&provincia=' +
        encodeURIComponent(provincia),
        {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        }
    )
    .then(function (respuesta) {

        if (!respuesta.ok) {

            throw new Error(
                'HTTP ' + respuesta.status
            );

        }

        return respuesta.json();

    })
    .then(function (distritos) {

        distrito.innerHTML = `
            <option value="">
                Seleccione un Distrito
            </option>
        `;


        let encontrado = false;


        if (Array.isArray(distritos)) {

            distritos.forEach(
                function (item) {

                    if (
                        !item ||
                        !item.DISTRITO
                    ) {

                        return;

                    }


                    const opcion =
                        document.createElement('option');

                    opcion.value =
                        item.DISTRITO;

                    opcion.textContent =
                        item.DISTRITO;


                    if (
                        distritoActual !== '' &&
                        item.DISTRITO === distritoActual
                    ) {

                        opcion.selected = true;

                        encontrado = true;

                    }


                    distrito.appendChild(
                        opcion
                    );

                }
            );

        }


        /*
         * Conservamos el distrito existente si por alguna
         * razón ya no aparece en la lista de IPRESS.
         */

        if (
            distritoActual !== '' &&
            !encontrado
        ) {

            const opcionActual =
                document.createElement('option');

            opcionActual.value =
                distritoActual;

            opcionActual.textContent =
                distritoActual;

            opcionActual.selected = true;

            distrito.appendChild(
                opcionActual
            );

        }

    })
    .catch(function (error) {

        console.error(
            'Error al cargar distritos:',
            error
        );

        distrito.innerHTML = `
            <option value="">
                Seleccione un Distrito
            </option>
        `;


        if (distritoActual !== '') {

            const opcion =
                document.createElement('option');

            opcion.value =
                distritoActual;

            opcion.textContent =
                distritoActual;

            opcion.selected = true;

            distrito.appendChild(
                opcion
            );

        }

    });

}


/* =========================================================
   NRO. FORMATO
   ========================================================= */

function actualizarNroFormato()
{

    const lote =
        document.getElementById(
            'NroFormatoLote'
        );

    const codigo =
        document.getElementById(
            'NroFormatoCodigo'
        );

    const nroFormato =
        document.getElementById(
            'NroFormato'
        );


    if (
        !lote ||
        !codigo ||
        !nroFormato
    ) {

        return;

    }


    const valorLote =
        String(
            lote.value || ''
        )
        .trim()
        .toUpperCase();


    const valorCodigo =
        String(
            codigo.value || ''
        )
        .trim();


    const lotesPermitidos =
        [
            '2',
            'E',
            '3',
            '8',
            'R',
            '9'
        ];


    if (
        valorLote === '' ||
        !lotesPermitidos.includes(valorLote) ||
        valorCodigo === ''
    ) {

        nroFormato.value = '';

        return;

    }


    nroFormato.value =
        '240-' +
        valorLote +
        '-' +
        valorCodigo;

}


/* =========================================================
   SELECCIÓN DE IPRESS
   ========================================================= */

function procesarIpressSeleccionada()
{

    const idEessElement =
        document.getElementById('IdEESS');


    if (!idEessElement) {
        return;
    }


    const idEess =
        String(
            idEessElement.value || ''
        ).trim();


    if (idEess === '') {
        return;
    }


    const distrito =
        document.getElementById('DISTRITO');


    if (distrito) {

        /*
         * Al cambiar de IPRESS, el distrito debe
         * corresponder a la nueva provincia.
         */

        distrito.dataset.actual = '';

    }


    cargarDatosIpress(
        idEess
    );

}


/* =========================================================
   INICIALIZACIÓN
   ========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function ()
    {

        /* =====================================================
           GESTANTE / FPP
           ===================================================== */

        const gestante =
            document.getElementById(
                'GESTANTE'
            );

        const fechaParto =
            document.getElementById(
                'FECHA_PROBABLE_PARTO'
            );


        if (gestante) {

            gestante.addEventListener(
                'change',
                controlarDatosGestante
            );

        }


        /*
         * Se ejecuta después de cargar los valores existentes
         * para que Editar respete las mismas restricciones de Crear.
         */

        controlarDatosGestante();


        /* =====================================================
           NRO. FORMATO
           ===================================================== */

        const lote =
            document.getElementById(
                'NroFormatoLote'
            );

        const codigo =
            document.getElementById(
                'NroFormatoCodigo'
            );


        if (lote) {

            lote.addEventListener(
                'change',
                actualizarNroFormato
            );

        }


        if (codigo) {

            /*
             * Misma restricción de Crear:
             * máximo 10 caracteres.
             */

            codigo.addEventListener(
                'input',
                function ()
                {

                    if (codigo.value.length > 10) {

                        codigo.value =
                            codigo.value.substring(
                                0,
                                10
                            );

                    }

                    actualizarNroFormato();

                }
            );

        }


        /*
         * Construimos nuevamente el formato al cargar Editar.
         */

        actualizarNroFormato();


        /* =====================================================
           DISTRITO EXISTENTE
           ===================================================== */

        const distrito =
            document.getElementById(
                'DISTRITO'
            );

        if (distrito) {

            distrito.dataset.actual =
                distrito.value || '';

        }


        /* =====================================================
           IPRESS EXISTENTE
           ===================================================== */

        const idEess =
            document.getElementById(
                'IdEESS'
            );


        if (
            idEess &&
            idEess.value
        ) {

            cargarDatosIpress(
                idEess.value
            );

        }


        /* =====================================================
           CAMBIO DE IPRESS
           ===================================================== */

        const resultadoIpress =
            document.getElementById(
                'resultadoIpress'
            );


        if (resultadoIpress) {

            resultadoIpress.addEventListener(
                'click',
                function ()
                {

                    /*
                     * app.js coloca primero el IdEESS.
                     * Esperamos 50 ms antes de leerlo.
                     */

                    setTimeout(
                        function ()
                        {

                            procesarIpressSeleccionada();

                        },
                        50
                    );

                }
            );

        }

    }
);

</script>


<?php

require_once __DIR__ . '/../layouts/footer.php';

?>