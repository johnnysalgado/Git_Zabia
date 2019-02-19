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
require('../inc/constante_insumo.php');
require('../inc/constante_enfermedad.php');
require('../inc/dao_cc.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idUsuario = $request->user;
    $token = $request->token;
    $isDefault = $request->isDefault;
    $estado = $request->status;
    $lenguaje = $request->language;
    
    if (trim($lenguaje) == "") {
        $lenguaje = LENGUAJE_ESPANOL;
    }

    $where = "";
    $arregloData = array();
    
    if ( ( trim($idUsuario) != "" ) && (!is_numeric($idUsuario)) ) {
        $mensaje= "";
        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "User no valid.";
        } else {
            $mensaje = "Usuario no válido.";
        }

        $output = array(
            'status' => '0'
            , 'message' => $mensaje
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    } else if ( trim($idUsuario) != '') {
        $where = "( id_usuario = $idUsuario )";
    }

    if ($token != '') {
        $token = str_replace("'", "''", $token);
        if ($where != '') {
            $where .= " AND ";
        }
        $where = "( token = \'$token\' )";
    }

    if (is_numeric($isDefault)) {
        if ($where != '') {
            $where .= " AND ";
        }
        $where = "( isdefault = $isDefault )";
    }

    if (is_numeric($estado)) {
        if ($where != '') {
            $where .= " AND ";
        }
        $where = "( estado = $estado )";
    }

    $idToken = "";
    $estado = "";
    $token = "";
    $last4 = "";
    $lastUsed = "";
    $nameOnCard = "";
    $expiration = "";
    $cardType = "";
    $isDefault = "";
    $isLastUsed = "";
    $createdBy = "";
    $created = "";
    $updatedBy = "";
    $updated = "";
    $daoCC = new DaoCC;
    $arregloToken = $daoCC->listarToken($where);
    foreach ($arregloToken as $item) {
        $idToken = $item['id_cc_token'];
        $idUsuario = $item['id_usuario'];
        $estado = $item['estado'];
        $token = $item['token'];
        $last4 = $item['last4'];
        $lastUsed = $item['lastused'];
        $nameOnCard = $item['nameoncard'];
        $expiration = $item['expiration'];
        $cardType = $item['cardtype'];
        $isDefault = $item['isdefault'];
        $isLastUsed = $item['islastused'];
        $createdBy = $item['created_by'];
        $created = $item['created'];
        $updatedBy = $item['updated_by'];
        $updated = $item['updated'];
        array_push($arregloData, array('idCCToken' => $idToken, 'idUser' => $idUsuario, 'status' => $estado, 'token' => $token, 'last4' => $last4, 'lastUsed' => $lastUsed, 'nameOnCard' => $nameOnCard, 'expiration' => $expiration, 'cardType' => $cardType, 'isDefault' => $isDefault, 'isLastUsed' => $isLastUsed, 'createdBy' => $createdBy, 'created' => $created, 'updatedBy' => $updatedBy, 'updated' => $updated));
    }

    $daoCC = null;

    //arreglo data

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);

}
?>
