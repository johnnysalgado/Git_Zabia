<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["id_insumo"])) {
        
        set_time_limit(2000);
        $idInsumo = $_POST["id_insumo"];
        $usuario = $_SESSION["U"];

        $query = "SELECT id_nutriente, unidad FROM nutriente WHERE estado = 1";
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $query3 = "";
            $idNutriente = $sql->field('id_nutriente');
            $unidadNutriente = $sql->field('unidad');
            $cantidad = $_POST["cantidad_" . $idNutriente];
            $idInsumoNutriente = $_POST["id_insumo_nutriente_" . $idNutriente];
            if ($idInsumoNutriente != "" && $idInsumoNutriente > 0) {
                if ($cantidad != "" && $cantidad > 0) {
                    $estado = "1";
                } else {
                    $estado = "0";
                    $cantidad = "NULL";
                    $unidadNutriente = "";
                }
                $query3 = "UPDATE insumo_nutriente SET estado = " . $estado . ", cantidad = " . $cantidad . ", unidad = '" . $unidadNutriente . "', usuario_modifica = '" . $usuario . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_insumo = " . $idInsumo . " AND id_nutriente = " . $idNutriente;
            } else {
                if ($cantidad != "" && $cantidad > 0) {
                    $query3 = "INSERT INTO insumo_nutriente (id_insumo, id_nutriente, unidad, cantidad, usuario_registro) VALUES (" . $idInsumo . ", " . $idNutriente . ", '" . $unidadNutriente . "', " . $cantidad . ", '" . $usuario . "')";
                }
            }
            //echo "query3: " . $query3 . "<hr/>";
            if ($query3 != "") {
                $cnx->execute($query3);
            }
        }

        $cnx = null;
        $_SESSION['INP'] = $_SESSION['INP'];
        $_SESSION['IPS'] = $_SESSION['IPS'];
        $_SESSION['ILP'] = $_SESSION['ILP'];
        header("Location: insumos.php");
        die();
    
    } else {

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

        }
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
                        <div class="col-lg-6 col-md-6 col-sm6 col-xs-12">
                            <h4 class="page-title">Nutriente para Insumo: <?php echo $nombreInsumo ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="insumo_nutriente.php" method="post" id="forma-insumo">
                                <div class="row">
<?php
    $query = "SELECT a.id_nutriente, a.nombre, a.unidad, b.id_insumo_nutriente, b.cantidad, CASE WHEN b.id_insumo_nutriente IS NULL THEN 1 ELSE 0 END AS orden_1 FROM nutriente a LEFT OUTER JOIN insumo_nutriente b ON a.id_nutriente = b.id_nutriente AND b.estado = 1 AND b.id_insumo = " . $idInsumo . " WHERE a.estado = 1 ORDER BY orden_1, a.nombre";
    $contadorNutrienteEnBlanco = 0;
    $sql = $cnx->query($query);
    $sql->read();
    while ($sql->next()) {
        $idNutriente = $sql->field('id_nutriente');
        $nombreNutriente = $sql->field('nombre');
        $unidadNutriente = $sql->field('unidad');
        $idInsumoNutriente = $sql->field('id_insumo_nutriente');
        $cantidad = $sql->field('cantidad');
        if ($idInsumoNutriente != "" && $idInsumoNutriente > 0) {
            $cantidad = round($cantidad, 4);
        } else {
            if ($contadorNutrienteEnBlanco == 0) {
?>
                                </div>
                                <hr />
                                <h4 class="text-danger font-weight-bold">Este insumo no presenta los siguientes nutrientes, actual&iacute;celo solo si hubiera alguna omisi&oacute;n:</h4>
                                <br />
                                <div class="row">
<?php
            }
            $contadorNutrienteEnBlanco = 1;
        }
?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo $nombreNutriente; ?> <?php if ($unidadNutriente != "") { echo "[" . $unidadNutriente . "]"; }?></label>
                                            <input type="number" name="cantidad_<?php echo $idNutriente; ?>" class="form-control" value="<?php echo $cantidad; ?>" step="0.0001" min="0" />
                                            <input type="hidden" name="id_insumo_nutriente_<?php echo $idNutriente; ?>" value="<?php echo $idInsumoNutriente; ?>" />
                                        </div>
                                    </div>
<?php
    }
    $cnx = null;
?>
                                </div>
                                <br />
                                <input type="hidden" name="id_insumo" value="<?php echo $idInsumo ?>" />
                                <div class="row">
                                    <div class="col-md-6 alert alert-info">
                                        * Ning&uacute;n cambio har&aacute; efecto a menos que se de clic en "Grabar".
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="submit" id="grabar-insumo-nutriente" value="Grabar" class="btn btn-success" />
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
