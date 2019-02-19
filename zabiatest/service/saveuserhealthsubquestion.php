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

error_reporting(E_ERROR);

$cnx = new MySQL();

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $response = $request->response;
    $lenguaje = $request->language;

    $arregloData = array();
    $user = str_replace("'", "", $user);

    $questionIDx = 0;
    $daoCuestionario = new DaoCuestionario();
    $responseArray = json_decode(json_encode($response), true);
    foreach ($responseArray as $item) {
        $idUsuarioPreguntaSubpregunta= $item['id'];
        $questionID= $item['questionID'];
        $userAnswerID= $item['userAnswerID'];
        $userAnswer= $item['userAnswer'];
        $subquestionID1= $item['subQuestionID1'];
        $subquestionUserAnswerID1= $item['subQuestionUserAnswerID1'];
        $subquestionUserAnswer1= $item['subQuestionUserAnswer1'];
        $subquestionID2= $item['subQuestionID2'];
        $subquestionUserAnswerID2= $item['subQuestionUserAnswerID2'];
        $subquestionUserAnswer2= $item['subQuestionUserAnswer2'];
        if ($userAnswerID == "") {
            $userAnswerID = 0;
        }
        if ($subquestionID2 == "") {
            $subquestionID2 = 0;
        }
        if ($subquestionUserAnswerID1 == "") {
            $subquestionUserAnswerID1 = 0;
        }
        if ($subquestionUserAnswerID2 == "") {
            $subquestionUserAnswerID2 = 0;
        }
        if ($questionIDx != $questionID) {
            $daoCuestionario->eliminarUsuarioPreguntaSubpregunta($user, $questionID, $user);
        }
        $userAnswer = str_replace("'", "''", $userAnswer);
        $subquestionUserAnswer2 = str_replace("'", "''", $subquestionUserAnswer2);
        $subquestionUserAnswer1 = str_replace("'", "''", $subquestionUserAnswer1);
        $questionIDx = $questionID;
        $daoCuestionario->grabarUsuarioPreguntaSubpregunta($user, $idUsuarioPreguntaSubpregunta, $questionID, $userAnswerID, $userAnswer, $subquestionID1, $subquestionUserAnswerID1, $subquestionUserAnswer1, $subquestionID2, $subquestionUserAnswerID2, $subquestionUserAnswer2, $user);
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
