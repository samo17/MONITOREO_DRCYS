<?php

$titulo = 'Nueva ocurrencia CA';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

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
                            Nueva ocurrencia
                        </h1>

                        <p class="text-muted mb-0">
                            Registro de ocurrencias de la Central de Afiliación
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
                            action="<?= url('ocurrenciasca/guardar') ?>"
                        >

                            <!-- =================================================
                                 CAMPOS OCULTOS
                                 ================================================= -->

                            <input
                                type="hidden"
                                name="IdAfiliados"
                                id="IdAfiliados"
                                value=""
                            >

                            <input
                                type="hidden"
                                name="NroFormato"
                                id="NroFormato"
                                value=""
                            >


                            <!-- =================================================
                                 DATOS DEL AFILIADO
                                 ================================================= -->

                            <h5 class="mb-3">
                                Datos del afiliado
                            </h5>


                            <!-- BUSCAR AFILIADO -->

                            <div class="row">

                                <div class="col-md-8 mb-3">

                                    <label
                                        for="DNI_PACIENTE_BUSCAR"
                                        class="form-label"
                                    >
                                        Buscar afiliado
                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="text"
                                            id="DNI_PACIENTE_BUSCAR"
                                            class="form-control"
                                            placeholder="Ingrese DNI o nombre del asegurado"
                                            autocomplete="off"
                                        >

                                        <button
                                            type="button"
                                            id="btnBuscarAfiliado"
                                            class="btn btn-primary"
                                        >
                                            Buscar
                                        </button>

                                    </div>

                                </div>

                            </div>


                            <!-- =================================================
                                 DATOS DEL AFILIADO SELECCIONADO
                                 ================================================= -->

                            <div
                                id="datosAfiliado"
                                style="display:none;"
                            >

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
                                            readonly
                                        >

                                    </div>


                                    <!-- Nombre -->

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
                                            readonly
                                        >

                                    </div>


                                    <!-- Sexo -->

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
                                            readonly
                                        >

                                    </div>

                                </div>


                                <div class="row">

                                    <!-- Fecha nacimiento -->

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
                                            readonly
                                        >

                                    </div>


                                    <!-- Edad -->

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

                                            <option value="SI">
                                                SI
                                            </option>

                                            <option value="NO">
                                                NO
                                            </option>

                                        </select>

                                    </div>


                                    <!-- FECHA PROBABLE DE PARTO -->

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
                                            disabled
                                            autocomplete="off"
                                        >

                                        <div class="form-text">
                                            Solo se habilita si Sexo = F,
                                            Edad &gt; 9 y Gestante = SI.
                                        </div>

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

                                            <option value="2">
                                                2
                                            </option>

                                            <option value="E">
                                                E
                                            </option>

                                            <option value="3">
                                                3
                                            </option>

                                            <option value="8">
                                                8
                                            </option>

                                            <option value="R">
                                                R
                                            </option>

                                            <option value="9">
                                                9
                                            </option>

                                        </select>


                                        <input
                                            type="text"
                                            id="NroFormatoCodigo"
                                            class="form-control"
                                            maxlength="10"
                                            placeholder="Código"
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

                                        <option value="SI">
                                            SI
                                        </option>

                                        <option value="NO">
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

                                        <option value="SI">
                                            SI
                                        </option>

                                        <option value="NO">
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
                                        value=""
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
                                    >

                                        <option value="">
                                            Seleccione un Distrito
                                        </option>

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
                                    ></textarea>

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
                                    >

                                </div>

                            </div>


                            <!-- =================================================
                                 TIPOS DE OCURRENCIA
                                 ================================================= -->

                            <div class="row">

                                <div class="col-md-12 mb-3">

                                    <label class="form-label">
                                        Tipos de ocurrencia
                                    </label>

                                    <div
                                        id="tiposOcurrencia"
                                        class="border rounded p-3"
                                    >

                                        <div class="text-muted">
                                            Cargando tipos de ocurrencia...
                                        </div>

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
                                    Guardar ocurrencia
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>


