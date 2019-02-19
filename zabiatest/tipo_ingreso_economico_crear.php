<?php
    require('inc/sesion.php');
    require('inc/constante_cuestionario.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_usuario.php');

    if (isset($_POST["descripcion"])) {

        $descripcion = $_POST["descripcion"];
        $orden = $_POST["orden"];
        $usuario = $_SESSION["U"];

        if ($descripcion != "") {
            $descripcion = str_replace("'", "''", $descripcion);
        }
        if ($orden == "") {
            $orden = 1;
        }

        $daoUsuario = new DaoUsuario();
        $idTipoIngresoEconomico = $daoUsuario->crearTipoIngresoEconomico($descripcion, $orden, $usuario);
        $daoUsuario = null;
        
        header("Location: tipo_ingreso_economico.php?");
        die();
    
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
                            <h4 class="page-title">Creaci&oacute;n de Nivel de ingreso econ&oacute;mico</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_ingreso_economico_crear.php" method="post" id="forma">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n</label>
                                            <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Orden</label>
                                            <input type="number" name="orden" id="orden" class="form-control" step="1" min="0" />
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
                            </form>
                        </div>
                    </div>
                </div>
                <?php require('inc/footer.php'); ?>
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
            $(this).attr('disabled','disabled');
            $('#forma').submit();
        });
        </script>
    </body>
</html>