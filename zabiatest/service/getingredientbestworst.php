<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

/* =============================================================== */
/* CONEXIÓNES REMOTAS
================================================================ */
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

/* =============================================================== */
/* CONEXIÓN A LA BASE DE DATOS
================================================================ */
require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/functions.php');
require('../inc/constante.php');
require('../inc/constante_insumo.php');
require('../inc/constante_enfermedad.php');
require('../inc/dao_insumo.php');

error_reporting(E_ERROR);

/* =============================================================== */
/* REQUEST + SQL QUERYS
================================================================ */

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idUsuario = $request->user;
    $lenguaje = $request->language;
    $pagina = $request->page;
    $registros = $request->records;

    $arregloData = array();
    $insumoMejorArray = array();
    $insumoPeorArray = array();
    
    if (trim($lenguaje) != "") {
        $lenguaje = str_replace("'", "", $lenguaje);
    }
    if (trim($pagina) == "") {
        $pagina = 0;
    }
    if (trim($registros) == "") {
        $registros = 0;
    }

    if ($lenguaje == LENGUAJE_INGLES) {
        $mensajeRojo = TEXTO_SEMA_ROJO_ING;
        $mensajeAmarillo = TEXTO_SEMA_AMARILLO_ING;
        $mensajeAzul = TEXTO_SEMA_AZUL_ING;
        $mensajeVerde = TEXTO_SEMA_VERDE_ING;
    } else {
        $mensajeRojo = TEXTO_SEMA_ROJO;
        $mensajeAmarillo = TEXTO_SEMA_AMARILLO;
        $mensajeAzul = TEXTO_SEMA_AZUL;
        $mensajeVerde = TEXTO_SEMA_VERDE;
    }

    $daoInsumo = new DaoInsumo();
    $arregloMejor = $daoInsumo->listarMejoresInsumo($idUsuario, $lenguaje, $pagina, $registros);
    foreach ($arregloMejor as $item) {
        $idInsumo = $item['id_insumo'];
        if ($lenguaje == LENGUAJE_INGLES) {
            $nombre = $item['insumo_ing'];
            $tipoAlimento = $item['tipo_alimento_ing'];
        } else {
            $nombre = $item['insumo'];
            $tipoAlimento = $item['tipo_alimento'];
        }
        $idTipoAlimento = $item['id_tipo_alimento'];
        $imagen = $item['imagen'];
        $nombreImagen = $imagen;
        $prioridad = $item['prioridad'];
        if ($imagen != "") {
            $setImagenURL = BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_SMALL . "/" . PREFIX_IMAGE_SMALL . "_$imagen " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_MEDIUM . "/" . PREFIX_IMAGE_MEDIUM . "_$imagen " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_LARGE . "/" . PREFIX_IMAGE_LARGE . "_$imagen " . WIDTH_IMAGE_LARGE;
            $imagen = BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imagen;
        } else {
            $setImagenURL = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_LARGE;
            $imagen = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg";
        }
        $accion = "";
        $icono = "";
        $mensaje = "";
        if ($prioridad == -2) {
            $accion = ACCION_ELIMINAR;
            $icono = ICONO_INSUMO_ROJO;
            $mensaje = $mensajeRojo;
        } else if ($prioridad == -1) {
            $accion = ACCION_RESTRINGIR;
            $icono = ICONO_INSUMO_AMARILLO;
            $mensaje = $mensajeAmarillo;
        } else if ($prioridad == 2) {
            $accion = ACCION_AUMENTAR;
            $icono = ICONO_INSUMO_AZUL;
            $mensaje = $mensajeAzul;
        } else {
            $accion = 'Beneficiar';
            $icono = ICONO_INSUMO_VERDE;
            $mensaje = $mensajeVerde;
        }
        array_push($arregloData, array('ingredientID' => $idInsumo, 'name' => $nombre, 'foodTypeID' => $idTipoAlimento, 'foodType' => $tipoAlimento, 'image' => $imagen, 'imageName' => $nombreImagen, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_IMAGE_SET, 'flagbw' => 'best', 'semaphore' => array('action' => $accion, 'icon' => BASE_REMOTE_IMAGE_LOGO_PATH . $icono, 'description' => $mensaje)));
    }

    $arregloPeor = $daoInsumo->listarPeoresInsumo($idUsuario, $pagina, $registros);
    foreach ($arregloPeor as $item) {
        $idInsumo = $item['id_insumo'];
        if ($lenguaje == LENGUAJE_INGLES) {
            $nombre = $item['insumo_ing'];
            $tipoAlimento = $item['tipo_alimento_ing'];
        } else {
            $nombre = $item['insumo'];
            $tipoAlimento = $item['tipo_alimento'];
        }
        $idTipoAlimento = $item['id_tipo_alimento'];
        $imagen = $item['imagen'];
        $nombreImagen = $imagen;
        $prioridad = $item['prioridad'];
        if ($imagen != "") {
            $setImagenURL = BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_SMALL . "/" . PREFIX_IMAGE_SMALL . "_$imagen " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_MEDIUM . "/" . PREFIX_IMAGE_MEDIUM . "_$imagen " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_LARGE . "/" . PREFIX_IMAGE_LARGE . "_$imagen " . WIDTH_IMAGE_LARGE;
            $imagen = BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imagen;
        } else {
            $setImagenURL = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_LARGE;
            $imagen = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg";
        }
        $accion = "";
        $icono = "";
        $mensaje = "";
        if ($prioridad == -2) {
            $accion = ACCION_ELIMINAR;
            $icono = ICONO_INSUMO_ROJO;
            $mensaje = $mensajeRojo;
        } else if ($prioridad == -1) {
            $accion = ACCION_RESTRINGIR;
            $icono = ICONO_INSUMO_AMARILLO;
            $mensaje = $mensajeAmarillo;
        } else if ($prioridad == 2) {
            $accion = ACCION_AUMENTAR;
            $icono = ICONO_INSUMO_AZUL;
            $mensaje = $mensajeAzul;
        } else {
            $accion = 'beneficiar';
            $icono = ICONO_INSUMO_VERDE;
            $mensaje = $mensajeVerde;
        }
        array_push($arregloData, array('ingredientID' => $idInsumo, 'name' => $nombre, 'foodTypeID' => $idTipoAlimento, 'foodType' => $tipoAlimento, 'image' => $imagen, 'imageName' => $nombreImagen, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_IMAGE_SET, 'flagbw' => 'worst', 'semaphore' => array('action' => $accion, 'icon' => BASE_REMOTE_IMAGE_LOGO_PATH . $icono, 'description' => $mensaje)));
    }
    $daoInsumo = null;

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
