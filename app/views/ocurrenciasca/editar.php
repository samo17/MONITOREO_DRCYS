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

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">IAFAS de procedencia</label>
                                    <input type="hidden" name="IdIAFAS" value="<?= (int)($ocurrencia['IdIAFAS'] ?? 0) ?>">
                                    <input type="text" class="form-control" value="<?= e($ocurrencia['IAFAS'] ?? 'SIS') ?>" readonly>
                                    <div class="form-text">La IAFAS de una ocurrencia existente se conserva al editar.</div>
                                </div>
                            </div>

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
                                        maxlength="8"
                                        minlength="8"
                                        pattern="[0-9]{8}"
                                        inputmode="numeric"
                                        value="<?= e($ocurrencia['DNI_PACIENTE'] ?? '') ?>"
                                        <?= empty($ocurrencia['IdAfiliados']) ? '' : 'readonly' ?>
                                        required
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
                                        name="NombrePaciente"
                                        id="NombreAsegurado"
                                        class="form-control"
                                        value="<?= e($ocurrencia['NombreAsegurado'] ?? '') ?>"
                                        <?= empty($ocurrencia['IdAfiliados']) ? '' : 'readonly' ?>
                                        required
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

                                    <select
                                            name="SexoPaciente"
                                            id="Sexo"
                                            class="form-select"
                                            required
                                        >
                                            <option value="">Seleccione</option>
                                            <option value="F" <?= strtoupper($ocurrencia['Sexo'] ?? '') === 'F' ? 'selected' : '' ?>>F</option>
                                            <option value="M" <?= strtoupper($ocurrencia['Sexo'] ?? '') === 'M' ? 'selected' : '' ?>>M</option>
                                        </select>

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
                                        name="FechaNacimientoPaciente"
                                        id="FechaNacimiento"
                                        class="form-control"
                                        value="<?= e($ocurrencia['FechaNacimiento'] ?? '') ?>"
                                        <?= empty($ocurrencia['IdAfiliados']) ? '' : 'readonly' ?>
                                        required
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
                                        type="number"
                                        min="0"
                                        step="1"
                                        inputmode="numeric"
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
                                        class="form-control bg-light"
                                        value="<?= e($ocurrencia['EessAdscrito'] ?? '') ?>"
                                        readonly
                                        <?= strtoupper(trim((string)($ocurrencia['IAFAS'] ?? 'SIS'))) !== 'SIS' ? 'disabled' : '' ?>
                                        aria-disabled="<?= strtoupper(trim((string)($ocurrencia['IAFAS'] ?? 'SIS'))) !== 'SIS' ? 'true' : 'false' ?>"
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
                                        readonly
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
                                        readonly
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
                                        readonly
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
                                        minlength="8"
                                        pattern="[0-9]{8}"
                                        inputmode="numeric"
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
        edad >= 9 &&
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



/* =========================================================
   NRO. FORMATO Y REGLA ESPECIAL DEL LOTE 2
   ========================================================= */

const lote = document.getElementById('NroFormatoLote');
const codigo = document.getElementById('NroFormatoCodigo');
const nroFormato = document.getElementById('NroFormato');
const dniAfiliado = document.getElementById('DNI_PACIENTE');

let loteAnterior = lote ? lote.value : '';

function actualizarNroFormato() {
    if (!lote || !codigo || !nroFormato) {
        return;
    }

    const valorLote = String(lote.value || '').trim().toUpperCase();
    const valorCodigo = String(codigo.value || '').trim();

    const lotesPermitidos = ['2', 'E', '3', '8', 'R', '9'];

    if (
        valorLote === '' ||
        !lotesPermitidos.includes(valorLote) ||
        valorCodigo === ''
    ) {
        nroFormato.value = '';
        return;
    }

    nroFormato.value = '240-' + valorLote + '-' + valorCodigo;
}

