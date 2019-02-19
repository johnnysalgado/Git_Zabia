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
    $questionID = $request->questionID;

    $arregloData = array();

    $query = "SELECT a.id_pregunta, a.descripcion, a.descripcion_ing, a.tipo_pregunta, a.tipo_respuesta, a.codigo FROM pregunta a WHERE a.id_pregunta = " . $questionID;
    $sql = $cnx->query($query);
    $sql->read();
    if ($sql->next()) {
        $idPregunta = $sql->field('id_pregunta');
        $descripcionPregunta = $sql->field('descripcion');
        $descripcionInglesPregunta = $sql->field('descripcion_ing');
        $tipoPregunta = $sql->field('tipo_pregunta');
        $tipoRespuesta = $sql->field('tipo_respuesta');
        $codigo = $sql->field('codigo');
        $respuestaArray = array();
        $respuestaUsuarioArray = array();
        $preguntaArray = array();
        if ($tipoRespuesta == TIPO_RESPUESTA_UNICA || $tipoRespuesta == TIPO_RESPUESTA_MULTIPLE) {
            $query2 = "SELECT a.id_respuesta, a.descripcion, a.descripcion_ing FROM respuesta a WHERE a.estado = 1 AND a.id_pregunta = " . $idPregunta . " ORDER BY orden";
/*
            $output = array('consulta' => $query2, 'postdata' => $postdata);
            $respuesta = json_encode($output);
            die ($respuesta);
*/
            $sql2 = $cnx->query($query2);
            $sql2->read();
            while($sql2->next()) {
                $idRespuesta = $sql2->field('id_respuesta');
                $descripcionRespuesta = $sql2->field('descripcion');
                $descripcionInglesRespuesta = $sql2->field('descripcion_ing');
                $respuestaUsuario = $sql2->field('respuesta_usuario');
                $idRespuestaUsuario = $sql2->field('id_respuesta_usuario');
                array_push($respuestaArray, array('id_answer' => $idRespuesta, 'description' => $descripcionRespuesta, 'description_english' => $descripcionInglesRespuesta));
            }
        }


        $preguntaArray = array('id_question' => $idPregunta, 'question_description' => $descripcionPregunta, 'question_description_english' => $descripcionInglesPregunta, 'question_type' => $tipoPregunta, 'answer_type' => $tipoRespuesta, 'question_code' => $codigo, 'answers' => $respuestaArray);

        array_push($arregloData, $preguntaArray);
    }

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
