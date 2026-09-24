<?php

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Session.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/models/TipoOcurrencia.php';

Session::start();

header('Content-Type: application/json; charset=utf-8');

try {

    Auth::requirePermission('OCURRENCIAS_CA');

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

        http_response_code(405);

        echo json_encode([
            'ok' => false,
            'mensaje' => 'Método no permitido.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $accion = $_GET['accion'] ?? '';

    if ($accion === 'tipos') {

        $modelo = new TipoOcurrencia();

        $tipos = $modelo->listarActivos();

        echo json_encode([
            'ok' => true,
            'datos' => $tipos
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    http_response_code(400);

    echo json_encode([
        'ok' => false,
        'mensaje' => 'Acción no válida.'
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'ok' => false,
        'mensaje' => 'No fue posible cargar los tipos de ocurrencia.'
    ], JSON_UNESCAPED_UNICODE);
}