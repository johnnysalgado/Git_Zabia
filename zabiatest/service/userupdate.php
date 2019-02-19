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
    $idUsuario = $request->user;
    $nombre = $request->firstName;
    $apellido = $request->lastName;
    $codigoPais = $request->country;
    $idRegion = $request->region;
    $telefono = $request->phone;
    $foto = $request->photo;
    $idTipoEmpleo = $request->employment;
    $idTipoIngresoEconomico = $request->incomeLevel;
    $idTipoEducacion = $request->educationLevel;
    $idTipoGenero = $request->gender;
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

    if ( trim($nombre) == "" 
        || trim($apellido) == "" 
        || trim($idTipoGenero) == ""
        || trim($telefono) == "") {
        $mensaje = MENSAJE_USUARIO_DATO_OBGLIGATORIO;
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    }

    $consulta = "";
    $mensaje = "";

    $nombre = str_replace("'", "''", $nombre);
    $apellido = str_replace("'", "''", $apellido);
    $codigoPais = str_replace("'", "", $codigoPais);
    $telefono = str_replace("'", "", $telefono);
    $foto = ""; //str_replace("'", "''", $foto);

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

    $daoUsuario = new DaoUsuario();

    $resultado = $daoUsuario->editarUsuario($idUsuario, $nombre, $apellido, $codigoPais, $idRegion, $telefono, $foto, $idTipoEmpleo, $idTipoIngresoEconomico, $idTipoEducacion, $idTipoGenero, $idUsuario);

    //matar el login primero.
    $arregloUsuario = $daoUsuario->obtenerUsuario($idUsuario);
    if (count($arregloUsuario) > 0) {
        $item = $arregloUsuario[0];
        if ($item['primer_log'] == null) {
            $email = $item['email'];
            $daoUsuario->grabarPrimerLogin($idUsuario, $email);
        }
        $mensaje = MENSAJE_GRABACION_EXITOSA;
        $status = 1;
    } else {
        $mensaje = MENSAJE_USUARIO_NO_EXISTE;
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