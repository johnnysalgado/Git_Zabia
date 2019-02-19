<?php
    require('inc/sesion.php');
    require('constante_dir.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_tipo.php');
    require('inc/constante.php');
    require('inc/constante_tipo.php');
    require('inc/constante_aws.php');
    require('Classes/ChilkatS3.php');
    require('Classes/ImageResize.php');
    require('inc/funcion_imagen.php');

    if (isset($_POST["id_tipo_cocina"])) {

        $idTipoCocina = $_POST["id_tipo_cocina"];
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
        if (isset($_POST["eliminar_imagen"])) {
            $eliminarImagen = $_POST["eliminar_imagen"];
        } else {
            $eliminarImagen = "0";
        }
        $nombreImagen = "";
        //levantar archivo 
        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $nombreImagen = $idTipoCocina . "_" . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = pathinfo($nombreImagen, PATHINFO_EXTENSION);
            $targetFile = TYPE_IMAGE_SHORT_PATH . $nombreImagen;
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile);
            //crear imágenes en varios tamaños y pasarlo a AWS S3
            setImageCuisineType ($targetFile, $nombreImagen);
        } else {
            $nombreImagen = $_POST["imagen"];
        }
        if ($eliminarImagen == "1"){
            $nombreImagen = "";
        }

        $daoTipo = new DaoTipo();
        $daoTipo->editarTipoCocina($idTipoCocina, $nombre, $nombreIngles, $nombreImagen, $estado, $usuario);
        $daoTipo = null;

        header("Location: tipo_cocina.php");
        die();
    
    } else {

        $nombre = "";
        $nombreIngles = "";
        $imagen = "";
        $estado = "";
        $usuarioRegistro = "";
        $fechaRegistro = "";
        $usuarioModifica = "";
        $fechaModifica = "";
        $idTipoCocina = $_GET["id"];
        $daoTipo = new DaoTipo();
        $arreglo = $daoTipo->obtenerTipoCocina($idTipoCocina);
        if (count($arreglo) > 0) {
            $item = $arreglo[0];
            $nombre = $item['nombre'];
            $nombreIngles = $item['nombre_ing'];
            $imagen = $item['imagen'];
            $estado = $item['estado'];
            $usuarioRegistro = $item['usuario_registro'];
            $fechaRegistro = $item['fecha_registro'];
            $usuarioModifica = $item['usuario_modifica'];
            $fechaModifica = $item['fecha_modifica'];
        }
        $daoTipo = null;
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="page-title">Modificaci&oacute;n de Tipo de cocina</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tipo_cocina_editar.php" method="post" id="forma" enctype="multipart/form-data">
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
                                            <label>Subir imagen</label>
                                            <input type="file" name="fileToUpload" value="<?php echo $imagen ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> </label>
                                            <?php if ($imagen != "") {?>
                                            <img src="<?php echo BASE_REMOTE_IMAGE_PATH . CUISINE_TYPE_IMAGE_REMOTE_PATH . $imagen ?>" class="img-responsive thumbnail m-r-15" alt="" />
                                            &nbsp;&nbsp;
                                            <input type="checkbox" name="eliminar_imagen" value="1" /> Eliminar imagen
                                            <?php }?>
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
                                        <input type="button" id="nuevo-tipo-cocina" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <input type="hidden" id="id_tipo_cocina" name="id_tipo_cocina" value="<?php echo $idTipoCocina;?>" />
                                <input type="hidden" name="imagen" value="<?php echo $imagen ?>" />
                            </form>
                        </div>
                    </div>
                </div>
                <?php require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
        $(document).ready(function () {
            $('#divError').hide();
            $('#nav-table').addClass('active');
        });

        $('#volver').click(function() {
            location.href = 'tipo_cocina.php';
        });

        $("#nuevo-tipo-cocina").click(function() {
            $('#divError').hide();
            $('#forma').submit();
        });
        </script>
    </body>
</html>