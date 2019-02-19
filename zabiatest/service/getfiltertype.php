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

require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/functions.php');
require('../inc/constante.php');

error_reporting(E_ERROR);

$cnx = new MySQL();

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $filter = $request->filter;
    $lenguaje = $request->language;

    $nombreCampo = ($lenguaje == LENGUAJE_INGLES) ? "nombre_ing" : "nombre";
    $consulta = "";
    $arregloData = array();
    
    if ($filter == "benefit") {
        $consulta = "SELECT id_beneficio AS id, $nombreCampo FROM beneficio WHERE estado = 1 ORDER BY $nombreCampo";
    } else if ($filter == "nutritional") {
        array_push($arregloData, array('code' => '00|199', 'label' => '0 - 199'));
        array_push($arregloData, array('code' => '200|399', 'label' => '200 - 299'));
        array_push($arregloData, array('code' => '400|599', 'label' => '400 - 599'));
        array_push($arregloData, array('code' => '500|999', 'label' => '500 - 999'));
        array_push($arregloData, array('code' => '1000|1999', 'label' => '1000 - 1999'));
        array_push($arregloData, array('code' => '2000|10000', 'label' => '> 2000'));
    } else if ($filter == "diet") {
        $consulta = "SELECT id_tipo_dieta AS id, $nombreCampo FROM tipo_dieta ORDER BY $nombreCampo";
        
    } else if ($filter == "cuisine") {
        $consulta = "SELECT id_tipo_cocina AS id, $nombreCampo FROM tipo_cocina ORDER BY $nombreCampo";
    } else {

        $cnx->close();
        $cnx = null;

        $mensaje =  ($lenguaje == LENGUAJE_INGLES) ? "Invalid filter." : "Filtro inválido";
        $output = array(
            'status' => '1'
            , 'message' => "$mensaje");
        $respuesta = json_encode($output);
        die ($respuesta);

    }

    if ($filter != 'nutritional') {
        $sql_query = $cnx->query($consulta);
        $sql_query->read();
        while ($sql_query->next())
        {
            $codigo = $sql_query->field('id');
            $nombre = $sql_query->field("$nombreCampo");
            array_push($arregloData, array('code' => $codigo, 'label' => $nombre));
        }
    }

    $cnx->close();
    $cnx = null;

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);

}
?>