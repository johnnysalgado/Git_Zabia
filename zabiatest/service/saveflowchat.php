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
require('../inc/constante_chat.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idPadre = $request->parentID;
    $descripcion = $request->description;
    $descripcionIngles = $request->descriptionEnglish;
    $tipoFlujo = $request->flowType;
    $agrupacion = $request->group;
    $imagen64 = $request->image64;
    $idPregunta = $request->healthQuestionID;
    $tag = $request->tag;
    $cantidadRegistro = $request->recordNumber;

    $usuarioEditar = $request->editUser;
    if ($descripcion != "") {
        $descripcion = str_replace("'", "", $descripcion);
    } else {
        $descripcion = "";
    }
    if ($descripcionIngles != "") {
        $descripcionIngles = str_replace("'", "", $descripcionIngles);
    } else {
        $descripcionIngles = "";
    }
    if ($tipoFlujo != "") {
        $tipoFlujo = str_replace("'", "", $tipoFlujo);
    } else {
        $tipoFlujo = "";
    }
    if ($agrupacion != "") {
        $agrupacion = str_replace("'", "", $agrupacion);
    } else {
        $agrupacion = "";
    }
    if ($idPregunta == "") {
        $idPregunta = -1;
    }
    if ($tag != "") {
        $tag = str_replace("'", "", $tag);
    } else {
        $tag = "";
    }
    if ($cantidadRegistro == "") {
        $cantidadRegistro = 0;
    }
    $usuarioEditar = str_replace("'", "", $usuarioEditar);

    $nombreArchivo = "";
    if ($imagen64 != "") {
        $mt = microtime(true);
        $mt =  $mt * 1000;
        $ticks = (string) $mt * 10;
        $nombreArchivo = save_base64_image(imagen64, $ticks, ICONO_IMAGE_PATH_FISICO);
    }

    $idFlujoChat = 0;
    $cnx = new MySQL();
    $query = "CALL USP_CREA_FLUJOCHAT ($idPadre, '$descripcion', '$descripcionIngles', '$tipoFlujo', , '$agrupacion', $nombreArchivo', $idPregunta, '$tag', $cantidadRegistro, $usuarioEditar', @p_id_flujochat);";
    $cnx->execute($query);
    $sql = $cnx->query("SELECT @p_id_flujochat AS id_flujochat");
    $sql->read();
    if ($sql->next()) {
        $idFlujoChat = $sql->field('id_flujochat');
    }
    /*
        $output = array('consulta' => $query, 'postdata' => $postdata);
        $respuesta = json_encode($output);
        die ($respuesta);
*/

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'flowID' => $idFlujoChat
        , 'message' => 'Flujo grabado correctamente');
    $respuesta = json_encode($output);
    die ($respuesta);
}

?>
