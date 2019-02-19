<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["id_insumo"])) {
        
        $idInsumo = $_POST["id_insumo"];
        $usuario = $_SESSION["U"];
        $unidad = $_POST["unidad"];
        $promedio = $_POST["promedio"];
        $valorMinimo = $_POST["valor_minimo"];
        $valorMaximo = $_POST["valor_maximo"];

        if ($promedio == "") {
            $promedio = 0;
        }
        if ($valorMinimo == "") {
            $valorMinimo = 0;
        }
        if ($valorMaximo == "") {
            $valorMaximo = 0;
        }
        if ($unidad != "") {
            $unidad = str_replace("'", "''", $unidad);
        } 

        $query = "SELECT id_insumo_orac FROM insumo_orac WHERE id_insumo = " . $idInsumo;
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->count() > 0) {
            $consulta = "UPDATE insumo_orac SET unidad = '" . $unidad . "', promedio = " . $promedio . ", minimo = " . $valorMinimo . ", maximo = " . $valorMaximo . ", usuario_modificacion='" . $usuario . "', fecha_modificacion = CURRENT_TIMESTAMP WHERE id_insumo = " . $idInsumo;
        } else {
            $consulta = "INSERT INTO insumo_orac (id_insumo, unidad, promedio, minimo, maximo, usuario_registro) VALUES (" . $idInsumo . ", '" . $unidad . "', " . $promedio . ", " . $valorMinimo . ", " . $valorMaximo . ", '" . $usuario . "')";
        }
        $cnx->execute($consulta);
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
            $nombreInsumo = "";
            $unidad = "";
            $promedio = 0;
            $valorMinimo = 0;
            $valorMaximo = 0;
            $query = "SELECT a.nombre, b.unidad, b.promedio, b.minimo, b.maximo FROM insumo a LEFT OUTER JOIN insumo_orac b ON a.id_insumo = b.id_insumo WHERE a.id_insumo = " . $idInsumo;
            $sql = $cnx->query($query);
            $sql->read();
            if ($sql->next()) {
                $nombreInsumo = $sql->field('nombre');
                $unidad = $sql->field('unidad');
                $promedio = $sql->field('promedio');
                $valorMinimo = $sql->field('minimo');
                $valorMaximo = $sql->field('maximo');
            }
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
                            <h4 class="page-title">Valor ORAC para insumo: <?php echo $nombreInsumo ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="insumo_orac.php" method="post" id="forma-insumo">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unidad</label>
                                            <input type="text" name="unidad" class="form-control" value="<?php echo $unidad; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Promedio</label>
                                            <input type="number" name="promedio" class="form-control" value="<?php echo $promedio; ?>" step="0.0001" min="0" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Valor m&iacute;nimo</label>
                                            <input type="number" name="valor_minimo" class="form-control" value="<?php echo $valorMinimo; ?>" step="0.0001" min="0" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Valor M&aacute;ximo</label>
                                            <input type="number" name="valor_maximo" class="form-control" value="<?php echo $valorMaximo; ?>" step="0.0001" min="0" />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <input type="hidden" name="id_insumo" value="<?php echo $idInsumo ?>" />
                                <div class="row">
                                    <div class="col-md-6 alert alert-info">
                                        * Ning&uacute;n cambio har&aacute; efecto a menos que se de clic en "Grabar".
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="submit" id="grabar-insumo-orac" value="Grabar" class="btn btn-success" />
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
