<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/mysql.php');
    require('inc/dao_insumo.php');
    require('inc/dao_nutriente.php');

    if (isset($_POST["nombre"])) {
        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        $idTipoNutriente = $_POST["tipo_nutriente"];
        $idTipoClase = $_POST["tipo_clase"];
        $idTipoCategoria = $_POST["tipo_categoria"];
        $idTipoFamilia = $_POST["tipo_familia"];
        $usuario = $_SESSION["U"];
        if ($nombre != "") {
            $nombre = str_replace("'", "''", $nombre);
        }

        $daoNutriente = new DaoNutriente();
        $resultado = $daoNutriente->crearTipoSubfamilia($idTipoFamilia, $nombre, $nombreIngles, $usuario);
        $daoNutriente = null;

        header("Location: tipo_subfamilia.php?tn=$idTipoNutriente&tcla=$idTipoClase&tcat=$idTipoCategoria&tfam=$idTipoFamilia");
        die();
    }

    if (isset($_GET["tfam"])) {
        $idTipoNutriente = $_GET["tn"];
        $idTipoClase = $_GET["tcla"];
        $idTipoCategoria = $_GET["tcat"];
        $idTipoFamilia = $_GET["tfam"];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <?php  require('inc/head.php'); ?>
    <body>
        <!-- Preloader -->
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
                            <h4 class="page-title">Creaci&oacute;n de Tipo sub familia</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_subfamilia_crear.php" method="post" name="forma" id="forma">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre ingl&eacute;s</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="64" value="" />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                        <input type="hidden" name="tipo_nutriente" id="tipo_nutriente" value="<?php echo $idTipoNutriente; ?>" />
                                        <input type="hidden" name="tipo_clase" id="tipo_clase" value="<?php echo $idTipoClase; ?>" />
                                        <input type="hidden" name="tipo_categoria" id="tipo_categoria" value="<?php echo $idTipoCategoria; ?>" />
                                        <input type="hidden" name="tipo_familia" id="tipo_familia" value="<?php echo $idTipoFamilia; ?>" />
                                    </div>
                                </div>
                                <div id="mensaje-nombre-vacio" class="row alert alert-danger">* El nombre no debe estar vac&iacute;o</div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#nav-table').addClass('active');
                $('#mensaje-nombre-vacio').hide();
            });

            $('#volver').click(function() {
                location.href = 'tipo_familia.php?tn=<?php echo $idTipoNutriente; ?>&tcla=<?php echo $idTipoClase; ?>&tcat=<?php echo $idTipoCategoria; ?>&tfam=<?php echo $idTipoFamilia; ?>';
            });

            $('#grabar').click(function() {
                $('#mensaje-nombre-vacio').hide();
                if ($.trim($('#nombre').val()) == '') {
                    $('#mensaje-nombre-vacio').show();
                } else {
                    $(this).attr('disabled','disabled');
                    $('#forma').submit();
                }
            });

        </script>
    </body>
</html>