<!-- =========================================================
     MODAL BUSCAR AFILIADOS
     ========================================================= -->

<div
    class="modal fade"
    id="modalAfiliados"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Buscar afiliado
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>

            </div>


            <div class="modal-body">

                <div
                    id="resultadoAfiliados"
                    class="table-responsive"
                >

                    <div class="text-muted">
                        Realice una búsqueda.
                    </div>

                </div>

            </div>

        </div>

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


function controlarFechaParto()
{
    controlarDatosGestante();
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

            console.error(
                'No se encontraron datos para la IPRESS seleccionada.'
            );

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


        if (!Array.isArray(distritos)) {
            return;
        }


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

                distrito.appendChild(
                    opcion
                );

            }
        );

    })
    .catch(function (error) {

        console.error(
            'Error al cargar distritos:',
            error
        );

        distrito.innerHTML = `
            <option value="">
                No fue posible cargar los distritos
            </option>
        `;

    });

}


/* =========================================================
   IPRESS SELECCIONADA
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


    cargarDatosIpress(
        idEess
    );

}


/* =========================================================
   FUNCIÓN PÚBLICA
   ========================================================= */

window.actualizarDatosIpressOcurrencia =
    function (datos)
    {

        if (!datos) {
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

    };


/* =========================================================
   INICIALIZACIÓN
   ========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function ()
    {

        const gestante =
            document.getElementById(
                'GESTANTE'
            );

        const fechaParto =
            document.getElementById(
                'FECHA_PROBABLE_PARTO'
            );


        if (gestante) {

            gestante.value = '';

            gestante.disabled = true;

            gestante.addEventListener(
                'change',
                controlarDatosGestante
            );

        }


        if (fechaParto) {

            fechaParto.value = '';

            fechaParto.disabled = true;

        }


        controlarDatosGestante();


        /*
         * =====================================================
         * SELECCIÓN DE AFILIADO
         * =====================================================
         *
         * app.js coloca Sexo y FechaNacimiento al seleccionar
         * el afiliado. Después de esa selección reevaluamos:
         *
         * Sexo = F  -> Gestante habilitado
         * Sexo = M  -> Gestante bloqueado
         * F + Edad > 9 + Gestante = SI -> FPP habilitada
         */

        $(document).on(
            'click',
            '.btnSeleccionarAfiliado',
            function ()
            {

                setTimeout(
                    function ()
                    {

                        controlarDatosGestante();

                    },
                    100
                );

            }
        );


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

        const nroFormato =
            document.getElementById(
                'NroFormato'
            );


        function actualizarNroFormato()
        {

            const valorLote =
                lote
                    ? String(
                        lote.value || ''
                    ).trim()
                    : '';

            const valorCodigo =
                codigo
                    ? String(
                        codigo.value || ''
                    ).trim()
                    : '';


            let resultado = '';


            if (
                valorLote !== '' &&
                valorCodigo !== ''
            ) {

                resultado =
                    '240-' +
                    valorLote +
                    '-' +
                    valorCodigo;

            }


            if (nroFormato) {

                nroFormato.value =
                    resultado;

            }

        }


        if (lote) {

            lote.addEventListener(
                'change',
                actualizarNroFormato
            );

        }


        if (codigo) {

            codigo.addEventListener(
                'input',
                actualizarNroFormato
            );

        }


        /* =====================================================
           SELECCIÓN DE IPRESS
           ===================================================== */

        const resultadoIpress =
            document.getElementById(
                'resultadoIpress'
            );


        if (resultadoIpress) {

            resultadoIpress.addEventListener(
                'click',
                function (evento)
                {

                    /*
                     * app.js primero coloca el IdEESS
                     * en el campo oculto.
                     *
                     * Esperamos unos milisegundos para
                     * asegurarnos de que ya se haya
                     * realizado esa asignación.
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