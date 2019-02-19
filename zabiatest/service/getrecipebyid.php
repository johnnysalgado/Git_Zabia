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
require('../inc/constante_receta.php');

error_reporting(E_ERROR);

$cnx = new MySQL();

/* =============================================================== */
/* REQUEST + SQL QUERYS
================================================================ */

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idPlato = $request->recipe;
    $user = $request->user;
    $lenguaje = $request->language;
    
    $consulta = "";
    $arregloData = array();

    if ($lenguaje == LENGUAJE_INGLES) {
        $nombreCampo = "nombre_ing";
    } else {
        $nombreCampo = "nombre";
    }
    
    if ($idPlato != "") {
        $consulta = " SELECT a.$nombreCampo, a.credito, a.imagen, a.porcion, a.preparacion, a.tiempo, a.megusta, a.kcal, a.precio, a.grasa, a.precio, a.dificultad, p.nombre as pais, r.nombre as region FROM plato a LEFT OUTER JOIN pais p ON a.cod_pais = p.cod_pais LEFT OUTER JOIN region r ON a.id_region = r.id_region WHERE id_plato = $idPlato";

/*
        $output = array('consulta' => $consulta, 'postdata' => $postdata);
        $respuesta = json_encode($output);
        die ($respuesta);
*/
        $sql_query = $cnx->query($consulta);
        $sql_query->read();
        while ($sql_query->next()) {
            $nombre = $sql_query->field("$nombreCampo");
            $imagen = $sql_query->field('imagen');
            $preparacion = $sql_query->field('preparacion');
            $favorito = "0";
            $porcion = $sql_query->field('porcion');
            $tiempo = $sql_query->field('tiempo');
            $credito = $sql_query->field('credito');;
            $like = $sql_query->field('megusta');
            $kcal = $sql_query->field('kcal');
            $grasa = $sql_query->field('grasa');
            $precio = $sql_query->field('precio');
            $dificultad = $sql_query->field('dificultad');
            $pais = $sql_query->field('pais');
            $region = $sql_query->field('region');
            $ingredienteArray = array();
            $beneficioArray = array();
            $cuisineArray = array();
            $dishtypeArray = array();

            if ($imagen != "") {
                $imagen = BASE_REMOTE_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . $imagen;                
            } else {
                $imagen = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg";
            }

            //plato ingredientes
            $consulta2 = "SELECT id_plato_insumo, descripcion, $nombreCampo, cantidad, unidad FROM plato_insumo WHERE id_plato = $idPlato AND estado = 1";
            $sql_query2 = $cnx->query($consulta2);
            $sql_query2->read();
            while ($sql_query2->next()) {
                $idPlatoInsumo = $sql_query2->field('id_plato_insumo');
                $nombreInsumo = $sql_query2->field("$nombreCampo");
                $descripcion = $sql_query2->field('descripcion');
                $quantity = $sql_query2->field('cantidad') . ' ' . $sql_query2->field('unidad');
                if ($descripcion == "") {
                    $descripcion = $quantity . ' de ' . $nombreInsumo;
                }
                array_push($ingredienteArray, array('id' => $idPlatoInsumo, 'name' => $nombreInsumo, 'quantity' => $quantity, 'description' => $descripcion));
            }
            //beneficios
            $consulta3 = "SELECT b.$nombreCampo FROM plato_beneficio a INNER JOIN beneficio b ON a.id_beneficio = b.id_beneficio WHERE a.id_plato = $idPlato AND b.estado = 1 ORDER BY 1";
            $sql_query3 = $cnx->query($consulta3);
            $sql_query3->read();
            while ($sql_query3->next()) {
                $beneficio = $sql_query3->field("$nombreCampo");
                array_push($beneficioArray, array('name' => $beneficio));
            }
            //tipo cocina
            $consulta4 = "SELECT b.$nombreCampo FROM plato_tipo_cocina a INNER JOIN tipo_cocina b ON a.id_tipo_cocina = b.id_tipo_cocina WHERE a.id_plato = $idPlato ORDER BY 1";
            $sql_query4 = $cnx->query($consulta4);
            $sql_query4->read();
            while ($sql_query4->next()) {
                $tipoCocina = $sql_query4->field("$nombreCampo");
                array_push($cuisineArray, array('name' => $tipoCocina));
            }
            //tipo plato
            $consulta5 = "SELECT b.$nombreCampo FROM plato_tipo_plato a INNER JOIN tipo_plato b ON a.id_tipo_plato = b.id_tipo_plato WHERE a.id_plato = $idPlato ORDER BY 1";
            $sql_query5 = $cnx->query($consulta5);
            $sql_query5->read();
            while ($sql_query5->next()) {
                $tipoPlato = $sql_query5->field("$nombreCampo");
                array_push($dishtypeArray, array('name' => $tipoPlato));
            }
            //favorito
            if ($user != "") {
                $consulta6 = "SELECT id_plato FROM usuario_plato WHERE estado = 1 AND id_usuario = $user AND id_plato = $idPlato" ;
                $sql_query6 = $cnx->query($consulta6);
                $sql_query6->read();
                while ($sql_query6->next()) {
                    $favorito = "1";
                }
            }

            $tiempo .= "m.";
            $grasa .= " gr.";
            $kcal = round($kcal, 0) . " kcal";
            $precio = "S/. " . round($precio, 0);

            $platoArray = array('id' => $idPlato, 'name' => $nombre, 'image_url' => $imagen, 'instructions' => $preparacion, 'favorite' => $favorito, 'servings' => $porcion, 'like' => $like, 'credit' => $credito, 'time' => $tiempo, 'cuisine' => $cuisineArray, 'dishtype' => $dishtypeArray, 'benefit' => $beneficioArray, 'nutritional' => $kcal, 'fat' => $grasa, 'price' => $precio, 'country' => $pais, 'region' => $region, 'difficulty' => $dificultad, 'ingredients' => $ingredienteArray);

            array_push($arregloData, $platoArray);
        }

        $cnx = null;
        $output = array(
            'status' => '1'
            , 'message' => ''
            , 'data' => $arregloData);
        $respuesta = json_encode($output);
        die ($respuesta);
    } else {
        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "ID not valid";
        } else {
            $mensaje = "No ha enviado un id válido";
        }
        $cnx = null;
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    }

}
?>
