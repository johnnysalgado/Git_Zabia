<?php
    require('inc/sesion.php');
    require('inc/constante.php');
    require('inc/constante_receta.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/equivalencia.php');
    
    set_time_limit(500);
    $cnx = new MySQL();

    $idPlato = $_GET["id"];

    $listaIdBeneficio = array();
    
    $query = "SELECT porcion FROM plato WHERE id_plato = $idPlato";
    $porcion = 1;
    $sql = $cnx->query($query);
    $sql->read();
    if($sql->next()) {
        $porcion = $sql->field('porcion');
    }

    $query = "SELECT a.id_nutriente, a.nutriente, SUM(a.cantidad) AS cantidad, a.unidad, a.cota_inferior, a.cota_superior, a.aporte FROM ( SELECT c.id_nutriente, c.nombre as nutriente, b.unidad, (a.gramo / 100) * b.cantidad AS cantidad, c.cota_inferior, c.cota_superior, c.aporte FROM ( SELECT a.id_insumo, SUM(a.gramo_porcion) gramo FROM ( SELECT a.id_insumo, CASE WHEN d.gramo IS NOT NULL THEN (d.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END ELSE (c.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END END AS gramo_porcion FROM plato_insumo a INNER JOIN plato b ON a.id_plato = b.id_plato LEFT OUTER JOIN insumo_medida c ON a.id_insumo = c.id_insumo AND a.unidad = c.unidad AND c.estado = 1 LEFT OUTER JOIN equivalencia d ON a.unidad = d.unidad AND d.estado = 1 WHERE a.id_plato = " . $idPlato . " AND a.estado = 1 AND a.id_insumo IS NOT NULL ) a GROUP BY a.id_insumo ) a INNER JOIN insumo_nutriente b ON a.id_insumo = b.id_insumo INNER JOIN nutriente c ON b.id_nutriente = c.id_nutriente WHERE b.estado = 1 AND (c.aporte='positivo' OR c.aporte='ambos') )  a GROUP BY nutriente, unidad ORDER BY nutriente";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $nutriente = $sql->field('nutriente');
        $idNutriente = $sql->field('id_nutriente');
        $cantidad = $sql->field('cantidad');
        $unidad = $sql->field('unidad');
        $cotaInferior = $sql->field('cota_inferior');
        $cotaSuperior = $sql->field('cota_superior');
        $aporte = $sql->field('aporte');
        if ($cantidad > $cotaInferior) {
            if ( ($cotaSuperior > 0 && $cantidad > $cotaSuperior) || ($cotaSuperior == 0) || ($cotaSuperior > 0 && $cantidad < $cotaSuperior && $aporte=='ambos')  ) {
                $query = "SELECT b.nombre AS beneficio, b.id_beneficio FROM nutriente_beneficio a INNER JOIN beneficio b ON a.id_beneficio = b.id_beneficio WHERE a.id_nutriente = " . $idNutriente . " AND b.estado = 1 ORDER BY beneficio";
                $sql2 = $cnx->query($query);
                $sql2->read();
                while ($sql2->next()) {
                    $idBeneficio = trim($sql2->field('id_beneficio'));
                    if (!in_array($idBeneficio, $listaIdBeneficio)) {
                        array_push($listaIdBeneficio, $idBeneficio);
                    }
                }
            }
        }
    }

    $query = "SELECT a.id_nutriente, a.nutriente, SUM(a.cantidad) AS cantidad, a.unidad, a.cota_inferior, a.cota_superior, a.aporte FROM ( SELECT c.id_nutriente, c.nombre as nutriente, b.unidad, (a.gramo / 100) * b.cantidad AS cantidad, c.cota_inferior, c.cota_superior, c.aporte FROM ( SELECT a.id_insumo, SUM(a.gramo_porcion) gramo FROM ( SELECT a.id_insumo, CASE WHEN d.gramo IS NOT NULL THEN (d.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END ELSE (c.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END END AS gramo_porcion FROM plato_insumo a INNER JOIN plato b ON a.id_plato = b.id_plato LEFT OUTER JOIN insumo_medida c ON a.id_insumo = c.id_insumo AND a.unidad = c.unidad AND c.estado = 1 LEFT OUTER JOIN equivalencia d ON a.unidad = d.unidad AND d.estado = 1 WHERE a.id_plato = " . $idPlato . " AND a.estado = 1  AND a.id_insumo IS NOT NULL ) a GROUP BY a.id_insumo ) a INNER JOIN insumo_nutriente b ON a.id_insumo = b.id_insumo INNER JOIN nutriente c ON b.id_nutriente = c.id_nutriente WHERE b.estado = 1 AND (c.aporte='negativo' OR c.aporte='ambos')) a GROUP BY nutriente, unidad ORDER BY nutriente";
    
    $sql = $cnx->query($query);
    $sql->read();
    $idNutriente = 0;
    while($sql->next()) {
        $nutriente = $sql->field('nutriente');
        $idNutriente = $sql->field('id_nutriente');
        $cantidad = $sql->field('cantidad');
        $unidad = $sql->field('unidad');
        $cotaInferior = $sql->field('cota_inferior');
        $cotaSuperior = $sql->field('cota_superior');
        //grabar caloría
        if ($idNutriente == CALORIA_ID) {
            if ($cantidad == '') {
                $cantidad = 0;
            }
            $cnx->insert('UPDATE plato SET kcal = ' . $cantidad . ' WHERE id_plato = ' . $idPlato);
        }
        //grabar grasa
        if ($idNutriente == GRASA_ID) {
            if ($cantidad == '') {
                $cantidad = 0;
            }
            $cnx->insert('UPDATE plato SET grasa = ' . $cantidad . ' WHERE id_plato = ' . $idPlato);
        }
        if ( $cotaSuperior > 0 && $cantidad > $cotaSuperior ) {
            $query = "SELECT b.nombre AS beneficio, b.id_beneficio FROM nutriente_beneficio a INNER JOIN beneficio b ON a.id_beneficio = b.id_beneficio WHERE a.id_nutriente = " . $idNutriente . " AND b.estado = 1 ORDER BY beneficio";
            $sql2 = $cnx->query($query);
            $sql2->read();
            while ($sql2->next()) {
                $idBeneficio = trim($sql2->field('id_beneficio'));
                if (($key = array_search($idBeneficio, $listaIdBeneficio)) !== false) {
                    unset($listaIdBeneficio[$key]);
                }
            }
        }
    }
    
    //delete beneficio plato
    $cnx->execute('DELETE FROM plato_beneficio WHERE id_plato = '. $idPlato);
    //graba el beneficio - plato    
    foreach ($listaIdBeneficio as $idBeneficio) {
        $cnx->insert('INSERT INTO plato_beneficio (id_plato, id_beneficio) VALUES (' . $idPlato . ', ' . $idBeneficio . ')');
    }

    //calcular precio
    $query = "SELECT a.cantidad, a.unidad, CASE WHEN c.gramo IS NULL THEN b.gramo ELSE c.gramo END AS gramo, d.precio FROM plato_insumo a LEFT OUTER JOIN insumo_medida b ON a.id_insumo = b.id_insumo AND a.unidad = b.unidad AND b.estado = 1 LEFT OUTER JOIN equivalencia c ON a.unidad = c.unidad AND c.estado = 1 LEFT OUTER JOIN insumo_precio d ON a.id_insumo = d.id_insumo AND d.estado = 1 WHERE a.estado = 1 AND id_plato = " . $idPlato . "";

    //echo $query;
    $sql = $cnx->query($query);
    $sql->read();
    $htmlIngrediente = "";
    $precioPlato = 0;
    while($sql->next()) {
        $cantidad = $sql->field('cantidad');
        $unidad = $sql->field('unidad');
        $gramo = $sql->field('gramo');
        $precio = $sql->field('precio');

        if ($porcion == 0) {
            $porcion = 1;
        }
        $gramoTotal = $cantidad * $gramo;
        $gramoPorcion = $gramoTotal / $porcion;
        $precioInsumo = $gramoTotal * $precio;
        $precioPlato += $precioInsumo;
    }
    $cnx->execute('UPDATE plato SET precio = ' . $precioPlato . ' WHERE id_plato = '. $idPlato);

    $cnx = null;

    header("Location: recetas.php");
    die();
?>
