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
    $name = $request->name;
    $password = $request->password;
    $source = $request->source;
    $consulta = "";
    $arregloData = array();

    if ($email == "" ) {
        $output = array(
            'status' => '0'
            , 'message' => 'El email no puede ser blanco'
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    }
    if ($source == TIPO_LOGIN_PROPIO) {
        if ($password == "") {
            $output = array(
                'status' => '0'
                , 'message' => 'La contraseña no puede ser blanco'
            );
            $respuesta = json_encode($output);
            die ($respuesta);
        }
    }

    $email = str_replace("'", "", $email);
    $name = str_replace("'", "", $name);
    $password = str_replace("'", "", $password);
    $consulta = "SELECT id_usuariolabel FROM usuariolabel WHERE correo = '" . $email . "'";
    $sql_query = $cnx->query($consulta);
    $sql_query->read();
    if ($sql_query->next()) {
        $codigo = $sql_query->field('id_usuariolabel');
    }
    if ($codigo != "") {
        if ($source == TIPO_LOGIN_PROPIO) {
            $output = array(
                'status' => '0'
                , 'message' => 'Correo ya existe en el sistema'
            );
            $respuesta = json_encode($output);
            die ($respuesta);
        } 
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $insert = "INSERT INTO usuariolabel (correo, contrasena, nombre, origen, usuario_registro) VALUES ('" . $email . "', '" . $hash . "', '" . $name . "', '" . $source . "', '" . $email . "')";
        $cnx->insert($insert);
    }
    $consulta = "SELECT id_usuariolabel, nombre FROM usuariolabel WHERE correo = '" . $email . "'";
    $sql_query = $cnx->query($consulta);
    $sql_query->read();
    if ($sql_query->next()) {
        $codigo = $sql_query->field('id_usuariolabel');
        $nombre = $sql_query->field('nombre');
        array_push($arregloData, array('code' => $codigo , 'nombre' => $nombre));
    }
    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData
    );
    $cnx = null;
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>