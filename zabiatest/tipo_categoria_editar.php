<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/mysql.php');
    require('inc/dao_nutriente.php');

    if (isset($_POST["tipo_categoria"])) {
        $idTipoNutriente= $_POST["tipo_nutriente"];
        $idTipoClase= $_POST["tipo_clase"];
        $idTipoCategoria= $_POST["tipo_categoria"];
        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
            if ($estado == "") {
                $estado = "0";
            }
        } else {
            $estado = "0";
        }
        $usuario = $_SESSION["U"];

        $daoNutriente = new DaoNutriente();
        $resultado = $daoNutriente->editarTipoCategoria($idTipoCategoria, $nombre, $nombreIngles, $estado, $usuario);
        $daoNutriente = null;

        header("Location: tipo_categoria.php?tn=$idTipoNutriente&tcla=$idTipoClase");
        die();
    }

    if (isset($_GET["id"])) {
        $idTipoCategoria= $_GET["id"];
        $idTipoClase= $_GET["tcla"];
        $idTipoNutriente= $_GET["tn"];
        if ($idTipoCategoria!= "" && $idTipoCategoria!= "0") {
            $daoNutriente = new DaoNutriente();
            $arreglo = $daoNutriente->obtenerCategoriaNutriente($idTipoCategoria);
            if (count($arreglo) > 0) {
                $nombre = $arreglo[0]['nombre'];
                $nombreIngles = $arreglo[0]['nombre_ing'];
                $estado = $arreglo[0]['estado'];
                $usuarioRegistro = $arreglo[0]['usuario_registro'];
                $fechaRegistro = $arreglo[0]['fecha_registro'];
                $usuarioModifica = $arreglo[0]['usuario_modifica'];
                $fechaModifica = $arreglo[0]['fecha_modifica'];
            }
            $daoNutriente = null;
        } else {
            header("Location: tipo_categoria.php?tn=$idTipoNutriente&tcla=$idTipoClase");
            die();
        }
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
                            <h4 class="page-title">Modificaci&oacute;n de Tipo categor&iacute;a</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_categoria_editar.php" method="post" name="forma" id="forma">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" value="<?php echo $nombre ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre [Ingl&eacute;s]</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="64" value="<?php echo $nombreIngles; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="estado" id="estado" <?php if ($estado == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="estado">Activo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Registrado</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo ($usuarioRegistro . ' - ' . $fechaRegistro); ?> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Modificado</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo ($usuarioModifica . ' - ' . $fechaModifica); ?> 
                                    </div>
                                </div>
                                <br />
                                <input type="hidden" name="tipo_nutriente" value="<?php echo $idTipoNutriente; ?>" />
                                <input type="hidden" name="tipo_clase" value="<?php echo $idTipoClase; ?>" />
                                <input type="hidden" name="tipo_categoria" value="<?php echo $idTipoCategoria;?>" />
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
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
                location.href = 'tipo_categoria.php?tn=<?php echo $idTipoNutriente; ?>&tcla=<?php echo $idTipoClase; ?>';
            });

            $('#grabar').click(function() {
                $('#mensaje-nombre-vacio').hide();
                if ($.trim($('#nombre').val()) == '') {
                    $('#mensaje-nombre-vacio').show();
                } else {
                    $('#forma').submit();
                }
            });

        </script>
    </body>
</html>
