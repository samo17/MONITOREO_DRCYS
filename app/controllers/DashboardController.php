<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController extends Controller
{
    private Dashboard $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new Dashboard();
    }

    public function index(): void
    {
        Auth::requireLogin();

        $anio = (int) ($_GET['anio'] ?? date('Y'));
        if ($anio < 2000 || $anio > 2100) {
            $anio = (int) date('Y');
        }

        $ipressPorUE = $this->dashboardModel->ipressPorUE();
        $afiliadosPorUE = $this->dashboardModel->afiliadosPorUE();
        $ocurrenciasMensuales = $this->dashboardModel->ocurrenciasMensuales($anio);

        $this->view('dashboard/index', [
            'anio' => $anio,
            'totalIpress' => $this->dashboardModel->totalIpress(),
            'totalAfiliados' => (int) $this->dashboardModel->totalAfiliados(),
            'totalOcurrencias' => $this->dashboardModel->totalOcurrencias(),
            'totalDigitadores' => $this->dashboardModel->totalDigitadores(),
            'ipressPorUE' => $ipressPorUE,
            'afiliadosPorUE' => $afiliadosPorUE,
            'ocurrenciasMensuales' => $ocurrenciasMensuales
        ]);
    }

    public function exportar(): void
    {
        Auth::requireLogin();

        $anio = (int) ($_GET['anio'] ?? date('Y'));
        if ($anio < 2000 || $anio > 2100) {
            $anio = (int) date('Y');
        }

        require_once BASE_PATH . '/vendor/autoload.php';

        $ipress = $this->dashboardModel->ipressPorUE();
        $afiliados = $this->dashboardModel->afiliadosPorUE();
        $ocurrencias = $this->dashboardModel->ocurrenciasMensuales($anio);

        $libro = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $hoja = $libro->getActiveSheet();
        $hoja->setTitle('IPRESS por UE');
        $hoja->fromArray(['Unidad Ejecutora', 'Total IPRESS'], null, 'A1');
        $fila = 2;
        foreach ($ipress as $dato) {
            $hoja->fromArray([$dato['etiqueta'], (int)$dato['total']], null, 'A' . $fila++);
        }

        $hoja2 = $libro->createSheet();
        $hoja2->setTitle('Afiliados por UE');
        $hoja2->fromArray(['Unidad Ejecutora', 'Total afiliados'], null, 'A1');
        $fila = 2;
        foreach ($afiliados as $dato) {
            $hoja2->fromArray([$dato['etiqueta'], (int)$dato['total']], null, 'A' . $fila++);
        }

        $hoja3 = $libro->createSheet();
        $hoja3->setTitle('Ocurrencias mensuales');
        $hoja3->fromArray(['Mes', 'Total ocurrencias'], null, 'A1');
        $fila = 2;
        foreach ($ocurrencias as $dato) {
            $hoja3->fromArray([$dato['mes'], (int)$dato['total']], null, 'A' . $fila++);
        }

        $resumen = $libro->createSheet();
        $resumen->setTitle('Resumen');
        $resumen->fromArray(
            ['Indicador', 'Total'],
            null,
            'A1'
        );
        $resumen->fromArray(['IPRESS registradas', $this->dashboardModel->totalIpress()], null, 'A2');
        $resumen->fromArray(['Afiliados registrados', $this->dashboardModel->totalAfiliados()], null, 'A3');
        $resumen->fromArray(['Ocurrencias registradas', $this->dashboardModel->totalOcurrencias()], null, 'A4');
        $resumen->fromArray(['Digitadores (excluye NO DIGITA)', $this->dashboardModel->totalDigitadores()], null, 'A5');

        foreach ([$hoja, $hoja2, $hoja3, $resumen] as $sheet) {
            $sheet->getStyle('A1:B1')->getFont()->setBold(true);
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
        }

        $nombre = 'reporte_dashboard_drcys_' . $anio . '_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombre . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($libro);
        $writer->save('php://output');
        exit;
    }

}
