<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Afiliado.php';

class AfiliadosController extends Controller
{
    private Afiliado $afiliadoModel;

    public function __construct()
    {
        $this->afiliadoModel = new Afiliado();
    }

    public function index(): void
    {
        Auth::requirePermission('AFILIADOS');

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
         * Total de afiliados
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
            'afiliados/index',
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

                'totalPaginas' =>
                    $totalPaginas
            ]
        );
    }


    /**
     * ==========================================================
     * EXPORTAR AFILIADOS
     * ==========================================================
     *
     * formato=csv
     * formato=excel
     *
     * Excel:
     * Hasta 20,000 registros.
     *
     * Más de 20,000:
     * Se utiliza CSV.
     */
    public function exportar(): void
    {
        Auth::requirePermission('AFILIADOS');

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
             * Hasta 20,000 registros:
             * Excel.
             *
             * Más de 20,000:
             * CSV.
             */
            if ($total <= 20000) {

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
             * Si supera 20,000,
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


    /**
     * ==========================================================
     * EXPORTAR EXCEL
     * ==========================================================
     */
    private function exportarExcel(
        string $buscar,
        string $ue,
        string $red,
        string $microred,
        string $idEess
    ): void {

        /**
         * Permitir que Excel supere los 30 segundos.
         */
        set_time_limit(0);

        /**
         * Memoria disponible para PhpSpreadsheet.
         */
        ini_set(
            'memory_limit',
            '512M'
        );

        /**
         * Cargar Composer.
         */
        require_once __DIR__ .
            '/../../vendor/autoload.php';

        /**
         * Obtener los registros.
         *
         * La consulta del modelo está configurada
         * sin buffer.
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
         * Crear libro Excel.
         */
        $spreadsheet =
            new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $hoja =
            $spreadsheet->getActiveSheet();

        $hoja->setTitle(
            'Afiliados'
        );

        /**
         * ======================================================
         * ENCABEZADOS
         * ======================================================
         */
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

        $columna = 1;

        foreach (
            $encabezados as $encabezado
        ) {

            $celda =
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $columna
                ) . '1';

            $hoja->setCellValue(
                $celda,
                $encabezado
            );

            $columna++;
        }

        /**
         * ======================================================
         * DATOS
         * ======================================================
         *
         * IMPORTANTE:
         * $filaExcel se inicializa antes del while.
         */
        $filaExcel = 2;

        while (
            $fila = $stmt->fetch(
                PDO::FETCH_ASSOC
            )
        ) {

            $datos = [
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
            ];

            $columna = 1;

            /**
             * Escribir cada columna del registro.
             */
            foreach (
                $datos as $valor
            ) {

                $celda =
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                        $columna
                    ) . $filaExcel;

                $hoja->setCellValue(
                    $celda,
                    $valor
                );

                $columna++;
            }

            /**
             * Siguiente fila.
             */
            $filaExcel++;
        }

        /**
         * Congelar encabezado.
         */
        $hoja->freezePane(
            'A2'
        );

        /**
         * Filtro automático.
         */
        $hoja->setAutoFilter(
            $hoja->calculateWorksheetDimension()
        );

        /**
         * Ancho de columnas.
         */
        foreach (
            range(
                1,
                count($encabezados)
            ) as $columna
        ) {

            $hoja
                ->getColumnDimensionByColumn(
                    $columna
                )
                ->setAutoSize(true);
        }

        /**
         * Nombre del archivo.
         */
        $nombreArchivo =
            'afiliados_' .
            date('Y-m-d_H-i-s') .
            '.xlsx';

        /**
         * Encabezados HTTP.
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

        /**
         * Generar Excel.
         */
        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );

        $writer->save(
            'php://output'
        );

        /**
         * Liberar memoria.
         */
        $spreadsheet->disconnectWorksheets();

        unset(
            $spreadsheet
        );

        exit;
    }
}