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
    $menu = $request->menu;

    $arregloData = array();
    
    $query = "SELECT a.id_comercio, a.nombre, a.imagen AS imagen_restaurante, a.direccion, a.latitud, a.longitud, b.id_carta, b.nombre AS menu, b.descripcion, b.imagen AS imagen_menu FROM comercio a INNER JOIN carta b ON a.id_comercio = c.id_comercio WHERE ( a.estado = 1 AND b.estado = 1 ) AND ( b.nombre LIKE '%" . $menu . "%' OR b.descripcion LIKE '%" . $menu . "%' ) ORDER BY nombre, menu";
/*
    $output = array('consulta' => $query);
    $respuesta = json_encode($output);
    die ($respuesta);
  */  
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $idComercio = $sql->field('id_comercio');
        $restaurante = $sql->field('nombre');
        $logoURL = $sql->field('imagen_restaurante');
        $direccion = $sql->field('direccion');
        $localLatitud = $sql->field('latitud');
        $localLongitud = $sql->field('longitud');
        if ($logoURL != "") {
            $logoURL = $base_path . RESTAURANT_IMAGE_SHORT_PATH . $logoURL;
        }
        $idMenu = $sql->field('id_carta');
        $menu = $sql->field('menu');
        $descripcion = $sql->field('descripcion');
        $imagenMenuURL = $sql->field('imagen_menu');
        if ($imagenMenuURL != "") {
            $imagenMenuURL = BASE_PATH . MENU_IMAGE_SHORT_PATH . $imagenMenuURL;
        }

        array_push($arregloData, array('restaurantID' => $idComercio, 'name' => $restaurante, 'address' => $direccion, 'latitude' => $localLatitud, 'longitude' => $localLongitud, 'logoURL' => $logoURL, 'menuID' => $idMenu, 'title' => $menu, 'detail' => $descripcion, 'image' => $imagenMenuURL));
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