function aplicarReglaLote() {
    if (!lote || !codigo) {
        return;
    }

    const valorLote = String(lote.value || '').trim().toUpperCase();

    if (valorLote === '2') {
        // En edición, el código debe corresponder al DNI actual.
        codigo.readOnly = true;
        codigo.value = dniAfiliado
            ? String(dniAfiliado.value || '').trim()
            : '';
    } else {
        codigo.readOnly = false;

        // Si se cambia desde lote 2, se elimina el DNI
        // autocompletado para ingresar el nuevo código.
        if (loteAnterior === '2') {
            codigo.value = '';
        }
    }

    loteAnterior = valorLote;
    actualizarNroFormato();
}

if (lote) {
    lote.addEventListener('change', aplicarReglaLote);
}

if (codigo) {
    codigo.addEventListener('input', function () {
        if (codigo.value.length > 10) {
            codigo.value = codigo.value.substring(0, 10);
        }

        actualizarNroFormato();
    });
}

// Aplicar la regla al abrir el formulario de edición.
aplicarReglaLote();



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


<script>
/* Reglas de validación y automatización de datos de paciente/IPRESS/responsable. */
(function () {
    const form = document.querySelector('form[action*="ocurrenciasca/guardar"], form[action*="ocurrenciasca/actualizar"]');
    if (!form) return;

    const byId = id => document.getElementById(id);
    const dni = byId('DNI_PACIENTE');
    const nombre = byId('NombreAsegurado');
    const sexo = byId('Sexo');
    const nacimiento = byId('FechaNacimiento');
    const edad = byId('Edad');
    const gestante = byId('GESTANTE');
    const fpp = byId('FECHA_PROBABLE_PARTO');
    const presencial = byId('PRESENCIAL');
    const dniResponsable = byId('DNI_RESPONSABLE');
    const personaResponsable = byId('PERSONA_RESPONSABLE');
    const distrito = byId('DISTRITO');

    // El distrito se muestra bloqueado, pero se envía su valor mediante un hidden.
    let distritoHidden = null;
    if (distrito) {
        distritoHidden = form.querySelector('input[type="hidden"][name="DISTRITO"]');
        if (!distritoHidden) {
            distritoHidden = document.createElement('input');
            distritoHidden.type = 'hidden';
            distritoHidden.name = 'DISTRITO';
            form.appendChild(distritoHidden);
        }
        distritoHidden.value = distrito.value || '';
        distrito.removeAttribute('name');
        distrito.disabled = true;
    }

    // Bloquear campos derivados de IPRESS y datos geográficos.
    ['UE','RED','MICRORED','PROVINCIA','COMUNIDAD','DIRECCION','REFERENCIA','EessAdscrito'].forEach(id => {
        const el = byId(id);
        if (el) el.readOnly = true;
    });

    function calcularEdad() {
        if (!nacimiento || !edad || !nacimiento.value) {
            if (edad) edad.value = '';
            return null;
        }
        const fecha = new Date(nacimiento.value + 'T00:00:00');
        const hoy = new Date();
        if (Number.isNaN(fecha.getTime()) || fecha > hoy) {
            edad.value = '';
            return null;
        }
        let anios = hoy.getFullYear() - fecha.getFullYear();
        const mes = hoy.getMonth() - fecha.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) anios--;
        edad.value = String(anios);
        return anios;
    }

    function controlarGestante() {
        const valorSexo = String(sexo?.value || '').trim().toUpperCase();
        const anios = calcularEdad();
        if (sexo) sexo.value = valorSexo;
        if (valorSexo !== 'F') {
            if (gestante) { gestante.value = ''; gestante.disabled = true; }
            if (fpp) { fpp.value = ''; fpp.disabled = true; }
            return;
        }
        if (gestante) gestante.disabled = false;
        const puedeFpp = !!gestante && gestante.value === 'SI' && anios !== null && anios > 9;
        if (fpp) {
            fpp.disabled = !puedeFpp;
            if (!puedeFpp) fpp.value = '';
        }
    }

    function controlarResponsable() {
        const esPresencial = String(presencial?.value || '').trim().toUpperCase() === 'SI';
        calcularEdad();
        // La copia depende únicamente de Presencial = SI; aplica a SIS y Otras IAFAS,
        // independientemente de la edad del paciente.
        if (esPresencial) {
            if (dniResponsable) {
                dniResponsable.value = dni?.value || '';
                dniResponsable.dataset.autocompletadoPaciente = '1';
            }
            if (personaResponsable) {
                personaResponsable.value = nombre?.value || '';
                personaResponsable.dataset.autocompletadoPaciente = '1';
            }
        } else {
            if (dniResponsable?.dataset.autocompletadoPaciente === '1') {
                dniResponsable.value = '';
                delete dniResponsable.dataset.autocompletadoPaciente;
            }
            if (personaResponsable?.dataset.autocompletadoPaciente === '1') {
                personaResponsable.value = '';
                delete personaResponsable.dataset.autocompletadoPaciente;
            }
        }
    }

    function validarDniPaciente() {
        if (!dni) return true;
        dni.value = dni.value.replace(/\D/g, '').slice(0, 8);
        dni.setCustomValidity(/^\d{8}$/.test(dni.value) ? '' : 'El DNI debe contener exactamente 8 dígitos.');
        return dni.checkValidity();
    }
    function validarDniResponsable() {
        if (!dniResponsable) return;
        dniResponsable.value = dniResponsable.value.replace(/\D/g, '').slice(0, 8);
        dniResponsable.setCustomValidity(dniResponsable.value === '' || /^\d{8}$/.test(dniResponsable.value) ? '' : 'El DNI del responsable debe contener 8 dígitos.');
    }

    if (dni) dni.addEventListener('input', () => { validarDniPaciente(); controlarResponsable(); });
    if (dniResponsable) dniResponsable.addEventListener('input', validarDniResponsable);
    if (sexo) sexo.addEventListener('input', () => {
        sexo.value = sexo.value.toUpperCase().replace(/[^FM]/g, '').slice(0, 1);
        controlarGestante();
    });
    if (nacimiento) nacimiento.addEventListener('change', () => { calcularEdad(); controlarGestante(); controlarResponsable(); });
    if (gestante) gestante.addEventListener('change', controlarGestante);
    if (presencial) presencial.addEventListener('change', controlarResponsable);
    if (nombre) nombre.addEventListener('input', controlarResponsable);

    // Los datos del afiliado SIS son cargados por el buscador existente.
    document.addEventListener('click', ev => {
        if (ev.target.closest('.btnSeleccionarAfiliado')) {
            setTimeout(() => { calcularEdad(); controlarGestante(); controlarResponsable(); }, 150);
        }
    });
    if (distrito) {
        const observer = new MutationObserver(() => {
            distritoHidden.value = distrito.value || '';
        });
        observer.observe(distrito, { childList: true, subtree: true, attributes: true });
    }
    if (distrito) distrito.addEventListener('change', () => { distritoHidden.value = distrito.value || ''; });

    form.addEventListener('submit', ev => {
        calcularEdad();
        controlarGestante();
        controlarResponsable();
        validarDniPaciente();
        validarDniResponsable();
        if (!validarDniPaciente() || !form.checkValidity()) {
            ev.preventDefault();
            form.reportValidity();
        }
    });

    // Inicialización con datos existentes en edición o al volver de una validación.
    calcularEdad();
    controlarGestante();
    controlarResponsable();
})();
</script>

