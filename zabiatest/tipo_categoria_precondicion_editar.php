<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante.php');
    require('inc/mysql.php');
    require('inc/dao_enfermedad.php');

    if (isset($_POST["tipo_categoria_precondicion"])) {
        $idTipoCategoriaPrecondicion= $_POST["tipo_categoria_precondicion"];
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

        $daoEnfermedad = new DaoEnfermedad();
        $resultado = $daoEnfermedad->editarTipoCategoriaPrecondicion($idTipoCategoriaPrecondicion, $nombre, $nombreIngles, $estado, $usuario);
        $daoEnfermedad = null;

        header("Location: tipo_categoria_precondicion.php");
        die();
    }

    if (isset($_GET["id"])) {
        $idTipoCategoriaPrecondicion = $_GET["id"];
        if ($idTipoCategoriaPrecondicion != "" && $idTipoCategoriaPrecondicion != "0") {
            $daoEnfermedad = new DaoEnfermedad();
            $arreglo = $daoEnfermedad->obtenerTipoCategoriaPrecondicion($idTipoCategoriaPrecondicion);
            if (count($arreglo) > 0) {
                $nombre = $arreglo[0]['nombre'];
                $nombreIng = $arreglo[0]['nombre_ing'];
                $estado = $arreglo[0]['estado'];
                $usuarioRegistro = $arreglo[0]['usuario_registro'];
                $fechaRegistro = $arreglo[0]['fecha_registro'];
                $usuarioModifica = $arreglo[0]['usuario_modifica'];
                $fechaModifica = $arreglo[0]['fecha_modifica'];
            }
            $daoEnfermedad = null;
        } else {
            header("Location: tipo_categoria_precondicion.php");
            die();
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="page-title">Modificaci&oacute;n de Categor&iacute;a de precondici&oacute;n</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_categoria_precondicion_editar.php" method="post" name="forma" id="forma">
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
                                <input type="hidden" name="tipo_categoria_precondicion" value="<?php echo $idTipoCategoriaPrecondicion; ?>" />
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <div id="mensaje-nombre-vacio" class="row alert alert-danger"></div>
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
                location.href = 'tipo_categoria_precondicion.php';
            });

            $('#grabar').click(function() {
                $('#mensaje-nombre-vacio').hide();
                if ($.trim($('#nombre').val()) == '') {
                    $('#mensaje-nombre-vacio').html('Nombre no puede estar vacío').show();
                } else {
                    if ($.trim($('#nombre_ing').val()) == '') {
                        $('#mensaje-nombre-vacio').html('Nombre inglés no puede estar vacío').show();
                    } else {
                        $('#forma').submit();
                    }
                }
            });

        </script>
    </body>
</html>
