<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_comercio.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["id_comercio"])) {
        $idComercio= $_POST["id_comercio"];
        $nombre = $_POST["nombre"];
        $telefono = $_POST["telefono"];
        $direccion = $_POST["direccion"];
        $ciudad = $_POST["ciudad"];
        $latitud = $_POST["latitud"];
        $longitud = $_POST["longitud"];
        $descripcion = $_POST["descripcion"];
        $horario = $_POST["horario"];
        $reserva = $_POST["reserva"];
        $urlCarta = $_POST["url_carta"];
        if (isset($_POST["eliminar_imagen"])) {
            $eliminarImagen = $_POST["eliminar_imagen"];
        } else {
            $eliminarImagen = "0";
        }
        if (isset($_POST["eliminar_imagen_marker"])) {
            $eliminarImagenMarker = $_POST["eliminar_imagen_marker"];
        } else {
            $eliminarImagenMarker = "0";
        }
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }
        $tipoCocinas = [];
        if (isset($_POST["tipo_cocina"])) {
            $tipoCocinas = $_POST["tipo_cocina"];
        }
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
        } else {
            $nombreImagen = $_POST["imagen"];
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
        } else {
            $nombreImagenMarker = $_POST["imagen_marker"];
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
            if ($reserva != "") {
                $reserva = str_replace("'", "''", $reserva);
            }
            if ($urlCarta != "") {
                $urlCarta = str_replace("'", "''", $urlCarta);
            }
            if ($estado == "") {
                $estado = "0";
            }
            if ($eliminarImagen == "1"){
                $nombreImagen = "";
            }
            if ($eliminarImagenMarker == "1"){
                $nombreImagenMarker = "";
            }

            $query = "UPDATE comercio SET nombre = '" . $nombre . "', telefono = '" . $telefono . "', direccion = '" . $direccion . "', ciudad = '" . $ciudad . "', latitud = '" . $latitud . "', longitud = '" . $longitud . "', descripcion = '" . $descripcion . "', horario = '" . $horario . "', reserva = '" . $reserva . "', url_carta = '" . $urlCarta . "', imagen = '" . $nombreImagen . "', imagen_marker = '" . $nombreImagenMarker . "', estado = " . $estado . ", fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_comercio = " . $idComercio. ";";

            $cnx->execute($query);

            //tipo cocina
            $query = "UPDATE comercio_tipo_cocina SET estado = 0 WHERE id_comercio = " . $idComercio;
            $cnx->execute($query);
            foreach ($tipoCocinas as $tipoCocina) {
                $query = "SELECT id_comercio_tipo_cocina FROM comercio_tipo_cocina WHERE id_tipo_cocina = " . $tipoCocina . " AND id_comercio = " . $idComercio;
                $existe = false;
                $sql = $cnx->query($query);
                $sql->read();
                if ($sql->count() > 0) {
                    $existe = true;
                }
                if ($existe) {
                    $query = "UPDATE comercio_tipo_cocina SET estado = 1, usuario_modifica = '" . $usuario . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_tipo_cocina = " . $tipoCocina . " AND id_comercio = " . $idComercio;
                } else {
                    $query = "INSERT INTO comercio_tipo_cocina (id_comercio, id_tipo_cocina, estado, usuario_registro) VALUES (" . $idComercio. ", " . $tipoCocina . ", 1, '". $usuario . "');";
                }
                $cnx->execute($query);
            }

            $cnx = null;
            header("Location: restaurantes.php");
            die();
        }
    
    } else {

        $idComercio= $_GET["id"];

        if ($idComercio!= "" && $idComercio!= "0") {
            $query = "SELECT tipo_comercio, nombre, telefono, direccion, ciudad, latitud, longitud, descripcion, horario, reserva, url_carta, imagen, imagen_marker, estado, usuario_registro, fecha_registro, usuario_modifica, fecha_modifica FROM comercio WHERE id_comercio = " . $idComercio;
            $sql = $cnx->query($query);
            $sql->read();
            $html = "";
            while($sql->next()) {
                $tipoComercio = $sql->field('tipo_comercio');
                $nombre = $sql->field('nombre');
                $telefono = $sql->field('telefono');
                $direccion = $sql->field('direccion');
                $ciudad = $sql->field('ciudad');
                $latitud = $sql->field('latitud');
                $longitud = $sql->field('longitud');
                $descripcion = $sql->field('descripcion');
                $horario = $sql->field('horario');
                $reserva = $sql->field('reserva');
                $urlCarta = $sql->field('url_carta');
                $imagen = $sql->field('imagen');
                $imagenMarker = $sql->field('imagen_marker');
                $estado = $sql->field('estado');
                $usuarioRegistro = $sql->field('usuario_registro');
                $fechaRegistro = $sql->field('fecha_registro');
                $usuarioModifica = $sql->field('usuario_modifica');
                $fechaModifica = $sql->field('fecha_modifica');
            }

            //tipo cocina
            $query = "SELECT a.nombre, a.id_tipo_cocina, b.id_comercio_tipo_cocina FROM tipo_cocina a LEFT OUTER JOIN comercio_tipo_cocina b ON a.id_tipo_cocina = b.id_tipo_cocina AND b.estado = 1 AND b.id_comercio = " . $idComercio. " WHERE ( a.estado = 1 ) ORDER BY nombre";
            $sql = $cnx->query($query);
            $sql->read();
            $htmlTipoCocina = "";
            while($sql->next()) {
                $tipo = $sql->field('nombre');
                $idTipoCocina = $sql->field('id_tipo_cocina');
                $idComercioTipoCocina = $sql->field('id_comercio_tipo_cocina');
                $htmlTipoCocina .= '<div class="col-md-3"> <input type="checkbox" name="tipo_cocina[]" value="' . $idTipoCocina . '"';
                if ($idComercioTipoCocina > 0) {
                    $htmlTipoCocina .= ' checked="checked"';
                }
                $htmlTipoCocina .= '/> <label>' . $tipo . '</label>  </div>';
            }

        } else {
            $cnx = null;
            header("Location: restaurantes.php");
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
                            <h4 class="page-title">Modificaci&oacute;n de Restaurante</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="restaurante_editar.php" method="post" enctype="multipart/form-data" name="forma_restaurante" id="forma-restaurante">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="256" value="<?php echo $nombre ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tel&eacute;fono</label>
                                            <input type="text" name="telefono" id="telefono" class="form-control" maxlength="128" value="<?php echo $telefono ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n</label>
                                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?php echo $descripcion ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Direcci&oacute;n</label>
                                            <input type="text" name="direccion" id="direccion" class="form-control" maxlength="256" value="<?php echo $direccion ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ciudad</label>
                                            <input type="text" name="ciudad" id="ciudad" class="form-control" maxlength="64" value="<?php echo $ciudad ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Latitud</label>
                                            <input type="text" name="latitud" id="latitud" class="form-control" maxlength="32" value="<?php echo $latitud ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Longitud</label>
                                            <input type="text" name="longitud" id="longitud" class="form-control" maxlength="32" value="<?php echo $longitud ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Horario</label>
                                            <input type="text" name="horario" id="horario" class="form-control" maxlength="64" value="<?php echo $horario ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Reserva</label>
                                            <input type="text" name="reserva" id="reserva" class="form-control" maxlength="32" value="<?php echo $reserva ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Url carta</label>
                                            <input type="text" name="url_carta" id="url_carta" class="form-control" maxlength="128" value="<?php echo $urlCarta ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Subir imagen</label>
                                            <input type="file" name="fileToUpload" value="<?php echo $imagen ?>" class="form-control" />
                                            <?php if ($imagen != "") {?>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <img src="<?php echo COMERCIO_IMAGE_SHORT_PATH . $imagen ?>" class="img-responsive thumbnail m-r-15" alt="" />
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="eliminar_imagen" value="1" /> Eliminar imagen
                                                </div>
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Subir imagen marker</label>
                                            <input type="file" name="fileToUpload2" value="<?php echo $imagenMarker ?>" class="form-control" />
                                            <?php if ($imagenMarker != "") {?>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <img src="<?php echo COMERCIO_IMAGE_SHORT_PATH . $imagenMarker ?>" class="col-md-3" alt="" />
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="eliminar_imagen_marker" value="1" /> Eliminar imagen marker
                                                </div>
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="estado" id="estado" <?php if ($estado == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="estado">Activo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tipo cocina</label>
                                            <div class="checkbox checkbox-success">
                                                <?php echo $htmlTipoCocina;?>
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
                                <input type="hidden" name="id_comercio" value="<?php echo $idComercio?>" />
                                <input type="hidden" name="imagen" value="<?php echo $imagen ?>" />
                                <input type="hidden" name="imagen_marker" value="<?php echo $imagenMarker ?>" />
                                <div class="row">
                                    <div class="col-md-6 alert alert-info">
                                        * Ning&uacute;n cambio har&aacute; efecto a menos que se de clic en "Grabar".
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-restaurante" value="Grabar" class="btn btn-success" />
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
                location.href = 'restaurantes.php';
            });

            $('#nuevo-restaurante').click(function() {
                $('#mensaje-nombre-vacio').hide();
                if ($.trim($('#nombre').val()) == '') {
                    $('#mensaje-nombre-vacio').show();
                } else {
                    $('#forma-restaurante').submit();
                }
            });
        </script>
    </body>
</html>
<?php
    $cnx = null;
?>