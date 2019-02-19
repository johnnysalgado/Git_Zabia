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
require('../inc/constante_usuario.php');

error_reporting(E_ERROR);

$cnx = new MySQL();

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $password = $request->password;

    if ($password == "") {
        $output = array(
            'status' => '0'
            , 'message' => 'La contraseña no puede ser blanco'
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    }

    $email = str_replace("'", "", $email);
    $password = str_replace("'", "", $password);
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $update = "UPDATE usuariolabel SET contrasena = '" . $hash . "' WHERE id_usuariolabel = " . $user;
    $cnx->execute($update);
    $output = array(
        'status' => '1'
        , 'message' => 'Cambiado correctamente'
    );
    $cnx = null;
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>