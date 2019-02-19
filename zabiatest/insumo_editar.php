<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('constante_dir.php');
    require('inc/constante.php');
    require('inc/constante_insumo.php');
    require('inc/mysql.php');
    require('inc/dao_insumo.php');
    require('inc/constante_aws.php');
    require('Classes/ChilkatS3.php');
    require('Classes/ImageResize.php');
    require('inc/funcion_imagen.php');

    if (isset($_POST["id_insumo"])) {

        $idInsumo = $_POST["id_insumo"];
        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        $idTipoAlimento = $_POST["tipo_alimento"];
        $flagSuperfood = $_POST["superfood"];
        $precio = $_POST["precio"];
        if (isset($_POST["eliminar_imagen"])) {
            $eliminarImagen = $_POST["eliminar_imagen"];
        } else {
            $eliminarImagen = "0";
        }
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }
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
        $nombreImagen = "";
        
        $intolerancias = [];
        if (isset($_POST["intolerancia"])) {
            $intolerancias = $_POST["intolerancia"];
        }

        $cnx = new MySQL();

        //levantar archivo 
        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $nombreImagen = $idInsumo . "_" . basename($_FILES["fileToUpload"]["name"]);
            $target_file = INSUMO_IMAGE_SHORT_PATH . $nombreImagen;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            //echo "current user: " . get_current_user() . "<br/>";
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            //crear imágenes en tamaños y pasarlo a AWS S3
            setImageIngredient ($target_file, $nombreImagen);
        } else {
            $nombreImagen = $_POST["imagen"];
        }
        if ($eliminarImagen == "1"){
            $nombreImagen = "";
        }

        $query = "UPDATE insumo SET nombre = '" . $nombre . "', nombre_ing = '" . $nombreIngles . "', id_tipo_alimento = " . $idTipoAlimento . ", flag_superfood=" . $flagSuperfood . ", estado = " . $estado . ", imagen = '" . $nombreImagen . "', fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_insumo = " . $idInsumo . ";";
        $cnx->execute($query);

        $query = "SELECT id_insumo_precio FROM insumo_precio WHERE id_insumo = " . $idInsumo;
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->count() > 0) {
            $query = "UPDATE insumo_precio SET precio = " . $precio . ", estado = 1, fecha_modificacion = CURRENT_TIMESTAMP, usuario_modificacion = '" . $usuario . "' WHERE id_insumo = " . $idInsumo;
        } else {
            $query = "INSERT INTO insumo_precio (id_insumo, precio, usuario_registro) VALUES (" . $idInsumo . ", " . $precio . ", '" . $usuario . "')";
        }
        $cnx->execute($query);
        $cnx->close();
        $cnx = null;

        $_SESSION['INP'] = $_SESSION['INP'];
        $_SESSION['IPS'] = $_SESSION['IPS'];
        $_SESSION['ILP'] = $_SESSION['ILP'];
        header("Location: insumos.php");
        die();
    
    } else {

        $cnx = new MySQL();
        $idInsumo = $_GET["id"];

        if ($idInsumo != "" && $idInsumo != "0") {
            $query = "SELECT a.nombre, a.nombre_ing, a.codigo_externo, a.id_tipo_alimento, a.flag_superfood, a.estado, a.usuario_registro, a.fecha_registro, a.usuario_modifica, a.fecha_modifica, b.precio, a.imagen, a.origen FROM insumo a LEFT OUTER JOIN insumo_precio b ON a.id_insumo = b.id_insumo WHERE a.id_insumo = " . $idInsumo;
            $sql = $cnx->query($query);
            $sql->read();
            $html = "";
            if ($sql->next()) {
                $nombre = $sql->field('nombre');
                $nombreIngles = $sql->field('nombre_ing');
                $codigoExterno = $sql->field('codigo_externo');
                $idTipoAlimento = $sql->field('id_tipo_alimento');
                $flagSuperfood = $sql->field('flag_superfood');
                $estado = $sql->field('estado');
                $usuarioRegistro = $sql->field('usuario_registro');
                $fechaRegistro = $sql->field('fecha_registro');
                $usuarioModifica = $sql->field('usuario_modifica');
                $fechaModifica = $sql->field('fecha_modifica');
                $precio = $sql->field('precio');
                $imagen = $sql->field('imagen');
                $origenData = $sql->field('origen');
            }
            if ($precio != "") {
                $precio = round($precio, 4);
            }

            //porcentaje peso
            $porcentajePeso = 0;
            $query = "SELECT cantidad, gramo FROM insumo_medida WHERE ( id_insumo = $idInsumo ) AND ( secuencia = 1 )";
            $sql = $cnx->query($query);
            $sql->read();
            $densidad = 0;
            if ($sql->next()) {
                $porcentajePeso = round($sql->field('gramo') / $sql->field('cantidad'), 2);
            }

            //densidad nutricional
            $query = "SELECT density FROM _static_food_density WHERE (id_insumo = $idInsumo )";
            $sql = $cnx->query($query);
            $sql->read();
            $densidad = 0;
            if ($sql->next()) {
                $densidad = round($sql->field('density'), 0);
            }

            //tipo alimento
            $query = "SELECT a.nombre, a.id_tipo_alimento FROM tipo_alimento a WHERE ( a.estado = 1 ) ORDER BY nombre";
            $sql = $cnx->query($query);
            $sql->read();
            $htmlTipoAlimento = "";
            while($sql->next()) {
                $tipo = $sql->field('nombre');
                $idTipo = $sql->field('id_tipo_alimento');
                $htmlTipoAlimento .= '<option value="' . $idTipo . '"';
                if ($idTipo == $idTipoAlimento) {
                    $htmlTipoAlimento .= ' selected = "selected"';
                }
                $htmlTipoAlimento .= '> ' . $tipo . ' </option>';
            }
            $cnx->close();
            $cnx = null;

        } else {
            header("Location: insumos.php");
            die();
        }
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
                            <h4 class="page-title">Modificaci&oacute;n de Insumo [<?php echo $idInsumo; ?>]</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="insumo_editar.php" method="post" id="forma-insumo" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="128" value="<?php echo $nombre ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre (Ingl&eacute;s)</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="128" value="<?php echo $nombreIngles ?>" />
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
                                                        <input type="radio" name="superfood" id="sf-si" value="1" <?php if ($flagSuperfood == '1') { echo 'checked="checked"'; } ?> />
                                                        <label for="top-si">Si</label>
                                                    </div>
                                                </label>
                                                <label class="radio-inline">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="superfood" id="sf-no" value="0" <?php if ($flagSuperfood == '0') { echo 'checked="checked"'; } ?> />
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
                                            <input type="number" name="precio" id="precio" class="form-control" min="0" step="0.0001" value="<?php echo $precio ?>" />
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Subir imagen</label>
                                            <input type="file" name="fileToUpload" value="<?php echo $imagen ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> </label>
                                            <?php if ($imagen != "") {?>
                                            <img src="<?php echo BASE_REMOTE_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imagen ?>" class="img-responsive thumbnail m-r-15" alt="" />
                                            &nbsp;&nbsp;
                                            <input type="checkbox" name="eliminar_imagen" value="1" /> Eliminar imagen>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Origen del dato</label> <br/>
                                            <span> <?php echo $origenData . " / " . $codigoExterno; ?> </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> % Porci&oacute;n Peso (SR-LEGACY) </label> <br/>
                                            <span> <?php echo $porcentajePeso; ?> </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Densidad nutricional </label> <br/>
                                            <span> <?php echo $densidad; ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <hr />
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
                                <div id="divError" class="row alert alert-danger"></div>
                                <input type="hidden" name="id_insumo" value="<?php echo $idInsumo ?>" />
                                <input type="hidden" name="imagen" value="<?php echo $imagen ?>" />
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
