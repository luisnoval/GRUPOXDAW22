<?php

class Controller {

    public function inicio() {
        $params = array(
            'mensaje' => 'Bienvenido al curso de symfony 1.4',
            'fecha' => date('d-m-Y'),
        );
        require __DIR__ . '/templates/inicio.php';
    }

    public function listar() {

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $params = array(
            'alimentos' => $m->dameAlimentos(),
        );

        require __DIR__ . '/templates/mostrarAlimentos.php';
    }

    public function wiki() {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $params = array(
            'alimentos' => $m->dameAlimentos(),
        );

        require __DIR__ . '/templates/wikiAlimentos.php';
    }

    public function insertar() {

        $params = array(
            'nombre' => '',
            'energia' => '',
            'proteina' => '',
            'hc' => '',
            'fibra' => '',
            'grasa' => '',
            'url' => 'index.php?ctl=insertar'
        );

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // comprobar campos formulario
            if ($m->validarDatos($_POST['nombre'], $_POST['energia'], $_POST['proteina'], $_POST['hc'], $_POST['fibra'], $_POST['grasa'])) {
                $m->insertarAlimento($_POST['nombre'], $_POST['energia'], $_POST['proteina'], $_POST['hc'], $_POST['fibra'], $_POST['grasa']);
                header('Location: index.php?ctl=listar');
            } else {
                $params = array(
                    'nombre' => $_POST['nombre'],
                    'energia' => $_POST['energia'],
                    'proteina' => $_POST['proteina'],
                    'hc' => $_POST['hc'],
                    'fibra' => $_POST['fibra'],
                    'grasa' => $_POST['grasa'],
                );
                $params['mensaje'] = 'No se ha podido insertar el alimento. Revisa el formulario';
            }
        }

        require __DIR__ . '/templates/formInsertar.php';
    }

    public function buscarPorNombre() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $params = array(
            'nombre' => '',
            'resultado' => array(),
        );

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params['nombre'] = $_POST['nombre'];
            $params['resultado'] = $m->buscarAlimentosPorNombre($_POST['nombre']);
        }

        require __DIR__ . '/templates/buscarPorNombre.php';
    }

    public function buscarPorEnergia() {
        $params = array(
            'energia' => '',
            'resultado' => array(),
            'mensaje' => ''
        );

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params['energia'] = $_POST['energia'];
            $params['resultado'] = $m->buscarAlimentosPorEnergia($_POST['energia']);
            if (count($params['resultado']) == 0)
                $params['mensaje'] = 'No se han encontrado alimentos con la energía indicada';
        }

        require __DIR__ . '/templates/buscarPorEnergia.php';
    }

    public function buscarAlimentosCombinada() {
        $params = array(
            'energia' => '',
            'nombre' => '',
            'resultado' => array(),
            'mensaje' => ''
        );

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params['energia'] = $_POST['energia'];
            $params['nombre'] = $_POST['nombre'];
            $params['resultado'] = $m->buscarAlimentosCombinada($_POST['energia'], $_POST['nombre']);
            if (count($params['resultado']) == 0)
                $params['mensaje'] = 'No se han encontrado alimentos con la energía y nombre indicados';
        }

        require __DIR__ . '/templates/buscarCombinada.php';
    }

    public function ver() {
        if (!isset($_GET['id'])) {
            throw new Exception('Página no encontrada');
        }

        $id = $_GET['id'];

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $alimento = $m->dameAlimento($id);

        $params = $alimento;

        require __DIR__ . '/templates/verAlimento.php';
    }

    public function login() {
        // error_reporting(E_ALL);
        // ini_set('display_errors', '1');
        $params = array(
            'nombreUser' => '',
            'password' => '',
            'mensaje' => ''
        );

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombreUser = $_POST["nombreUser"];
            $password = $_POST["password"];

            $result = $m->validarUser($nombreUser);

            if ($result["nombreUser"] == $nombreUser && password_verify($password, $result["password"])) {
                $_SESSION["nombreUser"] = $nombreUser;
                header("Location: index.php");
            } else {
                $params["nombreUser"] = $nombreUser;
                $params["password"] = $password;
                $params["mensaje"] = "Error: nombre de usuario y/o contraseña incorrectos";
            }
        }

        require __DIR__ . '/templates/login.php';
    }

    public function registro() {
        $params = array(
            'nombreUser' => '',
            'password' => '',
            'mensaje' => ''
        );

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombreUser = $_POST["nombreUser"];
            $password = $_POST["password"];

            $result = $m->validarUser($nombreUser);

            if ($result["nombreUser"] == $nombreUser) {
                $params["nombreUser"] = $nombreUser;
                $params["password"] = $password;
                $params["mensaje"] = "Error: el nombre de usuario ya existe, elija otro";
            } else {
                $m->insertarUser($nombreUser, $password);
                header("Location: index.php?ctl=login");
            }
        }

        require __DIR__ . '/templates/registro.php';
    }

    public function editar() {
         error_reporting(E_ALL);
         ini_set('display_errors', '1');
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if (!isset($_POST['nombre'])) {
            $alimento = $m->dameAlimento($_GET['id']);
            
            $_SESSION["alimento"] = $alimento["id"];
            
            $params = array(
                'nombre' => $alimento["nombre"],
                'energia' => $alimento["energia"],
                'proteina' => $alimento["proteina"],
                'hc' => $alimento["hidratocarbono"],
                'fibra' => $alimento["fibra"],
                'grasa' => $alimento["grasatotal"],
                'url' => "index.php?ctl=editar"
            );
        }

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($m->validarDatos($_POST['nombre'], $_POST['energia'], $_POST['proteina'], $_POST['hc'], $_POST['fibra'], $_POST['grasa'])) {
                $m->modificarAlimento($_SESSION["alimento"], $_POST['nombre'], $_POST['energia'], $_POST['proteina'], $_POST['hc'], $_POST['fibra'], $_POST['grasa']);
                header('Location: index.php?ctl=listar');
            } else {
                $params = array(
                    'nombre' => $_POST['nombre'],
                    'energia' => $_POST['energia'],
                    'proteina' => $_POST['proteina'],
                    'hc' => $_POST['hc'],
                    'fibra' => $_POST['fibra'],
                    'grasa' => $_POST['grasa'],
                );
                $params['mensaje'] = 'No se ha podido modificar el alimento. Revisa el formulario';
            }
        }

        require __DIR__ . '/templates/formInsertar.php';
    }
    
    public function borrar() {
        if (isset($_GET['id'])) {
            $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

            $m->borrarAlimento($_GET['id']);
            
            header(("Location:index.php"));
        } else {
            $params['mensaje'] = 'Error: debes indicar un alimento que borrar. Puede indicarlo en listar alimentos';
        }
    }

    public function cerrar() {
        session_destroy();
        header("Location: index.php");
    }

}

?>
