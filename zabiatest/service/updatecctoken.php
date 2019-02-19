<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

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
require('../inc/dao_cc.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $token= $request->token;
    $esDefault= $request->isDefault;
    $lenguaje= $request->language;
    
    $arregloData = array();
    $user = str_replace("'", "", $user);
    $token = str_replace("'", "''", $token);
    $esDefault = str_replace("'", "", $esDefault);

    $daoCC = new DaoCC();
    $resultado = $daoCC->editarCCToken($user, $token, $esDefault, $user);

    if ($lenguaje == LENGUAJE_INGLES) {
        $output = array(
            'status' => '0'
            , 'message' => 'Update succesfully');
    } else {
        $output = array(
            'status' => '1'
            , 'message' => 'Grabación satisfactoria');
    }
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
