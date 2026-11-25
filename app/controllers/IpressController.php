<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Ipress.php';

class IpressController extends Controller
{
    private Ipress $ipressModel;

public function __construct()
    {
        $this->ipressModel = new Ipress();
    }

public function index(): void
{
    Auth::requirePermission('IPRESS');

    $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
    $opcionesPorPagina = [5, 10, 20, 50, 100];
    $porPagina = (int) ($_GET['porPagina'] ?? 10);
    if (!in_array($porPagina, $opcionesPorPagina, true)) $porPagina = 10;

    $busqueda = trim($_GET['busqueda'] ?? '');
    $estado = $_GET['estado'] ?? '1';
    if (!in_array($estado, ['1', '0', 'todos'], true)) $estado = '1';
    $codUE = trim($_GET['codUE'] ?? '');
    $red = trim($_GET['red'] ?? '');
    $microred = trim($_GET['microred'] ?? '');

    $unidadesEjecutoras = $this->ipressModel->listarUnidadesEjecutoras();
    $redes = $codUE !== '' ? $this->ipressModel->listarRedesPorUE($codUE) : [];
    $microredes = ($codUE !== '' && $red !== '')
        ? $this->ipressModel->listarMicroredesPorRed($codUE, $red) : [];

    $total = $this->ipressModel->total($busqueda, $estado, $codUE, $red, $microred);
    $totalPaginas = max(1, (int) ceil($total / $porPagina));
    if ($pagina > $totalPaginas) $pagina = $totalPaginas;

    $ipress = $this->ipressModel->listar(
        $pagina, $porPagina, $busqueda, $estado, $codUE, $red, $microred
    );

    $this->view('ipress/index', [
        'titulo' => 'IPRESS',
        'ipress' => $ipress,
        'pagina' => $pagina,
        'porPagina' => $porPagina,
        'busqueda' => $busqueda,
        'estado' => $estado,
        'codUE' => $codUE,
        'red' => $red,
        'microred' => $microred,
        'unidadesEjecutoras' => $unidadesEjecutoras,
        'redes' => $redes,
        'microredes' => $microredes,
        'total' => $total,
        'totalPaginas' => $totalPaginas
    ]);
}

public function exportar(): void
{
    Auth::requirePermission('IPRESS');

    $busqueda = trim($_GET['busqueda'] ?? '');
    $estado = $_GET['estado'] ?? '1';
    if (!in_array($estado, ['1', '0', 'todos'], true)) $estado = '1';
    $codUE = trim($_GET['codUE'] ?? '');
    $red = trim($_GET['red'] ?? '');
    $microred = trim($_GET['microred'] ?? '');

    require_once BASE_PATH . '/vendor/autoload.php';

    $ipress = $this->ipressModel->listarParaExportar($busqueda, $estado, $codUE, $red, $microred);

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('IPRESS');

    $encabezados = [
        'RENAES',
        'IPRESS',
        'Tipo',
        'Nivel',
        'UE',
        'Red',
        'Microred',
        'Provincia',
        'Distrito',
        'Estado'
    ];

    $columna = 1;

    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue(
    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columna) . '1',
    $encabezado
);

