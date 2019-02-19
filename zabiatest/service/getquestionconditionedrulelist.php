<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

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
require('../inc/dao_cuestionario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $lenguaje = $request->language;

    $arregloData = array();
    $daoCuestionario = new DaoCuestionario();
    $arreglo = $daoCuestionario->listarPreguntaEnlazada();
    foreach($arreglo as $item) {
        $idPreguntaAfectada = $item['id_pregunta_afectada'];
        $idPreguntaAfecta = $item['id_pregunta_afecta'];
        $idRespuestaAfecta = $item['id_respuesta_afecta'];
        $omitir = $item['omitir'];
        $mostrar = $item['mostrar'];
        $condicion = "(\$('#" . PREFIJO_PREGUNTA_CONTROL . "_" . $idPreguntaAfecta . "').val()=='" . $idRespuestaAfecta . "');";
        if ($omitir == 1) {
            $condicion = "!$condicion";
        }
        array_push($arregloData, array('id' => PREFIJO_PREGUNTA_CONTROL . "_" . $idPreguntaAfectada, 'condition' => $condicion));
    }
    $daoCuestionario = null;

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
