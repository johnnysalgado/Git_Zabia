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
require('../inc/constante_receta.php');
require('../inc/constante_cuestionario.php');

error_reporting(E_ERROR);

$cnx = new MySQL();

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $order = $request->order;
    $benefit = $request->benefit;
    $nutritional = $request->nutritional;
    $dieta = $request->dieta;
    $cuisine = $request->cuisine;
    $ingredient = $request->ingredients;
    $price = $request->price;
    $restriction = $request->restrictions;
    $text = $request->text;
    $favorite = $request->favorite;
    $tipoPlato = $request->plateType;
    $lenguaje = $request->language;
    $page = $request->page;
    $records = $request->records;
    
    $consulta = "";
    $arregloData = array();
    $select = "";
    $from = "";
    $where = "";
    $innerJoin = "";
    $orderby = "";

    if ($lenguaje == LENGUAJE_INGLES) {
        $campoNombre = "nombre_ing";
    } else {
        $campoNombre = "nombre";
    }

    $from = " FROM plato p LEFT OUTER JOIN pais pa ON p.cod_pais = pa.cod_pais AND pa.estado = 1 LEFT OUTER JOIN region r ON p.id_region = r.id_region AND r.estado = 1";
    if ($order == "top") {
        $where .= " AND (p.top = 1)";
        $orderby = " ORDER BY p.$campoNombre";
    } else if ($order == "tipo-plato") {
        $select = ", tp.$campoNombre AS tipo_plato";
        $innerJoin = " INNER JOIN plato_tipo_plato ptp ON p.id_plato = ptp.id_plato INNER JOIN tipo_plato tp ON ptp.id_tipo_plato = tp.id_tipo_plato AND ptp.estado = 1";
        $orderby = " ORDER BY tipo_plato, p.$campoNombre";
    } else if ($order == "tipo-dieta") {
        $select = ", td.$campoNombre AS tipo_dieta";
        $innerJoin = " INNER JOIN plato_tipo_dieta ptd ON p.id_plato = ptd.id_plato INNER JOIN tipo_dieta td ON ptd.id_tipo_dieta = td.id_tipo_dieta AND ptd.estado = 1";
        $orderby = " ORDER BY tipo_dieta, p.$campoNombre";
    }

    if ($benefit != "") {
        $where .= " AND (pb.id_beneficio IN (";
        $wherex = "";
        $beneficios = explode("|", $benefit);
        $innerJoin .= " INNER JOIN plato_beneficio pb ON p.id_plato = pb.id_plato ";        
        foreach($beneficios as $elemento) {
            $wherex .= $elemento . ", ";
        }
        if ($wherex != "") {
            $wherex = substr($wherex, 0, -2);
        }
        $where .= $wherex . "))";
    }

    if ($cuisine != "") {
        $where .= " AND (ptc.id_tipo_cocina IN (";
        $wherex = "";
        $cuisines = explode("|", $cuisine);
        $innerJoin .= " INNER JOIN plato_tipo_cocina ptc ON p.id_plato = ptc.id_plato AND ptc.estado = 1";        
        foreach($cuisines as $elemento) {
            $wherex .= $elemento . ", ";
        }
        if ($wherex != "") {
            $wherex = substr($wherex, 0, -2);
        }
        $where .= $wherex . "))";
    }

    if ($tipoPlato != "") {
        $where .= " AND (ptp.id_tipo_plato IN (";
        $wherex = "";
        $tipoPlatos = explode("|", $tipoPlato);
        if ($order != "tipo-plato") {
            $innerJoin .= " INNER JOIN plato_tipo_plato ptp ON p.id_plato = ptp.id_plato AND ptp.estado = 1";
        }
        foreach($tipoPlatos as $elemento) {
            $wherex .= $elemento . ", ";
        }
        if ($wherex != "") {
            $wherex = substr($wherex, 0, -2);
        }
        $where .= $wherex . "))";
    }

    //verifica si el usuario ha elegido tipo dieta en el cuestionario
    $querytd = "SELECT DISTINCT b.id_tipo_dieta FROM usuario_pregunta_respuesta a INNER JOIN tipo_dieta b ON a.id_respuesta = b.id_tipo_dieta INNER JOIN pregunta p ON a.id_pregunta = p.id_pregunta WHERE a.estado = 1 AND b.estado = 1 AND p.estado = 1 AND p.dato_especial = '" . DATOESPECIAL_TIPO_DIETA . "' AND a.id_usuario = $user";

    $sql_td = $cnx->query($querytd);
    $sql_td->read();
    $td = "";
    while ($sql_td->next()) {
        $idTipoDieta_ = $sql_td->field('id_tipo_dieta');
        $td .= $idTipoDieta_ . ",";
    }
    if (trim($dieta) == "") {//si no hay tipo de dieta en el filtro entonces busca en el cuestionario
        if ($td != "") {
            $td = substr($td, 0, strlen($td) - 1);
        }
        $dieta = $td;
    }
    $sql_td = null;

    //******************* */

    if ($dieta != "") {
        $where .= " AND (ptd.id_tipo_dieta IN (";
        $wherex = "";
        $dietas = explode("|", $dieta);
        if ($order != "tipo-dieta") {
            $innerJoin .= " INNER JOIN plato_tipo_dieta ptd ON p.id_plato = ptd.id_plato AND ptd.estado = 1";        
            }
        foreach($dietas as $elemento) {
            $wherex .= $elemento . ", ";
        }
        if ($wherex != "") {
            $wherex = substr($wherex, 0, -2);
        }
        $where .= $wherex . "))";
    }

    if ($nutritional != "") {
        $wherex = "";
        $nutritionals = explode("|", $nutritional);
        $where .= " AND (p.kcal >= " . $nutritionals[0] . " AND p.kcal <= " . $nutritionals[1] . ")";
    }

    if ($ingredient != "") {
        $where .= " AND (p.id_plato IN (SELECT id_plato FROM plato_insumo WHERE estado = 1 AND (";
        $wherex = "";
        $ingredients = explode("|", $ingredient);
        $innerJoin .= " INNER JOIN plato_insumo pi ON p.id_plato = pi.id_plato";
        foreach($ingredients as $elemento) {
            $wherex .= " $campoNombre LIKE '%$elemento%' OR ";
        }
        if ($wherex != "") {
            $wherex = substr($wherex, 0, -3);
        }
        $where .= $wherex . ")))";
    }

    if ($restriction != "") {
        $where .= " AND (p.id_plato NOT IN (SELECT id_plato FROM plato_insumo WHERE estado = 1 AND $campoNombre  IN (";
        $wherex = "";
        $restrictions = explode("|", $restriction);
        foreach($restrictions as $elemento) {
            $wherex .= "'" . $elemento . "', ";
        }
        if ($wherex != "") {
            $wherex = substr($wherex, 0, -2);
        }
        $where .= $wherex . ")))";
    }

    if ($price != "") {
        $wherex = "";
        $prices = explode("|", $price);
        $where .= " AND (p.precio >= " . $prices[0] . " AND p.precio <= " . $prices[1] . ")";
    }

    if ($text != "") {
        $where .= " AND (p.$campoNombre LIKE '%$text%' OR p.preparacion LIKE '%$text%')";
    }

    if ($favorite == 1 && $user > 0) {
        $where .= " AND (p.id_plato IN (SELECT id_plato FROM usuario_plato WHERE id_usuario = $user AND estado = 1))";
    }

    if ($page == "") $page = 0;
    $page = ($records * $page);
    if ($records > 0) {
        $limit = " LIMIT " . $page . "," . $records;
    } else {
        $limit = "";
    }
    $consulta = "SELECT DISTINCT p.$campoNombre, p.id_plato, p.imagen, p.preparacion, p.porcion, p.credito, p.tiempo, p.megusta, p.kcal, p.grasa, p.precio, p.dificultad, pa.nombre as pais, r.nombre as region";
    $where = " WHERE p.estado = 1" . $where;
    $consulta = $consulta . $select . $from . $innerJoin . $where . $orderby. $limit;

    //echo $consulta;
    $sql_query = $cnx->query($consulta);
    $sql_query->read();
    while ($sql_query->next()) {
        $idPlato = $sql_query->field('id_plato');
        $nombre = $sql_query->field("$campoNombre");
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
        $tipoPlato_ = "";
        $tipoDieta_ = "";
        if ($order == "tipo-plato") {
            $tipoPlato_ = $sql_query->field('tipo_plato');
        } else if ($order == "tipo-dieta") {
            $tipoDieta_ = $sql_query->field('tipo_dieta');
        }
        if ($imagen != "") {
            $setImagenURL = BASE_REMOTE_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . PREFIX_RECIPE_IMAGE_SMALL . "/" . PREFIX_RECIPE_IMAGE_SMALL . "_$imagen " . WIDTH_RECIPE_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . PREFIX_RECIPE_IMAGE_MEDIUM . "/" . PREFIX_RECIPE_IMAGE_MEDIUM . "_$imagen " . WIDTH_RECIPE_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . PREFIX_RECIPE_IMAGE_LARGE . "/" . PREFIX_RECIPE_IMAGE_LARGE . "_$imagen " . WIDTH_RECIPE_IMAGE_LARGE;
            $imagen = BASE_REMOTE_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . $imagen;
        } else {
            $imagen = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg";
            $setImagenURL = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_LARGE;
        }

        $ingredienteArray = array();
        $beneficioArray = array();
        $cuisineArray = array();
        $dishtypeArray = array();
    
        //plato ingredientes
        $consulta2 = "SELECT id_plato_insumo, descripcion, $campoNombre, cantidad, unidad FROM plato_insumo WHERE id_plato = $idPlato AND estado = 1";
        $sql_query2 = $cnx->query($consulta2);
        $sql_query2->read();
        while ($sql_query2->next()) {
            $idPlatoInsumo = $sql_query2->field('id_plato_insumo');
            $nombreInsumo = $sql_query2->field("$campoNombre");
            $descripcion = $sql_query2->field('descripcion');
            $quantity = $sql_query2->field('cantidad') . ' ' . $sql_query2->field('unidad');
            if ($descripcion == "") {
                $descripcion = $quantity . ' de ' . $nombreInsumo;
            }
            array_push($ingredienteArray, array('id' => $idPlatoInsumo, 'name' => $nombreInsumo, 'quantity' => $quantity, 'description' => $descripcion));
        }
        //beneficios
        $consulta3 = "SELECT DISTINCT b.$campoNombre FROM plato_beneficio a INNER JOIN beneficio b ON a.id_beneficio = b.id_beneficio WHERE a.id_plato = $idPlato AND b.estado = 1 ORDER BY 1";
        $sql_query3 = $cnx->query($consulta3);
        $sql_query3->read();
        while ($sql_query3->next()) {
            $beneficio = $sql_query3->field("$campoNombre");
            array_push($beneficioArray, array('name' => $beneficio));
        }
        //tipo cocina
        $consulta4 = "SELECT b.$campoNombre FROM plato_tipo_cocina a INNER JOIN tipo_cocina b ON a.id_tipo_cocina = b.id_tipo_cocina WHERE a.id_plato = $idPlato ORDER BY 1";
        $sql_query4 = $cnx->query($consulta4);
        $sql_query4->read();
        while ($sql_query4->next()) {
            $tipoCocina = $sql_query4->field("$campoNombre");
            array_push($cuisineArray, array('name' => $tipoCocina));
        }
        //tipo plato
        $consulta5 = "SELECT b.$campoNombre FROM plato_tipo_plato a INNER JOIN tipo_plato b ON a.id_tipo_plato = b.id_tipo_plato WHERE a.id_plato = $idPlato ORDER BY 1";
        $sql_query5 = $cnx->query($consulta5);
        $sql_query5->read();
        while ($sql_query5->next()) {
            $tipoPlato = $sql_query5->field("$campoNombre");
            array_push($dishtypeArray, array('name' => $tipoPlato));
        }
        //favorito
        if ($user != "") {
            $consulta6 = "SELECT id_plato FROM usuario_plato WHERE estado = 1 AND id_usuario = $user  AND id_plato = $idPlato" ;
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

        $platoArray = array('id' => $idPlato, 'name' => $nombre, 'image_url' => $imagen, 'instructions' => $preparacion, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_RECIPE_IMAGE_SET, 'favorite' => $favorito, 'servings' => $porcion, 'like' => $like, 'credit' => $credito, 'time' => $tiempo, 'cuisine' => $cuisineArray, 'dishtype' => $dishtypeArray, 'benefit' => $beneficioArray, 'nutritional' => $kcal, 'fat' => $grasa, 'price' => $precio, 'country' => $pais, 'region' => $region, 'difficulty' => $dificultad, 'dish_type' => $tipoPlato_, 'diet_type' => $tipoDieta_, 'ingredients' => $ingredienteArray);

        array_push($arregloData, $platoArray);
    }

    $cnx = null;
    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
