<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante_insumo.php');
    require('inc/dao_insumo.php');

    $cnx = new MySQL();

    if (isset($_POST["id_insumo"])) {
        
        set_time_limit(2000);
        $idInsumo = $_POST["id_insumo"];
        $usuario = $_SESSION["U"];

        $daoInsumo = new DaoInsumo();
        $arregloMedida = $daoInsumo->listarInsumoMedida($idInsumo);
        foreach ($arregloMedida as $item) {
            $idInsumoMedida = $item['id_insumo_medida'];
            $secuencia = $_POST["secuencia_$idInsumoMedida"];
            $unidad = str_replace("'", "''", $_POST["unidad_$idInsumoMedida"]);
            $unidadIngles = str_replace("'", "''", $_POST["unidading_$idInsumoMedida"]);
            $daoInsumo->editarInsumoMedida($idInsumoMedida, $secuencia, $unidad, $unidadIngles, $usuario);
        }
        $daoInsumo = null;
/*
        foreach($arregloUnidadNoPeso as $unidad) {
            $gramo = $_POST["gramo_" . $unidad];
            $idInsumoMedida = $_POST["id_insumo_medida_" . $unidad];
            if ($idInsumoMedida != "" && $idInsumoMedida > 0) {
                if (trim($gramo) != "" && $gramo > 0) {
                    $estado = "1";
                } else {
                    $estado = "0";
                    $gramo = "NULL";
                }
                $query3 = "UPDATE insumo_medida SET estado = " . $estado . ", gramo = " . $gramo . ", usuario_modifica = '" . $usuario . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_insumo = " . $idInsumo . " AND unidad = '" . $unidad . "'";
            } else {
                if (trim($gramo) != "" && $gramo > 0) {
                    $query3 = "INSERT INTO insumo_medida (id_insumo, unidad, gramo, usuario_registro) VALUES (" . $idInsumo . ", '" . $unidad . "', " . $gramo . ", '" . $usuario . "')";
                }
            }
            //echo "query3: " . $query3 . "<hr/>";
            if ($query3 != "") {
                $cnx->execute($query3);
            }
        }
        $cnx = null;
*/
        $_SESSION['INP'] = $_SESSION['INP'];
        $_SESSION['IPS'] = $_SESSION['IPS'];
        $_SESSION['ILP'] = $_SESSION['ILP'];
        header("Location: insumos.php");
        die();
    
    } else {

        $idInsumo = $_GET["id"];

        if ($idInsumo == "" || $idInsumo == "0") {
            $cnx->close();
            $cnx = null;
            header("Location: insumos.php");
            die();
        }

        $query = "SELECT nombre FROM insumo WHERE id_insumo = " . $idInsumo;
        $sql = $cnx->query($query);
        $sql->read();
        $nombreInsumo = "";
        if ($sql->next()) {
            $nombreInsumo = $sql->field('nombre');
        }
        $cnx->close();
        $cnx = null;

        $htmlMedida = "";
        $daoInsumo = new DaoInsumo();
        $arregloMedida = $daoInsumo->listarInsumoMedida($idInsumo);
        foreach ($arregloMedida as $item) {
            $idInsumoMedida = $item['id_insumo_medida'];
            $secuencia = $item['secuencia'];
            $unidad = str_replace("\"", "&quot;", $item['unidad']);
            $unidadIngles = str_replace("\"", "&quot;", $item['unidad_ing']);
            $cantidad = $item['cantidad'];
            $gramo = $item['gramo'];
            $htmlMedida .= "<tr> <td> <input type=\"number\" name=\"secuencia_$idInsumoMedida\" id=\"secuencia_$idInsumoMedida\" value=\"$secuencia\" class=\"col-md-6 col-xs-6\" /> </td> <td> <input type=\"text\" name=\"unidad_$idInsumoMedida\" id=\"unidad_$idInsumoMedida\" value=\"$unidad\" class=\"col-md-12 col-xs-12\" /> </td> <td> <input type=\"text\" name=\"unidading_$idInsumoMedida\" id=\"unidading_$idInsumoMedida\" value=\"$unidadIngles\" class=\"col-md-12 col-xs-12\" /> </td> <td> <label>" . round($cantidad, 4) . " </td> <td> <label>" . round($gramo, 4) . " </label> </td> </tr>";
        }
        $daoInsumo = null;
    }

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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="page-title">Medidas para Insumo: <?php echo $nombreInsumo ?></h4>
                            <!--span>Colocar el peso de la unidad en gramos</span-->
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="insumo_medida.php" method="post" id="forma-insumo">
                                <div class="table-responsive">
                                    <table id="medida-table" class="table table-striped display" style="cursor:pointer;">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;"> Secuencia </th>
                                                <th style="width:30%;"> Unidad </th>
                                                <th style="width:30%;"> Unidad (Ingl&eacute;s) </th>
                                                <th style="width:10%;"> Cantidad </th>
                                                <th style="width:10%;"> Gramo </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $htmlMedida; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <br />
                                <input type="hidden" name="id_insumo" value="<?php echo $idInsumo ?>" />
                                <div class="row">
                                    <div class="col-md-6 alert alert-info">
                                        * Ning&uacute;n cambio har&aacute; efecto a menos que se de clic en "Grabar".
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="submit" id="grabar-insumo-medida" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                            </form>
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
