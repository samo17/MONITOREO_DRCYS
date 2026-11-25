<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Ipress.php';

/**
 * Módulo IPRESS Consulta.
 * Solo lectura: listado, detalle y exportación.
 */
class IpressConsultaController extends Controller
{
    private Ipress $ipressModel;

    public function __construct()
    {
        $this->ipressModel = new Ipress();
    }

    public function index(): void
{
    Auth::requirePermission('IPRESS_CONSULTA');

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

    $this->view('ipress_consulta/index', [
        'titulo' => 'IPRESS Consulta',
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

public function detalle(): void
    {
        Auth::requirePermission('IPRESS_CONSULTA');

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

        $this->view('ipress_consulta/detalle', [
            'titulo' => 'Detalle de IPRESS',
            'ipress' => $ipress
        ]);
    }

    public function exportar(): void
    {
        Auth::requirePermission('IPRESS_CONSULTA');

        $busqueda = trim($_GET['busqueda'] ?? '');
        $estado = $_GET['estado'] ?? '1';
        if (!in_array($estado, ['1', '0', 'todos'], true)) { $estado = '1'; }
        $codUE = trim($_GET['codUE'] ?? '');
        $red = trim($_GET['red'] ?? '');
        $microred = trim($_GET['microred'] ?? '');

        require_once BASE_PATH . '/vendor/autoload.php';

        // Recuperar todos los resultados respetando búsqueda y estado seleccionados.
        $totalRegistros = $this->ipressModel->total($busqueda, $estado, $codUE, $red, $microred);
        $paginas = (int) ceil($totalRegistros / 100);
        $registros = [];
        for ($pagina = 1; $pagina <= $paginas; $pagina++) {
            $lote = $this->ipressModel->listar($pagina, 100, $busqueda, $estado, $codUE, $red, $microred);
            foreach ($lote as $registro) {
                $registros[] = $registro;
            }
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('IPRESS');

        $encabezados = [
            'RENAES', 'IPRESS', 'Tipo', 'Nivel', 'UE',
            'Red', 'Microred', 'Provincia', 'Distrito', 'Estado'
        ];

        foreach ($encabezados as $indice => $encabezado) {
            $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($indice + 1);
            $sheet->setCellValue($columna . '1', $encabezado);
        }

        $fila = 2;
        foreach ($registros as $item) {
            $valores = [
                $item['RENAES'], $item['IPRESS_ATENC'], $item['TIPO'],
                $item['NIVEL'], $item['UE'], $item['RED'],
                $item['MICRORED'], $item['PROVINCIA'],
                $item['DISTRITO'], ((int) $item['ESTADO'] === 1 ? 'ACTIVO' : 'INACTIVO')
            ];
            foreach ($valores as $indice => $valor) {
                $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($indice + 1);
                $sheet->setCellValue($columna . $fila, $valor);
            }
            $fila++;
        }

        foreach (range('A', 'J') as $columna) {
            $sheet->getColumnDimension($columna)->setAutoSize(true);
        }

        $nombreArchivo = 'IPRESS_CONSULTA_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
