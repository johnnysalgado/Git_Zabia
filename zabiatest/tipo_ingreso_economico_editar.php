<?php
    require('inc/sesion.php');
    require('inc/constante_cuestionario.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_usuario.php');

    if (isset($_POST["id_tipo_ingreso_economico"])) {

        $idTipoIngresoEconomico = $_POST["id_tipo_ingreso_economico"];
        $descripcion = $_POST["descripcion"];
        $orden = $_POST["orden"];
        $usuario = $_SESSION["U"];
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }

        if ($descripcion != "") {
            $descripcion = str_replace("'", "''", $descripcion);
        }

        $daoUsuario = new DaoUsuario();
        $resultado = $daoUsuario->editarTipoIngresoEconomico($idTipoIngresoEconomico, $descripcion, $orden, $estado, $usuario);
        $daoUsuario = null;
        header("Location: tipo_ingreso_economico.php");
        die();
    
    } else {

        $idTipoIngresoEconomico = $_GET["id"];
        $daoUsuario = new DaoUsuario();
        $arreglo = $daoUsuario->obtenerTipoIngresoEconomico($idTipoIngresoEconomico);
        if (count($arreglo)) {
            $item = $arreglo[0];
            $descripcion = $item['descripcion'];
            $orden = $item['orden'];
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
                            <h4 class="page-title">Modificaci&oacute;n de Nivel de ingreso econ&oacute;mico</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_ingreso_economico_editar.php" method="post" id="forma">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="64" value="<?php echo $descripcion; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Orden</label>
                                            <input type="number" name="orden" id="orden" class="form-control" step="1" min="0" value="<?php echo $orden; ?>" />
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
                                <input type="hidden" id="id_tipo_ingreso_economico" name="id_tipo_ingreso_economico" value="<?php echo $idTipoIngresoEconomico;?>" />
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
            location.href = 'tipo_ingreso_economico.php?';
        });

        $("#nuevo").click(function() {
            $('#divError').hide();
            $('#forma').submit();
        });

        </script>
    </body>
</html>