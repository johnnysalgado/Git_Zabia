<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
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
    $idPreguntaTipoActividad = $request->healthActivityTypeID;
    $arregloData = array();

    $daoCuestionario = new DaoCuestionario();
    $arreglo = $daoCuestionario->obtenerPreguntaTipoActividad($idPreguntaTipoActividad);
    if (count($arreglo) > 0) {
        $descripcion = $arreglo[0]['nombre'];
        $descripcionIngles = $arreglo[0]['nombre_ing'];
        $orden = $arreglo[0]['orden'];
        $none = $arreglo[0]['none'];
        $estado = $arreglo[0]['estado'];
        $usuarioRegistro = $arreglo[0]['usuario_registro'];
        $fechaRegistro = $arreglo[0]['fecha_registro'];
        $usuarioModifica = $arreglo[0]['usuario_modifica'];
        $fechaModifica = $arreglo[0]['fecha_modifica'];
        array_push($arregloData, array('healthActivityTypeID' => $idPreguntaTipoActividad, 'description' => $descripcion, 'descriptionEnglish' => $descripcionIngles, 'order' => $orden, 'none' => $none, 'active' => $estado, 'registerUser' => $usuarioRegistro, 'registerDate' => $fechaRegistro, 'updateUser' => $usuarioModifica, 'updateDate' => $fechaModifica));
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
