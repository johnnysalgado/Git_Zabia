<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

/* =============================================================== */
/* CONEXIÓNES REMOTAS
================================================================ */
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
require('../inc/constante.php');
require('../inc/constante_usuario.php');
require('../inc/dao_usuario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $lenguaje = $request->language;

    $consulta = "";
    $mensaje = "";
    $arregloData = array();

    if ( $user == "" ) {
        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "user is blank";
        } else {
            $mensaje = "usuario en blanco";
        }
        $mensaje = 
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    }

    $user = str_replace("'", "", $user);

    $daoUsuario = new DaoUsuario();

    $arregloUsuario = $daoUsuario->obtenerUsuario($user);
    if ( count($arregloUsuario) > 0 ) {

        $item = $arregloUsuario[0];
        $idUsuario = $item['id_usuario'];
        $register = $item['primer_log'];
        $consent = $item['consentimiento'];
        $profile = $item['cuestionario'];
        $report = $item['reporte'];

        array_push($arregloData, array('register' => $register ? $register : '', 'consent' => $consent ? $consent : '', 'profile' => $profile ? $profile : '', 'report' => $report ? $report : ''));

        $output = array(
            'status' => '1'
            , 'message' => ''
            , 'data' => $arregloData
        );
        $respuesta = json_encode($output);
        die ($respuesta);

    } else {

        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "User error";
        } else {
            $mensaje = "Error en usuario";
        }
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
            , 'data' => null
        );
        $respuesta = json_encode($output);
        die ($respuesta);

    }

}
?>