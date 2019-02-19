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

require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/functions.php');
require('../inc/constante.php');

error_reporting(E_ERROR);

$cnx = new MySQL();

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $comercio = $request->place_id;
    $action = $request->action;
    $consulta = "";

    $consulta = "SELECT id_comercio FROM usuario_comercio WHERE id_usuario = " . $user . " AND id_comercio = " . $comercio;
    $existe = false;
    $sql_query = $cnx->query($consulta);
    $sql_query->read();
    if ($sql_query->count() > 0) {
        $existe = true;
    }
    if ($action == ACCION_INSERT) {
        if ($existe) {
            $insert = "UPDATE usuario_comercio SET estado = 1, fecha_modificacion = CURRENT_TIMESTAMP, usuario_modificacion = '" . $user . "' WHERE id_usuario = " . $user . " AND id_comercio = " . $comercio;
            $cnx->insert($insert);
        } else {
            $insert = "INSERT INTO usuario_comercio (id_usuario, id_comercio, estado, usuario_registro) VALUES (" . $user . ", " . $comercio . ", 1, '" . $user . "')";
            $cnx->insert($insert);
            $cnx->insert("UPDATE comercio SET megusta = megusta + 1 WHERE id_comercio = " . $comercio);
        }
    } else if ($action == ACCION_DELETE) {
        if ($existe) {
            $insert = "UPDATE usuario_comercio SET estado = 0, fecha_modificacion = CURRENT_TIMESTAMP, usuario_modificacion = '" . $user . "' WHERE id_usuario = " . $user . " AND id_comercio = " . $comercio;
            $cnx->insert($insert);
        }        
    }
    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => ''
    );
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>