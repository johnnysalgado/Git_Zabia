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
    $user = $request->user;
    $name = $request->name;
    $idLabel = $request->labelID;
    $description = $request->description;
    $gram = $request->gram;
    $barrCode = $request->barrCode;
    $usuarioEditar = $request->userEdit;
    $status = $request->status;
    $images = $request->images;
    if (!($user != "" && is_numeric($user))) {
        $user = 0;
    }
    $user = str_replace("'", "", $user);
    if ($name != "") {
        $name = str_replace("'", "", $name);
    } else {
        $name = "";
    }
    if ($description != "") {
        $description = str_replace("'", "", $description);
    } else {
        $description = "";
    }
    if (!($gram != "" && is_numeric($gram))) {
        $gram = 0;
    }
    if ($barrCode != "") {
        $barrCode = str_replace("'", "", $barrCode);
    } else {
        $barrCode = "";
    }
    if ($status != "") {
        $status = str_replace("'", "", $status);
    } else {
        $status = ESTATUS_PENDIENTE;
    }
    $usuarioEditar = str_replace("'", "", $usuarioEditar);

    $arregloInsert = array();
    foreach ($images as $item) {
        $imageType = $item->type;
        $image64 = $item->image64;
        $concept = $item->concept;
        $imageID = $item->imageID;
        $eliminar = $item->delete;
        $nombreArchivo = "";
        if ($image64 != "") {
            list($type, $image64) = explode(';', $image64);
            list(, $image64) = explode(',', $image64);
            $data64 = base64_decode($image64);
            $mt = microtime(true);
            $mt =  $mt * 1000;
            $ticks = (string) $mt * 10;
            $nombreArchivo = $user . "_" . $ticks . "." . $imageType;
            $rutaArchivo = LABEL_IMAGE_PATH_FISICO . $nombreArchivo;
            file_put_contents($rutaArchivo, $data64);
        }
        if ($imageID == 0) {
            array_push($arregloInsert, "INSERT INTO label_imagen (id_label, concepto, imagen, usuario_registro) VALUES (IDLABEL, '" . $concept . "', '" . $nombreArchivo . "', '" . $usuarioEditar . "')");
        } else {
            if ($eliminar == 1) {
                array_push($arregloInsert, "UPDATE label_imagen SET estado = 0, usuario_modifica= '" . $usuarioEditar . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_label_imagen = " . $imageID);
            } else if ($nombreArchivo != "") {
                array_push($arregloInsert, "UPDATE label_imagen SET imagen = '" . $nombreArchivo . "',  estado = 1, usuario_modifica= '" . $usuarioEditar . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_label_imagen = " . $imageID);
            }
        }
    }

    if ($idLabel == 0) {
        $query = "INSERT INTO label (id_usuariolabel, nombre_producto, descripcion, gramo, codigo_barra, estatus, usuario_registro) VALUES (" . $user . ", '" . $name . "', '" . $description . "', " . $gram . ", '" . $barrCode . "', '" . $status . "', '" . $usuarioEditar . "')";
        $idLabel = $cnx->insert($query);
    } else {
        $query = "UPDATE label SET nombre_producto = '" . $name . "', descripcion = '" . $description . "', gramo = " . $gram . ", codigo_barra = '" . $barrCode . "', estatus = '" . $status . "', fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuarioEditar . "' WHERE id_label = " . $idLabel;
        $cnx->execute($query);
    }

    foreach ($arregloInsert as $item) {
        if (strpos($item, "IDLABEL") > 0) {
            $cnx->execute(str_replace("IDLABEL", $idLabel, $item));
        } else {
            $cnx->execute($item);
        }
    }

    /*
        $output = array('consulta' => $query, 'postdata' => $postdata);
        $respuesta = json_encode($output);
        die ($respuesta);
*/

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'label' => $idLabel
        , 'message' => 'Etiqueta grabada correctamente');
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
