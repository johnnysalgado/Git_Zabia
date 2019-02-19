<?php
    require('inc/sesion.php');
    require('inc/constante.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/equivalencia.php');
    
    $idPlato = $_GET["id"];

    set_time_limit(500);
    $cnx = new MySQL();

    $query = "SELECT nombre, porcion FROM plato WHERE id_plato=" . $idPlato;
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $nombrePlato = $sql->field('nombre');
        $porcion = $sql->field('porcion');
    }
    if ($porcion == '') {
        $porcion = 0;
    }
        
    $query = "SELECT a.id_plato_insumo, a.id_insumo, a.nombre, a.descripcion, a.cantidad, a.unidad, CASE WHEN c.gramo IS NULL THEN b.gramo ELSE c.gramo END AS gramo, d.precio FROM plato_insumo a LEFT OUTER JOIN insumo_medida b ON a.id_insumo = b.id_insumo AND a.unidad = b.unidad AND b.estado = 1 LEFT OUTER JOIN equivalencia c ON a.unidad = c.unidad AND c.estado = 1 LEFT OUTER JOIN insumo_precio d ON a.id_insumo = d.id_insumo AND d.estado = 1 WHERE a.estado = 1 AND a.id_plato = " . $idPlato . "";

    //echo $query;
    $sql = $cnx->query($query);
    $sql->read();
    $i = 0;
    $htmlIngrediente = "";
    $precioPlato = 0;
    while($sql->next()) {
        $i++;
        $id = $sql->field('id_plato_insumo');
        $idInsumo = $sql->field('id_insumo');
        $nombre = $sql->field('nombre');
        $descripcion = $sql->field('descripcion');
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
        
        $htmlIngrediente .= '<tr style="cursor:pointer;" class="insumo">';
        $htmlIngrediente .= '<td>'.$i.'</td>';
        $htmlIngrediente .= '<td>'.$nombre.'</td>';
        $htmlIngrediente .= '<td>'.$descripcion.'</td>';
        $htmlIngrediente .= '<td>'.$cantidad.'</td>';
        $htmlIngrediente .= '<td>'.$unidad.'</td>';
        $htmlIngrediente .= '<td>'.number_format($gramoPorcion, 2).'</td>';
        $htmlIngrediente .= '<td>'.number_format($gramoTotal, 2).'</td>';
        $htmlIngrediente .= '<td>'.number_format($precioInsumo, 4).'</td>';
        $htmlIngrediente .= '</tr>';
        if ($idInsumo > 0) {
            $htmlIngrediente .= '<tr class="nutriente-cantidad-insumo"> <td colspan="8" style="width:100%"> <table class="table-sub">';
            $htmlIngrediente .= '<thead> <th>Nutriente</th> <th>Unidad</th> <th>Cantidad</th> <th>Inferior</th> <th>Superior</th> </thead>';
            $query = "SELECT a.id_nutriente, b.nombre AS nutriente, a.cantidad, b.cota_inferior, b.cota_superior, b.unidad FROM insumo_nutriente a INNER JOIN nutriente b ON a.id_nutriente = b.id_nutriente WHERE a.id_insumo = " . $idInsumo . " ORDER BY nutriente";
            //echo $query;
            $sql2 = $cnx->query($query);
            $sql2->read();
            while($sql2->next()) {
                $idNutriente = $sql2->field('id_nutriente');
                $nutriente = $sql2->field('nutriente');
                $cotaInferior = $sql2->field('cota_inferior');
                $cotaSuperior = $sql2->field('cota_superior');
                $unidadNutriente = $sql2->field('unidad');
                $cantidadNutriente = $sql2->field('cantidad');
                $cantidadNutriente = ($gramoPorcion / 100.00 ) * $cantidadNutriente;
                $htmlIngrediente .= '<tr>';
                $htmlIngrediente .= '<td>' . $nutriente . '</td>';
                $htmlIngrediente .= '<td>' . $unidadNutriente . '</td>';
                $htmlIngrediente .= '<td>' . number_format($cantidadNutriente, 2) . '</td>';
                $htmlIngrediente .= '<td>' . number_format($cotaInferior, 2) . '</td>';
                $htmlIngrediente .= '<td>' . number_format($cotaSuperior, 2) . '</td>';
                $htmlIngrediente .= '</tr>';
            }
            $htmlIngrediente .= '</table> </td> </tr>';
        }
    }

    $listaBeneficio = array();
    $listaIdBeneficio = array();
    
    $query = "SELECT a.id_nutriente, a.nutriente, SUM(a.cantidad) AS cantidad, a.unidad, a.cota_inferior, a.cota_superior, a.aporte FROM ( SELECT c.id_nutriente, c.nombre as nutriente, b.unidad, (a.gramo / 100) * b.cantidad AS cantidad, c.cota_inferior, c.cota_superior, c.aporte FROM ( SELECT a.id_insumo, SUM(a.gramo_porcion) gramo FROM ( SELECT a.id_insumo, CASE WHEN d.gramo IS NOT NULL THEN (d.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END ELSE (c.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END END AS gramo_porcion FROM plato_insumo a INNER JOIN plato b ON a.id_plato = b.id_plato LEFT OUTER JOIN insumo_medida c ON a.id_insumo = c.id_insumo AND a.unidad = c.unidad AND c.estado = 1 LEFT OUTER JOIN equivalencia d ON a.unidad = d.unidad AND d.estado = 1 WHERE a.id_plato = " . $idPlato . " AND a.estado = 1 AND a.id_insumo IS NOT NULL ) a GROUP BY a.id_insumo ) a INNER JOIN insumo_nutriente b ON a.id_insumo = b.id_insumo INNER JOIN nutriente c ON b.id_nutriente = c.id_nutriente WHERE b.estado = 1 AND ( c.aporte='positivo' OR c.aporte='ambos' ) ) a GROUP BY nutriente, unidad ORDER BY nutriente";

    $sql = $cnx->query($query);
    $sql->read();
    $i = 0;
    $htmlNutriente = "";
    while($sql->next()) {
        $i++;
        $nutriente = $sql->field('nutriente');
        $idNutriente = $sql->field('id_nutriente');
        $cantidad = $sql->field('cantidad');
        $unidad = $sql->field('unidad');
        $cotaInferior = $sql->field('cota_inferior');
        $cotaSuperior = $sql->field('cota_superior');
        $aporte = $sql->field('aporte');
        $htmlNutriente .= '<tr style="cursor:pointer;"  class="nutriente">';
        $htmlNutriente .= '<td>'.$i.'</td>';
        $htmlNutriente .= '<td>'.$nutriente.'</td>';
        $htmlNutriente .= '<td>'.number_format($cantidad, 2).'</td>';
        $htmlNutriente .= '<td>'.$unidad.'</td>';
        $htmlNutriente .= '<td>'.number_format($cotaInferior, 2).'</td>';
        $htmlNutriente .= '<td>'.number_format($cotaSuperior, 2).'</td>';
        $htmlNutriente .= '</tr>';

        $htmlNutriente .= '<tr class="insumo-cantidad-nutriente"> <td colspan="6" style="width:100%"> <table class="table-sub">';
        $htmlNutriente .= '<thead> <th>Insumo</th> <th>Unidad</th> <th>Cantidad</th> </thead>';
        $query = "	SELECT c.nombre as insumo, b.unidad, (a.gramo / 100) * b.cantidad AS cantidad FROM ( SELECT a.id_insumo, CASE WHEN d.gramo IS NOT NULL THEN (d.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END ELSE (c.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END END AS gramo	FROM plato_insumo a INNER JOIN plato b ON a.id_plato = b.id_plato LEFT OUTER JOIN insumo_medida c ON a.id_insumo = c.id_insumo AND a.unidad = c.unidad AND c.estado = 1 LEFT OUTER JOIN equivalencia d ON a.unidad = d.unidad AND d.estado = 1 
		WHERE a.id_plato = " . $idPlato . " AND a.estado = 1 AND a.id_insumo IS NOT NULL ) a INNER JOIN insumo_nutriente b ON a.id_insumo = b.id_insumo INNER JOIN insumo c ON a.id_insumo = c.id_insumo WHERE b.estado = 1 AND c.estado = 1 AND b.id_nutriente = " . $idNutriente . " AND a.gramo IS NOT NULL ORDER BY insumo";
        //echo $query;
        $sql2 = $cnx->query($query);
        $sql2->read();
        while($sql2->next()) {
            $htmlNutriente .= '<tr>';
            $htmlNutriente .= '<td>' . $sql2->field('insumo') . '</td>';
            $htmlNutriente .= '<td>' . number_format($sql2->field('cantidad'), 4) . '</td>';
            $htmlNutriente .= '<td>' . $sql2->field('unidad') . '</td>';
            $htmlNutriente .= '</tr>';
        }
        $htmlNutriente .= '</table> </td> </tr>';

        $htmlNutriente .= '<tr>';
        $htmlNutriente .= '<td colspan="6"> <div class="row">';
        if ($cantidad > $cotaInferior) {
            if ( ($cotaSuperior > 0 && $cantidad > $cotaSuperior) || ($cotaSuperior == 0) || ($cotaSuperior > 0 && $cantidad < $cotaSuperior && $aporte=='ambos')  ) {
                $query = "SELECT b.nombre AS beneficio, b.id_beneficio FROM nutriente_beneficio a INNER JOIN beneficio b ON a.id_beneficio = b.id_beneficio WHERE a.id_nutriente = " . $idNutriente . " AND b.estado = 1 ORDER BY beneficio";
                $sql2 = $cnx->query($query);
                $sql2->read();
                while ($sql2->next()) {
                    $beneficio = trim($sql2->field('beneficio'));
                    $idBeneficio = trim($sql2->field('id_beneficio'));
                    $htmlNutriente .= '<div class="col-md-3 col-xs-3"><li> ' . $beneficio . '</li></div>';
                    if (!in_array($beneficio, $listaBeneficio)) {
                        array_push($listaBeneficio, $beneficio);
                        array_push($listaIdBeneficio, $idBeneficio);
                    }
                }
            }
        }
        $htmlNutriente .= '</div> </td>';
        $htmlNutriente .= '</tr>';
    }

    $query = "SELECT a.id_nutriente, a.nutriente, SUM(a.cantidad) AS cantidad, a.unidad, a.cota_inferior, a.cota_superior, a.aporte FROM ( SELECT c.id_nutriente, c.nombre as nutriente, b.unidad, (a.gramo / 100) * b.cantidad AS cantidad, c.cota_inferior, c.cota_superior, c.aporte FROM ( SELECT a.id_insumo, SUM(a.gramo_porcion) gramo FROM ( SELECT a.id_insumo, CASE WHEN d.gramo IS NOT NULL THEN (d.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END ELSE (c.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END END AS gramo_porcion FROM plato_insumo a INNER JOIN plato b ON a.id_plato = b.id_plato LEFT OUTER JOIN insumo_medida c ON a.id_insumo = c.id_insumo AND a.unidad = c.unidad AND c.estado = 1 LEFT OUTER JOIN equivalencia d ON a.unidad = d.unidad AND d.estado = 1 WHERE a.id_plato = " . $idPlato . " AND a.estado = 1 AND a.id_insumo IS NOT NULL ) a GROUP BY a.id_insumo ) a INNER JOIN insumo_nutriente b ON a.id_insumo = b.id_insumo INNER JOIN nutriente c ON b.id_nutriente = c.id_nutriente WHERE b.estado = 1 AND ( c.aporte='negativo' OR c.aporte='ambos' ) ) a GROUP BY nutriente, unidad ORDER BY nutriente";
    //echo $query;
    
    $sql = $cnx->query($query);
    $sql->read();
    $i = 0;
    $htmlAntinutriente = "";
    $idNutriente = 0;
    while($sql->next()) {
        $i++;
        $nutriente = $sql->field('nutriente');
        $idNutriente = $sql->field('id_nutriente');
        $cantidad = $sql->field('cantidad');
        $unidad = $sql->field('unidad');
        $cotaInferior = $sql->field('cota_inferior');
        $cotaSuperior = $sql->field('cota_superior');
        $aporte = $sql->field('aporte');
        $htmlAntinutriente .= '<tr style="cursor:pointer;" class="nutriente">';
        $htmlAntinutriente .= '<td>'.$i.'</td>';
        $htmlAntinutriente .= '<td>'.$nutriente.'</td>';
        $htmlAntinutriente .= '<td>'.number_format($cantidad, 2).'</td>';
        $htmlAntinutriente .= '<td>'.$unidad.'</td>';
        $htmlAntinutriente .= '<td>'.number_format($cotaInferior, 2).'</td>';
        $htmlAntinutriente .= '<td>'.number_format($cotaSuperior, 2).'</td>';
        $htmlAntinutriente .= '</tr>';

        $htmlAntinutriente .= '<tr class="insumo-cantidad-nutriente"> <td colspan="6" style="width:100%"> <table class="table-sub">';
        $htmlAntinutriente .= '<thead> <th>Insumo</th> <th>Unidad</th> <th>Cantidad</th> </thead>';
        $query = "	SELECT c.nombre as insumo, b.unidad, (a.gramo / 100) * b.cantidad AS cantidad FROM ( SELECT a.id_insumo, CASE WHEN d.gramo IS NOT NULL THEN (d.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END ELSE (c.gramo * a.cantidad) / CASE WHEN b.porcion IS NULL THEN 1 ELSE b.porcion END END AS gramo	FROM plato_insumo a INNER JOIN plato b ON a.id_plato = b.id_plato LEFT OUTER JOIN insumo_medida c ON a.id_insumo = c.id_insumo AND a.unidad = c.unidad AND c.estado = 1 LEFT OUTER JOIN equivalencia d ON a.unidad = d.unidad AND d.estado = 1 
		WHERE a.id_plato = " . $idPlato . " AND a.estado = 1 AND a.id_insumo IS NOT NULL ) a INNER JOIN insumo_nutriente b ON a.id_insumo = b.id_insumo INNER JOIN insumo c ON a.id_insumo = c.id_insumo WHERE b.estado = 1 AND c.estado = 1 AND b.id_nutriente = " . $idNutriente . " AND a.gramo IS NOT NULL ORDER BY insumo";
        //echo $query;
        $sql2 = $cnx->query($query);
        $sql2->read();
        while($sql2->next()) {
            $htmlAntinutriente .= '<tr>';
            $htmlAntinutriente .= '<td>' . $sql2->field('insumo') . '</td>';
            $htmlAntinutriente .= '<td>' . number_format($sql2->field('cantidad'), 4) . '</td>';
            $htmlAntinutriente .= '<td>' . $sql2->field('unidad') . '</td>';
            $htmlAntinutriente .= '</tr>';
        }
        $htmlAntinutriente .= '</table> </td> </tr>';

        $htmlAntinutriente .= '<tr>';
        $htmlAntinutriente .= '<td colspan="6"> <div class="row">';

        if ( ($cotaSuperior > 0 && $cantidad >= $cotaSuperior)) {
            $query = "SELECT b.nombre AS beneficio, b.id_beneficio FROM nutriente_beneficio a INNER JOIN beneficio b ON a.id_beneficio = b.id_beneficio WHERE a.id_nutriente = " . $idNutriente . " AND b.estado = 1 ORDER BY beneficio";
            $sql2 = $cnx->query($query);
            $sql2->read();
            while ($sql2->next()) {
                $beneficio = trim($sql2->field('beneficio'));
                $idBeneficio = trim($sql2->field('id_beneficio'));
                $htmlAntinutriente .= '<div class="col-md-3 col-xs-3"><li> ' . $beneficio . '</li></div>';
                if (($key = array_search($beneficio, $listaBeneficio)) !== false) {
                    unset($listaBeneficio[$key]);
                }
                if (($key = array_search($idBeneficio, $listaIdBeneficio)) !== false) {
                    unset($listaIdBeneficio[$key]);
                }
            }
        }
        $htmlAntinutriente .= '</div> </td>';
        $htmlAntinutriente .= '</tr>';
    }
    
    $htmlBeneficio = '';
    $i = 0;
    foreach ($listaBeneficio as $beneficio) {
        $i++;
        $htmlBeneficio .= '<tr>';
        $htmlBeneficio .= '<td>' . $i . '</td>';
        $htmlBeneficio .= '<td>' . $beneficio . '</td>';
        $htmlBeneficio .= '<tr>';
    }
    if ($htmlBeneficio == '') {
        $htmlBeneficio = '<tr><td colspan="2">No hay beneficios para este plato</td></tr>';
    }
    $cnx = null;

