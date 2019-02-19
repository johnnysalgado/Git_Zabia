<?php
    require('inc/sesion.php');
    require('inc/constante_cuestionario.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_usuario.php');

    if (isset($_POST["id_tipo_educacion"])) {

        $idTipoEducacion = $_POST["id_tipo_educacion"];
        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        $usuario = $_SESSION["U"];
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }

        if ($nombre != "") {
            $nombre = str_replace("'", "''", $nombre);
        }
        if ($nombreIngles != "") {
            $nombreIngles = str_replace("'", "''", $nombreIngles);
        }

        $daoUsuario = new DaoUsuario();
        $resultado = $daoUsuario->editarTipoEducacion($idTipoEducacion, $nombre, $nombreIngles, $estado, $usuario);
        $daoUsuario = null;
        header("Location: tipo_educacion.php");
        die();
    
    } else {

        $idTipoEducacion = $_GET["id"];
        $daoUsuario = new DaoUsuario();
        $arreglo = $daoUsuario->obtenerTipoEducacion($idTipoEducacion);
        if (count($arreglo)) {
            $item = $arreglo[0];
            $nombre = $item['nombre'];
            $nombreIngles = $item['nombre_ing'];
            $estado = $item['estado'];
            $usuarioRegistro = $item['usuario_registro'];
            $fechaRegistro = $item['fecha_registro'];
            $usuarioModifica = $item['usuario_modifica'];
            $fechaModifica = $item['fecha_modifica'];
        }
        $daoUsuario = null;
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
                            <h4 class="page-title">Modificaci&oacute;n de Nivel educaci&oacute;n</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_educacion_editar.php" method="post" id="forma">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" value="<?php echo $nombre; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre (Ingl&eacute;s)</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="64" value="<?php echo $nombreIngles; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <br/>
                                                <input type="checkbox" name="estado" id="estado" <?php if ($estado == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="estado">Activo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div id="divError" class="row alert alert-danger"></div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <input type="hidden" id="id_tipo_educacion" name="id_tipo_educacion" value="<?php echo $idTipoEducacion;?>" />
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
        $(document).ready(function () {
            $('#divError').hide();
            $('#nav-tabla').addClass('active');
        });

        $('#volver').click(function() {
            location.href = 'tipo_educacion.php?';
        });

        $("#nuevo").click(function() {
            $('#divError').hide();
            $('#forma').submit();
        });

        </script>
    </body>
</html>