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
require('../inc/constante_chat.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idFlujoChat = $request->flowchatID;
    $arregloData = array();
    $arregloOrdenado = array();

    $imagenURL = "";
    $cnx = new MySQL();
    $query = "CALL USP_OBTEN_FLUJOCHAT ($idFlujoChat);";
    $sql = $cnx->query($query);
    $sql->read();
    while ($sql->next()) {
        $idFlujoChat = $sql->field('id_flujochat');
        $idPadre = $sql->field('id_padre');
        $descripcion = $sql->field('descripcion');
        $descripcionIngles = $sql->field('descripcion_ing');
        $tipoFuncion = $sql->field('tipo_funcion');
        $presentacion = $sql->field('presentacion');
        $imagen = $sql->field('imagen');
        if ($imagen != '') {
            $imagenURL = ICONO_IMAGE_PATH . $imagen;
        }
        $orden = $sql->field('orden');
        $idPregunta = $sql->field('id_pregunta');
        $tag = $sql->field('tag');
        $cantidadRegistro = $sql->field('cantidad_registro');
        $usuarioRegistro = $sql->field('usuario_registro');
        $fechaRegistro = $sql->field('fecha_registro');
        $usuarioModifica = $sql->field('usuario_modifica');
        $fechaModifica = $sql->field('fecha_modifica');
        $descripcionPregunta = $sql->field('descripcion_pregunta');

        array_push($arregloData, array('flowChatID' => $idFlujoChat, 'parentID' => $idPadre, 'description' => $descripcion, 'descriptionEnglish' => $descripcionIngles, 'functionType' => $tipoFuncion, 'presentation' => $presentacion, 'image' => $imagen, 'urlImage' => $imagenURL, 'order' => $orden, 'healthQuestionID' => $idPregunta, 'tag' => $tag, 'recordNumber' => $cantidadRegistro, 'healthQuestionDescription' => $descripcionPregunta));
    }

    /*
        $output = array('consulta' => $query, 'postdata' => $postdata);
        $respuesta = json_encode($output);
        die ($respuesta);
*/

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}

?>
