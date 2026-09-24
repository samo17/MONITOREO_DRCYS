<?php

/**
 * Escapa caracteres especiales para mostrar datos de forma segura en HTML.
 */
function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

/**
 * Genera una URL dentro del proyecto.
 */
function url(string $route = ''): string
{
    $baseUrl = '/DRCYS/public/index.php';

    if ($route === '') {
        return $baseUrl;
    }

    return $baseUrl . '?route=' . urlencode($route);
}

/**
 * Devuelve la fecha y hora actual.
 */
function ahora(): string
{
    return date('Y-m-d H:i:s');
}

/**
 * Verifica si una solicitud es AJAX.
 */
function esAjax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Redirige a una ruta dentro del proyecto.
 */
function redirect(string $route = ''): never
{
    header('Location: ' . url($route));
    exit;
}