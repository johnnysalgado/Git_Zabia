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
require('../inc/dao_afiliado.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $email = $request->email;
    $password = $request->password;
    $source = $request->source;
    $affiliateCode = $request->affiliateCode;
    $lenguaje = $request->language;

    $consulta = "";
    $mensaje = "";
    $status = "0";
    $arregloData = array();
    
    if ($lenguaje == "") {
        $lenguaje = LENGUAJE_ESPANOL;
    }
    $constanteMensajeUsuario = "../inc/lang/mensaje_usuario_$lenguaje.php";
    if (file_exists($constanteMensajeUsuario)) {
        require($constanteMensajeUsuario);
    } else {
        require("../inc/lang/mensaje_usuario.php");
    }

    $daoUsuario = new DaoUsuario();
    
    $arregloUsuario = $daoUsuario->obtenerUsuarioPorCorreo($email);
    if ( count($arregloUsuario) > 0 ) {
        $item = $arregloUsuario[0];
        $idAfiliado = $item['id_affiliates'];

        $codigoAfiliado = "";
        $daoAfiliado = new DaoAfiliado();
        $arregloAfiliado = $daoAfiliado->obtenerAfiliado($idAfiliado);
        if (count($arregloAfiliado) > 0) {
            $codigoAfiliado = $arregloAfiliado[0]["cod_affiliates"];
        }
        $daoAfiliado = null;
        if ($codigoAfiliado == $affiliateCode) {
            $salt = "";
            $hashPassword = $item['password'];
            if(securePassword($password, $salt, $hashPassword)) {
                $idUsuario = $item['id_usuario'];
                $nombre = $item['nombre'];
                $foto = $item['foto'];
                //fechas
                $registro = $item['primer_log'];
                $consentimiento = $item['consentimiento'];
                $cuestionario = $item['cuestionario'];
                $reporte = $item['reporte'];
                $esPrimerLogin = 0;
                if ($registro == null) {
                    $esPrimerLogin = 1;
                }
                if ($foto != "") {
                    $urlFoto = BASE_REMOTE_IMAGE_PATH . "/" . FOLDER_USER_IMAGE . $foto;
                } else {
                    $urlFoto = BASE_REMOTE_IMAGE_LOGO_PATH . "user_blank.png";
                }
                array_push($arregloData, array('code' => $idUsuario , 'name' => $nombre, 'nombre' => $nombre, 'photo' => $urlFoto, 'affiliatesID' => $idAfiliado, "isFirstLogin" => $esPrimerLogin, 'register' => $registro ? $registro : '', 'consent' => $consentimiento ? $consentimiento : '', 'profile' => $cuestionario ? $cuestionario : '', 'report' => $reporte ? $reporte : ''));
                $status = "1";
            } else {
                $status = "0";
                $mensaje = MENSAJE_USUARIO_CREDENCIAL_ERRADA;
            }
        } else {
            $status = "0";
            $mensaje = MENSAJE_USUARIO_AFILIADO_ERRADO;
        }
    } else {
        $status = "0";
        $mensaje = MENSAJE_USUARIO_CREDENCIAL_ERRADA;
    }

    $daoUsuario = null;

    $output = array(
        'status' => "$status"
        , 'message' => $mensaje
        , 'data' => $arregloData
    );

    $respuesta = json_encode($output);
    die ($respuesta);
}
?>