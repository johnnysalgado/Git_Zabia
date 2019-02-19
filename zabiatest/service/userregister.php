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
require('../inc/dao_afiliado.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $email = $request->email;
    $nombre = $request->firstName;
    $apellido = $request->lastName;
    $password = $request->password;
    $codigoPais = $request->country;
    $idRegion = $request->region;
    $telefono = $request->phone;
    $origen = $request->source;
    $foto = $request->photo;
    $idTipoEmpleo = $request->employment;
    $idTipoIngresoEconomico = $request->incomeLevel;
    $idTipoEducacion = $request->educationLevel;
    $idTipoGenero = $request->gender;
    $codigoafiliado = $request->affiliateCode;
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

    $consulta = "";
    $mensaje = "";
    $arregloData = array();

    if ($origen == "") {
        $origen = TIPO_LOGIN_PROPIO;
    }

    if ($email == "" ) {
        $mensaje = MENSAJE_EMAIL_EN_BLANCO;
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    }

    if ($origen == TIPO_LOGIN_PROPIO) {
        if ($password == "") {
            $mensaje = MENSAJE_USUARIO_REGISTRO_INVALIDO;
            $output = array(
                'status' => '0'
                , 'message' => $mensaje
            );
            $respuesta = json_encode($output);
            die ($respuesta);
        }
    }

    $email = str_replace("'", "", $email);
    $nombre = str_replace("'", "''", $nombre);
    $telefono = str_replace("'", "", $telefono);
    $foto = str_replace("'", "''", $foto);
    $password = str_replace("'", "''", $password);

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
        if ($codigoAfiliado == $affiliateCode ) {
            $idUsuario = $item['id_usuario'];
            if ($origen == TIPO_LOGIN_PROPIO) {
                $mensaje = MENSAJE_USUARIO_EMAIL_YA_EXISTE;
                $output = array(
                    'status' => '0'
                    , 'message' => $mensaje
                );
                $respuesta = json_encode($output);
                die ($respuesta);
            } 
        } else {
            $output = array(
                'status' => '0'
                , 'message' => MENSAJE_USUARIO_AFILIADO_ERRADO
            );
            $respuesta = json_encode($output);
            die ($respuesta);
        }
    } else {
        if ($origen == TIPO_LOGIN_PROPIO) {
            $salt = guidv4();
            $hashPassword = securePassword($password, $salt, true);
        } else {
            $salt = "";
            $hashPassword = "";
        }
        if ($apellido == null) {
            $apellido = "";
        } else {
            $apellido = str_replace("'", "''", $apellido);
        }
        if ($codigoPais == null) {
            $codigoPais = "";
        } else {
            $codigoPais = str_replace("'", "''", $codigoPais);
        }
        if ($idRegion == null || $idRegion == "") {
            $idRegion = 0;
        }
        if ($idTipoEmpleo == null || $idTipoEmpleo == "") {
            $idTipoEmpleo = 0;
        }
        if ($idTipoIngresoEconomico == null || $idTipoIngresoEconomico == "") {
            $idTipoIngresoEconomico = 0;
        }
        if ($idTipoEducacion == null || $idTipoEducacion == "") {
            $idTipoEducacion = 0;
        }
        if ($idTipoGenero == null || $idTipoGenero == "") {
            $idTipoGenero = 0;
        }
        $idUsuario = $daoUsuario->crearUsuario($email, $hashPassword, $nombre, $apellido, $codigoPais, $idRegion, $telefono, $origen, $foto, $salt, $idTipoEmpleo, $idTipoIngresoEconomico, $idTipoEducacion, $idTipoGenero, $codigoafiliado, $email);
    }

    $arregloUsuario = $daoUsuario->obtenerUsuario($idUsuario);
    if ( count($arregloUsuario) > 0 ) {
        $item = $arregloUsuario[0];
        $nombre = $item['nombre'] . " " . $item['apellido'];
        $foto = $item['foto'];
        $idAfiliado = $item['id_affiliates'];
        if ($foto != "") {
            $urlFoto = BASE_REMOTE_IMAGE_PATH . "/" . FOLDER_USER_IMAGE . $foto;
        } else {
            $urlFoto = BASE_REMOTE_IMAGE_LOGO_PATH . "user_blank.png";
        }
        array_push($arregloData, array('code' => $idUsuario , 'name' => $nombre, 'nombre' => $nombre, 'foto' => $urlFoto, 'photo' => $urlFoto, 'affiliatesID' => $idAfiliado));
    }
    $daoUsuario = null;

    $mensaje = MENSAJE_GRABACION_EXITOSA;

    $output = array(
        'status' => '1'
        , 'message' => $mensaje
        , 'data' => $arregloData
    );
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>