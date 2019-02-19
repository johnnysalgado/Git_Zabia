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
    $email = $request->email;
    $password = $request->password;
    $source = $request->source;
    $consulta = "";
    $hash = "";
    $arregloData = array();
    
    $consulta = "SELECT id_usuariolabel, contrasena, nombre FROM usuariolabel WHERE correo = '" . $email . "' AND origen = '" . $source . "'";
    $sql_query = $cnx->query($consulta);
    $sql_query->read();
    if ($sql_query->next()) {
        $codigo = $sql_query->field('id_usuariolabel');
        $nombre = $sql_query->field('nombre');
        $hash = $sql_query->field('contrasena');
        array_push($arregloData, array('code' => $codigo , 'nombre' => $nombre));
    }

    if ($source == TIPO_LOGIN_PROPIO) {
        if (password_verify($password, $hash)) {
            $output = array(
                'status' => '1'
                , 'message' => ''
                , 'data' => $arregloData
            );
        } else {
            $output = array(
                'status' => '0'
                , 'message' => 'Credenciales inválidas'
            );
        }
    } else {
        $output = array(
            'status' => '0'
            , 'message' => 'No existe en el aplicativo'
        );
    }
    $cnx = null;
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>