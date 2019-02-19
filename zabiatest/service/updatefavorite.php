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
    $recipe = $request->recipe;
    $action = $request->action;
    $consulta = "";

    $consulta = "SELECT id_plato, estado FROM usuario_plato WHERE id_usuario = " . $user . " AND id_plato = " . $recipe;
    $sql_query = $cnx->query($consulta);
    $sql_query->read();
    $idPlato = 0;
    while ($sql_query->next())
    {
        $idPlato = $sql_query->field('id_plato');
        $estado = $sql_query->field('estado');
    }
    if ($action == ACCION_INSERT) {
        if ($idPlato > 0) {
            $insert = "UPDATE usuario_plato SET estado = 1, fecha_modificacion = CURRENT_TIMESTAMP, usuario_modificacion = '" . $user . "' WHERE id_usuario = " . $user . " AND id_plato = " . $recipe;
            $cnx->insert($insert);
        } else {
            $insert = "INSERT INTO usuario_plato (id_usuario, id_plato, estado, usuario_registro) VALUES (" . $user . ", " . $recipe . ", 1, '" . $user . "')";
            $cnx->insert($insert);
            $cnx->insert("UPDATE plato SET megusta = megusta + 1 WHERE id_plato = " . $recipe);
        }
    } else if ($action == ACCION_DELETE) {
        if ($idPlato > 0) {
            $insert = "UPDATE usuario_plato SET estado = 0, fecha_modificacion = CURRENT_TIMESTAMP, usuario_modificacion = '" . $user . "' WHERE id_usuario = " . $user . " AND id_plato = " . $recipe;
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