<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/TipoOcurrencia.php';

class TiposOcurrenciaController extends Controller
{
    private TipoOcurrencia $tipoOcurrenciaModel;

    public function __construct()
    {
        $this->tipoOcurrenciaModel = new TipoOcurrencia();
    }

    public function index(): void
    {
        Auth::requirePermission('TIPOS_OCURRENCIA');

        $buscar = trim($_GET['buscar'] ?? '');

        $estadoFiltro = $_GET['estado'] ?? '';

        if ($estadoFiltro === '1') {
            $estado = 1;
        } elseif ($estadoFiltro === '0') {
            $estado = 0;
        } else {
            $estado = null;
            $estadoFiltro = '';
        }

        $pagina = isset($_GET['pagina'])
            ? (int) $_GET['pagina']
            : 1;

        if ($pagina < 1) {
            $pagina = 1;
        }

        $porPagina = 10;

        $totalTipos = $this->tipoOcurrenciaModel->contar(
            $buscar,
            $estado
        );

        $totalPaginas = max(
            1,
            (int) ceil($totalTipos / $porPagina)
        );

        if ($pagina > $totalPaginas) {
            $pagina = $totalPaginas;
        }

        $offset = ($pagina - 1) * $porPagina;

        $tipos = $this->tipoOcurrenciaModel->listar(
            $buscar,
            $estado,
            $porPagina,
            $offset
        );

        $this->view('tiposocurrencia/index', [
            'tipos' => $tipos,
            'buscar' => $buscar,
            'estadoFiltro' => $estadoFiltro,
            'pagina' => $pagina,
            'porPagina' => $porPagina,
            'totalTipos' => $totalTipos,
            'totalPaginas' => $totalPaginas
        ]);
    }

    public function crear(): void
    {
        Auth::requirePermission('TIPOS_OCURRENCIA');

        $this->view('tiposocurrencia/crear');
    }

    public function guardar(): void
    {
        Auth::requirePermission('TIPOS_OCURRENCIA');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(
                'index.php?route=tiposocurrencia'
            );
        }

        $descripcion = trim(
            $_POST['Descripcion'] ?? ''
        );

        if ($descripcion === '') {
            die('La descripción es obligatoria.');
        }

        if (mb_strlen($descripcion) > 150) {
            die(
                'La descripción no puede superar los 150 caracteres.'
            );
        }

        try {

            $this->tipoOcurrenciaModel->crear(
                $descripcion
            );

            $this->redirect(
                'index.php?route=tiposocurrencia'
            );

        } catch (PDOException $e) {

            if ($e->getCode() === '23000') {
                die(
                    'El tipo de ocurrencia ya se encuentra registrado.'
                );
            }

            die(
                'No fue posible registrar el tipo de ocurrencia.'
            );

        } catch (Throwable $e) {

            die(
                'Ocurrió un error al registrar el tipo de ocurrencia.'
            );
        }
    }

    public function editar(): void
    {
        Auth::requirePermission('TIPOS_OCURRENCIA');

        $idTipoOcurrencia = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        if ($idTipoOcurrencia <= 0) {
            die('Tipo de ocurrencia no válido.');
        }

        $tipo = $this->tipoOcurrenciaModel->buscarPorId(
            $idTipoOcurrencia
        );

        if (!$tipo) {
            die('Tipo de ocurrencia no encontrado.');
        }

        $this->view('tiposocurrencia/editar', [
            'tipo' => $tipo
        ]);
    }

    public function actualizar(): void
    {
        Auth::requirePermission('TIPOS_OCURRENCIA');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(
                'index.php?route=tiposocurrencia'
            );
        }

        $idTipoOcurrencia = isset($_POST['IdTipoOcurrencia'])
            ? (int) $_POST['IdTipoOcurrencia']
            : 0;

        $descripcion = trim(
            $_POST['Descripcion'] ?? ''
        );

        if ($idTipoOcurrencia <= 0) {
            die('Tipo de ocurrencia no válido.');
        }

        if ($descripcion === '') {
            die('La descripción es obligatoria.');
        }

        if (mb_strlen($descripcion) > 150) {
            die(
                'La descripción no puede superar los 150 caracteres.'
            );
        }

        $tipo = $this->tipoOcurrenciaModel->buscarPorId(
            $idTipoOcurrencia
        );

        if (!$tipo) {
            die('Tipo de ocurrencia no encontrado.');
        }

        try {

            $this->tipoOcurrenciaModel->actualizar(
                $idTipoOcurrencia,
                $descripcion
            );

            $this->redirect(
                'index.php?route=tiposocurrencia'
            );

        } catch (PDOException $e) {

            if ($e->getCode() === '23000') {
                die(
                    'El tipo de ocurrencia ya se encuentra registrado.'
                );
            }

            die(
                'No fue posible actualizar el tipo de ocurrencia.'
            );

        } catch (Throwable $e) {

            die(
                'Ocurrió un error al actualizar el tipo de ocurrencia.'
            );
        }
    }

    public function cambiarEstado(): void
    {
        Auth::requirePermission('TIPOS_OCURRENCIA');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(
                'index.php?route=tiposocurrencia'
            );
        }

        $idTipoOcurrencia = isset($_POST['IdTipoOcurrencia'])
            ? (int) $_POST['IdTipoOcurrencia']
            : 0;

        $estado = isset($_POST['Estado'])
            ? (int) $_POST['Estado']
            : -1;

        if ($idTipoOcurrencia <= 0) {
            die('Tipo de ocurrencia no válido.');
        }

        if (!in_array($estado, [0, 1], true)) {
            die('Estado no válido.');
        }

        $tipo = $this->tipoOcurrenciaModel->buscarPorId(
            $idTipoOcurrencia
        );

        if (!$tipo) {
            die('Tipo de ocurrencia no encontrado.');
        }

        try {

            $this->tipoOcurrenciaModel->cambiarEstado(
                $idTipoOcurrencia,
                $estado
            );

            $this->redirect(
                'index.php?route=tiposocurrencia'
            );

        } catch (Throwable $e) {

            die(
                'No fue posible cambiar el estado del tipo de ocurrencia.'
            );
        }
    }
}