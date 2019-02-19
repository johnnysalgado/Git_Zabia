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
require('../inc/constante.php');
require('../inc/constante_insumo.php');
require('../inc/dao_insumo.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $lenguaje = $request->language;

    if (trim($lenguaje) != "") {
        $lenguaje = str_replace("'", "", $lenguaje);
    } else {
        $lenguaje = LENGUAJE_ESPANOL;
    }

    $arregloData = array();

    $daoInsumo = new DaoInsumo();
    $arregloFoodgroup = $daoInsumo->listarFoodGroup(LISTA_ACTIVO, $lenguaje);
    foreach ($arregloFoodgroup as $item) {
        $idFoodgroup = $item['id_foodgroup'];
        $imagen = $item['image'];
        $nombreImagen = $imagen;
        $setImagenURL = "";
        if ($imagen != "") {
            $imagen = BASE_REMOTE_IMAGE_PATH . TIPO_ALIMENTO_IMAGE_REMOTE_PATH . $imagen;
            $setImagenURL = BASE_REMOTE_IMAGE_PATH . TIPO_ALIMENTO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_SMALL . "/" . PREFIX_IMAGE_SMALL . "_$nombreImagen " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_PATH . TIPO_ALIMENTO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_MEDIUM . "/" . PREFIX_IMAGE_MEDIUM . "_$nombreImagen " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_PATH . TIPO_ALIMENTO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_LARGE . "/" . PREFIX_IMAGE_LARGE . "_$nombreImagen " . WIDTH_IMAGE_LARGE;
        } else {
            $imagen = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg";
            $setImagenURL = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_LARGE;
        }

        $nombreIng = $item['name'];
        $nombre = $item['name_s'];
        $foodgroupPrincipal = $nombre;
        if ($lenguaje == LENGUAJE_INGLES) {
            $foodgroupPrincipal = $nombreIng;
        }

        array_push($arregloData, array('foodGroupID' => $idFoodgroup, 'name' => $foodgroupPrincipal, 'image' => $imagen, 'imageName' => $nombreImagen, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_IMAGE_SET));
    }

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>