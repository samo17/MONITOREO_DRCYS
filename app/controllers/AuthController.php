<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController extends Controller
{
    private Usuario $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Muestra el formulario de login.
     */
    public function login(): void
    {
        if (Auth::check()) {
            $this->redirect('index.php?route=dashboard');
        }

        $this->view('auth/login');
    }

    /**
     * Procesa el inicio de sesión.
     */
    public function autenticar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?route=login');
        }

        $usuario = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($usuario === '' || $password === '') {
            Session::set(
                'error_login',
                'Debe ingresar usuario y contraseña.'
            );

            $this->redirect('index.php?route=login');
        }

        $datosUsuario = $this->usuarioModel->buscarPorUsuario($usuario);

        if (!$datosUsuario) {
            Session::set(
                'error_login',
                'Usuario o contraseña incorrectos.'
            );

            $this->redirect('index.php?route=login');
        }

        if ((int) $datosUsuario['Estado'] !== 1) {
            Session::set(
                'error_login',
                'El usuario se encuentra inactivo.'
            );

            $this->redirect('index.php?route=login');
        }

        if (!password_verify($password, $datosUsuario['Password'])) {
            Session::set(
                'error_login',
                'Usuario o contraseña incorrectos.'
            );

            $this->redirect('index.php?route=login');
        }

        /*
         * Regeneramos el ID de sesión después
         * de una autenticación correcta.
         */
        Session::start();
        session_regenerate_id(true);

        Session::set('IdUsuario', $datosUsuario['IdUsuario']);
        Session::set('Usuario', $datosUsuario['Usuario']);
        Session::set('DNI', $datosUsuario['DNI']);
        Session::set('Nombres', $datosUsuario['Nombres']);
        Session::set('Apellidos', $datosUsuario['Apellidos']);

        $this->usuarioModel->actualizarUltimoAcceso(
            (int) $datosUsuario['IdUsuario']
        );

        $this->redirect('index.php?route=dashboard');
    }

    /**
     * Cierra la sesión.
     */
    public function logout(): void
    {
        Auth::logout();

        $this->redirect('index.php?route=login');
    }
}