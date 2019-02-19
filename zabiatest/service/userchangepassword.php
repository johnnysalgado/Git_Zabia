<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
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
require('../inc/dao_usuario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idUsuario = $request->user;
    $contrasenaAnterior = $request->oldPassword;
    $contrasenaNueva = $request->newPassword;
    $lenguaje = $request->language;
    $arregloData = array();

    $mensaje = "";
    $status = 1;
    $contrasenaBD = "";
    $daoUsuario = new DaoUsuario();
    $arreglo = $daoUsuario->obtenerUsuario($idUsuario);
    if (count($arreglo) > 0) {
        $contrasenaBD = $arreglo[0]['password'];
    }

    if ($contrasenaBD == $contrasenaAnterior) {
        //if es la misma contraseña se va a grabar
        $salt = guidv4();
        $hashPassword = securePassword($contrasenaNueva, $salt, true);
        $resultado = $daoUsuario->cambiarContrasena($idUsuario, $hashPassword, $salt, $email);
        if ($resultado) {
            if ($lenguaje == LENGUAJE_INGLES) {
                $mensaje = "Password change successfully";
            } else {
                $mensaje = "Cambio de clave satisfactorio";
            };
            $status = 1;
        } else {
            if ($lenguaje == LENGUAJE_INGLES) {
                $mensaje = "Unknown error.";
            } else {
                $mensaje = "Error no conocido.";
            }
            $status = 0;
        }
    } else {
        $status = 0;
        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "Credentials error";
        } else {
            $mensaje = "Errores en las credenciales";
        };
    }
    $daoCuestionario = null;

    $output = array(
        'status' => "$status"
        , 'message' => $mensaje
        , 'data' => '');
    $respuesta = json_encode($output);
    die ($respuesta);
}

?>
