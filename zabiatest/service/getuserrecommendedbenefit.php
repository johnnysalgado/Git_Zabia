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

error_reporting(E_ERROR);

$cnx = new MySQL();

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    
    $arregloData = array();
    $arregloBenefit = array();

    $query = "SELECT DISTINCT beneficio FROM ( SELECT d.nombre AS beneficio FROM pregunta a INNER JOIN usuario_pregunta_respuesta b ON a.id_pregunta = b.id_pregunta INNER JOIN respuesta_beneficio c ON b.id_respuesta = c.id_respuesta INNER JOIN beneficio d ON c.id_beneficio = d.id_beneficio WHERE b.estado = 1 AND b.id_usuario = " . $user . " UNION ALL SELECT c.nombre AS beneficio FROM ( SELECT 30 AS imc_cota_inf, 0 AS imc_cota_sup, 103 AS id_beneficio UNION SELECT 30 AS imc_cota_inf, 0 AS imc_cota_sup, 108 AS id_beneficio UNION SELECT 25 AS imc_cota_inf, 35 AS imc_cota_sup, 108 AS id_beneficio ) a INNER JOIN ( SELECT a.peso / (a.talla * a.talla) AS imc FROM ( SELECT SUM(q.peso) AS peso, SUM(q.talla) AS talla FROM ( SELECT CASE WHEN a.codigo = 'PESO' THEN CAST(b.respuesta AS DECIMAL(10, 2)) ELSE 0 END AS peso, CASE WHEN a.codigo = 'TALLA' THEN CAST(b.respuesta AS DECIMAL(10, 2)) ELSE 0 END AS talla FROM pregunta a INNER JOIN usuario_pregunta_respuesta b ON a.id_pregunta = b.id_pregunta WHERE ( b.id_usuario = " . $user . " ) AND ( a.codigo = 'PESO' OR a.codigo = 'TALLA' ) AND ( a.estado = 1 ) AND ( b.estado = 1 ) ) q ) a ) b ON ( b.imc >= a.imc_cota_inf ) AND ( b.imc < a.imc_cota_sup OR a.imc_cota_sup = 0 ) INNER JOIN beneficio c ON a.id_beneficio = c.id_beneficio ) f";

    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $beneficio = $sql->field('beneficio');
        array_push($arregloBenefit, array('name' => $beneficio));
    }

    $query = "SELECT a.peso / (a.talla * a.talla) AS imc FROM ( SELECT SUM(q.peso) AS peso, SUM(q.talla) AS talla FROM ( SELECT CASE WHEN a.codigo = 'PESO' THEN CAST(b.respuesta AS DECIMAL(10, 2)) ELSE 0 END AS peso, CASE WHEN a.codigo = 'TALLA' THEN CAST(b.respuesta AS DECIMAL(10, 2)) ELSE 0 END AS talla FROM pregunta a INNER JOIN usuario_pregunta_respuesta b ON a.id_pregunta = b.id_pregunta WHERE ( b.id_usuario = " . $user . " ) AND ( a.codigo = 'PESO' OR a.codigo = 'TALLA' ) AND ( a.estado = 1 ) AND ( b.estado = 1 ) ) q ) a";

    $imc = "";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $imc = $sql->field('imc');
    }
    if ($imc != "") {
        $imc = round($imc, 0);
    }

    array_push($arregloData, array('benefit' => $arregloBenefit, 'imc' => $imc));

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);

}
?>
