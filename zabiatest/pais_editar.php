<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["cod_pais"])) {

        $codigoPais = $_POST["cod_pais"];
        $nombre = $_POST["pais"];
        $usuario = $_SESSION["U"];
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }
        if ($estado == "") {
            $estado = "0";
        }

        $query = "UPDATE pais SET nombre = '" . $nombre . "', estado = " . $estado . ", fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE cod_pais = '" . $codigoPais . "'";
        $cnx->execute($query);
        header("Location: paises.php");
        die();
    
    } else {

        $codigoPais = $_GET["id"];
        if ($codigoPais != "") {
            $query = "SELECT nombre, estado, usuario_registro, fecha_registro, usuario_modifica, fecha_modifica FROM pais WHERE cod_pais = '" . $codigoPais . "'";
            $sql = $cnx->query($query);
            $sql->read();
            $html = "";
            while($sql->next()) {
                $nombre = $sql->field('nombre');
                $estado = $sql->field('estado');
                $usuarioRegistro = $sql->field('usuario_registro');
                $fechaRegistro = $sql->field('fecha_registro');
                $usuarioModifica = $sql->field('usuario_modifica');
                $fechaModifica = $sql->field('fecha_modifica');
            }
        }
    }

    $cnx = null;
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
                            <h4 class="page-title">Modificaci&oacute;n de Pa&iacute;s</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="pais_editar.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pa&iacute;s</label>
                                            <input type="text" name="pais" id="pais" class="form-control" maxlength="64" value="<?php echo $nombre ?>" />
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
                                <br />
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
                                <input type="hidden" name="cod_pais" value="<?php echo $codigoPais ?>" />
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="submit" id="nuevo-pais" value="Grabar" class="btn btn-success" />
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
        $('#volver').click(function() {
            location.href = 'paises.php';
        });
        </script>
    </body>
</html>