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
require('../inc/dao_usuario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $lenguaje = $request->language;

    $arregloData = array();
    $daoUsuario = new DaoUsuario();
    $arregloEmpleo = $daoUsuario->listarTipoEmpleo(LISTA_ACTIVO, $lenguaje);
    foreach($arregloEmpleo as $item) {
        $idTipoEmpleo = $item['id_tipo_empleo'];
        if ($lenguaje == LENGUAJE_INGLES) {
            $nombre = $item['nombre_ing'];
        } else {
            $nombre = $item['nombre'];
        }
        array_push($arregloData, array('employeeID' => $idTipoEmpleo, 'description' => $nombre));
    }
    $daoUsuario = null;

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
