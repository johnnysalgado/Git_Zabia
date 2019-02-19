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
require('../inc/constante_enfermedad.php');
require('../inc/dao_insumo.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idUsuario = $request->user;
    $idFoodGroupSet = $request->foodGroup;
    $busqueda = $request->search;
    $lenguaje = $request->language;
    $page = $request->page;
    $records = $request->records;
    
    if (trim($lenguaje) != "") {
        $lenguaje = str_replace("'", "", $lenguaje);
    } else {
        $lenguaje = LENGUAJE_ESPANOL;
    }
    if (trim($busqueda) != "") {
        $busqueda = str_replace("'", "''", $busqueda);
    }
    if (trim($idFoodGroupSet) != "") {
        $idFoodGroupSet = str_replace("|", ",", str_replace("'", "''", $idFoodGroupSet));
    }

    $arregloData = array();

    $daoInsumo = new DaoInsumo();
    $listaInsumo = $daoInsumo->listarInsumoSemaforoPorUsuarioPorFoodGroup($idUsuario, $idFoodGroupSet, $busqueda, $lenguaje, $page, $records);

    foreach ($listaInsumo as $item) {
        $idInsumo = $item['id_insumo'];
        $nombre = $item['insumo'];
        $nombreIngles = $item['insumo_ing'];
        $foodGroup = $item['foodgroup'];
        $foodGroupIngles = $item['foodgroup_ing'];
        $imagen = $item['imagen'];
        $nombreImagen = $imagen;
        $setImagenURL = "";
        if ($imagen != "") {
            $imagen = BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imagen;
            $setImagenURL = BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_SMALL . "/" . PREFIX_IMAGE_SMALL . "_$nombreImagen " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_MEDIUM . "/" . PREFIX_IMAGE_MEDIUM . "_$nombreImagen " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_LARGE . "/" . PREFIX_IMAGE_LARGE . "_$nombreImagen " . WIDTH_IMAGE_LARGE;
        } else {
            $imagen = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg";
            $setImagenURL = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_LARGE;
        }
        $prioridad = $item['prioridad'];
        if ($prioridad == PRIORIDAD_ELIMINAR) {
            if ($lenguaje == LENGUAJE_INGLES) {
                $mensaje = TEXTO_SEMA_ROJO_ING;
            } else {
                $mensaje = TEXTO_SEMA_ROJO;
            }
            $accion = ACCION_ELIMINAR;
            $icono = BASE_REMOTE_IMAGE_LOGO_PATH . "icono_rojo.png";
        } else if ($prioridad == PRIORIDAD_RESTRINGIR) {
            if ($lenguaje == LENGUAJE_INGLES) {
                $mensaje = TEXTO_SEMA_AMARILLO_ING;
            } else {
                $mensaje = TEXTO_SEMA_AMARILLO;
            }
            $accion = ACCION_RESTRINGIR;
            $icono = BASE_REMOTE_IMAGE_LOGO_PATH . "icono_amarillo.png";
        } else if (prioridad == PRIORIDAD_AUMENTAR) {
            if ($lenguaje == LENGUAJE_INGLES) {
                $mensaje = TEXTO_SEMA_AZUL_ING;
            } else {
                $mensaje = TEXTO_SEMA_AZUL;
            }
            $accion = ACCION_AUMENTAR;
            $icono = BASE_REMOTE_IMAGE_LOGO_PATH . "icono_azul.png";
        } else {
            if ($lenguaje == LENGUAJE_INGLES) {
                $mensaje = TEXTO_SEMA_VERDE_ING;
            } else {
                $mensaje = TEXTO_SEMA_VERDE;
            }
            $accion = "beneficiar";
            $icono = BASE_REMOTE_IMAGE_LOGO_PATH . "icono_azul.png";
            $prioridad = PRIORIDAD_NORMAL;
        }
        $semaforoInsumoUsuarioArray = array();
        array_push($semaforoInsumoUsuarioArray, array('action' => $accion, 'icon' => $icono, 'description' => $mensaje));

        if ($lenguaje == LENGUAJE_INGLES) {
            $nombrePrincipal = $nombreIngles;
            $foodGroupPrincipal = $foodGroupIngles;
        } else {
            $nombrePrincipal =  $nombre;
            $foodGroupPrincipal = $foodGroup;
        }

        array_push($arregloData, array('ingredientID' => $idInsumo, 'name' => $nombrePrincipal, 'alternativeName' => '', 'foodGroup' => $foodGroupPrincipal, 'image' => $imagen, 'imageName' => $nombreImagen, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_IMAGE_SET, 'semaphore' => $semaforoInsumoUsuarioArray));
    }

    $daoInsumo = null;

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
