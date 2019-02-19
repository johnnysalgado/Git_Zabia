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

/* =============================================================== */
/* REQUEST + SQL QUERYS
================================================================ */
//$base_path = 'http://www.southtech.pe/zabiatest/';
$base_path = 'http://54.152.38.95/zabiabo/';

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $type = $request->type;

    //tipos: "symptom" : "nutrient" : "ingredient"
    $arregloData = array();
    $query = "";

    if ($type == "symptom") {
        $query = "SELECT nombre FROM tag WHERE ( estado = 1 ) AND ( tipo_tag = 'Síntoma' ) ORDER BY nombre ";
    } else if ($type == "nutrient") {
        $query = "SELECT nombre FROM nutriente WHERE ( estado = 1 ) ORDER BY nombre ";
    } else if ($type == "ingredient") {
        $query = "";
    }

    if ($query != "") {
        $sql = $cnx->query($query);
        $sql->read();
        while($sql->next()) {
            $nombre = $sql->field('nombre');
            array_push($arregloData, array('tag' => $nombre));
        }

        $cnx = null;
        $output = array(
            'status' => '1'
            , 'message' => ''
            , 'data' => $arregloData);
        $respuesta = json_encode($output);
        die ($respuesta);
    } else {
        $output = array(
            'status' => '0'
            , 'message' => 'No hay registros para esta lista');
        $respuesta = json_encode($output);
        die ($respuesta);
    }
}
?>