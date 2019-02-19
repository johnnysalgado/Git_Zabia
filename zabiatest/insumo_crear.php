<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('constante_dir.php');
    require('inc/constante_insumo.php');
    require('inc/constante_aws.php');
    require('inc/mysql.php');
    require('inc/dao_insumo.php');
    require('Classes/ChilkatS3.php');
    require('Classes/ImageResize.php');
    require('inc/funcion_imagen.php');

    if (isset($_POST["nombre"])) {

        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        $idTipoAlimento = $_POST["tipo_alimento"];
        $flagSuperfood = $_POST["superfood"];
        $precio = $_POST["precio"];
        if ($idTipoAlimento == "") {
            $idTipoAlimento = "NULL";
        }
        $usuario = $_SESSION["U"];

        if ($nombre != "") {
            $nombre = str_replace("'", "''", $nombre);
        }
        if ($nombreIngles != "") {
            $nombreIngles = str_replace("'", "''", $nombreIngles);
        }
        if (trim($precio) == "") {
            $precio = 0;
        }

        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $mt = microtime(true);
            $mt =  $mt * 1000; //microsecs
            $ticks = (string) $mt * 10;
            $nombreImagen = $ticks . basename($_FILES["fileToUpload"]["name"]);
            $target_file = INSUMO_IMAGE_SHORT_PATH . $nombreImagen;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            //crear imágenes en tamaños y pasarlo a AWS S3
            setImageIngredient ($target_file, $nombreImagen);
        }

        $cnx = new MySQL();

        $query = "INSERT INTO insumo (nombre, nombre_ing, id_tipo_alimento, flag_superfood, imagen, usuario_registro) VALUES ('" . $nombre . "', '" . $nombreIngles . "', " . $idTipoAlimento . ", " . $flagSuperfood . ", '" . $nombreImagen . "', '" . $usuario . "')";
        //echo $query;
        $idInsumo = $cnx->insert($query);

        $query = "INSERT INTO insumo_precio (id_insumo, precio, usuario_registro) VALUES (" . $idInsumo . ", " . $precio . ", '" . $usuario . "')";
        $cnx->execute($query);

        $cnx->close();
        $cnx = null;

        $_SESSION['INP'] = $_SESSION['INP'];
        $_SESSION['IPS'] = $_SESSION['IPS'];
        $_SESSION['ILP'] = $_SESSION['ILP'];
        header("Location: insumos.php");
        die();

    } else {

        //tipo alimento
        $query = "SELECT a.nombre, a.id_tipo_alimento FROM tipo_alimento a WHERE ( a.estado = 1 ) ORDER BY nombre";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        $htmlTipoAlimento = "";
        while($sql->next()) {
            $tipo = $sql->field('nombre');
            $idTipo = $sql->field('id_tipo_alimento');
            $htmlTipoAlimento .= '<option value="' . $idTipo . '"> ' . $tipo . ' </option>';
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="page-title">Creaci&oacute;n de Insumo</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="insumo_crear.php" method="post" id="forma-insumo" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="128" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre (Ingl&eacute;s)</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="128" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipo alimento</label>
                                            <select name="tipo_alimento" id="tipo_alimento" class="form-control">
                                                <option value="">[Seleccione]</option>
                                                <?php echo $htmlTipoAlimento; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Superfood</label>
                                            <div class="radio-list">
                                                <label class="radio-inline p-0">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="superfood" id="sf-si" value="1" />
                                                        <label for="top-si">Si</label>
                                                    </div>
                                                </label>
                                                <label class="radio-inline">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="superfood" id="sf-no" value="0" checked="checked" />
                                                        <label for="top-no">No</label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Precio (gramo)</label>
                                            <input type="number" name="precio" id="precio" class="form-control" min="0" step="0.0001" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Imagen</label>
                                            <input type="file" name="fileToUpload" value="" class="form-control" />
                                            </div>
                                    </div>
                                </div>
                                <br />
                                <div id="divError" class="row alert alert-danger"></div>
                                <div class="row">
                                    <div class="col-md-6 alert alert-info">
                                        * Ning&uacute;n cambio har&aacute; efecto a menos que se de clic en "Grabar".
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-insumo" value="Grabar" class="btn btn-success" />
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
            $(document).ready(function() {
                $('#divError').hide();
                $('#nav-ingredient').addClass('active');
            });

            $('#volver').click(function() {
                location.href = 'insumos.php';
            });

            $("#nuevo-insumo").click(function() {
                $('#divError').hide();
                if ($.trim($('#nombre').val()) == '') {
                    $('#divError').html('El Nombre no debe estar vacío.').show();
                } else {
                    $(this).attr('disabled','disabled');
                    $('#forma-insumo').submit();
                }
            });
        </script>
    </body>
</html>