        $columna++;
    }

    $fila = 2;

    foreach ($ipress as $item) {

        $sheet->setCellValue('A' . $fila, $item['RENAES']);
        $sheet->setCellValue('B' . $fila, $item['IPRESS_ATENC']);
        $sheet->setCellValue('C' . $fila, $item['TIPO']);
        $sheet->setCellValue('D' . $fila, $item['NIVEL']);
        $sheet->setCellValue('E' . $fila, $item['UE']);
        $sheet->setCellValue('F' . $fila, $item['RED']);
        $sheet->setCellValue('G' . $fila, $item['MICRORED']);
        $sheet->setCellValue('H' . $fila, $item['PROVINCIA']);
        $sheet->setCellValue('I' . $fila, $item['DISTRITO']);
        $sheet->setCellValue('J' . $fila, $item['ESTADO']);

        $fila++;
    }

    foreach (range('A', 'J') as $columna) {
        $sheet->getColumnDimension($columna)->setAutoSize(true);
    }

    $nombreArchivo = 'IPRESS_' . date('Ymd_His') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');

    exit;
}
public function crear(): void
{
    Auth::requirePermission('IPRESS');

    $unidadesEjecutoras =
        $this->ipressModel->listarUnidadesEjecutoras();

    $provincias =
        $this->ipressModel->listarProvincias();

    $this->view('ipress/crear', [
        'unidadesEjecutoras' => $unidadesEjecutoras,
        'provincias'         => $provincias
    ]);
}
public function guardar(): void
{
    Auth::requirePermission('IPRESS');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('index.php?route=ipress');
    }

    $renaes = trim($_POST['RENAES'] ?? '');
    $ipressAtenc = trim($_POST['IPRESS_ATENC'] ?? '');
    $tipo = trim($_POST['TIPO'] ?? '');
    $nivel = trim($_POST['NIVEL'] ?? '');
    $clasNoClas = trim($_POST['CLAS_NOCLAS'] ?? '');
    $codPPDD = trim($_POST['COD_PPDD'] ?? '');
    $codUE = trim($_POST['COD_UE'] ?? '');
    $ue = trim($_POST['UE'] ?? '');
    $red = trim($_POST['RED'] ?? '');
    $microred = trim($_POST['MICRORED'] ?? '');
    $provincia = trim($_POST['PROVINCIA'] ?? '');
    $distrito = trim($_POST['DISTRITO'] ?? '');
    $grupo = trim($_POST['GRUPO'] ?? '');

    $latitud = trim($_POST['LATITUD_GEOGRAFICA'] ?? '');
    $longitud = trim($_POST['LONGITUD_GEOGRAFICA'] ?? '');
    $coordenadaEste = trim($_POST['COORDENADA_ESTE'] ?? '');
    $coordenadaNorte = trim($_POST['COORDENADA_NORTE'] ?? '');

    /*
     * Validar campos obligatorios
     */
    if (
        $renaes === '' ||
        $ipressAtenc === '' ||
        $tipo === '' ||
        $nivel === '' ||
        $clasNoClas === '' ||
        $codUE === '' ||
        $ue === '' ||
        $red === '' ||
        $microred === '' ||
        $provincia === '' ||
        $distrito === '' ||
        $grupo === ''
    ) {
        die('Todos los campos obligatorios deben ser completados.');
    }

    /*
     * Validar RENAES
     */
    if (!preg_match('/^[0-9]{1,8}$/', $renaes)) {
        die('El RENAES debe contener únicamente números y máximo 8 dígitos.');
    }

    if ($this->ipressModel->existeRenaes($renaes)) {
        die('El RENAES ya se encuentra registrado.');
    }

    /*
     * Validar valores permitidos
     */
    $tiposPermitidos = ['PP.DD', 'IPRESS'];

    $nivelesPermitidos = [
        'I-1',
        'I-2',
        'I-3',
        'I-4',
        'II-1',
        'II-2',
        'III-1',
        'Sin Nivel'
    ];

    $clasificacionesPermitidas = [
        'CLAS',
        'NO CLAS'
    ];

    $gruposPermitidos = [
        'SIN POBLACION',
        'CON POBLACION'
    ];

    if (!in_array($tipo, $tiposPermitidos, true)) {
        die('El tipo de IPRESS no es válido.');
    }

    if (!in_array($nivel, $nivelesPermitidos, true)) {
        die('El nivel seleccionado no es válido.');
    }

    if (!in_array($clasNoClas, $clasificacionesPermitidas, true)) {
        die('La clasificación seleccionada no es válida.');
    }

    if (!in_array($grupo, $gruposPermitidos, true)) {
        die('El grupo seleccionado no es válido.');
    }

    /*
     * Convertir opcionales vacíos a NULL
     */
    $codPPDD = $codPPDD !== '' ? $codPPDD : null;
    $latitud = $latitud !== '' ? $latitud : null;
    $longitud = $longitud !== '' ? $longitud : null;
    $coordenadaEste = $coordenadaEste !== '' ? $coordenadaEste : null;
    $coordenadaNorte = $coordenadaNorte !== '' ? $coordenadaNorte : null;

    try {

        $idEESS = $this->ipressModel->crear([
            'RENAES' => $renaes,
            'IPRESS_ATENC' => $ipressAtenc,
            'TIPO' => $tipo,
            'NIVEL' => $nivel,
            'CLAS_NOCLAS' => $clasNoClas,
            'COD_PPDD' => $codPPDD,
            'COD_UE' => $codUE,
            'UE' => $ue,
            'RED' => $red,
            'MICRORED' => $microred,
            'PROVINCIA' => $provincia,
            'DISTRITO' => $distrito,
            'LATITUD_GEOGRAFICA' => $latitud,
            'LONGITUD_GEOGRAFICA' => $longitud,
            'COORDENADA_ESTE' => $coordenadaEste,
            'COORDENADA_NORTE' => $coordenadaNorte,
            'GRUPO' => $grupo
        ]);

        $this->redirect('index.php?route=ipress');

    } catch (PDOException $e) {

        if ($e->getCode() === '23000') {
            die('El RENAES ya se encuentra registrado.');
        }

        die('No fue posible registrar la IPRESS.');
    }
}

