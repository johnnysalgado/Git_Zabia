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
$base_path = BASE_PATH;

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
//    $user = $request->user;
    $tags = $request->tags;
    $numeroPagina = $request->pageNumber;
    $cantidadRegistro = $request->recordNumber;

    $arregloData = array();
    $query = "SELECT a.id_nota, a.titulo, a.detalle, a.imagen, a.url_video FROM nota a WHERE ( a.estado = 1 ) ";
    if ($tags != "") {
        $tagsArreglo = explode(",", $tags);
        $cantidadTags = count($tagsArreglo);
        if ($cantidadTags > 0) {
            $query .= " AND ( 1= 1 ";
            foreach ($tagsArreglo as $tag) {
                $query .= " OR ( a.tag LIKE '%" . trim(str_replace("'", "", $tag)) . "%' ) "; 
            }
            $query .= ")";
        }
    }
    $query .= " ORDER BY a.id_nota DESC";
    if ($cantidadRegistro > 0) {
        if ($numeroPagina == "") $numeroPagina = 0;
        $numeroPagina = ($cantidadRegistro * $numeroPagina);
        $query .= " LIMIT " . $numeroPagina . "," . $cantidadRegistro;
    }
/*
    $output = array(
        'consulta' => $query
        , 'request' => $request);
    $respuesta = json_encode($output);
    die ($respuesta);
*/
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $idNota = $sql->field('id_nota');
        $titulo = $sql->field('titulo');
        $detalle = $sql->field('detalle');
        $imagen = $sql->field('imagen');
        $urlVideo = $sql->field('url_video');
        if ($imagen != "") {
            $imagen = $base_path . "imagen/tip/" . $imagen;
        }

        array_push($arregloData, array('tipID' => $idNota, 'title' => $titulo, 'detail' => $detalle, 'image_url' => $imagen, 'video_url' => $urlVideo));
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