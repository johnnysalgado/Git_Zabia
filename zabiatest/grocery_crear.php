<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_comercio.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["tipo_comercio"])) {
        $tipoComercio = $_POST["tipo_comercio"];
        $nombre = $_POST["nombre"];
        $telefono = $_POST["telefono"];
        $direccion = $_POST["direccion"];
        $ciudad = $_POST["ciudad"];
        $latitud = $_POST["latitud"];
        $longitud = $_POST["longitud"];
        $descripcion = $_POST["descripcion"];
        $horario = $_POST["horario"];
        $usuario = $_SESSION["U"];

        $uploadOk = 1;
        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $mt = microtime(true);
            $mt =  $mt*1000; //microsecs
            $ticks = (string)$mt*10;
            $nombreImagen = $ticks . basename($_FILES["fileToUpload"]["name"]);
            $target_file = COMERCIO_IMAGE_SHORT_PATH . $nombreImagen;
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $uploadOk = 1;
                } else {
                    echo "Hay error al subir la imagen.";
                    $uploadOk = 0;
                }
            } else {
                echo "Archivo no es una imagen.";
                $uploadOk = 0;
            }
        }

        if ($_FILES["fileToUpload2"]["tmp_name"] != "") {
            $mt = microtime(true);
            $mt =  $mt*1000; //microsecs
            $ticks = (string)$mt*10;
            $nombreImagenMarker = $ticks . basename($_FILES["fileToUpload2"]["name"]);
            $target_file = COMERCIO_IMAGE_SHORT_PATH . $nombreImagenMarker;
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $check = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
            if($check !== false) {
                move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file);
            }
        } 

        if ($uploadOk == 1) {

            if ($nombre != "") {
                $nombre = str_replace("'", "''", $nombre);
            }
            if ($telefono != "") {
                $telefono = str_replace("'", "''", $telefono);
            }
            if ($direccion != "") {
                $direccion = str_replace("'", "''", $direccion);
            }
            if ($ciudad != "") {
                $ciudad = str_replace("'", "''", $ciudad);
            }
            if ($latitud != "") {
                $latitud = str_replace("'", "''", $latitud);
            }
            if ($longitud != "") {
                $longitud = str_replace("'", "''", $longitud);
            }
            if ($descripcion != "") {
                $descripcion = str_replace("'", "''", $descripcion);
            }
            if ($horario != "") {
                $horario = str_replace("'", "''", $horario);
            }

            $query = "INSERT INTO comercio (tipo_comercio, nombre, telefono, direccion, ciudad, latitud, longitud, descripcion, horario, imagen, imagen_marker, usuario_registro) VALUES ('" . $tipoComercio . "', '" . $nombre . "', '" . $telefono . "', '" . $direccion . "', '" . $ciudad . "', '" . $latitud . "', '" . $longitud . "', '" . $descripcion . "', '" . $horario . "', '" . $nombreImagen . "', '" . $nombreImagenMarker . "', '" . $usuario . "');";

            $idComercio = $cnx->insert($query);

            $cnx = null;
            header("Location: groceries.php");
            die();
        }
    } 
?>
<!DOCTYPE html>
<html lang="en">
    <?php  require('inc/head.php'); ?>
    <body>
        <!-- Preloader -->
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
                            <h4 class="page-title">Creaci&oacute;n de Tienda de Comestible</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="grocery_crear.php" method="post" enctype="multipart/form-data" name="forma_grocery" id="forma-grocery">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre (*)</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="256" />
                                            <input type="hidden" name="tipo_comercio" id="tipo_comercio" value ="<?php echo TIPO_COMERCIO_GROCERY; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tel&eacute;fono</label>
                                            <input type="text" name="telefono" id="telefono" class="form-control" maxlength="128" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n</label>
                                            <textarea name="descripcion" id="descripcion" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Direcci&oacute;n</label>
                                            <input type="text" name="direccion" id="direccion" class="form-control" maxlength="256" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ciudad</label>
                                            <input type="text" name="ciudad" id="ciudad" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Latitud</label>
                                            <input type="text" name="latitud" id="latitud" class="form-control" maxlength="32" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Longitud</label>
                                            <input type="text" name="longitud" id="longitud" class="form-control" maxlength="32" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Horario</label>
                                            <input type="text" name="horario" id="horario" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Subir imagen</label>
                                            <input type="file" name="fileToUpload" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Subir imagen marker (Mapa)</label>
                                            <input type="file" name="fileToUpload2" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-md-6 alert alert-info">
                                        * Ning&uacute;n cambio har&aacute; efecto a menos que se de clic en "Grabar".
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-grocery" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <div id="mensaje-nombre-vacio" class="row alert alert-danger">* El nombre no debe estar vac&iacute;o</div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#nav-place').addClass('active');
                $('#mensaje-nombre-vacio').hide();
            });

            $('#volver').click(function() {
                location.href = 'groceries.php';
            });

            $('#nuevo-grocery').click(function() {
                $('#mensaje-nombre-vacio').hide();
                if ($.trim($('#nombre').val()) == '') {
                    $('#mensaje-nombre-vacio').show();
                } else {
                    $(this).attr('disabled','disabled');
                    $('#forma-grocery').submit();
                }
            });
        </script>
    </body>
</html>
<?php
    $cnx = null;
?>