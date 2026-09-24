<?php

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Session.php';
require_once __DIR__ . '/../app/core/Auth.php';

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


    $buscar = trim($_GET['buscar'] ?? '');


    if ($buscar === '') {

        echo json_encode([
            'ok' => false,
            'mensaje' => 'Debe ingresar un DNI o nombre.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    $database = new Database();
    $db = $database->connect();


    $sql = "SELECT
                IdAfiliados,
                NroFormato,
                NumeroDni,
                NombreAsegurado,
                Sexo,
                FechaNacimiento,
                Estado,
                EessAdscrito,
                Provincia,
                Distrito
            FROM maestro_afiliados
            WHERE NumeroDni LIKE ?
               OR NombreAsegurado LIKE ?
            ORDER BY NombreAsegurado ASC,
                     IdAfiliados ASC";


    $valorBuscar = '%' . $buscar . '%';


    $stmt = $db->prepare($sql);

    $stmt->execute([
        $valorBuscar,
        $valorBuscar
    ]);


    $afiliados = $stmt->fetchAll();


    echo json_encode([
        'ok' => true,
        'cantidad' => count($afiliados),
        'datos' => $afiliados
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'ok' => false,
        'mensaje' => 'No fue posible realizar la búsqueda.'
    ], JSON_UNESCAPED_UNICODE);
}