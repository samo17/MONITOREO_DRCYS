<?php

$titulo = 'Cargar Afiliados';

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
                        Cargar Afiliados
                    </h1>

                    <p class="text-muted mb-0">
                        Carga masiva de afiliados mediante archivo CSV
                    </p>

                </div>


                <!-- Información -->
                <div class="alert alert-info">

                    <strong>Importante:</strong>

                    El archivo CSV debe contener los 17 campos del maestro de afiliados.
                    Esta acción importa el contenido a la tabla de trabajo <strong>m_afiliados</strong>;
                    no modifica la tabla operativa <strong>afiliados</strong>.

                    <br>
                    Después de revisar la importación, ejecute por separado la opción
                    <strong>Sincronizar afiliados</strong>. La sincronización compara DNI + NroFormato,
                    evalúa fechas en formato yyyy-mm-dd y contempla coincidencias seguras de Lote E a Lote 2.
                    Los registros del destino no se eliminan.

                </div>


                <!-- Formulario -->
                <div class="card shadow-sm mb-4">

                    <div class="card-header">

                        <strong>
                            Seleccionar archivo CSV
                        </strong>

                    </div>

                    <div class="card-body">

                        <form
                            method="POST"
                            action="index.php?route=afiliados/analizar"
                            enctype="multipart/form-data"
                        >

                            <div class="row g-3 align-items-end">

                                <div class="col-md-8">

                                    <label
                                        for="archivo_csv"
                                        class="form-label"
                                    >
                                        Archivo CSV
                                    </label>

                                    <input
                                        type="file"
                                        name="archivo_csv"
                                        id="archivo_csv"
                                        class="form-control"
                                        accept=".csv,text/csv"
                                        required
                                    >

                                    <div class="form-text">
                                        Seleccione el archivo CSV que desea analizar.
                                    </div>

                                </div>


                                <div class="col-md-4 d-flex gap-2">

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Analizar archivo
                                    </button>

                                    <a
                                        href="index.php?route=afiliados"
                                        class="btn btn-secondary"
                                    >
                                        Regresar
                                    </a>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>


                <?php if (!empty($resultado)): ?>

                    <!-- Resultado del análisis -->
                    <div class="card shadow-sm mb-4">

                        <div class="card-header">

                            <strong>
                                Resultado del análisis
                            </strong>

                        </div>

                        <div class="card-body">

                            <div class="row g-3 mb-4">

                                <div class="col-md-4">

                                    <div class="alert alert-primary mb-0">

                                        <strong>Archivo:</strong><br>

                                        <?= e($nombreArchivo ?? '') ?>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="alert alert-success mb-0">

                                        <strong>Registros encontrados:</strong><br>

                                        <?= number_format((int) ($resultado['cantidadRegistros'] ?? 0)) ?>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="alert alert-secondary mb-0">

                                        <strong>Columnas:</strong><br>

                                        <?= number_format((int) ($resultado['cantidadColumnas'] ?? 0)) ?>

                                    </div>

                                </div>

                            </div>


                            <h5 class="mb-3">
                                Encabezados detectados
                            </h5>

                            <div class="table-responsive mb-4">

                                <table class="table table-bordered table-sm">

                                    <thead class="table-light">

                                        <tr>

                                            <th>
                                                #
                                            </th>

                                            <th>
                                                Campo
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php foreach (($resultado['encabezados'] ?? []) as $indice => $encabezado): ?>

                                            <tr>

                                                <td>
                                                    <?= $indice + 1 ?>
                                                </td>

                                                <td>
                                                    <?= e($encabezado) ?>
                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>


                            <h5 class="mb-3">
                                Vista previa
                            </h5>

                            <div class="table-responsive mb-4">

                                <table class="table table-bordered table-hover table-sm">

                                    <thead class="table-light">

                                        <tr>

                                            <?php foreach (($resultado['encabezados'] ?? []) as $encabezado): ?>

                                                <th>
                                                    <?= e($encabezado) ?>
                                                </th>

                                            <?php endforeach; ?>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php foreach (($resultado['vistaPrevia'] ?? []) as $fila): ?>

                                            <tr>

                                                <?php foreach ($fila as $valor): ?>

                                                    <td>
                                                        <?= e($valor) ?>
                                                    </td>

                                                <?php endforeach; ?>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>


                            <!-- Confirmación de carga -->
                            <div class="alert alert-warning">

                                <strong>Antes de cargar:</strong>

                                revise el archivo y confirme que la estructura
                                y los datos sean correctos.

                                Para realizar la carga, seleccione nuevamente
                                el mismo archivo CSV.

                            </div>


                            <form
                                method="POST"
                                action="index.php?route=afiliados/cargar-csv"
                                enctype="multipart/form-data"
                            >

                                <div class="row g-3 align-items-end">

                                    <div class="col-md-8">

                                        <label
                                            for="archivo_csv_confirmacion"
                                            class="form-label"
                                        >
                                            Confirmar archivo CSV
                                        </label>

                                        <input
                                            type="file"
                                            name="archivo_csv"
                                            id="archivo_csv_confirmacion"
                                            class="form-control"
                                            accept=".csv,text/csv"
                                            required
                                        >

                                    </div>


                                    <div class="col-md-4 d-flex gap-2">

                                        <button
                                            type="submit"
                                            class="btn btn-success"
                                            onclick="return confirm('¿Confirma importar este CSV a m_afiliados? Esta acción no modifica afiliados; la sincronización es independiente.');"
                                        >
                                            Importar a m_afiliados
                                        </button>

                                        <a
                                            href="index.php?route=afiliados"
                                            class="btn btn-secondary"
                                        >
                                            Cancelar
                                        </a>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                <?php endif; ?>


            </div>

        </main>

    </div>

</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
