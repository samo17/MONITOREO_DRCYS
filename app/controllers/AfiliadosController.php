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

                'totalGlobalAfiliados' =>
                    $totalGlobalAfiliados,

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
     * Hasta 60,000 registros.
     *
     * Más de 60,000:
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


    /**
     * ==========================================================
     * EXPORTAR EXCEL CON OPENSpout
     * ==========================================================
     *
     * Escribe el archivo fila por fila para reducir el consumo
     * de memoria. Conserva los filtros y las 25 columnas actuales.
     */
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

    /**
     * ==========================================================
     * FORMULARIO DE CARGA MASIVA DE AFILIADOS
     * ==========================================================
     */
    public function cargar(): void
    {
        Auth::requirePermission('AFILIADOS');

        $this->view(
            'afiliados/cargar',
            [
                'resultado' => null,
                'nombreArchivo' => ''
            ]
        );
    }


    /**
     * ==========================================================
     * ANALIZAR CSV DE AFILIADOS
     * ==========================================================
     *
     * Recibe el archivo CSV y muestra una vista previa sin
     * modificar la base de datos.
     */
    public function analizarCsv(): void
    {
        Auth::requirePermission('AFILIADOS');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?route=afiliados');
        }

        if (
            !isset($_FILES['archivo_csv']) ||
            !is_array($_FILES['archivo_csv'])
        ) {
            \Session::flash('error', 'No se recibió ningún archivo CSV.');
            $this->redirect('index.php?route=afiliados');
        }

        $archivo = $_FILES['archivo_csv'];

        if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            \Session::flash('error', 'No fue posible recibir el archivo CSV.');
            $this->redirect('index.php?route=afiliados');
        }

        $nombre = (string) ($archivo['name'] ?? '');
        $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

        if ($extension !== 'csv') {
            \Session::flash('error', 'El archivo debe tener formato CSV.');
            $this->redirect('index.php?route=afiliados');
        }

        try {
            $resultado = $this->afiliadoModel->analizarCsv(
                (string) ($archivo['tmp_name'] ?? '')
            );

            $this->view(
                'afiliados/cargar',
                [
                    'resultado' => $resultado,
                    'nombreArchivo' => $nombre
                ]
            );
        } catch (\Throwable $e) {
            \Session::flash(
                'error',
                'No fue posible analizar el CSV: ' . $e->getMessage()
            );

            $this->redirect('index.php?route=afiliados');
        }
    }


    /**
     * ==========================================================
     * CARGAR CSV DE AFILIADOS
     * ==========================================================
     *
     * Actualiza por NumeroDni cuando ya existe y agrega los
     * registros nuevos. No elimina registros existentes.
     */
    public function cargarCsv(): void
    {
        Auth::requirePermission('AFILIADOS');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?route=afiliados');
        }

        if (
            !isset($_FILES['archivo_csv']) ||
            !is_array($_FILES['archivo_csv'])
        ) {
            \Session::flash('error', 'No se recibió ningún archivo CSV.');
            $this->redirect('index.php?route=afiliados');
        }

        $archivo = $_FILES['archivo_csv'];

        if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            \Session::flash('error', 'No fue posible recibir el archivo CSV.');
            $this->redirect('index.php?route=afiliados');
        }

        $nombre = (string) ($archivo['name'] ?? '');
        $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

        if ($extension !== 'csv') {
            \Session::flash('error', 'El archivo debe tener formato CSV.');
            $this->redirect('index.php?route=afiliados');
        }

        try {
            $resultado = $this->afiliadoModel->cargarCsv(
                (string) ($archivo['tmp_name'] ?? '')
            );

            \Session::flash(
                'success',
                'Importación a m_afiliados completada. ' .
                'Registros importados: ' . (int) $resultado['totalProcesados'] .
                '. La tabla afiliados no fue modificada.'
            );
        } catch (\Throwable $e) {
            // Diagnóstico temporal: mostrar el error real de PHP/MySQL
            // en lugar de ocultarlo mediante una redirección.
            http_response_code(500);

            $mensaje = htmlspecialchars(
                $e->getMessage(),
                ENT_QUOTES,
                'UTF-8'
            );

            $archivoError = htmlspecialchars(
                $nombre,
                ENT_QUOTES,
                'UTF-8'
            );

            echo '<!doctype html>';
            echo '<html lang="es">';
            echo '<head>';
            echo '<meta charset="utf-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
            echo '<title>Error al cargar afiliados</title>';
            echo '<style>';
            echo 'body{font-family:Arial,sans-serif;background:#f5f6f8;margin:0;padding:40px;color:#212529;}';
            echo '.box{max-width:900px;margin:auto;background:#fff;border:1px solid #dc3545;border-radius:8px;padding:25px;box-shadow:0 2px 8px rgba(0,0,0,.08);}';
            echo '.title{color:#b02a37;margin-top:0;}';
            echo '.error{background:#f8d7da;border:1px solid #f1aeb5;color:#842029;padding:15px;border-radius:6px;white-space:pre-wrap;word-break:break-word;}';
            echo '.file{background:#f1f3f5;padding:10px;border-radius:6px;margin:15px 0;}';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<div class="box">';
            echo '<h2 class="title">Error al cargar afiliados</h2>';
            echo '<div class="file"><strong>Archivo:</strong> ' . $archivoError . '</div>';
            echo '<div class="error"><strong>Mensaje real:</strong>' . "\n\n" . $mensaje . '</div>';
            echo '<p>La importación se ejecuta en una transacción. Verifique el mensaje antes de volver a intentarlo.</p>'; 
            echo '<p><a href="index.php?route=afiliados">Volver a Afiliados</a></p>';
            echo '</div>';
            echo '</body>';
            echo '</html>';
            exit;
        }

        $this->redirect('index.php?route=afiliados');
    }

    /** Ejecuta la sincronización separada desde m_afiliados hacia afiliados. */
    public function sincronizar(): void
    {
        Auth::requirePermission('AFILIADOS');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?route=afiliados');
            return;
        }

        try {
            $modo = (string) ($_POST['modo_sincronizacion'] ?? 'comparar');

            if ($modo === 'desde_cero') {
                // Protección del lado del servidor: no confiar solo en el botón deshabilitado.
                $totalDestino = $this->afiliadoModel->contar('', '', '', '', '');

                if ((int) $totalDestino > 0) {
                    throw new RuntimeException(
                        'No se puede sincronizar desde cero porque la tabla afiliados ya contiene ' .
                        number_format((int) $totalDestino) . ' registros. Use «Sincronizar comparando».'
                    );
                }

                $r = $this->afiliadoModel->sincronizarAfiliadosDesdeCero();
                \Session::flash(
                    'success',
                    'Carga inicial completada. Registros en origen: ' . (int) $r['totalFuente'] .
                    '; insertados: ' . (int) $r['insertados'] .
                    '; lotes procesados: ' . (int) $r['lotes'] .
                    '. No se compararon registros ni se eliminaron datos.'
                );
            } else {
                $r = $this->afiliadoModel->sincronizarAfiliados();
                \Session::flash('success',
                    'Sincronización comparativa completada. Fuente: ' . (int)$r['totalFuente'] .
                    '; insertados: ' . (int)$r['insertados'] .
                    '; actualizados por DNI + formato: ' . (int)$r['actualizadosDniFormato'] .
                    '; actualizados sin DNI: ' . (int)$r['actualizadosSinDni'] .
                    '; actualizados de Lote E a Lote 2: ' . (int)$r['actualizadosLoteE'] .
                    '; nuevos insertados: ' . (int)$r['insertados'] .
                    '; afiliados sin DNI en la fuente: ' . (int)$r['sinDniEnFuente'] .
                    '; sin clave completa para identificar: ' . (int)$r['sinDniSinClaveCompleta'] .
                    '; pendientes de revisión: ' . (int)$r['pendientesRevision'] .
                    '. Los registros operativos no fueron eliminados.'
                );
            }
        } catch (Throwable $e) {
            \Session::flash('error', 'No se pudo completar la sincronización de afiliados: ' . $e->getMessage());
        }

        $this->redirect('index.php?route=afiliados');
    }

    /**
 * Mostrar el detalle de un afiliado.
 */
public function detalle(): void
{
    Auth::requirePermission('AFILIADOS');

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
        $this->redirect('index.php?route=afiliados');
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
        $this->redirect('index.php?route=afiliados');
        return;
    }

    $this->view('afiliados/detalle', [
        'afiliado' => $afiliado
    ]);
}

}