<?php

require_once __DIR__ . '/../layouts/footer.php';

?>

<script>
/* Reglas comunes SIS / Otras IAFAS */
(function () {
    const $ = (id) => document.getElementById(id);
    const fecha = $('FechaNacimiento');
    const edad = $('Edad');
    const sexo = $('Sexo');
    const gestante = $('GESTANTE');
    const parto = $('FECHA_PROBABLE_PARTO');
    const iafas = $('IdIAFAS');
    const eessAnterior = $('EessAdscrito');
    const presencial = $('PRESENCIAL');
    const dniPaciente = $('DNI_PACIENTE');
    const nombrePaciente = $('NombreAsegurado');
    const dniResponsable = $('DNI_RESPONSABLE');
    const personaResponsable = $('PERSONA_RESPONSABLE');
    const distrito = $('DISTRITO');

    function esSIS() {
        if (!iafas || !iafas.value) return false;
        const opt = iafas.options[iafas.selectedIndex];
        return !!opt && String(opt.dataset.nombre || '').trim().toUpperCase() === 'SIS';
    }

    function calcularEdad() {
        if (!fecha || !edad || !fecha.value) {
            if (edad) edad.value = '';
            actualizarGestante();
            actualizarResponsable();
            return null;
        }
        const nacimiento = new Date(fecha.value + 'T00:00:00');
        const hoy = new Date();
        if (Number.isNaN(nacimiento.getTime()) || nacimiento > hoy) {
            edad.value = '';
            actualizarGestante();
            actualizarResponsable();
            return null;
        }
        let anios = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) anios--;
        edad.value = String(anios);
        actualizarGestante();
        actualizarResponsable();
        return anios;
    }

    function actualizarGestante() {
        if (!sexo || !gestante || !parto) return;
        const s = String(sexo.value || '').trim().toUpperCase();
        const e = edad && edad.value !== '' ? Number(edad.value) : null;
        const habilitar = s === 'F' && e !== null && Number.isFinite(e) && e >= 9;
        gestante.disabled = !habilitar;
        if (!habilitar) {
            gestante.value = '';
            parto.value = '';
            parto.disabled = true;
            return;
        }
        const esGestante = String(gestante.value || '').trim().toUpperCase() === 'SI';
        parto.disabled = !esGestante;
        if (!esGestante) parto.value = '';
    }

    function actualizarResponsable() {
        if (!presencial || !dniPaciente || !nombrePaciente || !dniResponsable || !personaResponsable) return;
        const esPresencial = String(presencial.value || '').trim().toUpperCase() === 'SI';
        if (esPresencial) {
            dniResponsable.value = dniPaciente.value || '';
            personaResponsable.value = nombrePaciente.value || '';
            dniResponsable.dataset.autocompletadoPaciente = '1';
            personaResponsable.dataset.autocompletadoPaciente = '1';
        } else {
            if (dniResponsable.dataset.autocompletadoPaciente === '1') {
                dniResponsable.value = '';
                delete dniResponsable.dataset.autocompletadoPaciente;
            }
            if (personaResponsable.dataset.autocompletadoPaciente === '1') {
                personaResponsable.value = '';
                delete personaResponsable.dataset.autocompletadoPaciente;
            }
        }
    }

    function aplicarIAFAS() {
        const sis = esSIS();
        if (eessAnterior) {
            eessAnterior.readOnly = true;
            if (!sis) eessAnterior.value = '';
        }
        // Distrito debe poder seleccionarse para SIS y Otras IAFAS.
        if (distrito) {
            distrito.disabled = false;
            distrito.name = 'DISTRITO';
            const hiddenDistrito = document.querySelector('input[type="hidden"][name="DISTRITO"]');
            if (hiddenDistrito) hiddenDistrito.remove();
        }
        if (sexo) {
            sexo.disabled = false;
            sexo.required = true;
        }
        calcularEdad();
    }

    if (fecha) {
        fecha.addEventListener('input', calcularEdad);
        fecha.addEventListener('change', calcularEdad);
    }
    if (sexo) {
        sexo.addEventListener('change', actualizarGestante);
        sexo.addEventListener('input', actualizarGestante);
    }
    if (gestante) gestante.addEventListener('change', actualizarGestante);
    if (presencial) presencial.addEventListener('change', actualizarResponsable);
    if (dniPaciente) {
        dniPaciente.addEventListener('input', actualizarResponsable);
        dniPaciente.addEventListener('change', actualizarResponsable);
    }
    if (nombrePaciente) {
        nombrePaciente.addEventListener('input', actualizarResponsable);
        nombrePaciente.addEventListener('change', actualizarResponsable);
    }
    if (iafas) iafas.addEventListener('change', aplicarIAFAS);

    // Los datos SIS se rellenan por selección de afiliado mediante JS;
    // estos eventos vuelven a ejecutar las reglas tras la carga.
    [fecha, sexo, dniPaciente, nombrePaciente].forEach(el => {
        if (!el) return;
        el.addEventListener('blur', () => {
            calcularEdad();
            actualizarResponsable();
        });
    });

    aplicarIAFAS();
    calcularEdad();
})();
</script>


