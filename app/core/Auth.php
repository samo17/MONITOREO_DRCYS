<?php

require_once __DIR__ . '/Session.php';

class Auth
{
    /**
     * Verifica si existe un usuario autenticado.
     */
    public static function check(): bool
{
    if (!Session::has('IdUsuario')) {
        return false;
    }

    $idUsuario = self::id();

    if ($idUsuario === null) {
        return false;
    }

    require_once __DIR__ . '/../models/Usuario.php';

    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->buscarPorId($idUsuario);

    if (!$usuario) {
        Session::destroy();
        return false;
    }

    if ((int) $usuario['Estado'] !== 1) {
        Session::destroy();
        return false;
    }

    return true;
}

    /**
     * Verifica que el usuario esté autenticado.
     * Si no lo está, lo envía al login.
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /DRCYS/public/index.php?route=login');
            exit;
        }
    }

    /**
     * Obtiene el ID del usuario autenticado.
     */
    public static function id(): ?int
    {
        $id = Session::get('IdUsuario');

        return $id !== null ? (int) $id : null;
    }

    /**
     * Obtiene el DNI del usuario autenticado.
     */
    public static function dni(): ?string
    {
        return Session::get('DNI');
    }

    /**
     * Obtiene el nombre completo del usuario autenticado.
     */
    public static function nombreCompleto(): string
    {
        $nombres = Session::get('Nombres', '');
        $apellidos = Session::get('Apellidos', '');

        return trim($nombres . ' ' . $apellidos);
    }

    /**
     * Cierra la sesión del usuario.
     */
    public static function logout(): void
    {
        Session::destroy();
    }
    public static function tienePermiso(string $modulo): bool
{
    if (!self::check()) {
        return false;
    }

    $idUsuario = self::id();

    if ($idUsuario === null) {
        return false;
    }

    require_once __DIR__ . '/../models/Usuario.php';

    $usuarioModel = new Usuario();

    return $usuarioModel->tienePermiso(
        $idUsuario,
        $modulo
    );
}
public static function requirePermission(string $modulo): void
{
    self::requireLogin();

    if (!self::tienePermiso($modulo)) {
        http_response_code(403);

        die(
            '<h1>403</h1>' .
            '<p>No tiene permisos para acceder a este módulo.</p>'
        );
    }
}
}