public function detalle(): void
{
    Auth::requirePermission('IPRESS');

    $idEESS = (int) ($_GET['id'] ?? 0);

    if ($idEESS <= 0) {
        http_response_code(400);
        die('IPRESS no válida.');
    }

    $ipress = $this->ipressModel->buscarPorIdDetalle($idEESS);

    if (!$ipress) {
        http_response_code(404);
        die('IPRESS no encontrada.');
    }

    $this->view('ipress/detalle', [
        'ipress' => $ipress
    ]);
}

public function editar(): void
{
    Auth::requirePermission('IPRESS');

    $idEESS = (int) ($_GET['id'] ?? 0);

    if ($idEESS <= 0) {
        http_response_code(400);
        die('IPRESS no válida.');
    }

    $ipress = $this->ipressModel->buscarPorIdParaEditar($idEESS);

    if (!$ipress) {
        http_response_code(404);
        die('IPRESS no encontrada.');
    }

    $unidadesEjecutoras =
        $this->ipressModel->listarUnidadesEjecutoras();

    $provincias =
        $this->ipressModel->listarProvincias();

    $this->view('ipress/editar', [
        'ipress' => $ipress,
        'unidadesEjecutoras' => $unidadesEjecutoras,
        'provincias' => $provincias
    ]);
}

public function actualizar(): void
{
    Auth::requirePermission('IPRESS');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('index.php?route=ipress');
    }

    $idEESS = (int) ($_POST['IdEESS'] ?? 0);

    if ($idEESS <= 0) {
        die('IPRESS no válida.');
    }

    $renaes = trim($_POST['RENAES'] ?? '');
    $ipressAtenc = trim($_POST['IPRESS_ATENC'] ?? '');
    $tipo = trim($_POST['TIPO'] ?? '');
    $nivel = trim($_POST['NIVEL'] ?? '');
    $clasNoClas = trim($_POST['CLAS_NOCLAS'] ?? '');
    $codPPDD = trim($_POST['COD_PPDD'] ?? '');
    $codUE = trim($_POST['COD_UE'] ?? '');
    $ue = trim($_POST['UE'] ?? '');
    $red = trim($_POST['RED'] ?? '');
    $microred = trim($_POST['MICRORED'] ?? '');
    $provincia = trim($_POST['PROVINCIA'] ?? '');
    $distrito = trim($_POST['DISTRITO'] ?? '');
    $grupo = trim($_POST['GRUPO'] ?? '');
    $estado = isset($_POST['ESTADO']) ? (int) $_POST['ESTADO'] : -1;

    $latitud = trim($_POST['LATITUD_GEOGRAFICA'] ?? '');
    $longitud = trim($_POST['LONGITUD_GEOGRAFICA'] ?? '');
    $coordenadaEste = trim($_POST['COORDENADA_ESTE'] ?? '');
    $coordenadaNorte = trim($_POST['COORDENADA_NORTE'] ?? '');

    /*
     * Validar campos obligatorios
     */
    if (
        $renaes === '' ||
        $ipressAtenc === '' ||
        $tipo === '' ||
        $nivel === '' ||
        $clasNoClas === '' ||
        $codUE === '' ||
        $ue === '' ||
        $red === '' ||
        $microred === '' ||
        $provincia === '' ||
        $distrito === '' ||
        $grupo === ''
    ) {
        die('Todos los campos obligatorios deben ser completados.');
    }

    /*
     * Validar RENAES
     */
    if (!preg_match('/^[0-9]{1,8}$/', $renaes)) {
        die('El RENAES debe contener únicamente números y máximo 8 dígitos.');
    }

    if ($this->ipressModel->existeRenaes($renaes, $idEESS)) {
        die('El RENAES ya se encuentra registrado en otra IPRESS.');
    }

    /*
     * Validar estado
     */
    if (!in_array($estado, [0, 1], true)) {
        die('El estado seleccionado no es válido.');
    }

    /*
     * Validar valores permitidos
     */
    $tiposPermitidos = [
        'PP.DD',
        'IPRESS'
    ];

    $nivelesPermitidos = [
        'I-1',
        'I-2',
        'I-3',
        'I-4',
        'II-1',
        'II-2',
        'III-1',
        'Sin Nivel'
    ];

    $clasificacionesPermitidas = [
        'CLAS',
        'NO CLAS'
    ];

    $gruposPermitidos = [
        'SIN POBLACION',
        'CON POBLACION'
    ];

    if (!in_array($tipo, $tiposPermitidos, true)) {
        die('El tipo de IPRESS no es válido.');
    }

    if (!in_array($nivel, $nivelesPermitidos, true)) {
        die('El nivel seleccionado no es válido.');
    }

    if (!in_array($clasNoClas, $clasificacionesPermitidas, true)) {
        die('La clasificación seleccionada no es válida.');
    }

    if (!in_array($grupo, $gruposPermitidos, true)) {
        die('El grupo seleccionado no es válido.');
    }

    /*
     * Campos opcionales
     */
    $codPPDD = $codPPDD !== '' ? $codPPDD : null;
    $latitud = $latitud !== '' ? $latitud : null;
    $longitud = $longitud !== '' ? $longitud : null;
    $coordenadaEste = $coordenadaEste !== '' ? $coordenadaEste : null;
    $coordenadaNorte = $coordenadaNorte !== '' ? $coordenadaNorte : null;

    try {

        $actualizado = $this->ipressModel->actualizar(
            $idEESS,
            [
                'RENAES' => $renaes,
                'IPRESS_ATENC' => $ipressAtenc,
                'TIPO' => $tipo,
                'NIVEL' => $nivel,
                'CLAS_NOCLAS' => $clasNoClas,
                'COD_PPDD' => $codPPDD,
                'COD_UE' => $codUE,
                'UE' => $ue,
                'RED' => $red,
                'MICRORED' => $microred,
                'PROVINCIA' => $provincia,
                'DISTRITO' => $distrito,
                'LATITUD_GEOGRAFICA' => $latitud,
                'LONGITUD_GEOGRAFICA' => $longitud,
                'COORDENADA_ESTE' => $coordenadaEste,
                'COORDENADA_NORTE' => $coordenadaNorte,
                'ESTADO' => $estado,
                'GRUPO' => $grupo
            ]
        );

        if (!$actualizado) {
            die('No se realizaron cambios en la IPRESS.');
        }

        $this->redirect('index.php?route=ipress');

    } catch (PDOException $e) {

        if ($e->getCode() === '23000') {
            die('El RENAES ya se encuentra registrado en otra IPRESS.');
        }

        die('No fue posible actualizar la IPRESS.');
    }
}

