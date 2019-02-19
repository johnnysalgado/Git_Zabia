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

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idRestaurante = $request->restaurantID;

    $arregloData = array();
    
    $query = "SELECT a.id_carta, a.nombre, a.descripcion, a.imagen, b.nombre AS nombre_restaurante FROM carta a INNER JOIN restaurante b ON a.id_restaurante = b.id_restaurante WHERE a.id_restaurante = " . $idRestaurante . " ORDER BY a.nombre ASC";
/*
    $output = array('consulta' => $query);
    $respuesta = json_encode($output);
    die ($respuesta);
  */  
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $idMenu = $sql->field('id_carta');
        $menu = $sql->field('nombre');
        $descripcion = $sql->field('descripcion');
        $imagenURL = $sql->field('imagen');
        $restaurante = $sql->field('nombre_restaurante');
        if ($imagenURL != "") {
            $imagenURL = $base_path . "imagen/restaurante/carta/" . $imagenURL;
        }

        array_push($arregloData, array('menuID' => $idMenu, 'title' => $menu, 'detail' => $descripcion, 'image' => $imagenURL, 'restaurant' => $restaurante));
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