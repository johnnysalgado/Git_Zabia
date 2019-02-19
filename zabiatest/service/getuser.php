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
require('../inc/constante.php');
require('../inc/constante_usuario.php');
require('../inc/dao_usuario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $lenguaje = $request->language;

    $consulta = "";
    $mensaje = "";
    $arregloData = array();

    if ( $user == "" ) {
        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "user is blank";
        } else {
            $mensaje = "usuario en blanco";
        }
        $mensaje = 
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    }

    $user = str_replace("'", "", $user);

    $daoUsuario = new DaoUsuario();

    $arregloUsuario = $daoUsuario->obtenerUsuario($user);
    if ( count($arregloUsuario) > 0 ) {

        $item = $arregloUsuario[0];
        $idUsuario = $item['id_usuario'];
        $idAfiliado = $item['id_affiliates'];
        $email = $item['email'];
        $nombre = $item['nombre'];
        $apellido = $item['apellido'];
        $codigoPais = $item['cod_pais'];
        $pais = $item['pais'];
        $idRegion = $item['id_region'];
        $region = $item['region'];
        $telefono = $item['telefono'];
        $origen = $item['origen'];
        $foto = $item['foto'];
        if ($foto != "") {
            $urlFoto = BASE_REMOTE_IMAGE_PATH . "/" . FOLDER_USER_IMAGE . $foto;
        } else {
            $urlFoto = BASE_REMOTE_IMAGE_LOGO_PATH . "user_blank.png";
        }
        $idTipoEmpleo = $item['id_tipo_empleo'];
        if ($lenguaje == LENGUAJE_INGLES) {
            $tipoEmpleo = $item['tipo_empleo_ing'];
        } else {
            $tipoEmpleo = $item['tipo_empleo'];
        }
        $idTipoIngresoEconomico = $item['id_tipo_ingreso_economico'];
        $tipoIngresoEconomico = $item['tipo_ingreso_economico'];
        $idTipoEducacion = $item['id_tipo_educacion'];
        if ($lenguaje == LENGUAJE_INGLES) {
            $tipoEducacion = $item['tipo_educacion_ing'];
        } else {
            $tipoEducacion = $item['tipo_educacion'];
        }
        $idTipoGenero = $item['id_tipo_genero'];
        if ($lenguaje == LENGUAJE_INGLES) {
            $tipoGenero = $item['tipo_genero_ing'];
        } else {
            $tipoGenero = $item['tipo_genero'];
        }
        if ($codigoPais != null) {
            $codigoPais = strtoupper($codigoPais);
        }
        $estado = $item['estado'];

        array_push($arregloData, array('user' => $idUsuario, 'affiliatesID' => $idAfiliado, 'email' => $email, 'firstName' => $nombre, 'lastName' => $apellido, 'country' => array('code' => $codigoPais, 'description' => $pais), 'region' => array('id' => $idRegion, 'description' => $region), 'phone' => $telefono, 'source' => $origen, 'photo' => $urlFoto, 'employment' => array('id' => $idTipoEmpleo, 'description' => $tipoEmpleo), 'incomeLevel' => array('id' => $idTipoIngresoEconomico, 'description' => $tipoIngresoEconomico), 'educationLevel' => array('id' => $idTipoEducacion, 'description' => $tipoEducacion), 'gender' => array('id' => $idTipoGenero, 'description' => $tipoGenero), 'status' => $estado));

        $output = array(
            'status' => '1'
            , 'message' => ''
            , 'data' => $arregloData
        );
        $respuesta = json_encode($output);
        die ($respuesta);

    } else {

        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "User error";
        } else {
            $mensaje = "Error en usuario";
        }
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
            , 'data' => null
        );
        $respuesta = json_encode($output);
        die ($respuesta);

    }

}
?>