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
    $email = $request->email;
    $codigoVerificacion = $request->code;
    $contrasenaNueva = $request->newPassword;
    $lenguaje = $request->language;

    if ($lenguaje == "") {
        $lenguaje = LENGUAJE_ESPANOL;
    }
    $constanteMensajeUsuario = "../inc/lang/mensaje_usuario_$lenguaje.php";
    if (file_exists($constanteMensajeUsuario)) {
        require($constanteMensajeUsuario);
    } else {
        require("../inc/lang/mensaje_usuario.php");
    }

    $result = "";
    $status = 1;
    $codigoVerificacion = trim($codigoVerificacion);

    $daoUsuario = new DaoUsuario();
    $arregloUsuario = $daoUsuario->obtenerUsuarioPorCorreo($email);
    if (count($arregloUsuario) > 0) {
        $idUsuario = $arregloUsuario[0]['id_usuario'];
        $arregloToken = $daoUsuario->obtenerVerificacionReseteoClave($idUsuario);
        if (count($arregloToken) > 0) {
            $itemToken = $arregloToken[0];
            $token = $itemToken['token'];
            $duracion = $itemToken['duracion'];
            $tiempoTranscurrido = $itemToken['tiempo_transcurrido'];
            if ($token == $codigoVerificacion) {
                if ($duracion >= $tiempoTranscurrido) {
                    $salt = guidv4();
                    $hashPassword = securePassword($contrasenaNueva, $salt, true);
                    $resultado = $daoUsuario->cambiarContrasena($idUsuario, $hashPassword, $salt, $email);
                    if ($resultado) {
                        $mensaje = MENSAJE_USUARIO_RESETEO_CLAVE_EXITOSO;
                        $status = 1;
                        $estado = 0;
                        $eliminar = $daoUsuario->eliminarReseteoClave($idUsuario, $token, $estado, $email);
                    } else {
                        $mensaje = MENSAJE_ERROR_DESCONOCIDO;
                        $status = 0;
                    }
                } else {
                    $mensaje = MENSAJE_USUARIO_RESETEO_CLAVE_VENCIO_TIEMPO_CODIGO;
                    $status = 0;
                    $estado = -1;
                    $eliminar = $daoUsuario->eliminarReseteoClave($idUsuario, $token, $estado, $email);
                }
            } else {
                $mensaje = MENSAJE_USUARIO_RESETEO_CLAVE_CODIGO_ERRADO;
                $status = 0;
            }
        } else {
            $mensaje = MENSAJE_USUARIO_RESETEO_CLAVE_NO_SOLICITO_CODIGO;
            $status = 0;
        }
    } else {
        $mensaje = MENSAJE_EMAIL_NO_EXISTE;
        $status = -1;
    }
    $daoUsuario = null;
    $output = array(
        'status' => "$status"
        , 'message' => $mensaje);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
