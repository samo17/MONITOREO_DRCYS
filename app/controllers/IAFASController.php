<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/IAFAS.php';

class IAFASController extends Controller
{
    private IAFAS $model;
    public function __construct() { $this->model = new IAFAS(); }

    public function index(): void
    {
        Auth::requirePermission('IAFAS');
        $buscar = trim($_GET['buscar'] ?? '');
        $estadoFiltro = $_GET['estado'] ?? '';
        $estado = in_array($estadoFiltro, ['0','1'], true) ? (int)$estadoFiltro : null;
        if ($estado === null) $estadoFiltro = '';
        $pagina = max(1, (int)($_GET['pagina'] ?? 1)); $porPagina = 10;
        $total = $this->model->contar($buscar, $estado);
        $paginas = max(1, (int)ceil($total / $porPagina)); $pagina = min($pagina, $paginas);
        $registros = $this->model->listar($buscar, $estado, $porPagina, ($pagina - 1) * $porPagina);
        $this->view('iafas/index', compact('registros','buscar','estadoFiltro','pagina','porPagina','total','paginas'));
    }

    public function crear(): void { Auth::requirePermission('IAFAS'); $this->view('iafas/crear'); }

    public function guardar(): void
    {
        Auth::requirePermission('IAFAS');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('index.php?route=iafas');
        $nombre = trim($_POST['Nombre'] ?? '');
        if ($nombre === '' || mb_strlen($nombre) > 150) die('El nombre es obligatorio y no debe superar 150 caracteres.');
        try { $this->model->crear($nombre); $this->redirect('index.php?route=iafas'); }
        catch (PDOException $e) { if ($e->getCode() === '23000') die('La IAFAS ya se encuentra registrada.'); die('No fue posible registrar la IAFAS.'); }
    }

    public function editar(): void
    {
        Auth::requirePermission('IAFAS'); $id = (int)($_GET['id'] ?? 0); $iafas = $this->model->buscarPorId($id);
        if (!$iafas) die('IAFAS no encontrada.'); $this->view('iafas/editar', ['iafas' => $iafas]);
    }

    public function actualizar(): void
    {
        Auth::requirePermission('IAFAS');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('index.php?route=iafas');
        $id = (int)($_POST['IdIAFAS'] ?? 0); $nombre = trim($_POST['Nombre'] ?? '');
        if ($id <= 0 || $nombre === '' || mb_strlen($nombre) > 150) die('Datos de IAFAS no válidos.');
        if (!$this->model->buscarPorId($id)) die('IAFAS no encontrada.');
        try { $this->model->actualizar($id, $nombre); $this->redirect('index.php?route=iafas'); }
        catch (PDOException $e) { if ($e->getCode() === '23000') die('Ya existe una IAFAS con ese nombre.'); die('No fue posible actualizar la IAFAS.'); }
    }

    public function cambiarEstado(): void
    {
        Auth::requirePermission('IAFAS');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('index.php?route=iafas');
        $id = (int)($_POST['IdIAFAS'] ?? 0); $estado = (int)($_POST['Estado'] ?? -1);
        if ($id <= 0 || !in_array($estado, [0,1], true)) die('Datos no válidos.');
        if (!$this->model->buscarPorId($id)) die('IAFAS no encontrada.');
        $this->model->cambiarEstado($id, $estado); $this->redirect('index.php?route=iafas');
    }
}
