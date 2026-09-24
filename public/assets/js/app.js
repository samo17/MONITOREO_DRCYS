$(document).ready(function () {

    /*
     * =========================================================
     * UE → RED
     * =========================================================
     */
    $('#COD_UE').on('change', function () {

        const codUE = $(this).val();
        const nombreUE = $(this).find(':selected').data('ue');

        const $red = $('#RED');
        const $microred = $('#MICRORED');

        $('#UE').val(nombreUE || '');

        $red.html(
            '<option value="">Cargando redes...</option>'
        );
        $red.prop('disabled', true);

        $microred.html(
            '<option value="">Seleccione primero una Red...</option>'
        );
        $microred.prop('disabled', true);

        if (codUE === '') {

            $red.html(
                '<option value="">Seleccione primero una UE...</option>'
            );

            return;
        }

        $.ajax({
            url: '/DRCYS/ajax/ipress.php',
            type: 'GET',
            dataType: 'json',
            data: {
                accion: 'redes',
                codUE: codUE
            },

            success: function (redes) {

                $red.empty();

                if (redes.length === 0) {

                    $red.append(
                        '<option value="">Sin redes disponibles</option>'
                    );

                    return;
                }

                $red.append(
                    '<option value="">Seleccione una Red...</option>'
                );

                $.each(redes, function (index, item) {

                    $red.append(
                        $('<option>', {
                            value: item.RED,
                            text: item.RED
                        })
                    );

                });

                $red.prop('disabled', false);
            },

            error: function () {

                $red.html(
                    '<option value="">Error al cargar las redes</option>'
                );

                $red.prop('disabled', true);

                alert('No fue posible cargar las redes.');
            }
        });

    });


    /*
     * =========================================================
     * RED → MICRORED
     * =========================================================
     */
    $('#RED').on('change', function () {

        const codUE = $('#COD_UE').val();
        const red = $(this).val();

        const $microred = $('#MICRORED');

        $microred.html(
            '<option value="">Cargando microredes...</option>'
        );

        $microred.prop('disabled', true);

        if (codUE === '' || red === '') {

            $microred.html(
                '<option value="">Seleccione primero una Red...</option>'
            );

            return;
        }

        $.ajax({
            url: '/DRCYS/ajax/ipress.php',
            type: 'GET',
            dataType: 'json',
            data: {
                accion: 'microredes',
                codUE: codUE,
                red: red
            },

            success: function (microredes) {

                $microred.empty();

                if (microredes.length === 0) {

                    $microred.append(
                        '<option value="">Sin microredes disponibles</option>'
                    );

                    return;
                }

                $microred.append(
                    '<option value="">Seleccione una Microred...</option>'
                );

                $.each(microredes, function (index, item) {

                    $microred.append(
                        $('<option>', {
                            value: item.MICRORED,
                            text: item.MICRORED
                        })
                    );

                });

                $microred.prop('disabled', false);
            },

            error: function () {

                $microred.html(
                    '<option value="">Error al cargar las microredes</option>'
                );

                $microred.prop('disabled', true);

                alert('No fue posible cargar las microredes.');
            }
        });

    });


    /*
     * =========================================================
     * PROVINCIA → DISTRITO
     * =========================================================
     */
    $('#PROVINCIA').on('change', function () {

        const provincia = $(this).val();
        const $distrito = $('#DISTRITO');

        $distrito.html(
            '<option value="">Cargando distritos...</option>'
        );

        $distrito.prop('disabled', true);

        if (provincia === '') {

            $distrito.html(
                '<option value="">Seleccione primero una provincia...</option>'
            );

            return;
        }

        $.ajax({
            url: '/DRCYS/ajax/ipress.php',
            type: 'GET',
            dataType: 'json',
            data: {
                accion: 'distritos',
                provincia: provincia
            },

            success: function (distritos) {

                $distrito.empty();

                if (distritos.length === 0) {

                    $distrito.append(
                        '<option value="">Sin distritos disponibles</option>'
                    );

                    return;
                }

                $distrito.append(
                    '<option value="">Seleccione un Distrito...</option>'
                );

                $.each(distritos, function (index, item) {

                    $distrito.append(
                        $('<option>', {
                            value: item.DISTRITO,
                            text: item.DISTRITO
                        })
                    );

                });

                $distrito.prop('disabled', false);
            },

            error: function () {

                $distrito.html(
                    '<option value="">Error al cargar los distritos</option>'
                );

                $distrito.prop('disabled', true);

                alert('No fue posible cargar los distritos.');
            }
        });

    });


    /*
     * =========================================================
     * EDICIÓN DE IPRESS
     * =========================================================
     */
    if ($('#formEditarIpress').length) {

        const codUEActual = $('#COD_UE').val();
        const redActual = $('#RED').val();
        const microredActual = $('#MICRORED').val();

        const provinciaActual = $('#PROVINCIA').val();
        const distritoActual = $('#DISTRITO').val();


        if (codUEActual !== '') {

            $.ajax({
                url: '/DRCYS/ajax/ipress.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    accion: 'redes',
                    codUE: codUEActual
                },

                success: function (redes) {

                    const $red = $('#RED');

                    $red.empty();

                    $red.append(
                        '<option value="">Seleccione una Red...</option>'
                    );

                    $.each(redes, function (index, item) {

                        const option = $('<option>', {
                            value: item.RED,
                            text: item.RED
                        });

                        if (item.RED === redActual) {
                            option.prop('selected', true);
                        }

                        $red.append(option);
                    });

                    $red.prop('disabled', false);


                    if (redActual !== '') {

                        $.ajax({
                            url: '/DRCYS/ajax/ipress.php',
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                accion: 'microredes',
                                codUE: codUEActual,
                                red: redActual
                            },

                            success: function (microredes) {

                                const $microred = $('#MICRORED');

                                $microred.empty();

                                $microred.append(
                                    '<option value="">Seleccione una Microred...</option>'
                                );

                                $.each(microredes, function (index, item) {

                                    const option = $('<option>', {
                                        value: item.MICRORED,
                                        text: item.MICRORED
                                    });

                                    if (item.MICRORED === microredActual) {
                                        option.prop('selected', true);
                                    }

                                    $microred.append(option);
                                });

                                $microred.prop('disabled', false);
                            }
                        });
                    }
                }
            });
        }


        if (provinciaActual !== '') {

            $.ajax({
                url: '/DRCYS/ajax/ipress.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    accion: 'distritos',
                    provincia: provinciaActual
                },

                success: function (distritos) {

                    const $distrito = $('#DISTRITO');

                    $distrito.empty();

                    $distrito.append(
                        '<option value="">Seleccione un Distrito...</option>'
                    );

                    $.each(distritos, function (index, item) {

                        const option = $('<option>', {
                            value: item.DISTRITO,
                            text: item.DISTRITO
                        });

                        if (item.DISTRITO === distritoActual) {
                            option.prop('selected', true);
                        }

                        $distrito.append(option);
                    });

                    $distrito.prop('disabled', false);
                }
            });
        }
    }


    /*
     * =========================================================
     * BÚSQUEDA DE AFILIADOS
     * =========================================================
     */

    let afiliadosEncontrados = [];


    $(document).on(
        'click',
        '#btnBuscarAfiliado',
        function () {

            const buscar = String(
                $('#DNI_PACIENTE_BUSCAR').val() || ''
            ).trim();

            if (buscar === '') {

                alert(
                    'Ingrese un DNI o nombre para buscar.'
                );

                $('#DNI_PACIENTE_BUSCAR').focus();

                return;
            }

            const boton = $(this);

            boton.prop('disabled', true);

            $('#resultadoAfiliados').html(`
                <div class="text-center text-muted py-4">
                    Buscando afiliados...
                </div>
            `);

            const modalElement =
                document.getElementById('modalAfiliados');

            if (!modalElement) {

                alert(
                    'No se encontró el modal de afiliados.'
                );

                boton.prop('disabled', false);

                return;
            }

            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );

            modal.show();


            $.ajax({

                url: '/DRCYS/ajax/afiliados.php',

                type: 'GET',

                dataType: 'json',

                data: {
                    buscar: buscar
                },

                success: function (respuesta) {

                    if (!respuesta.ok) {

                        $('#resultadoAfiliados').html(`
                            <div class="alert alert-warning">
                                ${respuesta.mensaje}
                            </div>
                        `);

                        return;
                    }

                    afiliadosEncontrados =
                        respuesta.datos || [];

                    if (
                        afiliadosEncontrados.length === 0
                    ) {

                        $('#resultadoAfiliados').html(`
                            <div class="alert alert-info">
                                No se encontraron afiliados con el criterio de búsqueda.
                            </div>
                        `);

                        return;
                    }

                    let html = `
                        <div class="table-responsive">

                            <table class="table table-striped table-hover align-middle">

                                <thead class="table-primary">

                                    <tr>
                                        <th>DNI</th>
                                        <th>Nombre</th>
                                        <th>Nro. Formato</th>
                                        <th>Estado</th>
                                        <th>EESS Adscrito</th>
                                        <th>Provincia</th>
                                        <th>Distrito</th>
                                        <th class="text-center">
                                            Acción
                                        </th>
                                    </tr>

                                </thead>

                                <tbody>
                    `;


                    afiliadosEncontrados.forEach(
                        function (afiliado, indice) {

                            const estado =
                                String(
                                    afiliado.Estado ?? ''
                                ).trim().toLowerCase();

                            const badgeEstado =
                                estado === 'activo'
                                    ? '<span class="badge bg-success">Activo</span>'
                                    : '<span class="badge bg-danger">Inactivo</span>';


                            html += `
                                <tr>

                                    <td>
                                        ${afiliado.NumeroDni ?? ''}
                                    </td>

                                    <td>
                                        ${afiliado.NombreAsegurado ?? ''}
                                    </td>

                                    <td>
                                        ${afiliado.NroFormato ?? ''}
                                    </td>

                                    <td>
                                        ${badgeEstado}
                                    </td>

                                    <td>
                                        ${afiliado.EessAdscrito ?? ''}
                                    </td>

                                    <td>
                                        ${afiliado.Provincia ?? ''}
                                    </td>

                                    <td>
                                        ${afiliado.Distrito ?? ''}
                                    </td>

                                    <td class="text-center">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-primary btnSeleccionarAfiliado"
                                            data-indice="${indice}"
                                        >
                                            Seleccionar
                                        </button>

                                    </td>

                                </tr>
                            `;

                        }
                    );


                    html += `
                                </tbody>

                            </table>

                        </div>
                    `;

                    $('#resultadoAfiliados').html(html);
                },

                error: function () {

                    $('#resultadoAfiliados').html(`
                        <div class="alert alert-danger">
                            No fue posible realizar la búsqueda de afiliados.
                        </div>
                    `);

                },

                complete: function () {

                    boton.prop('disabled', false);

                }

            });

        }
    );


    /*
     * =========================================================
     * ENTER → BUSCAR AFILIADO
     * =========================================================
     */

    $(document).on(
        'keydown',
        '#DNI_PACIENTE_BUSCAR',
        function (e) {

            if (e.key === 'Enter') {

                e.preventDefault();

                $('#btnBuscarAfiliado').trigger('click');
            }

        }
    );


    /*
     * =========================================================
     * SELECCIONAR AFILIADO
     * =========================================================
     */

    $(document).on(
        'click',
        '.btnSeleccionarAfiliado',
        function () {

            const indice = parseInt(
                $(this).data('indice'),
                10
            );

            const afiliado =
                afiliadosEncontrados[indice];

            if (!afiliado) {

                alert(
                    'No fue posible seleccionar el afiliado.'
                );

                return;
            }


            /*
             * Datos del afiliado
             */

            $('#IdAfiliados').val(
                afiliado.IdAfiliados ?? ''
            );

            $('#DNI_PACIENTE').val(
                afiliado.NumeroDni ?? ''
            );

            $('#NroFormato').val(
                afiliado.NroFormato ?? ''
            );

            $('#NombreAsegurado').val(
                afiliado.NombreAsegurado ?? ''
            );

            $('#Sexo').val(
                afiliado.Sexo ?? ''
            );

            $('#FechaNacimiento').val(
                afiliado.FechaNacimiento ?? ''
            );

            $('#EessAdscrito').val(
                afiliado.EessAdscrito ?? ''
            );

            $('#Provincia').val(
                afiliado.Provincia ?? ''
            );

            $('#Distrito').val(
                afiliado.Distrito ?? ''
            );


            /*
             * =================================================
             * CALCULAR EDAD
             * =================================================
             */

            const sexo =
                String(
                    afiliado.Sexo ?? ''
                ).trim().toUpperCase();

            const fechaNacimiento =
                afiliado.FechaNacimiento;

            let edad = null;

            $('#Edad').val('');


            if (fechaNacimiento) {

                const nacimiento =
                    new Date(fechaNacimiento);

                const hoy =
                    new Date();

                edad =
                    hoy.getFullYear() -
                    nacimiento.getFullYear();

                const mes =
                    hoy.getMonth() -
                    nacimiento.getMonth();

                if (
                    mes < 0 ||
                    (
                        mes === 0 &&
                        hoy.getDate() <
                        nacimiento.getDate()
                    )
                ) {

                    edad--;

                }

                $('#Edad').val(
                    edad + ' años'
                );
            }


            /*
             * =================================================
             * FECHA PROBABLE DE PARTO
             * =================================================
             */

            const $fechaParto =
                $('#FECHA_PROBABLE_PARTO');

            $fechaParto.val('');

            $fechaParto.prop(
                'disabled',
                true
            );


            if (
                sexo === 'F' &&
                edad !== null &&
                edad >= 9
            ) {

                $fechaParto.prop(
                    'disabled',
                    false
                );

            }


            /*
             * Mostrar datos del afiliado
             */

            $('#datosAfiliado').show();


            /*
             * Cerrar modal
             */

            const modalElement =
                document.getElementById(
                    'modalAfiliados'
                );

            const modal =
                bootstrap.Modal.getInstance(
                    modalElement
                );

            if (modal) {

                modal.hide();

            }

        }
    );


    /*
     * =========================================================
     * CARGAR TIPOS DE OCURRENCIA
     * =========================================================
     */

    if ($('#tiposOcurrencia').length) {

        $.ajax({

            url: '/DRCYS/ajax/ocurrenciasca.php',

            type: 'GET',

            dataType: 'json',

            data: {
                accion: 'tipos'
            },

            success: function (respuesta) {

                if (!respuesta.ok) {

                    $('#tiposOcurrencia').html(`
                        <div class="alert alert-warning mb-0">
                            ${respuesta.mensaje}
                        </div>
                    `);

                    return;
                }

                const tipos =
                    respuesta.datos || [];

                if (tipos.length === 0) {

                    $('#tiposOcurrencia').html(`
                        <div class="alert alert-info mb-0">
                            No hay tipos de ocurrencia activos.
                        </div>
                    `);

                    return;
                }

                let html =
                    '<div class="row">';


                tipos.forEach(
                    function (tipo) {

                        html += `
                            <div class="col-md-6 mb-2">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="tiposOcurrencia[]"
                                        value="${tipo.IdTipoOcurrencia}"
                                        id="tipoOcurrencia_${tipo.IdTipoOcurrencia}"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="tipoOcurrencia_${tipo.IdTipoOcurrencia}"
                                    >
                                        ${tipo.Descripcion}
                                    </label>

                                </div>

                            </div>
                        `;

                    }
                );


                html += '</div>';

                $('#tiposOcurrencia').html(html);
            },

            error: function () {

                $('#tiposOcurrencia').html(`
                    <div class="alert alert-danger mb-0">
                        No fue posible cargar los tipos de ocurrencia.
                    </div>
                `);

            }

        });

    }

    /*
 * =========================================================
 * CARGAR IPRESS EN OCURRENCIAS CA
 * =========================================================
 */

