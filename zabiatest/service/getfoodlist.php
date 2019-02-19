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

error_reporting(E_ERROR);

function existeEnArregloIntolerancia($arregloIntolerancia, $intolerancia) {
	$resultado = false;
	foreach($arregloIntolerancia as $item) {
		if ($item['intolerance'] == $intolerancia) {
			$resultado = true;
			break;
		}
	}
	return $resultado;
}

function existeEnArregloPrecondicion($arregloPrecondicion, $precondicion) {
	$resultado = false;
	foreach($arregloPrecondicion as $item) {
		if ($item['precondition'] == $precondicion) {
			$resultado = true;
			break;
		}
	}
	return $resultado;
}

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $tipoAlimento = $request->foodType;
    $texto = $request->search;
    $rsCode = $request->rsCode;
    $lenguaje = $request->language;
    $page = $request->page;
    $records = $request->records;
    
    $arregloData = array();

    if (trim($tipoAlimento) != "") {
        $tipoAlimento = str_replace("'", "''", $tipoAlimento);
        $tipoAlimento = str_replace("|", ",", $tipoAlimento);
        $tipoAlimento = str_replace(" ", "", $tipoAlimento);
    }
    if (trim($rsCode) != "") {
        $rsCode = str_replace("'", "", $rsCode);
    }
    if (trim($texto) != "") {
        $texto = str_replace("'", "''", $texto);
    }
    if (trim($lenguaje) != "") {
        $lenguaje = str_replace("'", "", $lenguaje);
    } else {
        $lenguaje = LENGUAJE_ESPANOL;
    }

    if ($lenguaje == LENGUAJE_INGLES) {
        $mensajeRojo = TEXTO_SEMA_ROJO_ING;
        $mensajeAmarillo = TEXTO_SEMA_AMARILLO_ING;
        $mensajeAzul = TEXTO_SEMA_AZUL_ING;
        $mensajeVerde = TEXTO_SEMA_VERDE_ING;
    } else {
        $mensajeRojo = TEXTO_SEMA_ROJO;
        $mensajeAmarillo = TEXTO_SEMA_AMARILLO;
        $mensajeAzul = TEXTO_SEMA_AZUL;
        $mensajeVerde = TEXTO_SEMA_VERDE;
    }

    $consulta = "CALL USP_LIST_FOOD ('$tipoAlimento', '$texto', '$rsCode', '$lenguaje', $page, $records)";
    //echo $consulta;

    $idInsumox = 0;
    $cnx = new MySQL();
    $sql_query = $cnx->query($consulta);
    $sql_query->read();
    $contadorRegistro = $sql_query->count();
    while ($sql_query->next()) {
        $idInsumo = $sql_query->field('id_insumo');

        if ($idInsumo != $idInsumox) {
            if ($idInsumox != 0) {
                $nombrePrincipal =  $nombre;
                $nombreAlterno = $nombreIng;
                $tipoAlimentoPrincipal = $tipoAlimento;
                $tipoAlimentoAlterno = $tipoAlimentoIng;
                if ($lenguaje == LENGUAJE_INGLES) {
                    $nombrePrincipal = $nombreIng;
                    $nombreAlterno = $nombre;
                    $tipoAlimentoPrincipal = $tipoAlimentoIng;
                    $tipoAlimentoAlterno = $tipoAlimento;
                }
                $insumoArray = array('id' => $idInsumo, 'name' => $nombrePrincipal, 'alternativeName' => '', 'foodType' => $tipoAlimentoPrincipal, "alternativeFoodType" => '', 'image' => $imagen, 'imageName' => $nombreImagen, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_IMAGE_SET, 'protein' => $proteina, 'carbohydrt' => $carbohidrato, 'calorie' => $caloria, 'fat' => $grasa, 'density' => round($densidad, 4), 'precondition' => $precondicionArray, 'intolerance' => $intoleranciaArray);
                array_push($arregloData, $insumoArray);
            }

            $codigoRS = $sql_query->field('rs_code');
            $nombreIng = $sql_query->field('insumo_ing');
            $nombre = $sql_query->field('insumo');
            $tipoAlimento = $sql_query->field('tipo_alimento');
            $tipoAlimentoIng = $sql_query->field('tipo_alimento_ing');
            $nombreImagen = $sql_query->field('imagen');
            $prioridad = $sql_query->field('prioridad');
            $proteina = round($sql_query->field('proteina'), 2);
            $carbohidrato = round($sql_query->field('carbohidrato'), 2);
            $caloria = round($sql_query->field('caloria'), 0);
            $grasa = round($sql_query->field('grasa'), 2);
            $densidad = round($sql_query->field('density'), 0);
            if ($nombreImagen != "") {
                $imagen = BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . INSUMO_FOLDER_MINIATURA . PREFIX_INSUMO_IMAGE_MINIATURA . "_$nombreImagen";
            } else {
                $imagen = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg";
            }
            $accion = "beneficiar";
            $icono = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_VERDE;
            $descripcion = $mensajeVerde;
            if ($prioridad == -2) {
                $accion = ACCION_ELIMINAR;
                $icono = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_ROJO;
                $descripcion = $mensajeRojo;
            } else if ($prioridad == -1) {
                $accion = ACCION_RESTRINGIR;
                $icono = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_AMARILLO;
                $descripcion = $mensajeAmarillo;
            } else if ($prioridad == 2) {
                $accion = ACCION_AUMENTAR;
                $icono = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_AZUL;
                $descripcion = $mensajeAzul;
            }
            $precondicionArray = array();
            $intoleranciaArray = array();
        }

        //intolerancia
        $intolerancia = $sql_query->field('intolerancia');
        $intoleranciaPrioridad = $sql_query->field('prioridad_intolerancia');
        if (!existeEnArregloIntolerancia($intoleranciaArray, $intolerancia)) {
            $accionIntolerancia = "beneficiar";
            $iconoIntolerancia = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_VERDE;
            if ($intoleranciaPrioridad == -2) {
                $accionIntolerancia = ACCION_ELIMINAR;
                $iconoIntolerancia = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_ROJO;
            } else if ($intoleranciaPrioridad == -1) {
                $accionIntolerancia = ACCION_RESTRINGIR;
                $iconoIntolerancia = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_AMARILLO;
            } else if ($intoleranciaPrioridad == 2) {
                $accionIntolerancia = ACCION_AUMENTAR;
                $iconoIntolerancia = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_AZUL;
            }
            array_push($intoleranciaArray, array('intolerance' => $intolerancia, 'icon' => $iconoIntolerancia, 'action' => $accionIntolerancia));
        }

        //precondición
        $precondicion = $sql_query->field('precondicion');
        $precondicionPrioridad = $sql_query->field('prioridad_precondicion');
        if (!existeEnArregloPrecondicion($precondicionArray, $precondicion)) {
            $accionPrecondicion = "beneficiar";
            $iconoPrecondicion = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_VERDE;
            if ($precondicionPrioridad == -2) {
                $accionPrecondicion = ACCION_ELIMINAR;
                $iconoPrecondicion = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_ROJO;
            } else if ($precondicionPrioridad == -1) {
                $accionPrecondicion = ACCION_RESTRINGIR;
                $iconoPrecondicion = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_AMARILLO;
            } else if ($precondicionPrioridad == 2) {
                $accionPrecondicion = ACCION_AUMENTAR;
                $iconoPrecondicion = BASE_REMOTE_IMAGE_LOGO_PATH . ICONO_INSUMO_AZUL;
            }
            array_push($precondicionArray, array('precondition' => $precondicion, 'icon' => $iconoPrecondicion, 'action' => $accionPrecondicion));
        }

        $idInsumox = $idInsumo;
    }


    if (count($arregloData) == 0 && $contadorRegistro > 0) {
        $nombrePrincipal =  $nombre;
        $nombreAlterno = $nombreIng;
        $tipoAlimentoPrincipal = $tipoAlimento;
        $tipoAlimentoAlterno = $tipoAlimentoIng;
        if ($lenguaje == LENGUAJE_INGLES) {
            $nombrePrincipal = $nombreIng;
            $nombreAlterno = $nombre;
            $tipoAlimentoPrincipal = $tipoAlimentoIng;
            $tipoAlimentoAlterno = $tipoAlimento;
        }
        $insumoArray = array('id' => $idInsumo, 'name' => $nombrePrincipal, 'alternativeName' => '', 'foodType' => $tipoAlimentoPrincipal, "alternativeFoodType" => '', 'image' => $imagen, 'imageName' => $nombreImagen, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_IMAGE_SET, 'protein' => $proteina, 'carbohydrt' => $carbohidrato, 'calorie' => $caloria, 'fat' => $grasa, 'density' => $densidad, 'precondition' => $precondicionArray, 'intolerance' => $intoleranciaArray);
        array_push($arregloData, $insumoArray);
    }

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