<script>
/* Control prioritario de Gestante / Fecha probable de parto. */
(function () {
    const sexo = document.getElementById('Sexo');
    const nacimiento = document.getElementById('FechaNacimiento');
    const edad = document.getElementById('Edad');
    const gestante = document.getElementById('GESTANTE');
    const fpp = document.getElementById('FECHA_PROBABLE_PARTO');
    if (!sexo || !gestante || !fpp) return;

    function calcularEdadActual() {
        if (!nacimiento || !nacimiento.value) return null;
        const n = new Date(nacimiento.value + 'T00:00:00');
        const h = new Date();
        if (Number.isNaN(n.getTime()) || n > h) return null;
        let a = h.getFullYear() - n.getFullYear();
        if (h.getMonth() < n.getMonth() ||
            (h.getMonth() === n.getMonth() && h.getDate() < n.getDate())) a--;
        if (edad) edad.value = String(a);
        return a;
    }

    function aplicarReglaGestante() {
        const s = String(sexo.value || '').trim().toUpperCase();
        const a = calcularEdadActual();
        const permitido = s === 'F' && a !== null && a >= 9;
        gestante.disabled = !permitido;
        if (!permitido) {
            gestante.value = '';
            fpp.value = '';
            fpp.disabled = true;
            return;
        }
        // Mantener la selección SI/NO; solo actualizar la fecha.
        const respuesta = String(gestante.value || '').trim().toUpperCase();
        fpp.disabled = respuesta !== 'SI';
        if (respuesta !== 'SI') fpp.value = '';
    }

    // Captura primero el evento para impedir que listeners anteriores borren SI/NO.
    document.addEventListener('change', function (ev) {
        if ([sexo, nacimiento, gestante].includes(ev.target)) {
            ev.stopImmediatePropagation();
            aplicarReglaGestante();
        }
    }, true);
    document.addEventListener('input', function (ev) {
        if ([sexo, nacimiento].includes(ev.target)) {
            ev.stopImmediatePropagation();
            aplicarReglaGestante();
        }
    }, true);

    aplicarReglaGestante();
})();
</script>


