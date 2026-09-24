<?php

class DigitadoresController extends Controller
{
    private Digitador $digitadorModel;

    public function __construct()
    {
        $this->digitadorModel = new Digitador();
    }

    /*
    |--------------------------------------------------------------------------
    | Listado de digitadores
    |--------------------------------------------------------------------------
    */
    public function index(): void
    {
        Auth::requirePermission('DIGITADORES');

        $buscar = trim($_GET['buscar'] ?? '');
        $condicion = trim($_GET['condicion'] ?? '');
        $tipo = trim($_GET['tipo'] ?? '');
        $grupo = trim($_GET['grupo'] ?? '');

        $pagina = max(
            1,
            (int) ($_GET['pagina'] ?? 1)
        );

        $porPagina = 10;

        $totalDigitadores = $this->digitadorModel->contar(
            $buscar,
            $condicion,
            $tipo,
            $grupo
        );

        $totalPaginas = max(
            1,
            (int) ceil($totalDigitadores / $porPagina)
        );

        if ($pagina > $totalPaginas) {
            $pagina = $totalPaginas;
        }

        $offset = ($pagina - 1) * $porPagina;

        $digitadores = $this->digitadorModel->listar(
            $buscar,
            $condicion,
            $tipo,
            $grupo,
            $porPagina,
            $offset
        );

        $tipos = $this->digitadorModel->listarTipos();

        $grupos = $this->digitadorModel->listarGrupos();

        $condiciones = $this->digitadorModel->listarCondiciones();

        $this->view(
            'digitadores/index',
            [
                'digitadores' => $digitadores,
                'buscar' => $buscar,
                'condicion' => $condicion,
                'tipo' => $tipo,
                'grupo' => $grupo,
                'tipos' => $tipos,
                'grupos' => $grupos,
                'condiciones' => $condiciones,
                'pagina' => $pagina,
                'porPagina' => $porPagina,
                'totalDigitadores' => $totalDigitadores,
                'totalPaginas' => $totalPaginas
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Exportar a Excel
    |--------------------------------------------------------------------------
    */
    public function exportar(): void
    {
        Auth::requirePermission('DIGITADORES');

        $buscar = trim($_GET['buscar'] ?? '');
        $condicion = trim($_GET['condicion'] ?? '');
        $tipo = trim($_GET['tipo'] ?? '');
        $grupo = trim($_GET['grupo'] ?? '');

        require_once BASE_PATH . '/vendor/autoload.php';

        $stmt = $this->digitadorModel->exportar(
            $buscar,
            $condicion,
            $tipo,
            $grupo
        );

        $nombreArchivo = 'digitadores_' . date('Ymd_His') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Digitadores');

        $encabezados = [
            'ID',
            'Nombres',
            'DNI',
            'Fecha nacimiento',
            'Condición',
            'Fecha ingreso',
            'Teléfono',
            'Correo',
            'Tipo',
            'Grupo',
            'Meta diaria'
        ];

        foreach ($encabezados as $indice => $encabezado) {
            $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $indice + 1
            );

            $sheet->setCellValue(
                $columna . '1',
                $encabezado
            );
        }

        $fila = 2;

        while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $valores = [
                $registro['IdDIGITADOR'],
                $registro['NOMBRES'],
                $registro['DNI'],
                $registro['FECHA_NACIMIENTO'],
                $registro['CONDICION'],
                $registro['FECHA_INGRESO'],
                $registro['TELEFONO'],
                $registro['CORREO'],
                $registro['TIPO'],
                $registro['GRUPO'],
                $registro['META_DIARIA']
            ];

            foreach ($valores as $indice => $valor) {

                $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $indice + 1
                );

                $sheet->setCellValue(
                    $columna . $fila,
                    $valor
                );
            }

            $fila++;
        }

        $ultimaColumna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
            count($encabezados)
        );

        $sheet->getStyle(
            'A1:' . $ultimaColumna . '1'
        )->getFont()->setBold(true);

        foreach (range(1, count($encabezados)) as $indice) {

            $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $indice
            );

            $sheet->getColumnDimension($columna)->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        header(
            'Content-Disposition: attachment; filename="' .
            $nombreArchivo .
            '"'
        );

        header('Cache-Control: max-age=0');

        $writer->save('php://output');

        $spreadsheet->disconnectWorksheets();

