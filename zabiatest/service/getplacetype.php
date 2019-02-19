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
require('../inc/mysql.php');
require('../inc/functions.php');
require('../inc/constante_comercio.php');
require('../inc/dao_comercio.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);

    $arregloData = array();

    $daoComercio = new DaoComercio();
    $arregloTipoComercio = $daoComercio->listarTipoComercioExisteEnBD();
    foreach ($arregloTipoComercio as $item) {
        array_push($arregloData, array('placeType' => $item['tipo_comercio']));
    }

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>