<!-- Validación estricta de DNI: bloquea letras y símbolos antes de que aparezcan. -->
<script>
(function () {
    'use strict';
    const ids = ['DNI_PACIENTE', 'DNI_RESPONSABLE'];
    const selectores = ids.map(id => '#' + id).join(',');

    function esCampoDni(el) {
        return el instanceof HTMLInputElement && ids.includes(el.id);
    }

    // Evita teclas no numéricas desde el teclado físico; permite navegación y atajos.
    document.addEventListener('keydown', function (ev) {
        if (!esCampoDni(ev.target) || ev.target.readOnly || ev.target.disabled) return;
        const permitidas = ['Backspace','Delete','Tab','Enter','Escape','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End'];
        if (permitidas.includes(ev.key) || ev.ctrlKey || ev.metaKey || ev.altKey) return;
        if (!/^\d$/.test(ev.key)) ev.preventDefault();
        else {
            const el = ev.target;
            const seleccion = el.selectionEnd - el.selectionStart;
            if (el.value.length - seleccion >= 8) ev.preventDefault();
        }
    }, true);

    // beforeinput bloquea letras antes de que se incorporen al valor.
    document.addEventListener('beforeinput', function (ev) {
        const el = ev.target;
        if (!esCampoDni(el) || el.readOnly || el.disabled || !ev.data) return;
        if (!/^\d+$/.test(ev.data)) ev.preventDefault();
        else {
            const seleccion = (el.selectionEnd || 0) - (el.selectionStart || 0);
            if (el.value.length - seleccion + ev.data.length > 8) ev.preventDefault();
        }
    }, true);

    // Pegar solo permite conservar dígitos; nunca introduce letras o símbolos.
    document.addEventListener('paste', function (ev) {
        const el = ev.target;
        if (!esCampoDni(el) || el.readOnly || el.disabled) return;
        ev.preventDefault();
        const texto = (ev.clipboardData || window.clipboardData).getData('text');
        const limpio = texto.replace(/\D/g, '');
        const inicio = el.selectionStart || 0;
        const fin = el.selectionEnd || 0;
        const disponible = 8 - (el.value.length - (fin - inicio));
        const insertar = limpio.slice(0, Math.max(0, disponible));
        el.setRangeText(insertar, inicio, fin, 'end');
        el.dispatchEvent(new Event('input', { bubbles: true }));
    }, true);

    // Respaldo para dictado, autocompletado, móviles y cambios programáticos del usuario.
    document.addEventListener('input', function (ev) {
        const el = ev.target;
        if (!esCampoDni(el) || el.readOnly || el.disabled) return;
        const limpio = el.value.replace(/\D/g, '').slice(0, 8);
        if (el.value !== limpio) {
            const posicion = el.selectionStart;
            el.value = limpio;
            if (typeof posicion === 'number') {
                const nuevaPosicion = Math.min(posicion, limpio.length);
                try { el.setSelectionRange(nuevaPosicion, nuevaPosicion); } catch (_) {}
            }
        }
        el.setCustomValidity(limpio.length === 8 ? '' : 'Ingrese exactamente 8 dígitos numéricos.');
    }, true);

    document.addEventListener('submit', function (ev) {
        const form = ev.target;
        if (!(form instanceof HTMLFormElement)) return;
        let valido = true;
        ids.forEach(id => {
            const el = form.querySelector('#' + id);
            if (!el || el.disabled || el.readOnly) return;
            el.value = el.value.replace(/\D/g, '').slice(0, 8);
            const requerido = el.required || el.value !== '';
            el.setCustomValidity(!requerido || /^\d{8}$/.test(el.value) ? '' : 'Ingrese exactamente 8 dígitos numéricos.');
            if (!el.checkValidity()) valido = false;
        });
        if (!valido) {
            ev.preventDefault();
            ev.stopImmediatePropagation();
        }
    }, true);
})();
</script>