?>
<!DOCTYPE html>
<html lang="es">
    <?php  require('inc/head.php'); ?>
    <body>
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>
        <div id="wrapper">
            <!-- Navigation -->
            <?php  require('inc/nav_horizontal.php'); ?>
            <!-- Left navbar-header -->
            <?php  require('inc/nav_vertical.php'); ?>
            <!-- Left navbar-header end -->
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row bg-title">
                        <div class="col-lg-6 col-md-6 col-sm6 col-xs-12">
                            <h4 class="page-title"><?php echo $nombrePlato ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <span>Receta para <?php echo $porcion ?> porciones</span>
                            <hr/>
                            <h5>Ingredientes</h5>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <table class="table table-bordered table-hover table-click">
                                        <thead>
                                            <tr>
                                                <th rowspan="2"> # </th>
                                                <th rowspan="2"> Ingrediente </th>
                                                <th rowspan="2"> Descripción </th>
                                                <th rowspan="2"> Cantidad </th>
                                                <th rowspan="2"> Medida </th>
                                                <th colspan="2"> Gramos </th>
                                                <th rowspan="2"> Precio </th>
                                            </tr>
                                            <tr>
                                                <th>Porci&oacute;n</th>
                                                <th>Plato</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $htmlIngrediente; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">Precio plato</td>
                                                <td > <?php echo number_format($precioPlato, 4) ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                            <h5>Nutrientes</h5>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Nutriente </th>
                                            <th> Cantidad </th>
                                            <th> Unidad </th>
                                            <th> Inferior </th>
                                            <th> Superior </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $htmlNutriente; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                            <h5>Antinutrientes</h5>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Nutriente </th>
                                            <th> Cantidad </th>
                                            <th> Unidad </th>
                                            <th> Inferior </th>
                                            <th> Superior </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $htmlAntinutriente; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                            <h5>Beneficios</h5>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Beneficio </th>
                                            <!--th> (en inglés) </th-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $htmlBeneficio; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
        $(document).ready(function () {
            $('.nutriente-cantidad-insumo').hide();
            $('.insumo-cantidad-nutriente').hide();
        });
        $('.insumo').click(function() {
            if ($(this).next().hasClass('nutriente-cantidad-insumo')) {
                $(this).next().toggle();
            }
        });
        $('.nutriente').click(function() {
            if ($(this).next().hasClass('insumo-cantidad-nutriente')) {
                $(this).next().toggle();
            }
        });
        </script>
    </body>
</html>