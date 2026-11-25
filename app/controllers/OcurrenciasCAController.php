<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/OcurrenciaCA.php';

class OcurrenciasCAController extends Controller
{
    private OcurrenciaCA $ocurrenciaModel;

    public function __construct()
    {
        $this->ocurrenciaModel = new OcurrenciaCA();
    }

    /**
     * Muestra una alerta legible cuando se intenta registrar una duplicidad.
     */
    private function mostrarAlertaDuplicidad(string $mensaje): void
    {
        http_response_code(409);
        $mensajeSeguro = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');
        echo '<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ocurrencia duplicada | DRCyS</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #f3f6fa; font-family: Arial, sans-serif; color: #263445; }
        .alert-card { width: 100%; max-width: 560px; background: #fff; border: 1px solid #f3d18a; border-top: 5px solid #d99a18; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(20, 40, 70, .10); }
        .alert-icon { width: 48px; height: 48px; display: grid; place-items: center; border-radius: 50%; background: #fff3cd; color: #8a5b00; font-size: 26px; font-weight: 700; margin-bottom: 16px; }
        h1 { font-size: 22px; margin: 0 0 12px; }
        p { line-height: 1.6; margin: 0 0 22px; color: #4b5968; }
        .status { display: inline-block; margin-bottom: 22px; padding: 6px 10px; border-radius: 20px; background: #fff3cd; color: #795400; font-size: 12px; font-weight: 700; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 11px 16px; border-radius: 7px; text-decoration: none; font-weight: 700; font-size: 14px; cursor: pointer; border: 0; }
        .btn-primary { background: #175cd3; color: #fff; }
        .btn-secondary { background: #eef2f6; color: #263445; }
        @media (max-width: 480px) { .alert-card { padding: 22px; } .actions .btn { width: 100%; text-align: center; } }
    </style>
</head>
<body>
    <main class="alert-card" role="alert" aria-live="assertive">
        <div class="alert-icon" aria-hidden="true">!</div>
        <h1>Ocurrencia duplicada</h1>
        <p>' . $mensajeSeguro . '</p>
        <span class="status">Registro no guardado</span>
        <div class="actions">
            <button class="btn btn-primary" type="button" onclick="history.back()">Volver al formulario</button>
            <a class="btn btn-secondary" href="index.php?route=ocurrenciasca">Ir al listado</a>
        </div>
    </main>
</body>
</html>';
        exit;
    }


    public function index(): void
    {
        Auth::requirePermission('OCURRENCIAS_CA');

        /*
         * Búsqueda general
         */
        $buscar = trim(
            $_GET['buscar'] ?? ''
        );

        /*
         * Responsable de afiliación
         */
        $dniResponsableAfiliacion =
            trim(
                $_GET['dni_responsable_afiliacion'] ?? ''
            );

        if ($dniResponsableAfiliacion === '') {
            $dniResponsableAfiliacion = null;
        }

        /*
         * Fecha inicio
         */
        $fechaInicio =
            trim(
                $_GET['fecha_inicio'] ?? ''
            );

        if ($fechaInicio === '') {
            $fechaInicio = null;
        }

        /*
         * Fecha fin
         */
        $fechaFin =
            trim(
                $_GET['fecha_fin'] ?? ''
            );

        if ($fechaFin === '') {
            $fechaFin = null;
        }

        /*
         * Unidad Ejecutora
         */
        $codUE =
            trim(
                $_GET['cod_ue'] ?? ''
            );

        if ($codUE === '') {
            $codUE = null;
        }

        /*
         * Validación de fechas
         */
        if (
            $fechaInicio !== null &&
            !preg_match(
                '/^\d{4}-\d{2}-\d{2}$/',
                $fechaInicio
            )
        ) {
            $fechaInicio = null;
        }

        if (
            $fechaFin !== null &&
            !preg_match(
                '/^\d{4}-\d{2}-\d{2}$/',
                $fechaFin
            )
        ) {
            $fechaFin = null;
        }

        /*
         * Si la fecha inicial es mayor que la final,
         * no aplicamos el rango.
         */
        if (
            $fechaInicio !== null &&
            $fechaFin !== null &&
            $fechaInicio > $fechaFin
        ) {
            $fechaInicio = null;
            $fechaFin = null;
        }

        /*
         * Paginación
         */
        $pagina = isset($_GET['pagina'])
            ? (int) $_GET['pagina']
            : 1;

        if ($pagina < 1) {
            $pagina = 1;
        }

        $porPagina = 10;

        /*
         * Total de registros
         */
        $totalOcurrencias =
            $this->ocurrenciaModel->contar(
                $buscar,
                $dniResponsableAfiliacion,
                $fechaInicio,
                $fechaFin,
                $codUE
            );

        /*
         * Total de páginas
         */
        $totalPaginas = max(
            1,
            (int) ceil(
                $totalOcurrencias / $porPagina
            )
        );

        if ($pagina > $totalPaginas) {
            $pagina = $totalPaginas;
        }

        /*
         * Offset
         */
        $offset =
            ($pagina - 1) * $porPagina;

        /*
         * Listado
         */
        $ocurrencias =
            $this->ocurrenciaModel->listar(
                $buscar,
                $dniResponsableAfiliacion,
                $fechaInicio,
                $fechaFin,
                $codUE,
                $porPagina,
                $offset
            );

        /*
         * Responsables de afiliación
         */
        $responsables =
            $this->ocurrenciaModel
                ->listarResponsablesAfiliacion();

        /*
         * Unidades ejecutoras
         */
        $unidadesEjecutoras =
            $this->ocurrenciaModel
                ->listarUnidadesEjecutoras();

        /*
         * Vista
         */
        $this->view(
            'ocurrenciasca/index',
            [
                'ocurrencias' =>
                    $ocurrencias,

                'buscar' =>
                    $buscar,

                'dniResponsableAfiliacion' =>
                    $dniResponsableAfiliacion,

                'fechaInicio' =>
                    $fechaInicio,

                'fechaFin' =>
                    $fechaFin,

                'codUE' =>
                    $codUE,

                'unidadesEjecutoras' =>
                    $unidadesEjecutoras,

                'responsables' =>
                    $responsables,

                'pagina' =>
                    $pagina,

                'porPagina' =>
                    $porPagina,

                'totalOcurrencias' =>
                    $totalOcurrencias,

                'totalPaginas' =>
                    $totalPaginas
            ]
        );
    }



    /**
     * Consulta de una ocurrencia en modo lectura.
     */
    public function detalle(): void
    {
        Auth::requirePermission('OCURRENCIAS_CA');

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            Session::flash('error', 'Ocurrencia no válida.');
            redirect('ocurrenciasca');
        }

        $ocurrencia = $this->ocurrenciaModel->buscarPorId($id);

        if (!$ocurrencia) {
            Session::flash('error', 'La ocurrencia solicitada no existe.');
            redirect('ocurrenciasca');
        }

        $this->view('ocurrenciasca/detalle', [
            'ocurrencia' => $ocurrencia
        ]);
    }

    public function crear(): void
    {
        Auth::requirePermission('OCURRENCIAS_CA');

        $iafas = $this->ocurrenciaModel->listarIafasActivas();
        $this->view('ocurrenciasca/crear', ['iafas' => $iafas]);
    }


    public function guardar(): void
    {
        Auth::requirePermission('OCURRENCIAS_CA');

        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST'
        ) {
            $this->redirect(
                'index.php?route=ocurrenciasca'
            );
        }

        $idAfiliados = (int) ($_POST['IdAfiliados'] ?? 0);
        $idIAFAS = (int) ($_POST['IdIAFAS'] ?? 0);
        $nombrePaciente = trim($_POST['NombrePaciente'] ?? '');
        $sexoPaciente = trim($_POST['SexoPaciente'] ?? '');
        $fechaNacimientoPaciente = trim($_POST['FechaNacimientoPaciente'] ?? '');

        $dniPaciente =
            trim(
                $_POST['DNI_PACIENTE'] ?? ''
            );

        $fechaProbableParto =
            trim(
                $_POST['FECHA_PROBABLE_PARTO'] ?? ''
            );

        $nroFormato =
            trim(
                $_POST['NroFormato'] ?? ''
            );

        $gestante =
            trim(
                $_POST['GESTANTE'] ?? ''
            );

        $emergencias =
            trim(
                $_POST['EMERGENCIAS'] ?? ''
            );

        $presencial =
            trim(
                $_POST['PRESENCIAL'] ?? ''
            );

        $distrito =
            trim(
                $_POST['DISTRITO'] ?? ''
            );

        $comunidad =
            trim(
                $_POST['COMUNIDAD'] ?? ''
            );

        $direccion =
            trim(
                $_POST['DIRECCION'] ?? ''
            );

        $referencia =
            trim(
                $_POST['REFERENCIA'] ?? ''
            );

        $idEess =
            isset($_POST['IdEESS'])
                ? (int) $_POST['IdEESS']
                : 0;

        $observaciones =
            trim(
                $_POST['OBSERVACIONES'] ?? ''
            );

        $personaResponsable =
            trim(
                $_POST['PERSONA_RESPONSABLE'] ?? ''
            );

        $dniResponsable =
            trim(
                $_POST['DNI_RESPONSABLE'] ?? ''
            );

        $fonoResponsable =
            trim(
                $_POST['FONO_RESPONSABLE'] ?? ''
            );

        $tipos =
            $_POST['tiposOcurrencia'] ?? [];

        if (!is_array($tipos)) {
            $tipos = [];
        }

        $dniResponsableAfiliacion =
            Auth::dni();

        if (
            $dniResponsableAfiliacion === null ||
            trim($dniResponsableAfiliacion) === ''
        ) {
            die(
                'No se pudo identificar al usuario responsable de afiliación.'
            );
        }

        $iafasSeleccionada = $this->ocurrenciaModel->buscarIAFAS($idIAFAS);
        if (!$iafasSeleccionada || (int)$iafasSeleccionada['Estado'] !== 1) {
            die('Debe seleccionar una IAFAS activa.');
        }
        $esSIS = strtoupper(trim($iafasSeleccionada['Nombre'])) === 'SIS';
        if ($esSIS && $idAfiliados <= 0) die('Debe buscar y seleccionar un afiliado SIS.');
        if (!$esSIS) {
            $idAfiliados = null;
            $idEess = null;
            $distrito = '';
            if ($nombrePaciente === '' || $sexoPaciente === '' || $fechaNacimientoPaciente === '') {
                die('Para Otras IAFAS debe registrar nombres y apellidos, sexo y fecha de nacimiento.');
            }
            $nroFormato = '';
        }
        // Validar el valor original antes de cualquier normalización:
        // no aceptar letras, espacios ni caracteres especiales.
        if (!preg_match('/^[0-9]{8}$/', $dniPaciente)) {
            die('El DNI del paciente debe contener exactamente 8 números, sin letras ni caracteres especiales.');
        }
        $dniPaciente = $dniPaciente;

        if ($dniResponsable !== '' && !preg_match('/^[0-9]{8}$/', $dniResponsable)) {
            die('El DNI del responsable debe contener exactamente 8 números, sin letras ni caracteres especiales.');
        }
        if (!in_array(strtoupper($sexoPaciente), ['F', 'M'], true)) {
            die('El sexo del paciente debe ser F o M.');
        }
        $sexoPaciente = strtoupper($sexoPaciente);
        if ($fechaNacimientoPaciente === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaNacimientoPaciente)) {
            die('Ingrese una fecha de nacimiento válida.');
        }
        try {
            $fechaNac = new DateTime($fechaNacimientoPaciente);
            $hoyValidacion = new DateTime('today');
            if ($fechaNac > $hoyValidacion) die('La fecha de nacimiento no puede ser futura.');
            $edadPaciente = $fechaNac->diff($hoyValidacion)->y;
        } catch (Throwable $e) {
            die('La fecha de nacimiento no es válida.');
        }

        /*
         * Normalizar fecha probable de parto
         */
        if ($fechaProbableParto === '') $fechaProbableParto = null;
        if ($nombrePaciente === '') $nombrePaciente = null;
        if ($sexoPaciente === '') $sexoPaciente = null;
        if ($fechaNacimientoPaciente === '') $fechaNacimientoPaciente = null;

        /*
         * Normalizar IPRESS
         */
        if ($idEess <= 0) {
            $idEess = null;
        }

        /*
         * Normalizar campos propios de la ocurrencia
         */
        $nroFormato =
            $nroFormato !== ''
                ? $nroFormato
                : null;

        $gestante =
            in_array($gestante, ['SI', 'NO'], true)
                ? $gestante
                : null;

        $emergencias =
            in_array($emergencias, ['SI', 'NO'], true)
                ? $emergencias
                : null;

        $presencial =
            in_array($presencial, ['SI', 'NO'], true)
                ? $presencial
                : null;

        // Si el paciente es adulto y la atención es presencial, el responsable
        // se identifica con los propios datos del paciente (SIS y otras IAFAS).
        if ($presencial === 'SI' && isset($edadPaciente) && $edadPaciente >= 18) {
            $dniResponsable = $dniPaciente;
            $personaResponsable = (string) ($nombrePaciente ?? '');
        }

        $distrito =
            $distrito !== ''
                ? $distrito
                : null;

        /*
         * Normalizar campos opcionales
         */
        $comunidad =
            $comunidad !== ''
                ? $comunidad
                : null;

        $direccion =
            $direccion !== ''
                ? $direccion
                : null;

        $referencia =
            $referencia !== ''
                ? $referencia
                : null;

        $observaciones =
            $observaciones !== ''
                ? $observaciones
                : null;

        $personaResponsable =
            $personaResponsable !== ''
                ? $personaResponsable
                : null;

        $dniResponsable =
            $dniResponsable !== ''
                ? $dniResponsable
                : null;

        $fonoResponsable =
            $fonoResponsable !== ''
                ? $fonoResponsable
                : null;

        /*
         * Eliminar tipos repetidos
         */
        $tipos = array_values(
            array_unique(
                array_map(
                    'intval',
                    $tipos
                )
            )
        );

        $duplicado = $esSIS
            ? $this->ocurrenciaModel->existeDuplicado($idAfiliados, $tipos)
            : $this->ocurrenciaModel->existeDuplicadoPorDNI($idIAFAS, $dniPaciente, $tipos);
        if ($duplicado) $this->mostrarAlertaDuplicidad('El paciente ya tiene registrada una ocurrencia del mismo tipo en la fecha de hoy.');

        $this->ocurrenciaModel
            ->crearConTipos(
                $idAfiliados,
                $dniPaciente,
                $fechaProbableParto,
                $comunidad,
                $direccion,
                $referencia,
                $idEess,
                $observaciones,
                $personaResponsable,
                $dniResponsable,
                $fonoResponsable,
                $dniResponsableAfiliacion,
                $tipos,
                $nroFormato,
                $gestante,
                $emergencias,
                $presencial,
                $distrito,
                $idIAFAS,
                $nombrePaciente,
                $sexoPaciente,
                $fechaNacimientoPaciente
            );

        $this->redirect(
            'index.php?route=ocurrenciasca'
        );
    }


    public function editar(): void
    {
        Auth::requirePermission('OCURRENCIAS_CA');

        $idOcurrencia =
            isset($_GET['id'])
                ? (int) $_GET['id']
                : 0;

        if ($idOcurrencia <= 0) {
            die(
                'Ocurrencia no válida.'
            );
        }

        $ocurrencia =
            $this->ocurrenciaModel
                ->buscarPorId(
                    $idOcurrencia
                );

        if (!$ocurrencia) {
            die(
                'Ocurrencia no encontrada.'
            );
        }

        $tiposSeleccionados =
            $this->ocurrenciaModel
                ->listarTipos(
                    $idOcurrencia
                );
                

        $tipos =
            $this->ocurrenciaModel
                ->listarTiposActivos();

        $this->view(
            'ocurrenciasca/editar',
            [
                'ocurrencia' =>
                    $ocurrencia,

                'tipos' =>
                    $tipos,

                'tiposSeleccionados' =>
                    $tiposSeleccionados,
                'iafas' => $this->ocurrenciaModel->listarIafasActivas()
            ]
        );
    }


    public function actualizar(): void
    {
        Auth::requirePermission('OCURRENCIAS_CA');

        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST'
        ) {
            $this->redirect(
                'index.php?route=ocurrenciasca'
            );
        }

        $idOcurrencia =
            isset($_POST['IdOcurrencia'])
                ? (int) $_POST['IdOcurrencia']
                : 0;

        $idAfiliados = (int) ($_POST['IdAfiliados'] ?? 0);
        $idIAFAS = (int) ($_POST['IdIAFAS'] ?? 0);
        $nombrePaciente = trim($_POST['NombrePaciente'] ?? '');
        $sexoPaciente = trim($_POST['SexoPaciente'] ?? '');
        $fechaNacimientoPaciente = trim($_POST['FechaNacimientoPaciente'] ?? '');

        $dniPaciente =
            trim(
                $_POST['DNI_PACIENTE'] ?? ''
            );

        $fechaProbableParto =
            trim(
                $_POST['FECHA_PROBABLE_PARTO'] ?? ''
            );

        $nroFormato =
            trim(
                $_POST['NroFormato'] ?? ''
            );

        $gestante =
            trim(
                $_POST['GESTANTE'] ?? ''
            );

        $emergencias =
            trim(
                $_POST['EMERGENCIAS'] ?? ''
            );

        $presencial =
            trim(
                $_POST['PRESENCIAL'] ?? ''
            );

        $distrito =
            trim(
                $_POST['DISTRITO'] ?? ''
            );

        $comunidad =
            trim(
                $_POST['COMUNIDAD'] ?? ''
            );

        $direccion =
            trim(
                $_POST['DIRECCION'] ?? ''
            );

        $referencia =
            trim(
                $_POST['REFERENCIA'] ?? ''
            );

        $idEess =
            isset($_POST['IdEESS'])
                ? (int) $_POST['IdEESS']
                : 0;

        $observaciones =
            trim(
                $_POST['OBSERVACIONES'] ?? ''
            );

        $personaResponsable =
            trim(
                $_POST['PERSONA_RESPONSABLE'] ?? ''
            );

        $dniResponsable =
            trim(
                $_POST['DNI_RESPONSABLE'] ?? ''
            );

        $fonoResponsable =
            trim(
                $_POST['FONO_RESPONSABLE'] ?? ''
            );

        $tipos =
            $_POST['tiposOcurrencia'] ?? [];

        if (!is_array($tipos)) {
            $tipos = [];
        }

        if ($idOcurrencia <= 0) {
            die(
                'Ocurrencia no válida.'
            );
        }

        $iafasSeleccionada = $this->ocurrenciaModel->buscarIAFAS($idIAFAS);
        if (!$iafasSeleccionada) die('La IAFAS seleccionada no existe.');
        $esSIS = strtoupper(trim($iafasSeleccionada['Nombre'])) === 'SIS';
        if ($esSIS && $idAfiliados <= 0) die('La ocurrencia SIS debe estar vinculada a un afiliado.');
        if (!$esSIS) {
            $idAfiliados = null;
            $idEess = null;
            $distrito = '';
            if ($nombrePaciente === '' || $sexoPaciente === '' || $fechaNacimientoPaciente === '') die('Complete nombres y apellidos, sexo e fecha de nacimiento.');
            $nroFormato = '';
        }
        // Validar el valor original antes de cualquier normalización:
        // no aceptar letras, espacios ni caracteres especiales.
        if (!preg_match('/^[0-9]{8}$/', $dniPaciente)) {
            die('El DNI del paciente debe contener exactamente 8 números, sin letras ni caracteres especiales.');
        }
        $dniPaciente = $dniPaciente;

        if ($dniResponsable !== '' && !preg_match('/^[0-9]{8}$/', $dniResponsable)) {
            die('El DNI del responsable debe contener exactamente 8 números, sin letras ni caracteres especiales.');
        }
        if (!in_array(strtoupper($sexoPaciente), ['F', 'M'], true)) {
            die('El sexo del paciente debe ser F o M.');
        }
        $sexoPaciente = strtoupper($sexoPaciente);
        if ($fechaNacimientoPaciente === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaNacimientoPaciente)) {
            die('Ingrese una fecha de nacimiento válida.');
        }
        try {
            $fechaNac = new DateTime($fechaNacimientoPaciente);
            $hoyValidacion = new DateTime('today');
            if ($fechaNac > $hoyValidacion) die('La fecha de nacimiento no puede ser futura.');
            $edadPaciente = $fechaNac->diff($hoyValidacion)->y;
        } catch (Throwable $e) {
            die('La fecha de nacimiento no es válida.');
        }

        /*
         * Normalizar fecha probable de parto
         */
        if ($fechaProbableParto === '') $fechaProbableParto = null;
        if ($nombrePaciente === '') $nombrePaciente = null;
        if ($sexoPaciente === '') $sexoPaciente = null;
        if ($fechaNacimientoPaciente === '') $fechaNacimientoPaciente = null;

        /*
         * Normalizar IPRESS
         */
        if ($idEess <= 0) {
            $idEess = null;
        }

        /*
         * Normalizar campos propios de la ocurrencia
         */
        $nroFormato =
            $nroFormato !== ''
                ? $nroFormato
                : null;

        $gestante =
            in_array($gestante, ['SI', 'NO'], true)
                ? $gestante
                : null;

        $emergencias =
            in_array($emergencias, ['SI', 'NO'], true)
                ? $emergencias
                : null;

        $presencial =
            in_array($presencial, ['SI', 'NO'], true)
                ? $presencial
                : null;

        // Si el paciente es adulto y la atención es presencial, el responsable
        // se identifica con los propios datos del paciente (SIS y otras IAFAS).
        if ($presencial === 'SI' && isset($edadPaciente) && $edadPaciente >= 18) {
            $dniResponsable = $dniPaciente;
            $personaResponsable = (string) ($nombrePaciente ?? '');
        }

        $distrito =
            $distrito !== ''
                ? $distrito
                : null;

        /*
         * Normalizar campos opcionales
         */
        $comunidad =
            $comunidad !== ''
                ? $comunidad
                : null;

        $direccion =
            $direccion !== ''
                ? $direccion
                : null;

        $referencia =
            $referencia !== ''
                ? $referencia
                : null;

        $observaciones =
            $observaciones !== ''
                ? $observaciones
                : null;

        $personaResponsable =
            $personaResponsable !== ''
                ? $personaResponsable
                : null;

        $dniResponsable =
            $dniResponsable !== ''
                ? $dniResponsable
                : null;

        $fonoResponsable =
            $fonoResponsable !== ''
                ? $fonoResponsable
                : null;

        /*
         * Eliminar tipos repetidos
         */
        $tipos = array_values(
            array_unique(
                array_map(
                    'intval',
                    $tipos
                )
            )
        );

        /*
         * Verificar que exista
         */
        $existente =
            $this->ocurrenciaModel
                ->buscarPorId(
                    $idOcurrencia
                );

        if (!$existente) {
            die(
                'Ocurrencia no encontrada.'
            );
        }

        $fechaRegistroExistente = $existente['FechaRegistro'] ?? null;
        $duplicado = $esSIS
            ? $this->ocurrenciaModel->existeDuplicado($idAfiliados, $tipos, $fechaRegistroExistente, $idOcurrencia)
            : $this->ocurrenciaModel->existeDuplicadoPorDNI($idIAFAS, $dniPaciente, $tipos, $fechaRegistroExistente, $idOcurrencia);
        if ($duplicado) $this->mostrarAlertaDuplicidad('El paciente ya tiene otra ocurrencia del mismo tipo registrada en esa fecha.');

        /*
         * Actualizar ocurrencia
         */
        $this->ocurrenciaModel
            ->actualizar(
                $idOcurrencia,
                $idAfiliados,
                $dniPaciente,
                $fechaProbableParto,
                $comunidad,
                $direccion,
                $referencia,
                $idEess,
                $observaciones,
                $personaResponsable,
                $dniResponsable,
                $fonoResponsable,
                $nroFormato,
                $gestante,
                $emergencias,
                $presencial,
                $distrito,
                $idIAFAS,
                $nombrePaciente,
                $sexoPaciente,
                $fechaNacimientoPaciente
            );

        /*
         * Actualizar tipos
         */
        $this->ocurrenciaModel
            ->guardarTipos(
                $idOcurrencia,
                $tipos
            );

        $this->redirect(
            'index.php?route=ocurrenciasca'
        );
    }


    public function exportarExcel(): void
    {
        Auth::requirePermission('OCURRENCIAS_CA');

        /*
         * Cargar PhpSpreadsheet
         */
        $autoload =
            BASE_PATH . '/vendor/autoload.php';

        if (!file_exists($autoload)) {
            die(
                'No se encontró PhpSpreadsheet.'
            );
        }

        require_once $autoload;

        /*
         * Filtros
         */
        $buscar = trim(
            $_GET['buscar'] ?? ''
        );

        $dniResponsableAfiliacion =
            trim(
                $_GET['dni_responsable_afiliacion'] ?? ''
            );

        if ($dniResponsableAfiliacion === '') {
            $dniResponsableAfiliacion = null;
        }

        $fechaInicio =
            trim(
                $_GET['fecha_inicio'] ?? ''
            );

        if ($fechaInicio === '') {
            $fechaInicio = null;
        }

        $fechaFin =
            trim(
                $_GET['fecha_fin'] ?? ''
            );

        if ($fechaFin === '') {
            $fechaFin = null;
        }

        /*
         * Unidad Ejecutora
         */
        $codUE =
            trim(
                $_GET['cod_ue'] ?? ''
            );

        if ($codUE === '') {
            $codUE = null;
        }

        /*
         * Validar fechas
         */
        if (
            $fechaInicio !== null &&
            !preg_match(
                '/^\d{4}-\d{2}-\d{2}$/',
                $fechaInicio
            )
        ) {
            $fechaInicio = null;
        }

        if (
            $fechaFin !== null &&
            !preg_match(
                '/^\d{4}-\d{2}-\d{2}$/',
                $fechaFin
            )
        ) {
            $fechaFin = null;
        }

        /*
         * Si el rango es incorrecto
         */
        if (
            $fechaInicio !== null &&
            $fechaFin !== null &&
            $fechaInicio > $fechaFin
        ) {
            die(
                'El rango de fechas no es válido.'
            );
        }

        /*
         * Obtener todos los registros
         * que cumplen los filtros.
         */
        $total =
            $this->ocurrenciaModel->contar(
                $buscar,
                $dniResponsableAfiliacion,
                $fechaInicio,
                $fechaFin,
                $codUE
            );

        $ocurrencias = [];

        if ($total > 0) {

            $ocurrencias =
                $this->ocurrenciaModel->listar(
                    $buscar,
                    $dniResponsableAfiliacion,
                    $fechaInicio,
                    $fechaFin,
                    $codUE,
                    $total,
                    0
                );
        }

        /*
         * Crear libro Excel
         */
        $spreadsheet =
            new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet =
            $spreadsheet->getActiveSheet();

        $sheet->setTitle(
            'Ocurrencias CA'
        );

        /*
         * Encabezados
         */
        $encabezados = [
            'ID',
            'DNI Paciente',
            'Asegurado',
            'Tipo(s) de ocurrencia',
            'Nro. Formato',
            'Fecha Probable Parto',
            'Gestante',
            'Emergencias',
            'Presencial',
            'Distrito',
            'Comunidad',
            'Dirección',
            'Referencia',
            'IPRESS',
            'Responsable',
            'DNI Responsable',
            'Fono Responsable',
            'Responsable Afiliación',
            'DNI Responsable Afiliación',
            'Fecha Registro'
        ];

        /*
         * Escribir encabezados
         */
        foreach (
            $encabezados as $indice => $encabezado
        ) {

            $letraColumna =
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $indice + 1
                );

            $sheet->setCellValue(
                $letraColumna . '1',
                $encabezado
            );
        }

        /*
         * Datos
         */
        $fila = 2;

        foreach ($ocurrencias as $ocurrencia) {

            $nombreResponsableAfiliacion =
                trim(
                    ($ocurrencia['NombresResponsableAfiliacion'] ?? '') .
                    ' ' .
                    ($ocurrencia['ApellidosResponsableAfiliacion'] ?? '')
                );

            $datos = [
                $ocurrencia['IdOcurrencia'] ?? '',
                $ocurrencia['DNI_PACIENTE'] ?? '',
                $ocurrencia['NombreAsegurado'] ?? '',
                $ocurrencia['TiposOcurrencia'] ?? '',
                $ocurrencia['NroFormato'] ?? '',
                $ocurrencia['FECHA_PROBABLE_PARTO'] ?? '',
                $ocurrencia['GESTANTE'] ?? '',
                $ocurrencia['EMERGENCIAS'] ?? '',
                $ocurrencia['PRESENCIAL'] ?? '',
                $ocurrencia['DISTRITO'] ?? '',
                $ocurrencia['COMUNIDAD'] ?? '',
                $ocurrencia['DIRECCION'] ?? '',
                $ocurrencia['REFERENCIA'] ?? '',
                $ocurrencia['IPRESS_ATENC'] ?? '',
                $ocurrencia['PERSONA_RESPONSABLE'] ?? '',
                $ocurrencia['DNI_RESPONSABLE'] ?? '',
                $ocurrencia['FONO_RESPONSABLE'] ?? '',
                $nombreResponsableAfiliacion,
                $ocurrencia['DNIUsuarioResponsable'] ?? '',
                $ocurrencia['FechaRegistro'] ?? ''
            ];

            foreach (
                $datos as $indice => $dato
            ) {

                $letraColumna =
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                        $indice + 1
                    );

                $sheet->setCellValue(
                    $letraColumna . $fila,
                    $dato
                );
            }

            $fila++;
        }

        /*
         * Formato de encabezados
         */
        $ultimaColumna =
            $sheet->getHighestColumn();

        $sheet
            ->getStyle(
                'A1:' . $ultimaColumna . '1'
            )
            ->getFont()
            ->setBold(true);

        /*
         * Autoajustar columnas
         */
       foreach (
    range(
        1,
        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
            $sheet->getHighestColumn()
        )
    ) as $columna
) {

    $sheet
        ->getColumnDimensionByColumn(
            $columna
        )
        ->setAutoSize(true);
}

        /*
         * Nombre del archivo
         */
        $nombreArchivo =
            'Ocurrencias_CA_' .
            date('Ymd_His') .
            '.xlsx';

        /*
         * Limpiar cualquier salida previa
         */
        if (ob_get_length()) {
            ob_end_clean();
        }

        /*
         * Descargar
         */
        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        header(
            'Content-Disposition: attachment; filename="' .
            $nombreArchivo .
            '"'
        );

        header(
            'Cache-Control: max-age=0'
        );

        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );

        $writer->save('php://output');

        exit;
    }
}