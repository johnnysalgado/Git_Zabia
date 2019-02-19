<?php
    require('inc/sesion.php');
    require('inc/constante_cuestionario.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');


    if (isset($_POST["nombre"])) {

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

        $query = "INSERT INTO tipo_dieta (nombre, nombre_ing, usuario_registro) VALUES ('$nombre', '$nombreIngles', '$usuario')";
        $cnx = new MySQL();
        $idTipoDieta = $cnx->insert($query);
        $cnx->close();
        $cnx = null;
        
        header("Location: tipo_dieta.php?");
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
                            <h4 class="page-title">Creaci&oacute;n de Tipo de dieta</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_dieta_crear.php" method="post" id="forma-tipo-dieta">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre (Ingl&eacute;s)</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div id="divError" class="row alert alert-danger"></div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-tipo-dieta" value="Grabar" class="btn btn-success" />
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
            location.href = 'tipo_dieta.php?';
        });

        $("#nuevo-tipo-dieta").click(function() {
            $('#divError').hide();
            $(this).attr('disabled','disabled');
            $('#forma-tipo-dieta').submit();
        });

        </script>
    </body>
</html>