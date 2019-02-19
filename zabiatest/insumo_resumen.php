<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    $idInsumo = $_GET["id"];

    if ($idInsumo == "" || $idInsumo == "0") {

        $cnx = null;
        header("Location: insumos.php");
        die();

    } else {

        $query = "SELECT nombre FROM insumo WHERE id_insumo = " . $idInsumo;
        $sql = $cnx->query($query);
        $sql->read();
        $nombreInsumo = "";
        if ($sql->next()) {
            $nombreInsumo = $sql->field('nombre');
        }

        //nutrientes
        $htmlNutriente = "";
        $consulta = "SELECT a.id_nutriente, b.nombre as nutriente, a.cantidad, b.unidad, b.cota_inferior, b.cota_superior, b.aporte FROM insumo_nutriente a INNER JOIN nutriente b ON a.id_nutriente = b.id_nutriente WHERE a.estado = 1 AND id_insumo = " . $idInsumo . " ORDER BY nutriente ASC";
        $sql_query = $cnx->query($consulta);
        $sql_query->read();
        while ($sql_query->next()) {
            $idNutriente = $sql_query->field('id_nutriente');
            $nutriente = $sql_query->field('nutriente');
            $cantidad = $sql_query->field('cantidad');
            $unidad = $sql_query->field('unidad');
            $cotaInferior = $sql_query->field('cota_inferior');
            $cotaSuperior = $sql_query->field('cota_superior');
            $aporte = $sql_query->field('aporte');
            $comentario = "";
            $consulta2 = "SELECT b.nombre as beneficio FROM nutriente_beneficio a INNER JOIN beneficio b ON a.id_beneficio = b.id_beneficio WHERE a.estado = 1 AND a.id_nutriente = " . $idNutriente . " ORDER BY beneficio ASC";
            $beneficios = "";
            $sql_query2 = $cnx->query($consulta2);
            $sql_query2->read();
            while ($sql_query2->next()) {
                $beneficios .= $sql_query2->field('beneficio') . ", ";
            }
            if ($beneficios != "" ) {
                $beneficios = substr($beneficios, 0, strlen($beneficios) - 2);
            }
            if ($aporte == APORTE_NEGATIVO || $aporte == APORTE_AMBOS) {
                if ($beneficios != "") {
                    if ($cotaSuperior > 0) {
                        $cantidadLimite = (100 * $cotaSuperior) / $cantidad;
                        $comentario .= "Si la cantidad del insumo es mayor a " . sprintf("%01.2f", $cantidadLimite) . " gramos por porci&oacute;n, entonces elimina los beneficios: " . $beneficios . ".";
                    } else {
                        if ($aporte == APORTE_NEGATIVO) {
                            $comentario .= "Este nutriente elimina directamente los beneficios : " . $beneficios . ".";
                        }
                    }
                }
            } else { //si aporte es positivo.
                if ($beneficios != "") {
                    if ($cotaInferior > 0) {
                        $cantidadLimite = (100 * $cotaInferior) / $cantidad;
                        $comentario .= "Si la cantidad del insumo es mayor a " . sprintf("%01.2f", $cantidadLimite) . " gramos por porci&oacute;n, entonces adiciona los beneficios: " . $beneficios . ".";
                    } else {
                        $comentario .= "Adiciona los beneficias: " . $beneficios . ".";
                    }
                }
            }
            $htmlNutriente .= ' <tr> <td >' . $nutriente . '</td>'
                . ' <td>' .  sprintf("%01.4f", $cantidad) . ' ' . $unidad . '</td>'
                . ' <td>' . sprintf("%01.4f", $cotaInferior) . '</td>'
                . ' <td>' . sprintf("%01.4f", $cotaSuperior) . '</td>'
                . ' <td>' . $comentario . '</td> </tr>';
        }
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
                            <h4 class="page-title">Resumen de <?php echo strtolower($nombreInsumo) ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h5>Cada 100 gramos de <?php echo $nombreInsumo?> aporta los siguientes nutrientes:</h5>
                            <div class="table-responsive">
                                <table id="nutriente-table" class="table  display">
                                    <thead>
                                        <tr>
                                            <th style="width:15%;"> Nutriente </th>
                                            <th style="width:15%;"> Cantidad </th>
                                            <th style="width:15%;"> L&iacute;mite inferior </th>
                                            <th style="width:15%;"> L&iacute;mite superior </th>
                                            <th style="width:40%;"> Comentario </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $htmlNutriente ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr />
                            <input type="hidden" name="id_insumo" value="<?php echo $idInsumo ?>" />
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#nav-ingredient').addClass('active');
            });

            $('#volver').click(function() {
                location.href = 'insumos.php';
            });

        </script>
    </body>
</html>
