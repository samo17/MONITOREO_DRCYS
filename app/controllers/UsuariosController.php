<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuariosController extends Controller
{
    private Usuario $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

public function index(): void
{
    Auth::requirePermission('USUARIOS');

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

    // Paginación
    $pagina = isset($_GET['pagina'])
        ? (int) $_GET['pagina']
        : 1;

    if ($pagina < 1) {
        $pagina = 1;
    }

    $porPagina = 10;

    $totalUsuarios = $this->usuarioModel->contar(
        $buscar,
        $estado
    );

    $totalPaginas = max(
        1,
        (int) ceil($totalUsuarios / $porPagina)
    );

    if ($pagina > $totalPaginas) {
        $pagina = $totalPaginas;
    }

    $offset = ($pagina - 1) * $porPagina;

    $usuarios = $this->usuarioModel->listar(
        $buscar,
        $estado,
        $porPagina,
        $offset
    );

    $this->view('usuarios/index', [
        'usuarios' => $usuarios,
        'buscar' => $buscar,
        'estadoFiltro' => $estadoFiltro,
        'pagina' => $pagina,
        'porPagina' => $porPagina,
        'totalUsuarios' => $totalUsuarios,
        'totalPaginas' => $totalPaginas
    ]);
}
public function crear(): void
{
    Auth::requirePermission('USUARIOS');

    $permisos = $this->usuarioModel->listarPermisos();

    $this->view('usuarios/crear', [
        'permisos' => $permisos
    ]);
}

public function editar(): void
{
    Auth::requirePermission('USUARIOS');

    $idUsuario = isset($_GET['id'])
        ? (int) $_GET['id']
        : 0;

    if ($idUsuario <= 0) {
        die('Usuario no válido.');
    }

    $usuario = $this->usuarioModel->buscarPorId($idUsuario);

    if (!$usuario) {
        die('Usuario no encontrado.');
    }

    $permisos = $this->usuarioModel->listarPermisos();

    $permisosUsuario = $this->usuarioModel->listarPermisosUsuario($idUsuario);

    $this->view('usuarios/editar', [
        'usuario' => $usuario,
        'permisos' => $permisos,
        'permisosUsuario' => $permisosUsuario
    ]);
}

public function actualizar(): void
{
    Auth::requirePermission('USUARIOS');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('index.php?route=usuarios');
    }

    $idUsuario = isset($_POST['IdUsuario'])
        ? (int) $_POST['IdUsuario']
        : 0;

    $usuario = trim($_POST['Usuario'] ?? '');
    $password = $_POST['Password'] ?? '';
    $nombres = trim($_POST['Nombres'] ?? '');
    $apellidos = trim($_POST['Apellidos'] ?? '');
    $dni = trim($_POST['DNI'] ?? '');
    $correo = trim($_POST['Correo'] ?? '');
    $telefono = trim($_POST['Telefono'] ?? '');
    $estado = isset($_POST['Estado'])
        ? (int) $_POST['Estado']
        : -1;

    $permisos = $_POST['permisos'] ?? [];

    /*
     * Validar usuario
     */
    if ($idUsuario <= 0) {
        die('Usuario no válido.');
    }

    /*
     * Verificar que exista
     */
    $usuarioActual = $this->usuarioModel->buscarPorId($idUsuario);

    if (!$usuarioActual) {
        die('Usuario no encontrado.');
    }

    /*
     * Validar campos obligatorios
     */
    if (
        $usuario === '' ||
        $nombres === '' ||
        $apellidos === '' ||
        $dni === ''
    ) {
        die('Todos los campos obligatorios deben ser completados.');
    }

    /*
     * Validar DNI
     */
    if (!preg_match('/^[0-9]{8}$/', $dni)) {
        die('El DNI debe contener exactamente 8 dígitos.');
    }

    /*
     * Validar estado
     */
    if (!in_array($estado, [0, 1], true)) {
        die('Estado no válido.');
    }

    /*
     * Validar permisos
     */
    $permisos = array_map('intval', $permisos);
    $permisos = array_values(array_unique($permisos));

    /*
     * Convertir campos opcionales vacíos a NULL
     */
    $dni = $dni !== '' ? $dni : null;
    $correo = $correo !== '' ? $correo : null;
    $telefono = $telefono !== '' ? $telefono : null;

    /*
     * Actualizar usuario y permisos
     */
    try {

        $this->usuarioModel->actualizarConPermisos(
            $idUsuario,
            $usuario,
            $nombres,
            $apellidos,
            $dni,
            $correo,
            $telefono,
            $estado,
            $permisos,
            $password !== '' ? $password : null
        );

        $this->redirect('index.php?route=usuarios');

    } catch (PDOException $e) {

        if ($e->getCode() === '23000') {
            die('El usuario o DNI ya se encuentra registrado.');
        }

        die('No fue posible actualizar el usuario.');

    } catch (Throwable $e) {

        die('Ocurrió un error al actualizar el usuario.');
    }
}

public function cambiarEstado(): void
{
    Auth::requirePermission('USUARIOS');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('index.php?route=usuarios');
    }

    $idUsuario = isset($_POST['IdUsuario'])
        ? (int) $_POST['IdUsuario']
        : 0;

    $estado = isset($_POST['Estado'])
        ? (int) $_POST['Estado']
        : -1;

    if ($idUsuario <= 0) {
        die('Usuario no válido.');
    }

    if (!in_array($estado, [0, 1], true)) {
        die('Estado no válido.');
    }

    $usuario = $this->usuarioModel->buscarPorId($idUsuario);

    if (!$usuario) {
        die('Usuario no encontrado.');
    }

    try {
        $this->usuarioModel->cambiarEstado(
            $idUsuario,
            $estado
        );

        $this->redirect('index.php?route=usuarios');
    } catch (Throwable $e) {
        die('No fue posible cambiar el estado del usuario.');
    }
}

public function guardar(): void
{
    Auth::requirePermission('USUARIOS');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('index.php?route=usuarios');
    }

    $usuario   = trim($_POST['Usuario'] ?? '');
    $password  = $_POST['Password'] ?? '';
    $nombres   = trim($_POST['Nombres'] ?? '');
    $apellidos = trim($_POST['Apellidos'] ?? '');
    $dni       = trim($_POST['DNI'] ?? '');
    $correo    = trim($_POST['Correo'] ?? '');
    $telefono  = trim($_POST['Telefono'] ?? '');

    $permisos = $_POST['permisos'] ?? [];

    /*
     * Validación básica
     */
    if (
        $usuario === '' ||
        $password === '' ||
        $nombres === '' ||
        $apellidos === '' ||
        $dni === ''
    ) {
        die('Todos los campos obligatorios deben ser completados.');
    }

    /*
     * Validar DNI
     */
    if (!preg_match('/^[0-9]{8}$/', $dni)) {
        die('El DNI debe contener exactamente 8 dígitos.');
    }

    /*
     * Validar permisos
     */
    $permisos = array_map('intval', $permisos);
    $permisos = array_values(array_unique($permisos));

    try {

        $idUsuario = $this->usuarioModel->crearConPermisos(
            $usuario,
            $password,
            $nombres,
            $apellidos,
            $dni !== '' ? $dni : null,
            $correo !== '' ? $correo : null,
            $telefono !== '' ? $telefono : null,
            $permisos
        );

        $this->redirect('index.php?route=usuarios');

    } catch (PDOException $e) {

        if ($e->getCode() === '23000') {
            die('El usuario o DNI ya se encuentra registrado.');
        }

        die('No fue posible registrar el usuario.');

    } catch (Throwable $e) {

        die('Ocurrió un error al registrar el usuario.');
    }
}
}