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
require('../inc/constante_cuestionario.php');

error_reporting(E_ERROR);

$cnx = new MySQL();

/* =============================================================== */
/* REQUEST + SQL QUERYS
================================================================ */

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $questionCode= $request->questionCode;
    $questionID= $request->questionID;
    $answerType = $request->answerType;
    $userAnswerID= $request->userAnswerID;
    $userAnswer= $request->userAnswer;
    
    $arregloData = array();
    $user = str_replace("'", "", $user);

    $query = "UPDATE usuario_pregunta_respuesta SET estado = 0, usuario_modifica = '$user', fecha_modifica = CURRENT_TIMESTAMP WHERE id_usuario = $user AND id_pregunta = $questionID";

    $cnx->execute($query);

    if ($answerType == TIPO_RESPUESTA_UNICA || $answerType == TIPO_RESPUESTA_MULTIPLE) {
        if (trim($userAnswerID) != "") {
            $userAnswerIDs = explode("|", $userAnswerID);
            foreach($userAnswerIDs as $uaID) {
                $query = "SELECT id_usuario_pregunta_respuesta FROM usuario_pregunta_respuesta WHERE id_usuario = " . $user . " AND id_pregunta = " . $questionID . " AND id_respuesta = " . $uaID;
                $sql = $cnx->query($query);
                $sql->read();
                if ($sql->count() > 0) {
                    $query = "UPDATE usuario_pregunta_respuesta SET estado = 1, usuario_modifica = '" . $user . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_usuario = " . $user . " AND id_pregunta = " . $questionID . " AND id_respuesta = " . $uaID;
                } else {
                    $query = "INSERT usuario_pregunta_respuesta (id_usuario, id_pregunta, id_respuesta, usuario_registro) values (" . $user . ", " . $questionID . ", " . $uaID . ", '" . $user ."')";
                }
                $cnx->execute($query);
            }
        }
    } else {
        if ($userAnswer != "") {
            $userAnswer = str_replace("'", "''", $userAnswer);
        }
        $query = "SELECT id_usuario_pregunta_respuesta FROM usuario_pregunta_respuesta WHERE id_usuario = $user AND id_pregunta = $questionID";
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->count() > 0) {
            $query = "UPDATE usuario_pregunta_respuesta SET respuesta = '$userAnswer', estado = 1, usuario_modifica = '$user', fecha_modifica = CURRENT_TIMESTAMP WHERE id_usuario = $user AND id_pregunta = $questionID";
        } else {
            $query = "INSERT usuario_pregunta_respuesta (id_usuario, id_pregunta, respuesta, usuario_registro) values ($user, $questionID, '$userAnswer', '$user')";
        }
        $cnx->execute($query);
    }

/*
        $output = array('consulta' => $query, 'postdata' => $postdata);
        $respuesta = json_encode($output);
        die ($respuesta);
*/

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => '');
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
