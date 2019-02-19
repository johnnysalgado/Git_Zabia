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
require('../inc/constante_cuestionario.php');
require('../inc/dao_cuestionario.php');

error_reporting(E_ERROR);

/* =============================================================== */
/* REQUEST + SQL QUERYS
================================================================ */

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idUsuario = $request->user;
    $idPregunta = $request->questionID;
    $codigoPregunta = $request->questionCode;
    $lenguaje = $request->language;

    $user = str_replace("'", "", $user);
    if ($idPregunta == "") {
        $idPregunta = 0;
    } else {
        $idPregunta = str_replace("'", "", $idPregunta);
    }
    if ($codigoPregunta != "") {
        $codigoPregunta = str_replace("'", "", $codigoPregunta);
    }

    $arregloData = array();
    $respuestaArray = array();

    $daoCuestionario = new DaoCuestionario();
    $arreglo = $daoCuestionario->obtenUsuarioPreguntaRespuesta($idUsuario, $idPregunta, $codigoPregunta, DATOESPECIAL_PRECONDICION, DATOESPECIAL_INTOLERANCIA_ALERGIA, DATOESPECIAL_TIPO_DIETA);
    $daoCuestionario = null;
    $idPregunta = 0;
    $idPreguntax = 0;
    $cantidadArreglo = count($arreglo);
    $contadorArreglo = 0;
    while ($contadorArreglo < $cantidadArreglo) {
        $item = $arreglo[$contadorArreglo];
        $idSeccion = $item['id_seccion_cuestionario'];
        $idPregunta = $item['id_pregunta'];
        if ($lenguaje == LENGUAJE_INGLES) {
            $descripcion = $item['pregunta_ing'];
            $seccion = $item['seccion_ing'];
        } else {
            $descripcion = $item['pregunta'];
            $seccion = $item['seccion'];
        }
        $tipoRespuesta = $item['tipo_respuesta'];
        $codigo = $item['codigo'];
        $idPreguntax = $idPregunta;
        while ($contadorArreglo < $cantidadArreglo && $idPregunta == $idPreguntax) {
            if ($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE || $tipoRespuesta == TIPO_RESPUESTA_UNICA) {
                $idRespuesta = $item['id_respuesta'];
                $idRespuestaUsuario = $item['id_respuesta_usuario'];
                if ($idRespuesta == $idRespuestaUsuario) {
                    $userSelected = "1";
                } else {
                    $userSelected = "0";
                }
                if ($lenguaje == LENGUAJE_INGLES) {
                    $descripcionRespuesta = $item['respuesta_ing'];
                } else {
                    $descripcionRespuesta = $item['respuesta'];
                }
                array_push($respuestaArray, array('id_answer' => $idRespuesta, 'description' => $descripcionRespuesta, 'userSelected' => $userSelected, 'userAnswer' => ''));
            } else {
                $respuestaUsuario = $item['respuesta_usuario'];
                array_push($respuestaArray, array('id_answer' => 0, 'description' => '', 'userSelected' => '0', 'userAnswer' => $respuestaUsuario));
            }
            $contadorArreglo++;
            $item = $arreglo[$contadorArreglo];
            $idPregunta = $item['id_pregunta'];
        }

        array_push($arregloData, array('questionID' => $idPreguntax, 'description' => $descripcion, 'sectionID' => $idSeccion, 'section' => $seccion, 'answerType' => $tipoRespuesta, 'questionCode' => $codigo, 'answer' => $respuestaArray));
    }

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}



?>