<script>
/* Control de campos institucionales segun IAFAS */
(function () {
    'use strict';
    const selector = document.getElementById('IdIAFAS');
    if (!selector) return;
    const ipress = document.getElementById('IPRESS_BUSCAR');
    const resultados = document.getElementById('resultadoIpress');
    const idEess = document.getElementById('IdEESS');
    const ue = document.getElementById('UE');
    const red = document.getElementById('RED');
    const microred = document.getElementById('MICRORED');
    const provincia = document.getElementById('PROVINCIA');
    const distrito = document.getElementById('DISTRITO');
    const eessAnterior = document.getElementById('EessAdscrito');
    function esSIS() {
        const op = selector.options[selector.selectedIndex];
        return !!op && String(op.dataset.nombre || '').trim().toUpperCase() === 'SIS';
    }
    function limpiarSelect(el, texto) {
        if (!el) return;
        el.innerHTML = '';
        const op = document.createElement('option');
        op.value = ''; op.textContent = texto; el.appendChild(op);
    }
    function aplicar() {
        const seleccionada = selector.value !== '';
        const sis = seleccionada && esSIS();
        if (ipress) { ipress.disabled = !sis; ipress.classList.toggle('bg-light', !sis); if (!sis) ipress.value = ''; }
        if (resultados) { resultados.innerHTML = ''; resultados.style.display = sis ? '' : 'none'; }
        if (!sis && idEess) idEess.value = '';
        [ue, red, microred, provincia].forEach(function (el) { if (!el) return; el.readOnly = true; el.classList.add('bg-light'); if (!sis) el.value = ''; });
        if (distrito) {
            distrito.disabled = !sis; distrito.classList.toggle('bg-light', !sis);
            if (!sis) limpiarSelect(distrito, seleccionada ? 'No aplica para esta IAFAS' : 'Seleccione una IAFAS');
            else if (distrito.options.length && distrito.options[0].textContent.indexOf('No aplica') !== -1) limpiarSelect(distrito, 'Seleccione un Distrito');
        }
        if (eessAnterior) { eessAnterior.readOnly = true; eessAnterior.disabled = seleccionada && !sis; eessAnterior.classList.add('bg-light'); if (!sis) eessAnterior.value = ''; }
    }
    selector.addEventListener('change', aplicar);
    aplicar();
})();
</script>
