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
require('../inc/dao_pais.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $codigoPais = $request->country;
    $lenguaje = $request->language;

    $mensaje = "";
    $status = "0";
    $arregloData = array();

    if ($codigoPais == "") {
        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "Country error";
        } else {
            $mensaje = "País errado";
        }
        $status = "0";
    } else {
        $codigoPais = str_replace("'", "", $codigoPais);
        $daoPais = new DaoPais();
        $arregloRegion = $daoPais->listarRegion($codigoPais, 1);
        if ( count($arregloRegion) > 0 ) {
            foreach($arregloRegion as $item) {
                $idRegion = $item['id_region'];
                $nombre = $item['nombre'];
                array_push($arregloData, array('countryCode' => $codigoPais, 'regionID' => $idRegion, 'regionName' => $nombre));
            }
            $status = "1";
        } else {
            if ($lenguaje == LENGUAJE_INGLES) {
                $mensaje = "Country error";
            } else {
                $mensaje = "País errado";
            }
            $status = "0";
        }
        $daoPais = null;
    }

    $output = array(
        'status' => $status
        , 'message' => $mensaje
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
