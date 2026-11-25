<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/MaestroAfiliado.php';

class MaestrosController extends Controller
{
    private MaestroAfiliado $maestroAfiliadoModel;

    public function __construct()
    {
        $this->maestroAfiliadoModel = new MaestroAfiliado();
    }

    /**
     * Pantalla principal del módulo Maestros.
     * Vista: app/views/maestros/index.php
     */
    public function index(): void
    {
        Auth::requirePermission('MAESTROS');

        $mensaje = null;
        $tipoMensaje = 'info';
        $totalActual = null;

        try {
            $totalActual = $this->maestroAfiliadoModel->contar();
        } catch (Throwable $e) {
            $mensaje = 'No fue posible consultar m_afiliados.';
            $tipoMensaje = 'danger';
        }

        $this->view('maestros/index', [
            'totalActual' => $totalActual,
            'mensaje' => $mensaje,
            'tipoMensaje' => $tipoMensaje,
            'resultado' => null,
        ]);
    }

    /**
     * Muestra el formulario y procesa la carga directa del CSV.
     * Vista: app/views/maestros/cargar.php
     */
    public function cargarAfiliados(): void
    {
        Auth::requirePermission('MAESTROS');

        $mensaje = null;
        $tipoMensaje = 'info';
        $resultado = null;
        $nombreArchivo = '';
        $totalActual = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $archivo = $_FILES['archivo_csv'] ?? null;
            $nombreArchivo = is_array($archivo)
                ? (string) ($archivo['name'] ?? '')
                : '';

            $tipoMensaje = 'danger';

            try {
                if (!is_array($archivo)) {
                    throw new RuntimeException('Selecciona un archivo CSV.');
                }

                if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                    throw new RuntimeException(
                        'No fue posible recibir el archivo. Verifica el tamaño permitido y vuelve a intentarlo.'
                    );
                }

                if (strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION)) !== 'csv') {
                    throw new RuntimeException('El archivo debe tener extensión .csv.');
                }

                $rutaTemporal = (string) ($archivo['tmp_name'] ?? '');
                if ($rutaTemporal === '' || !is_uploaded_file($rutaTemporal)) {
                    throw new RuntimeException(
                        'El archivo recibido no es válido. Selecciona el CSV nuevamente.'
                    );
                }

                $resultado = $this->maestroAfiliadoModel->cargarCsv($rutaTemporal);
                $mensaje = 'Carga completada correctamente en m_afiliados.';
                $tipoMensaje = 'success';
            } catch (Throwable $e) {
                $mensaje = $e->getMessage();
            }
        }

        try {
            $totalActual = $this->maestroAfiliadoModel->contar();
        } catch (Throwable $e) {
            if ($mensaje === null) {
                $mensaje = 'No fue posible consultar m_afiliados.';
                $tipoMensaje = 'danger';
            }
        }

        $this->view('maestros/cargar', [
            'totalActual' => $totalActual,
            'mensaje' => $mensaje,
            'tipoMensaje' => $tipoMensaje,
            'resultado' => $resultado,
            'nombreArchivo' => $nombreArchivo,
        ]);
    }

    /**
     * Compatibilidad con enlaces antiguos del módulo.
     */
    public function afiliados(): void
    {
        Auth::requirePermission('MAESTROS');
        $this->redirect('index.php?route=maestros');
    }
}
