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

error_reporting(E_ERROR);

$cnx = new MySQL();

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    
    $arregloSugerencia = array();
    $idRespuestas = "";
    $peso = 0;
    $talla = 0;
    $imc = 0;
    $query = "SELECT a.respuesta, b.codigo FROM usuario_pregunta_respuesta a INNER JOIN pregunta b ON a.id_pregunta = b.id_pregunta WHERE (b.codigo = 'PESO' OR b.codigo = 'TALLA')  AND a.estado = 1 AND b.estado = 1 AND a.id_usuario = " . $user;
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $codigo = $sql->field('codigo');
        $valor = $sql->field('respuesta');
        if ($codigo == CODIGO_PREGUNTA_PESO) {
            $peso = $valor;
            if ($peso == '') {
                $peso = 0;
            }
        } else if ($codigo == CODIGO_PREGUNTA_TALLA) {
            $talla = $valor;
            if ($talla == '') {
                $talla = 0;
            }
        }
    }
    //calcular IMC
    if ($talla != 0) {
        $imc = $peso / ($talla * $talla);
        $imc = round($imc, 2);
    } else {
        $imc = 0;
    }

    $query = "SELECT DISTINCT descripcion, tipo_sugerencia FROM sugerencia WHERE estado = 1 AND ( id_respuesta IN ( SELECT DISTINCT a.id_respuesta FROM usuario_pregunta_respuesta a WHERE a.estado = 1 AND a.id_respuesta IS NOT NULL AND a.id_usuario = " . $user . ") )";
    if ($imc > 0) {
        $query .= " OR ( id_sugerencia IN ( SELECT id_sugerencia FROM sugerencia WHERE estado = 1 AND codigo_especial = '";
        if ($imc > 30) {
            $query .= IMC_MAY30;
        } else if ($imc > 25) {
            $query .= IMC_MAY25;
        } else {
            $query .= IMC_MEN25;
        }
        $query .= "' ) )";
    }
    $query .= " ORDER BY tipo_sugerencia";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $sugerencia = $sql->field('descripcion');
        $tipoSugerencia = $sql->field('tipo_sugerencia');
        array_push($arregloSugerencia, array('suggestion' => $sugerencia, 'suggestionType' => $tipoSugerencia));
    }

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloSugerencia);
    $respuesta = json_encode($output);
    die ($respuesta);

}
?>
