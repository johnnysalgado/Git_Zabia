<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    set_time_limit(0);
    $mensaje = "";
    if (isset($_POST["crear"])) {
        $usuario = $_SESSION["U"];
        $query = "CALL USP_CALC_TABLA_INSUMO_INTOLERANCIA ('$usuario')";
        $cnx = new MySQL();
        $cnx->execute($query);
        $cnx->close();
        $cnx = null;
        header("Location: tabla_insumo_intolerancia.php?m=1");
        die();
    }

    if (isset($_GET["m"])) {
        $mensaje = $_GET["m"];
        if ($mensaje == "1") {
            $mensaje = "Cálculo realizado correctamente";
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="page-title">C&aacute;lculo de Insumo - nutriente - intolerancia</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tabla_insumo_intolerancia.php" method="post" id="forma">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="nuevo" value="Insumo Nutriente Intolerancia" class="btn btn-default" />
                                        <input type="hidden" name="crear" id="crear" />
                                    </div>
                                </div>
                            </form>
                            <br />
                            <div id="mensaje" class="row alert alert-success"><?php echo $mensaje; ?></div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            <?php
                if (trim($mensaje) == "") {
                    echo "$('#mensaje').hide();\n";
                } else {
                    echo "$('#mensaje').show();\n";
                }
            ?>

            $('#nuevo').click(function() {
                $(this).attr('disabled','disabled');
                $('#spiner').show();
                $('#forma').submit();
            });

            $(document).ready(function () {
                $('#nav-table').addClass('active');
            });

        </script>
    </body>
</html>