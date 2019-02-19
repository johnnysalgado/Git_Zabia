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
require('../inc/constante_cuestionario.php');
require('../inc/constante.php');
require('../inc/dao_cuestionario.php');

set_time_limit(0);

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $response = $request->response;
    $lenguaje = $request->language;

    $arregloData = array();
    $user = str_replace("'", "", $user);

    $daoCuestionario = new DaoCuestionario();
    $responseArray = json_decode(json_encode($response), true);

    foreach ($responseArray as $item) {
        $questionID= $item['questionID'];
        $answerType = $item['answerType'];
        $userAnswerID= $item['userAnswerID'];
        $userAnswer= $item['userAnswer'];

        $daoCuestionario->eliminarUsuarioPreguntaRespuesta($user, $questionID, $user);

        if ($answerType == TIPO_RESPUESTA_UNICA || $answerType == TIPO_RESPUESTA_MULTIPLE) {
            if (trim($userAnswerID) != "") {
                $userAnswerIDs = explode("|", $userAnswerID);
                if ($subpregunta != "") {
                    $subpreguntaArray = json_decode(json_encode($subpregunta), true);
                }
                foreach($userAnswerIDs as $uaID) {
                    $idUsuarioPreguntaRespuesta = $daoCuestionario->grabarUsuarioPreguntaRespuesta($user, $questionID, $uaID, $user);
                }
            }
        } else {
            if ($userAnswer != "") {
                $userAnswer = str_replace("'", "''", $userAnswer);
            }
            $idUsuarioPreguntaRespuesta = $daoCuestionario->grabarUsuarioPreguntaRespuestaTextual($user, $questionID, $userAnswer, $user);
        }
    }

    $daoCuestionario = null;

    if ($lenguaje == LENGUAJE_INGLES) {
        $mensaje = "Update successfull";
    } else {
        $mensaje = "Actualización exitosa";
    }
    $output = array(
        'status' => '1'
        , 'message' => $mensaje);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
