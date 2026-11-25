<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Afiliado.php';

/**
 * Módulo de consulta de afiliados.
 * Exclusivamente lectura: no contiene carga ni sincronización.
 */
class AfiliadosConsultaController extends Controller
{
    private Afiliado $afiliadoModel;

    public function __construct()
    {
        $this->afiliadoModel = new Afiliado();
    }

public function index(): void
    {
        Auth::requirePermission('AFILIADOS_CONSULTA');

        /**
         * Búsqueda por DNI o nombre
         */
        $buscar = trim(
            $_GET['buscar'] ?? ''
        );

        /**
         * Filtro Unidad Ejecutora
         */
        $ue = trim(
            $_GET['ue'] ?? ''
        );

        /**
         * Filtro Red
         */
        $red = trim(
            $_GET['red'] ?? ''
        );

        /**
         * Filtro Microred
         */
        $microred = trim(
            $_GET['microred'] ?? ''
        );

        /**
         * Filtro IPRESS
         */
        $idEess = trim(
            $_GET['id_eess'] ?? ''
        );

        /**
         * Paginación
         */
        $pagina = isset($_GET['pagina'])
            ? (int) $_GET['pagina']
            : 1;

        if ($pagina < 1) {
            $pagina = 1;
        }

        $porPagina = 20;

        /**
         * Total global de afiliados en la tabla, sin filtros.
         * Se usa para habilitar/deshabilitar la carga inicial.
         */
        $totalGlobalAfiliados = $this->afiliadoModel->contar('', '', '', '', '');

        /**
         * Total de afiliados que coinciden con los filtros actuales.
         */
        $totalAfiliados =
            $this->afiliadoModel->contar(
                $buscar,
                $ue,
                $red,
                $microred,
                $idEess
            );

        /**
         * Total de páginas
         */
        $totalPaginas = max(
            1,
            (int) ceil(
                $totalAfiliados / $porPagina
            )
        );

        if ($pagina > $totalPaginas) {
            $pagina = $totalPaginas;
        }

        /**
         * Offset
         */
        $offset =
            ($pagina - 1) * $porPagina;

        /**
         * Listado de afiliados
         */
        $afiliados =
            $this->afiliadoModel->buscar(
                $buscar,
                $ue,
                $red,
                $microred,
                $idEess,
                $porPagina,
                $offset
            );

        /**
         * Listas para filtros
         */
        $unidadesEjecutoras =
            $this->afiliadoModel
                ->listarUnidadesEjecutoras();

        $redes =
            $this->afiliadoModel
                ->listarRedes(
                    $ue
                );

        $microredes =
            $this->afiliadoModel
                ->listarMicroredes(
                    $ue,
                    $red
                );

        $ipress =
            $this->afiliadoModel
                ->listarIpress(
                    $ue,
                    $red,
                    $microred
                );

        /**
         * Vista
         */
        $this->view(
            'afiliados_consulta/index',
            [
                'afiliados' =>
                    $afiliados,

                'buscar' =>
                    $buscar,

                'ue' =>
                    $ue,

                'red' =>
                    $red,

                'microred' =>
                    $microred,

                'idEess' =>
                    $idEess,

                'unidadesEjecutoras' =>
                    $unidadesEjecutoras,

                'redes' =>
                    $redes,

                'microredes' =>
                    $microredes,

                'ipress' =>
                    $ipress,

                'pagina' =>
                    $pagina,

                'porPagina' =>
                    $porPagina,

                'totalAfiliados' =>
                    $totalAfiliados,

                'totalGlobalAfiliados' =>
                    $totalGlobalAfiliados,

                'totalPaginas' =>
                    $totalPaginas
            ]
        );
    }

public function exportar(): void
    {
        Auth::requirePermission('AFILIADOS_CONSULTA');

        /**
         * Permitir exportaciones grandes.
         */
        set_time_limit(0);

        /**
         * Evitar que advertencias/notices
         * se mezclen con el archivo.
         */
        ini_set(
            'display_errors',
            '0'
        );

        /**
         * Filtros.
         */
        $buscar = trim(
            $_GET['buscar'] ?? ''
        );

        $ue = trim(
            $_GET['ue'] ?? ''
        );

        $red = trim(
            $_GET['red'] ?? ''
        );

        $microred = trim(
            $_GET['microred'] ?? ''
        );

        $idEess = trim(
            $_GET['id_eess'] ?? ''
        );

        /**
         * Formato.
         *
         * Por defecto CSV.
         */
        $formato = strtolower(
            trim(
                $_GET['formato'] ?? 'csv'
            )
        );

        /**
         * ======================================================
         * EXCEL
         * ======================================================
         */
        if ($formato === 'excel') {

            /**
             * Contar registros que cumplen
             * los filtros.
             */
            $total =
                $this->afiliadoModel->contar(
                    $buscar,
                    $ue,
                    $red,
                    $microred,
                    $idEess
                );

            /**
             * Hasta 60,000 registros:
             * Excel.
             *
             * Más de 60,000:
             * CSV.
             */
            if ($total <= 60000) {

                $this->exportarExcel(
                    $buscar,
                    $ue,
                    $red,
                    $microred,
                    $idEess
                );

                return;
            }

            /**
             * Si supera 60,000,
             * continúa con CSV.
             */
            $formato = 'csv';
        }

        /**
         * ======================================================
         * CSV
         * ======================================================
         */

        $stmt =
            $this->afiliadoModel->exportar(
                $buscar,
                $ue,
                $red,
                $microred,
                $idEess
            );

        /**
         * Nombre del archivo.
         */
        $nombreArchivo =
            'afiliados_' .
            date('Y-m-d_H-i-s') .
            '.csv';

        /**
         * Encabezados HTTP.
         */
        header(
            'Content-Type: text/csv; charset=UTF-8'
        );

        header(
            'Content-Disposition: attachment; filename="' .
            $nombreArchivo .
            '"'
        );

        header(
            'Pragma: no-cache'
        );

        header(
            'Expires: 0'
        );

        /**
         * Salida directa.
         */
        $salida = fopen(
            'php://output',
            'w'
        );

        /**
         * BOM UTF-8.
         */
        fwrite(
            $salida,
            "\xEF\xBB\xBF"
        );

        /**
         * Encabezados CSV.
         */
        fputcsv(
            $salida,
            [
                'IdAfiliados',
                'IdSiasis',
                'TipoTabla',
                'NroFormato',
                'Correlativo',
                'TipoDocumento',
                'FechaFormato',
                'NumeroDni',
                'NombreAsegurado',
                'Sexo',
                'FechaNacimiento',
                'Estado',
                'FechaBaja',
                'EessAdscrito',
                'Provincia',
                'Distrito',
                'GrupoPoblacional',
                'MotivoBaja',
                'IdEESS',
                'RENAES',
                'IPRESS_ATENC',
                'COD_UE',
                'UE',
                'RED',
                'MICRORED'
            ],
            ';',
            '"',
            ''
        );

        /**
         * Procesar registro por registro.
         */
        while (
            $fila = $stmt->fetch(
                PDO::FETCH_ASSOC
            )
        ) {

            fputcsv(
                $salida,
                [
                    $fila['IdAfiliados'],
                    $fila['IdSiasis'],
                    $fila['TipoTabla'],
                    $fila['NroFormato'],
                    $fila['Correlativo'],
                    $fila['TipoDocumento'],
                    $fila['FechaFormato'],
                    $fila['NumeroDni'],
                    trim(
                        $fila['NombreAsegurado'] ?? ''
                    ),
                    $fila['Sexo'],
                    $fila['FechaNacimiento'],
                    $fila['Estado'],
                    $fila['FechaBaja'],
                    $fila['EessAdscrito'],
                    $fila['Provincia'],
                    $fila['Distrito'],
                    $fila['GrupoPoblacional'],
                    $fila['MotivoBaja'],
                    $fila['IdEESS'],
                    $fila['RENAES'],
                    $fila['IPRESS_ATENC'],
                    $fila['COD_UE'],
                    $fila['UE'],
                    $fila['RED'],
                    $fila['MICRORED']
                ],
                ';',
                '"',
                ''
            );
        }

        fclose(
            $salida
        );

        exit;
    }

private function exportarExcel(
        string $buscar,
        string $ue,
        string $red,
        string $microred,
        string $idEess
    ): void {

        set_time_limit(0);
        ini_set('display_errors', '0');

        require_once __DIR__ . '/../../vendor/autoload.php';

        $stmt = $this->afiliadoModel->exportar(
            $buscar,
            $ue,
            $red,
            $microred,
            $idEess
        );

        $encabezados = [
            'IdAfiliados',
            'IdSiasis',
            'TipoTabla',
            'NroFormato',
            'Correlativo',
            'TipoDocumento',
            'FechaFormato',
            'NumeroDni',
            'NombreAsegurado',
            'Sexo',
            'FechaNacimiento',
            'Estado',
            'FechaBaja',
            'EessAdscrito',
            'Provincia',
            'Distrito',
            'GrupoPoblacional',
            'MotivoBaja',
            'IdEESS',
            'RENAES',
            'IPRESS_ATENC',
            'COD_UE',
            'UE',
            'RED',
            'MICRORED'
        ];

        $nombreArchivo = 'afiliados_' . date('Y-m-d_H-i-s') . '.xlsx';

        // OpenSpout genera XLSX progresivamente, sin crear una hoja
        // completa en memoria como PhpSpreadsheet.
        $writer = new \OpenSpout\Writer\XLSX\Writer();

        try {
            $writer->openToBrowser($nombreArchivo);
            $writer->getCurrentSheet()->setName('Afiliados');
            $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues($encabezados));

            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $datos = [
                    $fila['IdAfiliados'],
                    $fila['IdSiasis'],
                    $fila['TipoTabla'],
                    $fila['NroFormato'],
                    $fila['Correlativo'],
                    $fila['TipoDocumento'],
                    $fila['FechaFormato'],
                    $fila['NumeroDni'],
                    trim($fila['NombreAsegurado'] ?? ''),
                    $fila['Sexo'],
                    $fila['FechaNacimiento'],
                    $fila['Estado'],
                    $fila['FechaBaja'],
                    $fila['EessAdscrito'],
                    $fila['Provincia'],
                    $fila['Distrito'],
                    $fila['GrupoPoblacional'],
                    $fila['MotivoBaja'],
                    $fila['IdEESS'],
                    $fila['RENAES'],
                    $fila['IPRESS_ATENC'],
                    $fila['COD_UE'],
                    $fila['UE'],
                    $fila['RED'],
                    $fila['MICRORED']
                ];

                $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues($datos));
            }

            $writer->close();
        } catch (\Throwable $e) {
            // Si la respuesta HTTP ya comenzó, no imprimir el error
            // encima del archivo. Registrar el detalle en el log PHP.
            error_log('Error al exportar afiliados a Excel: ' . $e->getMessage());

            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: text/plain; charset=UTF-8');
            }

            exit('No fue posible generar el archivo Excel. Revise el registro de errores de PHP.');
        }

        exit;
    }

public function detalle(): void
{
    Auth::requirePermission('AFILIADOS_CONSULTA');

    $idAfiliados = filter_input(
        INPUT_GET,
        'id',
        FILTER_VALIDATE_INT
    );

    if (!$idAfiliados || $idAfiliados < 1) {
        \Session::flash(
            'error',
            'El identificador del afiliado no es válido.'
        );
        $this->redirect('index.php?route=afiliados_consulta');
        return;
    }

    $afiliado = $this->afiliadoModel->buscarPorId(
        (int) $idAfiliados
    );

    if ($afiliado === null) {
        \Session::flash(
            'error',
            'No se encontró el afiliado solicitado.'
        );
        $this->redirect('index.php?route=afiliados_consulta');
        return;
    }

    $this->view('afiliados_consulta/detalle', [
        'afiliado' => $afiliado
    ]);
}
}
