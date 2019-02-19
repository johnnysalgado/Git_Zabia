<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

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

error_reporting(E_ERROR);

$cnx = new MySQL();

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $numeroPagina = $request->numeroPagina;
    $palabraSearch = $request->palabraSearch;
    $longitudPaginacion = $request->longitudPaginacion;
    $tipoNutriente = $request->tipoNutriente;
    $tipoClase = $request->tipoClase;
    $tipoCategoria = $request->tipoCategoria;
    $tipoFamilia = $request->tipoFamilia;
    $tipoSubfamilia = $request->tipoSubfamilia;
    $flagEsencial = $request->flagEsencial;
    $estadoBuscar = $request->estadoBuscar;

    session_start();
    $_SESSION['NNP'] = $numeroPagina;
    $_SESSION['NPS'] = $palabraSearch;
    $_SESSION['NLP'] = $longitudPaginacion;
    $_SESSION['NTNUT'] = $tipoNutriente;
    $_SESSION['NTCLA'] = $tipoClase;
    $_SESSION['NTCAT'] = $tipoCategoria;
    $_SESSION['NTFAM'] = $tipoFamilia;
    $_SESSION['NTSFAM'] = $tipoSubfamilia;
    $_SESSION['NFE'] = $flagEsencial;
    $_SESSION['NEST'] = $estadoBuscar;

    $output = array(
        'status' => '1'
        , 'message' => 'Sesión creada correctamente');
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>