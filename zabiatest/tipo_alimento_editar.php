<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/mysql.php');
    require('inc/dao_insumo.php');

    if (isset($_POST["tipo_alimento"])) {
        $idTipoAlimento= $_POST["tipo_alimento"];
        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        if (isset($_POST["flag_procesar"])) {
            $flagProcesar = $_POST["flag_procesar"];
            if ($flagProcesar == "") {
                $flagProcesar = "0";
            }
        } else {
            $flagProcesar = "0";
        }
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
            if ($estado == "") {
                $estado = "0";
            }
        } else {
            $estado = "0";
        }
        $usuario = $_SESSION["U"];

        $daoInsumo = new DaoInsumo();
        $resultado = $daoInsumo->editarTipoAlimento($idTipoAlimento, $nombre, $nombreIngles, $flagProcesar, $estado, $usuario);
        $daoInsumo = null;

        header("Location: tipo_alimento.php");
        die();
    }

    if (isset($_GET["id"])) {
        $idTipoAlimento = $_GET["id"];
        if ($idTipoAlimento!= "" && $idTipoAlimento!= "0") {
            $daoInsumo = new DaoInsumo();
            $arreglo = $daoInsumo->obtenerTipoAlimento($idTipoAlimento);
            if (count($arreglo) > 0) {
                $nombre = $arreglo[0]['nombre'];
                $nombreIng = $arreglo[0]['nombre_ing'];
                $CodigoExterno = $arreglo[0]['codigo_externo'];
                $FlagProcesar = $arreglo[0]['flag_procesar'];
                $estado = $arreglo[0]['estado'];
                $usuarioRegistro = $arreglo[0]['usuario_registro'];
                $fechaRegistro = $arreglo[0]['fecha_registro'];
                $usuarioModifica = $arreglo[0]['usuario_modifica'];
                $fechaModifica = $arreglo[0]['fecha_modifica'];
            }
            $daoInsumo = null;
        } else {
            header("Location: tipo_alimento.php");
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
                            <h4 class="page-title">Modificaci&oacute;n de Tipo alimento</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_alimento_editar.php" method="post" name="forma" id="forma">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="128" value="<?php echo $nombre ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre ingl&eacute;s</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="128" value="<?php echo $nombreIng ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="flag_procesar" id="flag_procesar" <?php if ($FlagProcesar == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="flag_procesar">H&aacute;bil para procesar</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="estado" id="estado" <?php if ($estado == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="estado">Activo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>C&oacute;digo RSLegacy: </label>
                                        <span><?php echo $CodigoExterno; ?></span>
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
                                <input type="hidden" name="tipo_alimento" value="<?php echo $idTipoAlimento; ?>" />
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
                location.href = 'tipo_alimento.php';
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