        unset($spreadsheet);

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario crear
    |--------------------------------------------------------------------------
    */
    public function crear(): void
    {
        Auth::requirePermission('DIGITADORES');

        $this->view(
            'digitadores/crear'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar digitador
    |--------------------------------------------------------------------------
    */
    public function guardar(): void
    {
        Auth::requirePermission('DIGITADORES');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('digitadores');
        }

        $datos = [
            'NOMBRES' => trim($_POST['NOMBRES'] ?? ''),
            'DNI' => trim($_POST['DNI'] ?? ''),
            'FECHA_NACIMIENTO' => trim(
                $_POST['FECHA_NACIMIENTO'] ?? ''
            ),
            'CONDICION' => trim(
                $_POST['CONDICION'] ?? ''
            ),
            'FECHA_INGRESO' => trim(
                $_POST['FECHA_INGRESO'] ?? ''
            ),
            'TELEFONO' => trim(
                $_POST['TELEFONO'] ?? ''
            ),
            'CORREO' => trim(
                $_POST['CORREO'] ?? ''
            ),
            'TIPO' => trim(
                $_POST['TIPO'] ?? ''
            ),
            'IMG' => trim(
                $_POST['IMG'] ?? ''
            ),
            'GRUPO' => trim(
                $_POST['GRUPO'] ?? ''
            ),
            'META_DIARIA' => trim(
                $_POST['META_DIARIA'] ?? ''
            )
        ];

        if ($datos['NOMBRES'] === '') {

            Session::flash(
                'error',
                'Los nombres son obligatorios.'
            );

            redirect('digitadores/crear');
        }

        if ($datos['DNI'] === '') {

            Session::flash(
                'error',
                'El DNI es obligatorio.'
            );

            redirect('digitadores/crear');
        }

        try {

            $this->digitadorModel->crear($datos);

            Session::flash(
                'success',
                'Digitador registrado correctamente.'
            );

            redirect('digitadores');

        } catch (Throwable $e) {

            Session::flash(
                'error',
                'No fue posible registrar el digitador.'
            );

            redirect('digitadores/crear');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario editar
    |--------------------------------------------------------------------------
    */
    public function editar(): void
    {
        Auth::requirePermission('DIGITADORES');

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {

            Session::flash(
                'error',
                'Digitador no válido.'
            );

            redirect('digitadores');
        }

        $digitador = $this->digitadorModel->buscarPorId($id);

        if (!$digitador) {

            Session::flash(
                'error',
                'El digitador no existe.'
            );

            redirect('digitadores');
        }

        $this->view(
            'digitadores/editar',
            [
                'digitador' => $digitador
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar digitador
    |--------------------------------------------------------------------------
    */
    public function actualizar(): void
    {
        Auth::requirePermission('DIGITADORES');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('digitadores');
        }

        $id = (int) (
            $_POST['IdDIGITADOR'] ?? 0
        );

        if ($id <= 0) {

            Session::flash(
                'error',
                'Digitador no válido.'
            );

            redirect('digitadores');
        }

        $datos = [
            'NOMBRES' => trim($_POST['NOMBRES'] ?? ''),
            'DNI' => trim($_POST['DNI'] ?? ''),
            'FECHA_NACIMIENTO' => trim(
                $_POST['FECHA_NACIMIENTO'] ?? ''
            ),
            'CONDICION' => trim(
                $_POST['CONDICION'] ?? ''
            ),
            'FECHA_INGRESO' => trim(
                $_POST['FECHA_INGRESO'] ?? ''
            ),
            'TELEFONO' => trim(
                $_POST['TELEFONO'] ?? ''
            ),
            'CORREO' => trim(
                $_POST['CORREO'] ?? ''
            ),
            'TIPO' => trim(
                $_POST['TIPO'] ?? ''
            ),
            'IMG' => trim(
                $_POST['IMG'] ?? ''
            ),
            'GRUPO' => trim(
                $_POST['GRUPO'] ?? ''
            ),
            'META_DIARIA' => trim(
                $_POST['META_DIARIA'] ?? ''
            )
        ];

        if ($datos['NOMBRES'] === '') {

            Session::flash(
                'error',
                'Los nombres son obligatorios.'
            );

            redirect(
                'digitadores/editar&id=' . $id
            );
        }

        if ($datos['DNI'] === '') {

            Session::flash(
                'error',
                'El DNI es obligatorio.'
            );

            redirect(
                'digitadores/editar&id=' . $id
            );
        }

        try {

            $this->digitadorModel->actualizar(
                $id,
                $datos
            );

            Session::flash(
                'success',
                'Digitador actualizado correctamente.'
            );

            redirect('digitadores');

        } catch (Throwable $e) {

            Session::flash(
                'error',
                'No fue posible actualizar el digitador.'
            );

            redirect(
                'digitadores/editar&id=' . $id
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Detalle
    |--------------------------------------------------------------------------
    */
    public function detalle(): void
    {
        Auth::requirePermission('DIGITADORES');

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {

            Session::flash(
                'error',
                'Digitador no válido.'
            );

            redirect('digitadores');
        }

        $digitador = $this->digitadorModel->buscarPorId($id);

        if (!$digitador) {

            Session::flash(
                'error',
                'El digitador no existe.'
            );

            redirect('digitadores');
        }

        $this->view(
            'digitadores/detalle',
            [
                'digitador' => $digitador
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    */
    public function eliminar(): void
    {
        Auth::requirePermission('DIGITADORES');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('digitadores');
        }

        $id = (int) (
            $_POST['IdDIGITADOR'] ?? 0
        );

        if ($id <= 0) {

            Session::flash(
                'error',
                'Digitador no válido.'
            );

            redirect('digitadores');
        }

        try {

            $this->digitadorModel->eliminar($id);

            Session::flash(
                'success',
                'Digitador eliminado correctamente.'
            );

        } catch (Throwable $e) {

            Session::flash(
                'error',
                'No fue posible eliminar el digitador.'
            );
        }

        redirect('digitadores');
    }
}