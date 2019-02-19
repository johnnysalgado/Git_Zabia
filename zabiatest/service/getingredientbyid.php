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
require('../inc/dao_insumo.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $idIngredient = $request->ingredient;
    $idUsuario = $request->user;
    $lenguaje = $request->language;
    
    if (trim($lenguaje) == "") {
        $lenguaje = LENGUAJE_ESPANOL;
    }

    if ($lenguaje == LENGUAJE_INGLES) {
        $mensajeRojo = TEXTO_SEMA_ROJO_ING;
        $mensajeAmarillo = TEXTO_SEMA_AMARILLO_ING;
        $mensajeAzul = TEXTO_SEMA_AZUL_ING;
        $mensajeVerde = TEXTO_SEMA_VERDE_ING;
        $nombreCampo = "nombre_ing";
    } else {
        $mensajeRojo = TEXTO_SEMA_ROJO;
        $mensajeAmarillo = TEXTO_SEMA_AMARILLO;
        $mensajeAzul = TEXTO_SEMA_AZUL;
        $mensajeVerde = TEXTO_SEMA_VERDE;
        $nombreCampo = "nombre";
    }

    $consulta = "";
    $arregloData = array();
    $servingArray = array();
    $caloriaArray = array();
    $nutriente1Array = array();
    $nutriente2Array = array();
    $nutrienteFactArray = array();
    $resumenArray = array();
    $caloriaPorGramaArray = array();
    $beneficioArray = array();
    $noBeneficioArray = array();
    $oracArray = array();
    $complementoArray = array();
    $semaforoInsumoUsuarioArray = array();
    $detalleInsumoUsuarioArray = array();
    
    if ($idIngredient != "") {

        $daoInsumo = new DaoInsumo;
        $arregloInsumo = $daoInsumo-> obtenerInsumoResumen($idIngredient);
        if (count($arregloInsumo) > 0) {
            $idInsumo = $arregloInsumo[0]['id_insumo'];
            $nombre = $arregloInsumo[0]['foodNameSPA'];
            $nombreIngles = $arregloInsumo[0]['foodName'];
            $tipoAlimento = $arregloInsumo[0]['foodTypeSPA'];
            $tipoAlimentoIngles = $arregloInsumo[0]['foodType'];
            $flagSuperfood = 0;
            $imagen = $arregloInsumo[0]['imagen'];
            $densidad = round($arregloInsumo[0]['density'], 0);
            $nombreImagen = $imagen;
            $setImagenURL = "";
            if ($imagen != "") {
                $imagen = BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imagen;
                $setImagenURL = BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_SMALL . "/" . PREFIX_IMAGE_SMALL . "_$nombreImagen " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_MEDIUM . "/" . PREFIX_IMAGE_MEDIUM . "_$nombreImagen " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . PREFIX_IMAGE_LARGE . "/" . PREFIX_IMAGE_LARGE . "_$nombreImagen " . WIDTH_IMAGE_LARGE;
            } else {
                $imagen = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg";
                $setImagenURL = BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_SMALL . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_MEDIUM . ", " . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_204.jpeg " . WIDTH_IMAGE_LARGE;
            }

            $arregloServing = $daoInsumo->obtenInsumoMedidaPorSecuencia($idInsumo, 1);
            if (count($arregloServing) > 0) {
                $servingItem = $arregloServing[0];
                $servingCantidad = round($servingItem["cantidad"], 0);
                $servingGramo = round($servingItem["gramo"], 0);
                $servingGramoTotal = $servingCantidad == 0 ? 0 : round($servingGramo / $servingCantidad, 0) ;
                if ($lenguaje == LENGUAJE_INGLES) {
                    $servingTitulo = "Serving size";
                    $servingTitulo2 = "Servings Per Container";
                    $servingDescripcion = $servingItem["unidad_ing"];
                } else {
                    $servingTitulo = "Tamaño de la porción";
                    $servingTitulo2 = "Porciones por contenido";
                    $servingDescripcion = $servingItem["unidad"];
                }
                array_push($servingArray, array("description" => $servingTitulo, "amount" => "1 $servingDescripcion ($servingGramoTotal"."g)"));
                array_push($servingArray, array("description" => $servingTitulo2, "amount" => $servingCantidad));
            }

            $nombrePrincipal =  $nombre;
            $nombreAlterno = $nombreIngles;
            $tipoAlimentoPrincipal = $tipoAlimento;
            if ($lenguaje == LENGUAJE_INGLES) {
                $nombrePrincipal = $nombreIngles;
                $nombreAlterno = $nombre;
                $tipoAlimentoPrincipal = $tipoAlimentoIngles;
            }

            $caloriaDeGrasa = 0;
            //nutrientes del segundo orden de nut label: nutrientes parte arriba :: 2
            $contadorNutriente = 0;
            $arregloNutriente = $daoInsumo->listarInsumoNutritionLabel($idIngredient, 2, $lenguaje);
            $cantidadNutriente = count($arregloNutriente);
            foreach ($arregloNutriente as $item ) {
                $idNutriente = $item['id_nutriente'];
                $cantidad = round($item['nutrientServingAmount'], 0);;
                $unidad = $item['nutrientUnit'];
                $dv2000 = round($item['dv2000'], 0);
                $dv2500 = round($item['dv2500'], 0);
                $porcentajeDv = round($item['dvPercent'], 0);
                $caloriaPorGramo = round($item['calorieByGram'], 0);
                $indentado = $item['labelIndent'];
                $esGrasa = $item['isFat'];
                $contadorNutriente++;
                if ($contadorNutriente == $cantidadNutriente) {
                    $last = 1;
                } else {
                    $last = 0;
                }
                if ($lenguaje == LENGUAJE_INGLES) {
                    $nutriente = $item['nutrient'];
                    $dvRelacion = $item['dvRelation'];
                } else {
                    $nutriente = $item['nutrientSPA'];
                    $dvRelacion = $item['dvRelation'];
                }
                if ($dv2000 == 0) {
                    $porcentajeDv = "";
                } else {
                    if ($cantidad == 0) {
                        $porcentajeDv = "0%";
                    } else {
                        $porcentajeDv = "$porcentajeDv%";
                    }
                }
                array_push($nutriente1Array, array('nutrienteID' => $idNutriente, 'description' => $nutriente, 'amount' => "$cantidad$unidad", 'percent' => $porcentajeDv, 'multilevel' => $indentado, 'last' => $last));
                if ($dv2000 > 0 && $dv2500 > 0) {
                    array_push($resumenArray, array('nutrienteID' => $idNutriente, 'title' => $nutriente, 'description' => $dvRelacion, 'amount1' => "$dv2000$unidad", 'amount2' => "$dv2500$unidad", 'multilevel' => $indentado));
                }
                if ($caloriaPorGramo > 0) {
                    array_push($caloriaPorGramaArray, array('nutrienteID' => $idNutriente, 'description' => $nutriente, 'gram' => $caloriaPorGramo));
                }
                if ($esGrasa == 1) {
                    $caloriaDeGrasa = $caloriaPorGramo * $cantidad;
                }
            }

            //nutrientes del segundo orden de nut label: Otros nutrientes:: 3
            $arregloNutriente = $daoInsumo->listarInsumoNutritionLabel($idIngredient, 3, $lenguaje);
            foreach ($arregloNutriente as $item ) {
                $idNutriente = $item['id_nutriente'];
                $nutriente = $item['nutrientSPA'];
                $nutrienteIngles = $item['nutrient'];
                $cantidad = round($item['nutrientServingAmount'], 0);;
                $unidad = $item['nutrientUnit'];
                $porcentajeDv = round($item['dvPercent'], 0);
                $caloriaPorGramo = round($item['calorieByGram'], 0);
                $esGrasa = $item['isFat'];
                if ($lenguaje == LENGUAJE_INGLES) {
                    $nutriente = $nutrienteIngles;
                }
                if ($cantidad == 0) {
                    $porcentajeDv = "0%";
                } else {
                    $porcentajeDv = "$porcentajeDv%";
                }
                array_push($nutriente2Array, array('nutrienteID' => $idNutriente, 'description' => $nutriente, 'percent' => $porcentajeDv));
                if ($caloriaPorGramo > 0) {
                    array_push($caloriaPorGramaArray, array('nutrienteID' => $idNutriente, 'description' => $nutriente, 'gram' => $caloriaPorGramo));
                }
                if ($esGrasa == 1) {
                    $caloriaDeGrasa = $caloriaPorGramo * $cantidad;
                }
            }

            //nutrientes del primer orden de nut label: Calories:: 1
            $arregloNutriente = $daoInsumo->listarInsumoNutritionLabel($idIngredient, 1, $lenguaje);
            if (count($arregloNutriente) > 0 ) {
                $item = $arregloNutriente[0];
                $idNutriente = $item['id_nutriente'];
                $nutriente = $item['nutrientSPA'];
                $nutrienteIngles = $item['nutrient'];
                $cantidad = round($item['nutrientServingAmount'], 0);
                //$unidad = $item['nutrientUnit'];
                $porcentajeDv = round($item['dvPercent'], 0);
                if ($lenguaje == LENGUAJE_INGLES) {
                    $nutriente = $nutrienteIngles;
                }
                array_push($caloriaArray, array('nutrienteID' => $idNutriente, 'description' => $nutriente, 'amount' => $cantidad));
                if ($lenguaje == LENGUAJE_INGLES) {
                    $tituloCaloriaDeGrasa = "Calories from Fat";
                } else {
                    $tituloCaloriaDeGrasa = "Calorías de Grasa";
                }
                array_push($caloriaArray, array('nutrienteID' => 0, 'description' => $tituloCaloriaDeGrasa, 'amount' => $caloriaDeGrasa));
            }

            $daoInsumo = null;

            $cnx = new MySQL();
            //beneficios
            $consulta = "SELECT DISTINCT a.id_beneficio, b.nombre as beneficio, b.nombre_ing FROM nutriente_beneficio a INNER JOIN beneficio b ON a.id_beneficio = b.id_beneficio INNER JOIN nutriente c ON a.id_nutriente = c.id_nutriente WHERE ( a.estado = 1 ) AND ( a.id_nutriente IN (SELECT id_nutriente FROM insumo_nutriente WHERE id_insumo = $idIngredient) ) AND (c.aporte = 'positivo' OR c.aporte = 'ambos') ORDER BY beneficio";
            $sql_query = $cnx->query($consulta);
            $sql_query->read();
            while ($sql_query->next()) {
                $idBeneficio = $sql_query->field('id_beneficio');
                $beneficio = $sql_query->field('beneficio');
                $beneficioIngles = $sql_query->field('nombre_ing');
                if ($lenguaje == LENGUAJE_INGLES) {
                    $beneficio = $beneficioIngles;
                } 
                array_push($beneficioArray, array('benefitID' => $idBeneficio, 'name' => $beneficio));
            }

            //no-beneficios
            $consulta = "SELECT DISTINCT a.id_beneficio, b.nombre as beneficio, b.nombre_ing FROM nutriente_beneficio a INNER JOIN beneficio b ON a.id_beneficio = b.id_beneficio INNER JOIN nutriente c ON a.id_nutriente = c.id_nutriente WHERE ( a.estado = 1 ) AND ( a.id_nutriente IN (SELECT id_nutriente FROM insumo_nutriente WHERE id_insumo = $idIngredient) ) AND (c.aporte = 'negativo' OR c.aporte = 'ambos') ORDER BY beneficio";
            $sql_query2 = $cnx->query($consulta);
            $sql_query2->read();
            while ($sql_query2->next()) {
                $idBeneficio = $sql_query2->field('id_beneficio');
                $beneficio = $sql_query2->field('beneficio');
                $beneficioIngles = $sql_query2->field('nombre_ing');
                if ($lenguaje == LENGUAJE_INGLES) {
                    $beneficio = $beneficioIngles;
                } 
                array_push($noBeneficioArray, array('benefitID' => $idBeneficio, 'name' => $beneficio));
            }

            
            //insumo complemento
            /*
            $consulta = "SELECT id_insumo, beneficio, beneficio_saludable, info_nutricional, estudios, dato_curioso FROM insumo_complemento WHERE id_insumo = " . $idIngredient;
            $sql_query = $cnx->query($consulta);
            $sql_query->read();
            if ($sql_query->next()) {
                $compBeneficio = $sql_query->field('beneficio');
                $compBeneficioSaludable = $sql_query->field('beneficio_saludable');
                $compInfoNutricional = $sql_query->field('info_nutricional');
                $compEstudios = $sql_query->field('estudios');
                $compDatoCurioso = $sql_query->field('dato_curioso');
                $complementoArray = array('benefit' => $compBeneficio, 'healthBenefit' => $compBeneficioSaludable, 'nutritionalInfo' => $compInfoNutricional, 'investigation' => $compEstudios, 'miscellaneous' => $compDatoCurioso);
            }
            */
            $cnx->close();
            $cnx = null;

            //orac
            $daoInsumo = new DaoInsumo();
            $arregloOrac = $daoInsumo->obtenerOracPorInsumo($idIngredient);
            if (count($arregloOrac) > 0) {
                $idInsumoOrac = $arregloOrac[0]['id_insumo_orac'];
                $unidad = $arregloOrac[0]['unidad'];
                $promedio = round($arregloOrac[0]['promedio'], 0);
                $minimo = round($arregloOrac[0]['minimo'], 0);
                $maximo = round($arregloOrac[0]['maximo'], 0);
                $sem = round($arregloOrac[0]['sem'], 0);
                $oracArray = array('oracID' => $idInsumoOrac, 'h_orac' => '', 'l_orac' => '', 'average' => $promedio, 'unit' => $unidad, 'min' => $minimo, 'max' => $maximo, 'sem' => $sem);
            }
            $daoInsumo = null;

            if ($idUsuario != "") {
                $iconoPrecondicionIntoleranciaPrefijoPath = BASE_REMOTE_IMAGE_PATH . ICON_REMOTE_PATH . ICON_PREFIX_PATH;
                $daoInsumo = new DaoInsumo();
                //beneficio
                $arregloBeneficio = $daoInsumo->listarBeneficio();
                //semáforo insumo usuario
                $semaforoInsumoUsuarioArray = $daoInsumo->obtenerInsumoSemaforoPorUsuario($idUsuario, $idIngredient);
                //detalle semáforo insumo usuario
                $detalleInsumoUsuarioArray = $daoInsumo->listarInsumoDetallePorUsuario($idUsuario, $idIngredient, $iconoPrecondicionIntoleranciaPrefijoPath, $lenguaje); 
                $daoInsumo = null;
                //si no trae, entonces el semáforo es verde
                if (count($semaforoInsumoUsuarioArray) == 0) {
                    $icono = BASE_REMOTE_IMAGE_PATH . ICON_REMOTE_PATH  . "icono_verde.png";
                    $descripcion = $mensajeVerde;
                    array_push($semaforoInsumoUsuarioArray, array('action' => 'beneficiar', 'icon' => $icono, 'description' => $descripcion));
                    //por lo tanto los beneficios del insumo se colocan en este detalle
                    foreach ($arregloBeneficio as $item) {
                        array_push($detalleInsumoUsuarioArray, array('userIngredientDetail' => $item["$nombreCampo"] , 'icon' => BASE_REMOTE_IMAGE_PATH . ICON_REMOTE_PATH  . $item['imagen']));
                    }
                } else {
                    $contadorSemaforo = 0;
                    while($contadorSemaforo < count($semaforoInsumoUsuarioArray)) {
                        $accion = $semaforoInsumoUsuarioArray[$contadorSemaforo]["action"];
                        if ($accion == ACCION_ELIMINAR) {
                            $semaforoInsumoUsuarioArray[$contadorSemaforo]["icon"] = BASE_REMOTE_IMAGE_PATH . ICON_REMOTE_PATH  . "icono_rojo.png";
                            $semaforoInsumoUsuarioArray[$contadorSemaforo]["description"] = $mensajeRojo;
                        } else if ($accion == ACCION_RESTRINGIR) {
                            $semaforoInsumoUsuarioArray[$contadorSemaforo]["icon"] = BASE_REMOTE_IMAGE_PATH . ICON_REMOTE_PATH  . "icono_amarillo.png";
                            $semaforoInsumoUsuarioArray[$contadorSemaforo]["description"] = $mensajeAmarillo;
                        } else if ($accion == ACCION_AUMENTAR) {
                            $semaforoInsumoUsuarioArray[$contadorSemaforo]["icon"] = BASE_REMOTE_IMAGE_PATH . ICON_REMOTE_PATH  . "icono_azul.png";
                            $semaforoInsumoUsuarioArray[$contadorSemaforo]["description"] = $mensajeAzul;
                        } else {
                            $semaforoInsumoUsuarioArray[$contadorSemaforo]["icon"] = BASE_REMOTE_IMAGE_PATH . ICON_REMOTE_PATH  . "icono_verde.png";
                            $semaforoInsumoUsuarioArray[$contadorSemaforo]["description"] = $mensajeVerde;
                        }
                        $contadorSemaforo++;
                    }
                }
            }

            if ($lenguaje == LENGUAJE_INGLES) {
                $tituloResumen = "* Percent Daily Values are based on a 2,000 calorie diet. Your daily values may be higher or lower depending on your calorie needs:";
            } else {
                $tituloResumen = "* Los porcentaje de Daily Values se basan en una dieta de 2,000 calorías. Los valores pueden ser mayores o menores dependiendo de sus necesidades calóricas:";
            }

            array_push($nutrienteFactArray, array('serving' => $servingArray, 'calories' => $caloriaArray, 'dailyValue' => $nutriente1Array, 'nutrients' => $nutriente2Array, 'titleSummary' => $tituloResumen, 'summary' => $resumenArray, 'caloriesPerGram' => $caloriaPorGramaArray));

            //arreglo final
            $arregloData = array('ingredientID' => $idInsumo, 'name' => $nombrePrincipal, 'foodType' => $tipoAlimentoPrincipal, 'image' => $imagen, 'imageSet' => $setImagenURL, 'imageSize' => SIZE_IMAGE_SET, 'density' => $densidad, 'nutritionFact' => $nutrienteFactArray, 'benefits' => $beneficioArray, 'noBenefits' => $noBeneficioArray, 'orac' => $oracArray, 'complement' => $complementoArray, 'semaphore' => $semaforoInsumoUsuarioArray, 'semaphoreDetail' => $detalleInsumoUsuarioArray);

            $output = array(
                'status' => '1'
                , 'data' => $arregloData);
            $respuesta = json_encode($output);
            die ($respuesta);
        } else {
            if ($lenguaje == LENGUAJE_INGLES) {
                $mensaje = "Ingredient does not exist";
            } else {
                $mensaje = 'No existe ingrediente';
            }
            $output = array(
                'status' => '0'
                , 'message' => $mensaje
            );
            $respuesta = json_encode($output);
            die ($respuesta);
        }
    } else {
        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "Invalid ID";
        } else {
            $mensaje = 'No ha enviado un id válido';
        }
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
        );
        $respuesta = json_encode($output);
        die ($respuesta);
    }

}
?>