if ($('#IdEESS').length) {

    const $ipress = $('#IdEESS');

    $ipress.html(`
        <option value="">
            Cargando IPRESS...
        </option>
    `);

    $.ajax({

        url: '/DRCYS/ajax/ipress.php',

        type: 'GET',

        dataType: 'json',

        data: {
            accion: 'listar'
        },

        success: function (ipress) {

            $ipress.empty();

            $ipress.append(`
                <option value="">
                    Seleccione una IPRESS
                </option>
            `);

            if (!Array.isArray(ipress) || ipress.length === 0) {

                $ipress.append(`
                    <option value="">
                        No hay IPRESS activas
                    </option>
                `);

                return;
            }

            $.each(ipress, function (index, item) {

                $ipress.append(
                    $('<option>', {
                        value: item.IdEESS,
                        text: item.RENAES + ' - ' + item.IPRESS_ATENC
                    })
                );

            });

        },

        error: function () {

            $ipress.html(`
                <option value="">
                    Error al cargar las IPRESS
                </option>
            `);

            alert(
                'No fue posible cargar las IPRESS.'
            );

        }

    });

}

/*
 * =========================================================
 * CARGAR IPRESS EN OCURRENCIAS CA
 * =========================================================
 */

if ($('#IdEESS').length) {

    const $ipress = $('#IdEESS');

    $ipress.html(`
        <option value="">
            Cargando IPRESS...
        </option>
    `);

    $.ajax({

        url: '/DRCYS/ajax/ipress.php',

        type: 'GET',

        dataType: 'json',

        data: {
            accion: 'listar'
        },

        success: function (ipress) {

            $ipress.empty();

            $ipress.append(`
                <option value="">
                    Seleccione una IPRESS
                </option>
            `);

            if (!Array.isArray(ipress) || ipress.length === 0) {

                $ipress.append(`
                    <option value="">
                        No hay IPRESS activas
                    </option>
                `);

                return;
            }

            $.each(ipress, function (index, item) {

                $ipress.append(
                    $('<option>', {
                        value: item.IdEESS,
                        text: item.RENAES +
                            ' - ' +
                            item.IPRESS_ATENC
                    })
                );

            });

        },

        error: function () {

            $ipress.html(`
                <option value="">
                    Error al cargar las IPRESS
                </option>
            `);

            alert(
                'No fue posible cargar las IPRESS.'
            );

        }

    });

}

