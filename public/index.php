<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/core/Database.php';
require_once BASE_PATH . '/app/core/Model.php';
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/core/Session.php';
require_once BASE_PATH . '/app/core/Auth.php';
require_once BASE_PATH . '/app/core/Helpers.php';
require_once BASE_PATH . '/app/models/Digitador.php';

Session::start();

$routes = require BASE_PATH . '/routes/web.php';

$route = $_GET['route'] ?? 'login';
$route = trim($route, '/');

if (!isset($routes[$route])) {
    http_response_code(404);

    echo '<h1>404</h1>';
    echo '<p>Página no encontrada.</p>';

    exit;
}

$routeConfig = $routes[$route];

$controllerName = $routeConfig['controller'];
$method = $routeConfig['method'];

$controllerFile =
    BASE_PATH . '/app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(500);
    die('Controlador no encontrado.');
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    http_response_code(500);
    die('Clase del controlador no encontrada.');
}

$controller = new $controllerName();

if (!method_exists($controller, $method)) {
    http_response_code(500);
    die('Método del controlador no encontrado.');
}

$controller->$method();