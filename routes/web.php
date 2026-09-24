<?php

return [

    // Login
    'login' => [
        'controller' => 'AuthController',
        'method'     => 'login'
    ],

    // Procesar login
    'login/autenticar' => [
        'controller' => 'AuthController',
        'method'     => 'autenticar'
    ],

    // Cerrar sesión
    'logout' => [
        'controller' => 'AuthController',
        'method'     => 'logout'
    ],

    // Dashboard
    'dashboard' => [
        'controller' => 'DashboardController',
        'method'     => 'index'
    ],
    // usuarios
    'usuarios' => [
    'controller' => 'UsuariosController',
    'method'     => 'index'
    ],
    // crear usuario
    'usuarios/crear' => [
    'controller' => 'UsuariosController',
    'method'     => 'crear'
],
// guardar usuario
'usuarios/guardar' => [
    'controller' => 'UsuariosController',
    'method'     => 'guardar'
],
'ipress' => [
    'controller' => 'IpressController',
    'method'     => 'index'
],
'ipress/exportar' => [
    'controller' => 'IpressController',
    'method' => 'exportar'
],
'ipress/crear' => [
    'controller' => 'IpressController',
    'method' => 'crear'
],
'ipress/guardar' => [
    'controller' => 'IpressController',
    'method' => 'guardar'
],
'ipress/detalle' => [
    'controller' => 'IpressController',
    'method' => 'detalle'
],
'ipress/editar' => [
    'controller' => 'IpressController',
    'method' => 'editar'
],
'ipress/actualizar' => [
    'controller' => 'IpressController',
    'method' => 'actualizar'
],
'ipress/cambiar-estado' => [
    'controller' => 'IpressController',
    'method' => 'cambiarEstado'
],
'usuarios/editar' => [
    'controller' => 'UsuariosController',
    'method' => 'editar'
],
'usuarios/actualizar' => [
    'controller' => 'UsuariosController',
    'method' => 'actualizar'
],
'usuarios/cambiar-estado' => [
    'controller' => 'UsuariosController',
    'method' => 'cambiarEstado'
],
// Afiliados
'afiliados' => [
    'controller' => 'AfiliadosController',
    'method' => 'index'
],

'afiliados/detalle' => [
    'controller' => 'AfiliadosController',
    'method' => 'detalle'
],
'tiposocurrencia' => [
    'controller' => 'TiposOcurrenciaController',
    'method' => 'index'
],
'tiposocurrencia/crear' => [
    'controller' => 'TiposOcurrenciaController',
    'method' => 'crear'
],

'tiposocurrencia/guardar' => [
    'controller' => 'TiposOcurrenciaController',
    'method' => 'guardar'
],

'tiposocurrencia/editar' => [
    'controller' => 'TiposOcurrenciaController',
    'method' => 'editar'
],

'tiposocurrencia/actualizar' => [
    'controller' => 'TiposOcurrenciaController',
    'method' => 'actualizar'
],

'tiposocurrencia/cambiar-estado' => [
    'controller' => 'TiposOcurrenciaController',
    'method' => 'cambiarEstado'
],
'ocurrenciasca' => [
    'controller' => 'OcurrenciasCAController',
    'method' => 'index'
],
'ocurrenciasca/crear' => [
    'controller' => 'OcurrenciasCAController',
    'method' => 'crear'
],
'ocurrenciasca/guardar' => [
    'controller' => 'OcurrenciasCAController',
    'method' => 'guardar'
],
'ocurrenciasca/editar' => [
    'controller' => 'OcurrenciasCAController',
    'method' => 'editar'
],
'ocurrenciasca/actualizar' => [
    'controller' => 'OcurrenciasCAController',
    'method' => 'actualizar'
],
'ocurrenciasca/exportar-excel' => [

    'controller' => 'OcurrenciasCAController',

    'method' => 'exportarExcel'
],
'afiliados/exportar' => [
    'controller' => 'AfiliadosController',
    'method' => 'exportar'
],

// Digitadores

'digitadores' => [
    'controller' => 'DigitadoresController',
    'method' => 'index'
],

'digitadores/crear' => [
    'controller' => 'DigitadoresController',
    'method' => 'crear'
],

'digitadores/guardar' => [
    'controller' => 'DigitadoresController',
    'method' => 'guardar'
],

'digitadores/editar' => [
    'controller' => 'DigitadoresController',
    'method' => 'editar'
],

'digitadores/actualizar' => [
    'controller' => 'DigitadoresController',
    'method' => 'actualizar'
],

'digitadores/detalle' => [
    'controller' => 'DigitadoresController',
    'method' => 'detalle'
],

'digitadores/eliminar' => [
    'controller' => 'DigitadoresController',
    'method' => 'eliminar'
],
'digitadores/exportar' => [
    'controller' => 'DigitadoresController',
    'method' => 'exportar'
],
];