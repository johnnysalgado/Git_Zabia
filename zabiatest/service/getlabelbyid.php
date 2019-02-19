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

/* =============================================================== */
/* CONEXIÓN A LA BASE DE DATOS
================================================================ */
require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/functions.php');
require('../inc/constante_label.php');

error_reporting(E_ERROR);

$cnx = new MySQL();

/* =============================================================== */
/* REQUEST + SQL QUERYS
================================================================ */

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idLabel = $request->label;
    
    $consulta = "";
    $arregloData = array();
    
    if ($idLabel != "") {
        $consulta = " SELECT nombre_producto, estatus FROM label WHERE id_label = " . $idLabel;

/*
        $output = array('consulta' => $consulta, 'postdata' => $postdata);
        $respuesta = json_encode($output);
        die ($respuesta);
*/
        $sql_query = $cnx->query($consulta);
        $sql_query->read();
        if ($sql_query->next()) {
            $nombre = $sql_query->field('nombre_producto');
            $estatus = $sql_query->field('estatus');
            $imagenArray = array();
            //imágenes
            $consulta2 = "SELECT id_label_imagen, concepto, imagen FROM label_imagen WHERE id_label = " . $idLabel . " AND estado = 1";
            $sql_query2 = $cnx->query($consulta2);
            $sql_query2->read();
            while ($sql_query2->next()) {
                $idLabelImagen = $sql_query2->field('id_label_imagen');
                $concepto = $sql_query2->field('concepto');
                $imagen = $sql_query2->field('imagen');
                $imagenRuta = LABEL_IMAGE_PATH . $imagen;
                array_push($imagenArray, array('id' => $idLabelImagen, 'concept' => $concepto, 'imageName' => $imagen, 'imagePath' => $imagenRuta));
            }
            array_push($arregloData, array('id' => $idLabel, 'name' => $nombre, 'status' => $estatus, 'images' => $imagenArray));
        }

        $cnx = null;
        $output = array(
            'status' => '1'
            , 'message' => ''
            , 'data' => $arregloData);
        $respuesta = json_encode($output);
        die ($respuesta);
    } else {
        $cnx = null;
        $output = array(
            'status' => '0'
            , 'message' => 'No ha enviado un id válido'
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    }

}
?>
