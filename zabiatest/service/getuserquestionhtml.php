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
require('../inc/dao_usuario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $idSeccion = $request->sectionID;
    $lenguaje = $request->language;

    $daoUsuario = new DaoUsuario();
    $arregloUsuario = $daoUsuario->obtenerUsuario($user);
    $daoUsuario = null;

    if (count($arregloUsuario) > 0) {
        $item = $arregloUsuario[0];
        $idAfiliado = $item['id_affiliates'];
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . dirname($_SERVER["PHP_SELF"]) . "/" . PREFIJO_AFILIADO . "$idAfiliado/getuserquestionhtml.php";
        //add affiliate id
        $arregloParametro = array('user' => $user, 'sectionID' => $idSeccion, 'affiliateID' => $idAfiliado, 'language' => $lenguaje);
        $jsonParametro = json_encode($arregloParametro);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonParametro);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        echo $response;
    } else {
        if ($lenguaje == LENGUAJE_INGLES ) {
            $mensaje = "User does not exists";
        } else {
            $mensaje = "Usuario no existe";
        }
        $output = array(
            'status' => '0'
            , 'message' => $mensaje);
        $respuesta = json_encode($output);
        die ($respuesta);
    }

}

?>
