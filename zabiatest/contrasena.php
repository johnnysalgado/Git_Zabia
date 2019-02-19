<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $mensaje = "";

    if (isset($_POST["contrasena_nueva"])) {

        $contrasenaNueva = $_POST["contrasena_nueva"];
        $contrasenaNueva = str_replace("'", "", $contrasenaNueva);
        $usuario = $_SESSION["U"];

        $cnx = new MySQL();

        $query = "UPDATE usuario_admin SET contrasena = '" . $contrasenaNueva . "' WHERE correo = '" . $usuario . "';";
        $cnx->insert($query);
    
        $cnx = null;

        $mensaje = "Grabado satisfactoriamente!";
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
                            <h4 class="page-title">Cambiar contrase&ntilde;a</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="contrasena.php" method="post" id="form-contrasena">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contrase&ntilde;a nueva</label>
                                            <input type="password" name="contrasena_nueva" id="contrasena_nueva" class="form-control" maxlength="64" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Confirmaci&oacute;n contrase&ntilde;a</label> 
                                            <input type="password" name="contrasena_confirmar" id="contrasena_confirmar" class="form-control" maxlength="64" value="" />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <?php if ($mensaje != "") { ?>
                                <div class="row alert alert-success">
                                    <?php echo $mensaje; ?>
                                </div>
                                <?php } ?>
                                <div id="divError" class="row alert alert-danger"></div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="btn-cambiar-contrasena" value="Grabar" class="btn btn-success" />
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
        $(document).ready(function () {
            $('#divError').hide();
        });

        $('#btn-cambiar-contrasena').click(function() {
            $('#divError').hide();
            var contrasenaNueva = $('#contrasena_nueva').val();
            var confirmarContrasena = $('#contrasena_confirmar').val();
            if ($.trim(contrasenaNueva) == '') {
                $('#divError').html('La contraseña nueva está vacía');
                $('#divError').show();
            } else {
                if (contrasenaNueva != confirmarContrasena) {
                    $('#divError').html('La confirmación de contraseña es inválida');
                    $('#divError').show();
                } else {
                    $('#form-contrasena').submit();
                }
            }
        });
        </script>
    </body>
</html>