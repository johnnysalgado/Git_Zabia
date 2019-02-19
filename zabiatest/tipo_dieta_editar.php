<?php
    require('inc/sesion.php');
    require('inc/constante_cuestionario.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');


    if (isset($_POST["id_tipo_dieta"])) {

        $idTipoDieta = $_POST["id_tipo_dieta"];
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

        $query = "UPDATE tipo_dieta SET nombre = '$nombre', nombre_ing = '$nombreIngles', estado = $estado, usuario_modifica = '$usuario', fecha_modifica = CURRENT_TIMESTAMP WHERE id_tipo_dieta = $idTipoDieta";
        $cnx = new MySQL();
        $cnx->execute($query);
        $cnx->close();
        $cnx = null;
        header("Location: tipo_dieta.php");
        die();
    
    } else {

        $idTipoDieta = $_GET["id"];
        $query = "SELECT nombre, nombre_ing, estado , fecha_registro, usuario_registro, fecha_modifica, usuario_modifica FROM tipo_dieta WHERE id_tipo_dieta = $idTipoDieta";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            $nombre = $sql->field('nombre');
            $nombreIngles = $sql->field('nombre_ing');
            $estado = $sql->field('estado');
            $usuarioRegistro = $sql->field('usuario_registro');
            $fechaRegistro = $sql->field('fecha_registro');
            $usuarioModifica = $sql->field('usuario_modifica');
            $fechaModifica = $sql->field('fecha_modifica');
        }
        $cnx->close();
        $cnx = null;
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
                            <h4 class="page-title">Modificaci&oacute;n de Tipo de dieta</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_dieta_editar.php" method="post" id="forma-tipo-dieta">
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
                                        <input type="button" id="nuevo-tipo-dieta" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <input type="hidden" id="id_tipo_dieta" name="id_tipo_dieta" value="<?php echo $idTipoDieta;?>" />
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
            location.href = 'tipo_dieta.php?';
        });

        $("#nuevo-tipo-dieta").click(function() {
            $('#divError').hide();
            $('#forma-tipo-dieta').submit();
        });

        </script>
    </body>
</html>