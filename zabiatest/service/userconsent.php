<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/functions.php');
require('../inc/constante.php');
require('../inc/constante_usuario.php');
require('../inc/dao_usuario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $str = $postdata;

    if ($lenguaje == "") {
        $lenguaje = LENGUAJE_INGLES; //ver cómo enviar lenguaje.
    }
    $constanteMensajeUsuario = "../inc/lang/mensaje_usuario_$lenguaje.php";
    if (file_exists($constanteMensajeUsuario)) {
        require($constanteMensajeUsuario);
    } else {
        require("../inc/lang/mensaje_usuario.php");
    }

    $m = substr($str, strpos($str, '<Email>')+7);
    $email = str_replace("'", "", substr($m, 0, strpos($m, '</Email>')));

    $m = substr($str, strpos($str, '<Signed>')+8);
    $fecha = str_replace("'", "", substr($m, 0, strpos($m, '</Signed>')));

    $daoUsuario = new DaoUsuario();
    $arreglo = $daoUsuario->obtenerUsuarioPorCorreo($email);

    if (count($arreglo) > 0) {
        $item = $arreglo[0];
        $idUsuario = $item['id_usuario'];
        $daoUsuario->grabarConsentimiento($idUsuario, $fecha);
        $mensaje = MENSAJE_GRABACION_EXITOSA;
        $status = 1;
    } else {
        $mensaje = MENSAJE_EMAIL_NO_EXISTE;
        $status = 0;
    }
    
    $daoUsuario = null;
    
    $output = array(
        'status' => "$status"
        , 'message' => $mensaje
    );
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>