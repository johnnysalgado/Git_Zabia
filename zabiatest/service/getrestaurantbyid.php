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
require('../inc/constante_comercio.php');

error_reporting(E_ERROR);

$cnx = new MySQL();

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $idComercio = $request->restaurant;

    $arregloData = array();

    $caja = getBoundaries($latitud, $longitud, $distancia);
    
    $query = "SELECT a.id_comercio, a.nombre, a.imagen, a.imagen_marker, a.direccion, a.latitud, a.longitud, a.reserva, a.horario, a.descripcion, a.megusta FROM comercio a WHERE (a.tipo_comercio = 'restaurante') AND a.id_comercio = " . $idComercio;
/*
    $output = array('consulta' => $query);
    $respuesta = json_encode($output);
    die ($respuesta);
*/
    $sql = $cnx->query($query);
    $sql->read();
    if($sql->next()) {
        $idComercio = $sql->field('id_comercio');
        $restaurante = $sql->field('nombre');
        $logoURL = $sql->field('imagen');
        $markerURL = $sql->field('imagen_marker');
        $direccion = $sql->field('direccion');
        $localLatitud = $sql->field('latitud');
        $localLongitud = $sql->field('longitud');
        $distancia = $sql->field('distancia');
        $reserva = $sql->field('reserva');
        $horario = $sql->field('horario');
        $descripcion = $sql->field('descripcion');
        $megusta = $sql->field('megusta');
        if ($distancia > 0) {
            $distancia = round($distancia, 2);
        }
        if ($logoURL != "") {
            $logoURL = BASE_PATH . COMERCIO_IMAGE_SHORT_PATH . $logoURL;
        }
        if ($markerURL != "") {
            $markerURL = BASE_PATH . COMERCIO_IMAGE_SHORT_PATH . $markerURL;
        }

        $sql2 = $cnx->query("SELECT b.nombre FROM comercio_tipo_cocina a INNER JOIN tipo_cocina b ON a.id_tipo_cocina = b.id_tipo_cocina WHERE a.estado = 1 AND b.estado = 1 AND a.id_comercio = " . $idComercio . " ORDER BY nombre");
        $arregloTipoCocina = array();
        $sql2->read();
        while($sql2->next()) {
            $tipo = $sql2->field('nombre');
            array_push($arregloTipoCocina, array('nombre' => $tipo));
        }

        //favorito
        if ($user != "") {
            $consulta3 = "SELECT id_comercio FROM usuario_comercio WHERE estado = 1 AND id_usuario = " . $user . " AND id_comercio = " . $idComercio ;
            $sql3 = $cnx->query($consulta3);
            $sql3->read();
            if ($sql3->count() > 0) {
                $favorito = "1";
            } else {
                $favorito = "0";
            }
        }

        array_push($arregloData, array('restaurantID' => $idComercio, 'name' => $restaurante, 'address' => $direccion, 'latitude' => $localLatitud, 'longitude' => $localLongitud, 'url_logo' => $logoURL, 'url_marker' => $markerURL, 'distance' => $distancia, 'schedule' => $horario, 'reservation' => $reserva, 'description' => $descripcion, 'like' => $megusta, 'favorite'=> $favorito, 'cuisine_type' => $arregloTipoCocina));
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