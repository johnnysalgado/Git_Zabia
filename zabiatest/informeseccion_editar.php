<?php
    require('inc/sesion.php');
    require('inc/constante_informe.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["id_seccion"])) {

        $idSeccion = $_POST["id_seccion"];
        $titulo = $_POST["titulo"];
        $orden = $_POST["orden"];
        $usuario = $_SESSION["U"];
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }
        if ($orden == "") {
            $orden = "1";
        }
        if ($titulo != "") {
            $titulo = str_replace("'", "''", $titulo);
        }

        $query = "UPDATE notainforme SET titulo = '" . $titulo . "', orden = " . $orden . ", estado = " . $estado . ", usuario_modifica = '" . $usuario . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_notainforme = " . $idSeccion;

        $cnx->execute($query);

        $cnx = null;
        header("Location: informesecciones.php");
        die();
    
    } else {
        $idSeccion = $_GET["id"];
        $query = "SELECT titulo, orden, fecha_registro, usuario_registro, fecha_modifica, usuario_modifica, estado FROM notainforme WHERE id_notainforme = " . $idSeccion;
        $sql = $cnx->query($query);
        $sql->read();
        while($sql->next()) {
            $titulo = $sql->field('titulo');
            $orden = $sql->field('orden');
            $estado = $sql->field('estado');
            $usuarioRegistro = $sql->field('usuario_registro');
            $fechaRegistro = $sql->field('fecha_registro');
            $usuarioModifica = $sql->field('usuario_modifica');
            $fechaModifica = $sql->field('fecha_modifica');
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
                            <h4 class="page-title">Modificaci&oacute;n de Secci&oacute;n de Informe</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="informeseccion_editar.php" method="post" id="forma-informe">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>T&iacute;tulo</label>
                                            <input type="text" name="titulo" id="titulo" class="form-control" maxlength="256" value="<?php echo $titulo; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>N&deg; orden</label>
                                            <input type="number" name="orden" id="orden"  value="<?php echo $orden; ?>" class="form-control" step="1" min="0" />
                                        </div>
                                    </div>
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
                                        <input type="button" id="nueva-seccion" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <input type="hidden" id="id_seccion" name="id_seccion" value="<?php echo $idSeccion;?>" />
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
            $('#nav-informe').addClass('active');
        });

        $('#volver').click(function() {
            location.href = 'informesecciones.php';
        });

        $("#nueva-seccion").click(function() {
            $('#divError').hide();
            if ($.trim($('#titulo').val()) == '') {
                $('#divError').html('El Título no debe estar vacíos').show();
            } else {
                $('#forma-informe').submit();
            }
        });
        </script>
    </body>
</html>