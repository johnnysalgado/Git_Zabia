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
require('../inc/constante_receta.php');
require('../inc/constante_cuestionario.php');

error_reporting(E_ERROR);


$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $lenguaje = $request->language;

    $arregloTop = array();
    $arregloTipoPlato = array();
    $arregloTipoDieta = array();
    $arregloData = array();

    function existeImagenTP($arregloTP, $imagen) {
        $existe = false;
        foreach ($arregloTP as $item) {
            if ($item['imagen'] == $imagen) {
                $existe = true;
                break;
            }
        }
        return $existe;
    }

    $cnx = new MySQL();

    $queryTop = "SELECT imagen FROM plato WHERE estado = 1 AND top = 1 AND imagen <> '' ORDER BY id_plato LIMIT 0, 1";
    $sql = $cnx->query($queryTop);
    $sql->read();
    while ($sql->next()) {
        $imagen = BASE_REMOTE_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . $sql->field('imagen');
        array_push($arregloTop, array('imagen' => $imagen));
    }

    $querytd = "SELECT DISTINCT b.id_tipo_dieta FROM usuario_pregunta_respuesta a INNER JOIN tipo_dieta b ON a.id_respuesta = b.id_tipo_dieta INNER JOIN pregunta p ON a.id_pregunta = p.id_pregunta WHERE a.estado = 1 AND b.estado = 1 AND p.dato_especial = '" . DATOESPECIAL_TIPO_DIETA . "' AND a.id_usuario = $user";
    $sql_td = $cnx->query($querytd);
    $sql_td->read();
    $td = "";
    while ($sql_td->next()) {
        $idTipoDieta = $sql_td->field('id_tipo_dieta');
        $td .= "$idTipoDieta,";
    }
    if ($td != "") {
        $td = substr($td, 0, strlen($td) - 1);
    }
    $sql_td = null;

    if ($lenguaje == LENGUAJE_INGLES) {
        $campoNombre = "nombre_ing";
    } else {
        $campoNombre = "nombre";
    }

    $queryTipoPlato = "SELECT a.$campoNombre, a.id_tipo_plato, b.imagen FROM ( SELECT a.id_tipo_plato, b.$campoNombre, MAX(c.id_plato) AS id_plato FROM plato_tipo_plato a INNER JOIN tipo_plato b ON a.id_tipo_plato = b.id_tipo_plato INNER JOIN plato c ON a.id_plato = c.id_plato WHERE ( a.estado = 1 ) AND ( b.estado = 1 ) AND ( c.estado = 1 ) AND ( c.imagen <> '' )";
    if (trim($td) != "") {
        $queryTipoPlato .= " AND ( a.id_plato IN (SELECT id_plato FROM plato_tipo_dieta WHERE id_tipo_dieta IN ($td)) )";
    }
    $queryTipoPlato .= " GROUP BY a.id_tipo_plato, b.$campoNombre ) a INNER JOIN plato b ON a.id_plato = b.id_plato ORDER BY a.$campoNombre";
    $sql = $cnx->query($queryTipoPlato);
    $sql->read();
    while ($sql->next()) {
        $plateTypeID = $sql->field('id_tipo_plato');
        $nombreTP = $sql->field("$campoNombre");
        $imagen = $plateTypeID . ".jpg";
        $setImagenURL = BASE_REMOTE_IMAGE_PATH . PLATE_TYPE_IMAGE_REMOTE_PATH . PREFIX_PLATE_TYPE_IMAGE_SMALL . "/" . PREFIX_PLATE_TYPE_IMAGE_SMALL . "_$imagen " . WIDTH_PLATE_TYPE_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_PATH . PLATE_TYPE_IMAGE_REMOTE_PATH . PREFIX_PLATE_TYPE_IMAGE_MEDIUM . "/" . PREFIX_PLATE_TYPE_IMAGE_MEDIUM . "_$imagen " . WIDTH_PLATE_TYPE_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_PATH . PLATE_TYPE_IMAGE_REMOTE_PATH . PREFIX_PLATE_TYPE_IMAGE_LARGE . "/" . PREFIX_PLATE_TYPE_IMAGE_LARGE . "_$imagen " . WIDTH_PLATE_TYPE_IMAGE_LARGE;
        $imagen = BASE_REMOTE_IMAGE_PATH . PLATE_TYPE_IMAGE_REMOTE_PATH . $imagen;

        array_push($arregloTipoPlato, array('plateTypeID' => $plateTypeID, 'plateType' => $nombreTP, 'imagen' => $imagen, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_PLATE_TYPE_IMAGE_SET));

    }

    $queryTipoDieta = "SELECT a.$campoNombre, a.id_tipo_dieta, b.imagen FROM ( SELECT a.id_tipo_dieta, b.$campoNombre, MAX(c.id_plato) AS id_plato FROM plato_tipo_dieta a INNER JOIN tipo_dieta b ON a.id_tipo_dieta = b.id_tipo_dieta INNER JOIN plato c ON a.id_plato = c.id_plato WHERE (a.estado = 1) AND (b.estado = 1) AND (c.estado = 1) AND (c.imagen <> '')";
    if (trim($td) != "") {
        $queryTipoDieta .= " AND (a.id_tipo_dieta IN ($td))";
    }
    $queryTipoDieta .= " GROUP BY a.id_tipo_dieta, b.$campoNombre ) a INNER JOIN plato b ON a.id_plato = b.id_plato ORDER BY a.$campoNombre";
    $sql = $cnx->query($queryTipoDieta);
    $sql->read();
    while ($sql->next()) {
        $idTipoD = $sql->field('id_tipo_dieta');
        $imagen = $idTipoD . ".jpg";
        $setImagenURL = BASE_REMOTE_IMAGE_PATH . DIET_TYPE_IMAGE_REMOTE_PATH . PREFIX_DIET_TYPE_IMAGE_SMALL . "/" . PREFIX_DIET_TYPE_IMAGE_SMALL . "_$imagen " . WIDTH_DIET_TYPE_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_PATH . DIET_TYPE_IMAGE_REMOTE_PATH . PREFIX_DIET_TYPE_IMAGE_MEDIUM . "/" . PREFIX_DIET_TYPE_IMAGE_MEDIUM . "_$imagen " . WIDTH_DIET_TYPE_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_PATH . DIET_TYPE_IMAGE_REMOTE_PATH . PREFIX_DIET_TYPE_IMAGE_LARGE . "/" . PREFIX_DIET_TYPE_IMAGE_LARGE . "_$imagen " . WIDTH_DIET_TYPE_IMAGE_LARGE;
        $imagen = BASE_REMOTE_IMAGE_PATH . DIET_TYPE_IMAGE_REMOTE_PATH . $imagen;
        array_push($arregloTipoDieta, array('dietTypeID' => $idTipoD, 'dietType' => $sql->field("$campoNombre"), 'imagen' => $imagen, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_DIET_TYPE_IMAGE_SET));
    }
    $sql = null;
    
    array_push($arregloData, array("top" => $arregloTop, "plateType" => $arregloTipoPlato, "dietType" => $arregloTipoDieta));

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
