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
                $fechaFin
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


    public function crear(): void
    {
        Auth::requirePermission('OCURRENCIAS_CA');

        $this->view(
            'ocurrenciasca/crear'
        );
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

        $idAfiliados =
            isset($_POST['IdAfiliados'])
                ? (int) $_POST['IdAfiliados']
                : 0;

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

        if ($idAfiliados <= 0) {
            die(
                'Debe seleccionar un afiliado.'
            );
        }

        if ($dniPaciente === '') {
            die(
                'El DNI del paciente es obligatorio.'
            );
        }

        /*
         * Normalizar fecha probable de parto
         */
        if ($fechaProbableParto === '') {
            $fechaProbableParto = null;
        }

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
                $distrito
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
                    $tiposSeleccionados
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

        $idAfiliados =
            isset($_POST['IdAfiliados'])
                ? (int) $_POST['IdAfiliados']
                : 0;

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

        if ($idAfiliados <= 0) {
            die(
                'Debe seleccionar un afiliado.'
            );
        }

        if ($dniPaciente === '') {
            die(
                'El DNI del paciente es obligatorio.'
            );
        }

        /*
         * Normalizar fecha probable de parto
         */
        if ($fechaProbableParto === '') {
            $fechaProbableParto = null;
        }

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
                $distrito
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
                $fechaFin
            );

        $ocurrencias = [];

        if ($total > 0) {

            $ocurrencias =
                $this->ocurrenciaModel->listar(
                    $buscar,
                    $dniResponsableAfiliacion,
                    $fechaInicio,
                    $fechaFin,
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