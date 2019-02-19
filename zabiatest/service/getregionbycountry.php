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

/* =============================================================== */
/* CONEXIÓN A LA BASE DE DATOS
================================================================ */
require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/functions.php');

error_reporting(E_ERROR);

$cnx = new MySQL();
$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $codigoPais = $request->country;

    $arregloData = array();
    
    if ($codigoPais != "") {
        $codigoPais = str_replace("'", "''", $codigoPais);
    }
    $query = "SELECT id_region, nombre FROM region WHERE estado = 1 AND cod_pais = '" . $codigoPais . "' ORDER BY nombre ASC";
/*
    $output = array('consulta' => $query);
    $respuesta = json_encode($output);
    die ($respuesta);
*/  
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $idRegion = $sql->field('id_region');
        $nombre = $sql->field('nombre');
        array_push($arregloData, array('regionID' => $idRegion, 'name' => $nombre));
    }

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>