<?php

ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
// web/index.php
// carga del modelo y los controladores
require_once __DIR__ . '/../../entrada/app/Config.php';
require_once __DIR__ . '/../../entrada/app/Model.php';
require_once __DIR__ . '/../../entrada/app/Controller.php';


session_start();

// enrutamiento
if (isset($_SESSION["nombreUser"])) {
    $map = array(
        'inicio' => array('controller' => 'Controller', 'action' => 'inicio'),
        'listar' => array('controller' => 'Controller', 'action' => 'listar'),
        'insertar' => array('controller' => 'Controller', 'action' => 'insertar'),
        'editar' => array('controller' => 'Controller', 'action' => 'editar'),
        'borrar' => array('controller' => 'Controller', 'action' => 'borrar'),
        'buscar' => array('controller' => 'Controller', 'action' => 'buscarPorNombre'),
        'buscarE' => array('controller' => 'Controller', 'action' => 'buscarPorEnergia'),
        'buscarAlimentosCombinada' => array('controller' => 'Controller', 'action' => 'buscarAlimentosCombinada'),
        'wiki' => array('controller' => 'Controller', 'action' => 'wiki'),
        'ver' => array('controller' => 'Controller', 'action' => 'ver'),
        'cerrar' => array('controller' => 'Controller', 'action' => 'cerrar')
    );
} else {
    $map = array(
        'inicio' => array('controller' => 'Controller', 'action' => 'inicio'),
        'listar' => array('controller' => 'Controller', 'action' => 'listar'),
        'buscar' => array('controller' => 'Controller', 'action' => 'buscarPorNombre'),
        'buscarE' => array('controller' => 'Controller', 'action' => 'buscarPorEnergia'),
        'buscarAlimentosCombinada' => array('controller' => 'Controller', 'action' => 'buscarAlimentosCombinada'),
        'wiki' => array('controller' => 'Controller', 'action' => 'wiki'),
        'ver' => array('controller' => 'Controller', 'action' => 'ver'),
        'login' => array('controller' => 'Controller', 'action' => 'login'),
        'registro' => array('controller' => 'Controller', 'action' => 'registro')
    );
}


// Parseo de la ruta
if (isset($_GET['ctl'])) {
    if (isset($map[$_GET['ctl']])) {
        $ruta = $_GET['ctl'];
    } else if ($_GET['ctl'] == 'insertar' ||
            $_GET['ctl'] == 'editar' ||
            $_GET['ctl'] == 'borrar' ||
            $_GET['ctl'] == 'cerrar' ||
            $_GET['ctl'] == 'login' || 
            $_GET['ctl'] == 'registro') {
        $ruta = 'inicio';
    } else {
        header('Status: 404 Not Found');
        echo '<html><body><h1>Error 404: No existe la ruta <i>' .
        $_GET['ctl'] .
        '</p></body></html>';
        exit;
    }
} else {
    $ruta = 'inicio';
}

$controlador = $map[$ruta];
// Ejecución del controlador asociado a la ruta

if (method_exists($controlador['controller'], $controlador['action'])) {
    call_user_func(array(new $controlador['controller'], $controlador['action']));
} else {

    header('Status: 404 Not Found');
    echo '<html><body><h1>Error 404: El controlador <i>' .
    $controlador['controller'] .
    '->' .
    $controlador['action'] .
    '</i> no existe</h1></body></html>';
}
?>
