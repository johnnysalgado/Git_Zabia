<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["tag"])) {
        $nombre = $_POST["tag"];
        $tipoTag = $_POST["tipo_tag"];
        $usuario = $_SESSION["U"];

        if ($nombre != "") {
            $nombre = str_replace("'", "''", $nombre);
        }

        $query = "INSERT INTO tag (nombre, tipo_tag,  usuario_registro) VALUES ('" . $nombre . "', '" . $tipoTag . "', '" . $usuario . "');";
        $idTag = $cnx->insert($query);

        header("Location: tags.php");
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
                            <h4 class="page-title">Creaci&oacute;n de Tag</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tag_crear.php" method="post" id="forma-tag">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tag</label>
                                            <input type="text" name="tag" id="tag" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipo tag</label>
                                            <select name="tipo_tag" id="tipo_tag" class="form-control">
                                                <option value="Libre">Libre</option>
                                                <option value="Síntoma">Síntoma</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-tag" value="Grabar" class="btn btn-success" />
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
                location.href = 'tags.php';
            });

            $('#nuevo-tag').click(function() {
                $(this).attr('disabled','disabled');
                $('#forma-tag').submit();
            });
        </script>
    </body>
</html>