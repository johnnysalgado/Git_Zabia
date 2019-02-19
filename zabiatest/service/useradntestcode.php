<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

/* =============================================================== */
/* CONEXIÓNES REMOTAS
================================================================ */
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
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
    $user = $request->user;
    $consulta = "";
    $codigo = "";
    $arregloData = array();
    
    $consulta = "SELECT codigo_examen_adn FROM usuario WHERE id_usuario = " . $user;
    $sql_query = $cnx->query($consulta);
    $sql_query->read();
    if ($sql_query->next())
    {
        $codigo = $sql_query->field('codigo_examen_adn');
        array_push($arregloData, array('code' => $codigo));
    }
    if ($codigo != "") {
        $output = array(
            'status' => '1'
            , 'message' => ''
            , 'data' => $arregloData
        );
    } else {
        $output = array(
            'status' => '0'
            , 'message' => 'Error al consultar'
        );
    }
    $cnx = null;
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>