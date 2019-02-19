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

    $arregloData = array();
    
    $query = "SELECT a.id_insumo, a.nombre, a.nombre_ing, a.id_tipo_alimento, b.nombre AS tipo_alimento FROM insumo a LEFT OUTER JOIN tipo_alimento b ON a.id_tipo_alimento = b.id_tipo_alimento WHERE a.estado = 1 AND a.flag_superfood = 1 ORDER BY a.nombre ASC";
/*
    $output = array('consulta' => $query);
    $respuesta = json_encode($output);
    die ($respuesta);
*/  
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $idInsumo = $sql->field('id_insumo');
        $nombre = $sql->field('nombre');
        $nombreIngles = $sql->field('nombre_ing');
        $tipoAlimento = $sql->field('tipo_alimento');
        array_push($arregloData, array('ingredientID' => $idInsumo, 'name' => $nombre, 'alternativeName' => $nombreIngles, 'foodType' => $tipoAlimento));
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