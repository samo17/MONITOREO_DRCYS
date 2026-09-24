<?php

$titulo = 'Editar IPRESS';

$ipress = $ipress ?? null;
$unidadesEjecutoras = $unidadesEjecutoras ?? [];
$provincias = $provincias ?? [];

if (!$ipress) {
    die('No se encontraron datos de la IPRESS.');
}

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-3 col-lg-2 p-0">
            <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
        </div>

        <main class="col-md-9 col-lg-10">

            <div class="container-fluid py-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1">Editar IPRESS</h1>
                        <p class="text-muted mb-0">
                            Modificación de los datos de la IPRESS
                        </p>
                    </div>

                    <a href="<?= url('ipress') ?>"
                       class="btn btn-secondary">
                        Volver
                    </a>
                </div>

                <div class="card shadow-sm">

                    <div class="card-header bg-primary text-white">
                        <strong>Datos de la IPRESS</strong>
                    </div>

                    <div class="card-body">

                       <form method="POST"
                        action="<?= url('ipress/actualizar') ?>"
                        id="formEditarIpress">

                            <input type="hidden"
                                   name="IdEESS"
                                   value="<?= (int) $ipress['IdEESS'] ?>">

                            <div class="row g-3">

                                <!-- RENAES -->
                                <div class="col-md-4">
                                    <label for="RENAES" class="form-label">
                                        RENAES <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="RENAES"
                                           name="RENAES"
                                           value="<?= e($ipress['RENAES']) ?>"
                                           maxlength="8"
                                           required>
                                </div>

                                <!-- IPRESS -->
                                <div class="col-md-8">
                                    <label for="IPRESS_ATENC" class="form-label">
                                        Nombre de IPRESS
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="IPRESS_ATENC"
                                           name="IPRESS_ATENC"
                                           value="<?= e($ipress['IPRESS_ATENC']) ?>"
                                           maxlength="250"
                                           required>
                                </div>

                                <!-- TIPO -->
                                <div class="col-md-4">
                                    <label for="TIPO" class="form-label">
                                        Tipo <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="TIPO"
                                            name="TIPO"
                                            required>

                                        <option value="">
                                            Seleccione...
                                        </option>

                                        <option value="PP.DD"
                                            <?= $ipress['TIPO'] === 'PP.DD' ? 'selected' : '' ?>>
                                            PP.DD
                                        </option>

                                        <option value="IPRESS"
                                            <?= $ipress['TIPO'] === 'IPRESS' ? 'selected' : '' ?>>
                                            IPRESS
                                        </option>

                                    </select>
                                </div>

                                <!-- NIVEL -->
                                <div class="col-md-4">
                                    <label for="NIVEL" class="form-label">
                                        Nivel <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="NIVEL"
                                            name="NIVEL"
                                            required>

                                        <option value="">Seleccione...</option>

                                        <?php
                                        $niveles = [
                                            'I-1',
                                            'I-2',
                                            'I-3',
                                            'I-4',
                                            'II-1',
                                            'II-2',
                                            'III-1',
                                            'Sin Nivel'
                                        ];
                                        ?>

                                        <?php foreach ($niveles as $nivel): ?>

                                            <option value="<?= e($nivel) ?>"
                                                <?= $ipress['NIVEL'] === $nivel ? 'selected' : '' ?>>
                                                <?= e($nivel) ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>
                                </div>

                                <!-- CLASIFICACION -->
                                <div class="col-md-4">
                                    <label for="CLAS_NOCLAS" class="form-label">
                                        Clasificación
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="CLAS_NOCLAS"
                                            name="CLAS_NOCLAS"
                                            required>

                                        <option value="">
                                            Seleccione...
                                        </option>

                                        <option value="CLAS"
                                            <?= $ipress['CLAS_NOCLAS'] === 'CLAS' ? 'selected' : '' ?>>
                                            CLAS
                                        </option>

                                        <option value="NO CLAS"
                                            <?= $ipress['CLAS_NOCLAS'] === 'NO CLAS' ? 'selected' : '' ?>>
                                            NO CLAS
                                        </option>

                                    </select>
                                </div>

                                <!-- UE -->
                                <div class="col-md-6">
                                    <label for="COD_UE" class="form-label">
                                        Unidad Ejecutora
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="COD_UE"
                                            name="COD_UE"
                                            required>

                                        <option value="">
                                            Seleccione una UE...
                                        </option>

                                        <?php foreach ($unidadesEjecutoras as $ue): ?>

                                            <option value="<?= e($ue['COD_UE']) ?>"
                                                    data-ue="<?= e($ue['UE']) ?>"
                                                <?= $ipress['COD_UE'] === $ue['COD_UE'] ? 'selected' : '' ?>>

                                                <?= e($ue['UE']) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <input type="hidden"
                                           id="UE"
                                           name="UE"
                                           value="<?= e($ipress['UE']) ?>">
                                </div>

                                <!-- RED -->
                                <div class="col-md-6">
                                    <label for="RED" class="form-label">
                                        Red <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="RED"
                                            name="RED"
                                            required
                                            disabled>

                                        <option value="<?= e($ipress['RED']) ?>">
                                            <?= e($ipress['RED']) ?>
                                        </option>

                                    </select>
                                </div>

                                <!-- MICRORED -->
                                <div class="col-md-6">
                                    <label for="MICRORED" class="form-label">
                                        Microred
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="MICRORED"
                                            name="MICRORED"
                                            required
                                            disabled>

                                        <option value="<?= e($ipress['MICRORED']) ?>">
                                            <?= e($ipress['MICRORED']) ?>
                                        </option>

                                    </select>
                                </div>

                                <!-- COD_PPDD -->
                                <div class="col-md-6">
                                    <label for="COD_PPDD" class="form-label">
                                        Código PP.DD
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="COD_PPDD"
                                           name="COD_PPDD"
                                           value="<?= e($ipress['COD_PPDD']) ?>"
                                           maxlength="50">
                                </div>

                                <!-- PROVINCIA -->
                                <div class="col-md-6">
                                    <label for="PROVINCIA" class="form-label">
                                        Provincia
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="PROVINCIA"
                                            name="PROVINCIA"
                                            required>

                                        <option value="">
                                            Seleccione una provincia...
                                        </option>

                                        <?php foreach ($provincias as $provincia): ?>

                                            <option value="<?= e($provincia['PROVINCIA']) ?>"
                                                <?= $ipress['PROVINCIA'] === $provincia['PROVINCIA'] ? 'selected' : '' ?>>

                                                <?= e($provincia['PROVINCIA']) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>
                                </div>

                                <!-- DISTRITO -->
                                <div class="col-md-6">
                                    <label for="DISTRITO" class="form-label">
                                        Distrito
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="DISTRITO"
                                            name="DISTRITO"
                                            required
                                            disabled>

                                        <option value="<?= e($ipress['DISTRITO']) ?>">
                                            <?= e($ipress['DISTRITO']) ?>
                                        </option>

                                    </select>
                                </div>

                                <!-- GRUPO -->
                                <div class="col-md-6">
                                    <label for="GRUPO" class="form-label">
                                        Grupo <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="GRUPO"
                                            name="GRUPO"
                                            required>

                                        <option value="">
                                            Seleccione...
                                        </option>

                                        <option value="SIN POBLACION"
                                            <?= $ipress['GRUPO'] === 'SIN POBLACION' ? 'selected' : '' ?>>
                                            SIN POBLACION
                                        </option>

                                        <option value="CON POBLACION"
                                            <?= $ipress['GRUPO'] === 'CON POBLACION' ? 'selected' : '' ?>>
                                            CON POBLACION
                                        </option>

                                    </select>
                                </div>

                                <!-- ESTADO -->
                                <div class="col-md-6">
                                    <label for="ESTADO" class="form-label">
                                        Estado <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select"
                                            id="ESTADO"
                                            name="ESTADO"
                                            required>

                                        <option value="1"
                                            <?= (int) $ipress['ESTADO'] === 1 ? 'selected' : '' ?>>
                                            ACTIVO
                                        </option>

                                        <option value="0"
                                            <?= (int) $ipress['ESTADO'] === 0 ? 'selected' : '' ?>>
                                            INACTIVO
                                        </option>

                                    </select>
                                </div>

                                <!-- LATITUD -->
                                <div class="col-md-6">
                                    <label for="LATITUD_GEOGRAFICA" class="form-label">
                                        Latitud geográfica
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="LATITUD_GEOGRAFICA"
                                           name="LATITUD_GEOGRAFICA"
                                           value="<?= e($ipress['LATITUD_GEOGRAFICA']) ?>"
                                           maxlength="50">
                                </div>

                                <!-- LONGITUD -->
                                <div class="col-md-6">
                                    <label for="LONGITUD_GEOGRAFICA" class="form-label">
                                        Longitud geográfica
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="LONGITUD_GEOGRAFICA"
                                           name="LONGITUD_GEOGRAFICA"
                                           value="<?= e($ipress['LONGITUD_GEOGRAFICA']) ?>"
                                           maxlength="50">
                                </div>

                                <!-- ESTE -->
                                <div class="col-md-6">
                                    <label for="COORDENADA_ESTE" class="form-label">
                                        Coordenada Este
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="COORDENADA_ESTE"
                                           name="COORDENADA_ESTE"
                                           value="<?= e($ipress['COORDENADA_ESTE']) ?>"
                                           maxlength="50">
                                </div>

                                <!-- NORTE -->
                                <div class="col-md-6">
                                    <label for="COORDENADA_NORTE" class="form-label">
                                        Coordenada Norte
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           id="COORDENADA_NORTE"
                                           name="COORDENADA_NORTE"
                                           value="<?= e($ipress['COORDENADA_NORTE']) ?>"
                                           maxlength="50">
                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">

                                <a href="<?= url('ipress') ?>"
                                   class="btn btn-secondary">
                                    Cancelar
                                </a>

                                <button type="submit"
                                        class="btn btn-primary">
                                    Guardar cambios
                                </button>

                            </div>

                        </form>

                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>