public function cambiarEstado(): void
{
    Auth::requirePermission('IPRESS');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die('Método no permitido.');
    }

    $idEESS = isset($_POST['IdEESS'])
        ? (int) $_POST['IdEESS']
        : 0;

    $estado = isset($_POST['ESTADO'])
        ? (int) $_POST['ESTADO']
        : -1;

    if ($idEESS <= 0) {
        die('IPRESS no válida.');
    }

    if (!in_array($estado, [0, 1], true)) {
        die('Estado no válido.');
    }

    $ipressModel = new Ipress();

    $ipress = $ipressModel->buscarPorIdDetalle($idEESS);

    if (!$ipress) {
        die('IPRESS no encontrada.');
    }

    $datos = [
        'RENAES' => $ipress['RENAES'],
        'IPRESS_ATENC' => $ipress['IPRESS_ATENC'],
        'TIPO' => $ipress['TIPO'],
        'NIVEL' => $ipress['NIVEL'],
        'CLAS_NOCLAS' => $ipress['CLAS_NOCLAS'],
        'COD_PPDD' => $ipress['COD_PPDD'],
        'COD_UE' => $ipress['COD_UE'],
        'UE' => $ipress['UE'],
        'RED' => $ipress['RED'],
        'MICRORED' => $ipress['MICRORED'],
        'PROVINCIA' => $ipress['PROVINCIA'],
        'DISTRITO' => $ipress['DISTRITO'],
        'LATITUD_GEOGRAFICA' => $ipress['LATITUD_GEOGRAFICA'],
        'LONGITUD_GEOGRAFICA' => $ipress['LONGITUD_GEOGRAFICA'],
        'COORDENADA_ESTE' => $ipress['COORDENADA_ESTE'],
        'COORDENADA_NORTE' => $ipress['COORDENADA_NORTE'],
        'ESTADO' => $estado,
        'GRUPO' => $ipress['GRUPO']
    ];

    $ipressModel->actualizar($idEESS, $datos);

    $this->redirect('index.php?route=ipress');
}

}