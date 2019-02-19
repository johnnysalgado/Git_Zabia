<?php
    require('inc/sesion.php');
    require('inc/constante_informe.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["titulo"])) {

        $titulo = $_POST["titulo"];
        $orden = $_POST["orden"];
        $usuario = $_SESSION["U"];

        if ($titulo != "") {
            $titulo = str_replace("'", "''", $titulo);
        }
        if ($orden == "") {
            $orden = "1";
        }

        $query = "INSERT INTO notainforme (titulo, tipo_notainforme, orden, usuario_registro) VALUES ('" . $titulo . "', '" . TIPO_NOTA_SECCION . "', " . $orden . ", '" . $usuario . "');";

        $idSeccion = $cnx->insert($query);

        $cnx = null;
        header("Location: informesecciones.php");
        die();
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
                            <h4 class="page-title">Creaci&oacute;n de Secci&oacute;n de Informe</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="informeseccion_crear.php" method="post" id="forma-seccion">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>T&iacute;tulo</label>
                                            <input type="text" name="titulo" id="titulo" class="form-control" maxlength="256" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>N&deg; orden</label>
                                            <input type="number" name="orden" id="orden" value="" class="form-control" step="1" min="0" />
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
            $('#nav-notainforme').addClass('active');
        });

        $('#volver').click(function() {
            location.href = 'informesecciones.php?';
        });

        $("#nueva-seccion").click(function() {
            $('#divError').hide();
            if ($.trim($('#titulo').val()) == '') {
                $('#divError').html('El Título no deben estar vacíos').show();
            } else {
                $(this).attr('disabled','disabled');
                $('#forma-seccion').submit();
            }
        });
        </script>
    </body>
</html>