/*
 * =========================================================
 * BÚSQUEDA DE IPRESS
 * =========================================================
 */

let ipressEncontradas = [];


$(document).on(
    'input',
    '#IPRESS_BUSCAR',
    function () {

        const buscar = String(
            $(this).val() || ''
        ).trim();

        const $resultado =
            $('#resultadoIpress');

        $('#IdEESS').val('');

        if (buscar.length < 2) {

            $resultado.empty();

            return;
        }

        $resultado.html(`
            <div class="list-group-item text-muted">
                Buscando...
            </div>
        `);


        $.ajax({

            url: '/DRCYS/ajax/ipress.php',

            type: 'GET',

            dataType: 'json',

            data: {
                accion: 'buscar',
                buscar: buscar
            },

            success: function (ipress) {

                ipressEncontradas =
                    Array.isArray(ipress)
                        ? ipress
                        : [];

                $resultado.empty();


                if (ipressEncontradas.length === 0) {

                    $resultado.html(`
                        <div class="list-group-item text-muted">
                            No se encontraron IPRESS.
                        </div>
                    `);

                    return;
                }


                $.each(
                    ipressEncontradas,
                    function (indice, item) {

                        const $opcion =
                            $('<button>', {
                                type: 'button',
                                class: 'list-group-item list-group-item-action'
                            });


                        $opcion.html(`
                            <strong>
                                ${item.RENAES ?? ''}
                            </strong>
                            -
                            ${item.IPRESS_ATENC ?? ''}
                        `);


                        $opcion.attr(
                            'data-indice',
                            indice
                        );


                        $resultado.append(
                            $opcion
                        );

                    }
                );

            },

            error: function () {

                $resultado.html(`
                    <div class="list-group-item list-group-item-danger">
                        No fue posible buscar las IPRESS.
                    </div>
                `);

            }

        });

    }
);


/*
 * =========================================================
 * SELECCIONAR IPRESS
 * =========================================================
 */

$(document).on(
    'click',
    '#resultadoIpress button',
    function () {

        const indice = parseInt(
            $(this).attr('data-indice'),
            10
        );

        const ipress =
            ipressEncontradas[indice];


        if (!ipress) {

            return;
        }


        $('#IdEESS').val(
            ipress.IdEESS ?? ''
        );


        $('#IPRESS_BUSCAR').val(
            (ipress.RENAES ?? '') +
            ' - ' +
            (ipress.IPRESS_ATENC ?? '')
        );


        $('#resultadoIpress').empty();

    }
);

});