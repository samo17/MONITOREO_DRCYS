<?php

require_once __DIR__ . '/../app/core/Database.php';

header('Content-Type: application/json; charset=utf-8');

$accion = $_GET['accion'] ?? '';

/* =========================================================
   REDES
   ========================================================= */

if ($accion === 'redes') {

    $codUE = trim($_GET['codUE'] ?? '');

    if ($codUE === '') {
        echo json_encode([]);
        exit;
    }

    try {

        $database = new Database();
        $db = $database->connect();

        $sql = "SELECT DISTINCT RED
                FROM ipress
                WHERE ESTADO = 1
                AND COD_UE = ?
                AND RED IS NOT NULL
                AND TRIM(RED) <> ''
                ORDER BY RED";

        $stmt = $db->prepare($sql);
        $stmt->execute([$codUE]);

        echo json_encode(
            $stmt->fetchAll(),
            JSON_UNESCAPED_UNICODE
        );

    } catch (Throwable $e) {

        http_response_code(500);

        echo json_encode([
            'error' => 'No fue posible obtener las redes.'
        ]);

    }

    exit;
}


/* =========================================================
   MICROREDES
   ========================================================= */

if ($accion === 'microredes') {

    $codUE = trim($_GET['codUE'] ?? '');
    $red = trim($_GET['red'] ?? '');

    if (
        $codUE === '' ||
        $red === ''
    ) {
        echo json_encode([]);
        exit;
    }

    try {

        $database = new Database();
        $db = $database->connect();

        $sql = "SELECT DISTINCT MICRORED
                FROM ipress
                WHERE ESTADO = 1
                AND COD_UE = ?
                AND RED = ?
                AND MICRORED IS NOT NULL
                AND TRIM(MICRORED) <> ''
                ORDER BY MICRORED";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            $codUE,
            $red
        ]);

        echo json_encode(
            $stmt->fetchAll(),
            JSON_UNESCAPED_UNICODE
        );

    } catch (Throwable $e) {

        http_response_code(500);

        echo json_encode([
            'error' => 'No fue posible obtener las microredes.'
        ]);

    }

    exit;
}


/* =========================================================
   DISTRITOS
   ========================================================= */

if ($accion === 'distritos') {

    $provincia = trim(
        $_GET['provincia'] ?? ''
    );

    if ($provincia === '') {

        echo json_encode([]);

        exit;
    }

    try {

        $database = new Database();
        $db = $database->connect();

        $sql = "SELECT DISTINCT DISTRITO
                FROM ipress
                WHERE ESTADO = 1
                AND PROVINCIA = ?
                AND DISTRITO IS NOT NULL
                AND TRIM(DISTRITO) <> ''
                ORDER BY DISTRITO";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            $provincia
        ]);

        echo json_encode(
            $stmt->fetchAll(),
            JSON_UNESCAPED_UNICODE
        );

    } catch (Throwable $e) {

        http_response_code(500);

        echo json_encode([
            'error' => 'No fue posible obtener los distritos.'
        ]);

    }

    exit;
}


/* =========================================================
   LISTAR IPRESS
   ========================================================= */

if ($accion === 'listar') {

    try {

        $database = new Database();
        $db = $database->connect();

        $sql = "SELECT
                    IdEESS,
                    RENAES,
                    IPRESS_ATENC
                FROM ipress
                WHERE ESTADO = 1
                ORDER BY CAST(RENAES AS UNSIGNED) ASC";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        echo json_encode(
            $stmt->fetchAll(),
            JSON_UNESCAPED_UNICODE
        );

    } catch (Throwable $e) {

        http_response_code(500);

        echo json_encode([
            'error' => 'No fue posible obtener las IPRESS.'
        ], JSON_UNESCAPED_UNICODE);

    }

    exit;
}


/* =========================================================
   BUSCAR IPRESS
   ========================================================= */

if ($accion === 'buscar') {

    $buscar = trim(
        $_GET['buscar'] ?? ''
    );

    $codUE = trim(
        $_GET['codUE'] ?? ''
    );

    $red = trim(
        $_GET['red'] ?? ''
    );

    $microred = trim(
        $_GET['microred'] ?? ''
    );


    if ($buscar === '') {

        echo json_encode([]);

        exit;
    }


    try {

        $database = new Database();
        $db = $database->connect();


        $sql = "SELECT
                    IdEESS,
                    RENAES,
                    IPRESS_ATENC,
                    COD_UE,
                    UE,
                    RED,
                    MICRORED,
                    PROVINCIA,
                    DISTRITO
                FROM ipress
                WHERE ESTADO = 1
                AND (
                    IPRESS_ATENC LIKE ?
                    OR RENAES LIKE ?
                )";


        $parametros = [];

        $valorBuscar =
            '%' . $buscar . '%';


        $parametros[] =
            $valorBuscar;

        $parametros[] =
            $valorBuscar;


        if ($codUE !== '') {

            $sql .= " AND COD_UE = ?";

            $parametros[] =
                $codUE;

        }


        if ($red !== '') {

            $sql .= " AND RED = ?";

            $parametros[] =
                $red;

        }


        if ($microred !== '') {

            $sql .= " AND MICRORED = ?";

            $parametros[] =
                $microred;

        }


        $sql .= " AND IPRESS_ATENC IS NOT NULL
                  AND TRIM(IPRESS_ATENC) <> ''
                  ORDER BY IPRESS_ATENC ASC
                  LIMIT 30";


        $stmt =
            $db->prepare($sql);


        $stmt->execute(
            $parametros
        );


        echo json_encode(
            $stmt->fetchAll(),
            JSON_UNESCAPED_UNICODE
        );

    } catch (Throwable $e) {

        http_response_code(500);

        echo json_encode([
            'error' => 'No fue posible buscar las IPRESS.'
        ], JSON_UNESCAPED_UNICODE);

    }

    exit;
}


/* =========================================================
   OBTENER IPRESS POR ID
   ========================================================= */

if ($accion === 'obtener') {

    $idEess = trim(
        $_GET['idEESS'] ?? ''
    );


    if (
        $idEess === '' ||
        !ctype_digit($idEess)
    ) {

        echo json_encode([]);

        exit;
    }


    try {

        $database = new Database();
        $db = $database->connect();


        $sql = "SELECT
                    IdEESS,
                    RENAES,
                    IPRESS_ATENC,
                    COD_UE,
                    UE,
                    RED,
                    MICRORED,
                    PROVINCIA
                FROM ipress
                WHERE ESTADO = 1
                AND IdEESS = ?
                LIMIT 1";


        $stmt =
            $db->prepare($sql);


        $stmt->execute([
            (int) $idEess
        ]);


        $ipress =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        echo json_encode(
            $ipress ?: [],
            JSON_UNESCAPED_UNICODE
        );

    } catch (Throwable $e) {

        http_response_code(500);

        echo json_encode([
            'error' => 'No fue posible obtener la IPRESS.'
        ]);

    }

    exit;
}


/* =========================================================
   ACCIÓN NO RECONOCIDA
   ========================================================= */

echo json_encode([]);