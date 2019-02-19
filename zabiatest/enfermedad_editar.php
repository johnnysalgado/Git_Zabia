<?php
    require('inc/sesion.php');
    require('constante_dir.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_enfermedad.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/constante_enfermedad.php');
    require('inc/constante_aws.php');
    require('Classes/ChilkatS3.php');
    require('Classes/ImageResize.php');
    require('inc/funcion_imagen.php');

    if (isset($_POST["id_enfermedad"])) {

        $idEnfermedad = $_POST["id_enfermedad"];
        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        if (isset($_POST["tipo_categoria_precondicion"])) {
            $idTipoCategoriaPrecondicion = $_POST["tipo_categoria_precondicion"];
        } else {
            $idTipoCategoriaPrecondicion = 0;
        }
        $recomendacion = $_POST["recomendacion"];
        $recomendacionIngles = $_POST["recomendacion_ing"];
        $referencia = $_POST["referencia"];
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
        if ($recomendacion != "") {
            $recomendacion = str_replace("'", "''", $recomendacion);
        }
        if ($recomendacionIngles != "") {
            $recomendacionIngles = str_replace("'", "''", $recomendacionIngles);
        }
        if ($referencia != "") {
            $referencia = str_replace("'", "''", $referencia);
        }
        if (isset($_POST["eliminar_imagen"])) {
            $eliminarImagen = $_POST["eliminar_imagen"];
        } else {
            $eliminarImagen = "0";
        }
        $nombreImagen = "";
        //levantar archivo 
        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $nombreImagen = PREFIJO_PRECONDICION_ICONO . $idEnfermedad . "_" . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = pathinfo($nombreImagen, PATHINFO_EXTENSION);
            $targetFile = ICON_SHORT_PATH . $nombreImagen;
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile);
            //crear imágenes en tamaño ícono y pasarlo a AWS S3
            setImageIcon ($targetFile, $nombreImagen);
        } else {
            $nombreImagen = $_POST["imagen"];
        }
        if ($eliminarImagen == "1"){
            $nombreImagen = "";
        }

        $daoEnfermedad = new DaoEnfermedad();
        $daoEnfermedad->editarEnfermedad($idEnfermedad, $idTipoCategoriaPrecondicion, $nombre, $nombreIngles, $nombreImagen, $recomendacion, $recomendacionIngles, $referencia, $estado, $usuario);
        $daoEnfermedad = null;

        header("Location: enfermedades.php?");
        die();
    
    } else {

        $idEnfermedad = $_GET["id"];
        $idTipoCategoriaPrecondicion = 0;
        $nombre = "";
        $nombreIngles = "";
        $imagen = "";
        $recomendacion = "";
        $recomendacionIngles = "";
        $referencia = "";
        $estado = "";
        $usuarioRegistro = "";
        $fechaRegistro = "";
        $usuarioModifica = "";
        $fechaModifica = "";
        $daoEnfermedad = new DaoEnfermedad();
        
        $arreglo = $daoEnfermedad->obtenerEnfermedad($idEnfermedad);
        if (count($arreglo) > 0) {
            $idTipoCategoriaPrecondicion = $arreglo[0]['id_tipo_categoria_precondicion'];
            $nombre = $arreglo[0]['nombre'];
            $nombreIngles = $arreglo[0]['nombre_ing'];
            $imagen = $arreglo[0]['imagen'];
            $recomendacion = $arreglo[0]['recomendacion'];
            $recomendacionIngles = $arreglo[0]['recomendacion_ing'];
            $referencia = $arreglo[0]['referencia'];
            $estado = $arreglo[0]['estado'];
            $usuarioRegistro = $arreglo[0]['usuario_registro'];
            $fechaRegistro = $arreglo[0]['fecha_registro'];
            $usuarioModifica = $arreglo[0]['usuario_modifica'];
            $fechaModifica = $arreglo[0]['fecha_modifica'];
        }

        $htmlCategoria = "";
        $arregloCategoria = $daoEnfermedad->listarTipoCategoriaPrecondicion(LISTA_ACTIVO, LENGUAJE_ESPANOL);
        foreach($arregloCategoria as $item) {
            $idTCP = $item["id_tipo_categoria_precondicion"];
            $nombreTCP = $item["nombre"];
            $htmlCategoria .= "<option value=\"$idTCP\" ";
            if ($idTCP == $idTipoCategoriaPrecondicion) {
                $htmlCategoria .= " selected=\"selected\" ";
            }
            $htmlCategoria .= ">$nombreTCP</option>";
        }
        $daoEnfermedad = null;

    }
?>
<!DOCTYPE html>
<html lang="en">
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
                            <h4 class="page-title">Modificaci&oacute;n de Enfermedad</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="enfermedad_editar.php" method="post" id="forma-enfermedad" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Categor&iacute;a</label>
                                            <select name="tipo_categoria_precondicion" id="tipo_categoria_precondicion" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                                <?php echo $htmlCategoria; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" value="<?php echo $nombre; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre Ingl&eacute;s</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="64" value="<?php echo $nombreIngles; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Subir &iacute;cono</label>
                                            <input type="file" name="fileToUpload" value="<?php echo $imagen ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> </label>
                                            <?php if ($imagen != "") {?>
                                            <img src="<?php echo BASE_REMOTE_IMAGE_PATH . ICON_REMOTE_PATH . ICON_PREFIX_PATH . $imagen ?>" class="img-responsive thumbnail m-r-15" alt="" />
                                            &nbsp;&nbsp;
                                            <input type="checkbox" name="eliminar_imagen" value="1" /> Eliminar imagen
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Recomendaci&oacute;n </label>
                                            <textarea name="recomendacion" id="recomendacion" class="form-control"  rows="4"><?php echo $recomendacion; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Recomendaci&oacute;n [ingl&eacute;s]</label>
                                            <textarea name="recomendacion_ing" id="recomendacion_ing" class="form-control"  rows="4"><?php echo $recomendacionIngles; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Referencia bibliogr&aacute;fica</label>
                                            <input type="text" name="referencia" id="referencia" class="form-control" value="<?php echo $referencia; ?>" maxlength="512" />
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
                                <hr/>
                                <div id="divError" class="row alert alert-danger"></div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-enfermedad" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <input type="hidden" id="id_enfermedad" name="id_enfermedad" value="<?php echo $idEnfermedad;?>" />
                                <input type="hidden" name="imagen" value="<?php echo $imagen ?>" />
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
                $('#nav-health').addClass('active');
            });

            $('#volver').click(function() {
                location.href = 'enfermedades.php?';
            });

            $("#nuevo-enfermedad").click(function() {
                $('#divError').hide();
                $('#forma-enfermedad').submit();
            });
        </script>
    </body>
</html>