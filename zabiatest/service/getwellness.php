<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

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

require('../inc/configuracion.php');
require('../inc/constante.php');
require('../inc/mysql.php');
require('../inc/functions.php');
require('../inc/dao_bienestar.php');
require('../inc/dao_usuario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $lenguaje = $request->language;

    $arregloWell = array();
    $arregloPorcentaje = array();
    $arregloUsuario = array();
    $arregloData = array();

    $daoBienestar = new DaoBienestar();
    $arreglo = $daoBienestar->listarBienestar(LISTA_ACTIVO);
    foreach($arreglo as $item) {
        $idBienestar = $item['id_bienestar'];
        if ($lenguaje == LENGUAJE_INGLES) {
            $nombre = $item['nombre_ing'];
            $descripcion = $item['descripcion_ing'];
        } else {
            $nombre = $item['nombre'];
            $descripcion = $item['descripcion'];
        }
        $porcentaje = $daoBienestar->obtenerPorcentajeBienestarPorBienestar($user, $idBienestar);
        array_push($arregloWell, array('wellnessID' => $idBienestar, 'name' => $nombre, 'name_english' => '', 'description' => $descripcion, 'iconClass' => $item['icono_clase'], 'order' => $item['orden'], 'percentage' => $porcentaje));
    }

    //duro la ciudad -> fijar la ciudad.
    $porcentaje = $daoBienestar->obtenerPorcentajeBienestarTotal($user);
    array_push($arregloPorcentaje, array('percentage' => $porcentaje, 'city' => ''));
    $daoBienestar = null;

    $daoUsuario = new DaoUsuario();
    $arreglo = $daoUsuario->obtenerUsuario($user);
    foreach($arreglo as $item) {
        $foto = $item['foto'];
        if ($foto == "") {
            $foto = ICONO_IMAGE_PATH . "user_blank.jpg";
        }
        array_push($arregloUsuario, array('userID' => $item['id_usuario'], 'email' => $item['email'], 'name' => $item['nombre'], 'photo' => $foto));
    }
    $daoUsuario = null;

    array_push($arregloData, array('percentage' => $arregloPorcentaje, 'wellnessList' => $arregloWell, 'user' => $arregloUsuario));

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
