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
    $user = $request->user;
    $tags = $request->tags;

    $arregloData = array();

    if ($tags != "") {
        $query = "SELECT a.id_nota, a.titulo, a.detalle, a.imagen, a.url_video FROM nota a WHERE ( a.estado = 1 ) ";
        $tagsArreglo = explode(",", $tags);
        $cantidadTags = count($tagsArreglo);
        if ($cantidadTags > 0) {
            $query .= " AND ( 1=1 ";
            foreach ($tagsArreglo as $tag) {
                $query .= " OR a.tag LIKE '%" . trim(str_replace("'", "", $tag)) . "%' "; 
            }
            $query .= ")";
        }
        $query .= " ORDER BY a.id_nota DESC";
    } else {
        $query = "SELECT DISTINCT d.id_nota, d.titulo, d.detalle, d.imagen, d.url_video FROM usuario_beneficio_sugerido a INNER JOIN nota_beneficio b ON a.id_beneficio = b.id_beneficio INNER JOIN beneficio c ON b.id_beneficio = c.id_beneficio INNER JOIN nota d ON b.id_nota = d.id_nota WHERE ( a.estado = '1') AND ( b.estado = '1' ) AND ( c.estado = '1' ) AND (d.estado = '1' ) AND ( a.id_usuario = " . $user . " ) UNION ALL SELECT DISTINCT a.id_nota, a.titulo, a.detalle, a.imagen, a.url_video FROM nota a INNER JOIN tag b ON a.tag LIKE concat('%', b.nombre, '%') WHERE ( a.estado = 1) AND ( b.estado = 1 ) AND ( b.tipo_tag = 'LIBRE' ) ORDER BY id_nota DESC";
    }
/*
    $output = array(
        'status' => '1'
        , 'message' => $query);
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