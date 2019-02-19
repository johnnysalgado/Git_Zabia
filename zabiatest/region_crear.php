<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    if (isset($_POST["cod_pais"])) {

        $cnx = new MySQL();

        $codigoPais = $_POST["cod_pais"];
        $idRegion = $_POST["id_region"];
        $nombre = $_POST["region"];
        $usuario = $_SESSION["U"];
        $query = "INSERT INTO region (nombre, cod_pais, usuario_registro) VALUES ('" . $nombre . "', '" . $codigoPais . "', '" . $usuario . "')";
        $cnx->insert($query);
        $cnx = null;
        header("Location: regiones.php?id=" . $codigoPais);
        die();
    } else {
        $codigoPais = $_GET["cp"];
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
                            <h4 class="page-title">Creaci&oacute;n de Regi&oacute;n</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="region_crear.php" method="post" id="forma-region">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Regi&oacute;n</label>
                                            <input type="text" name="region" id="region" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <input type="hidden" name="cod_pais" value="<?php echo $codigoPais ?>" />
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-region" value="Grabar" class="btn btn-success" />
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
            $('#volver').click(function() {
                location.href = 'regiones.php?id=<?php echo $codigoPais; ?>';
            });

            $('#nuevo-region').click(function() {
                $(this).attr('disabled','disabled');
                $('#forma-region').submit();
            });
        </script>
    </body>
</html>