<?php
    require('inc/sesion.php');
    require('constante_dir.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_intolerancia.php');
    require('inc/constante.php');
    require('inc/constante_intolerancia.php');
    require('inc/constante_aws.php');
    require('Classes/ChilkatS3.php');
    require('Classes/ImageResize.php');
    require('inc/funcion_imagen.php');

    if (isset($_POST["nombre"])) {

        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        $recomendacion = $_POST["recomendacion"];
        $recomendacionIngles = $_POST["recomendacion_ing"];
        $referencia = $_POST["referencia"];
        $usuario = $_SESSION["U"];
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
        $nombreImagen = "";

        //levantar archivo 
        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $mt = microtime(true);
            $mt =  $mt * 1000; //microsecs
            $ticks = (string) $mt * 10;
            $imageUploadName = basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = pathinfo($imageUploadName, PATHINFO_EXTENSION);
            $nombreImagen = PREFIJO_INTOLERANCIA_ICONO . $ticks . "." . $imageFileType;
            $targetFile = ICON_SHORT_PATH . $nombreImagen;
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile);
            //crear imágenes en tamaño ícono y pasarlo a AWS S3
            setImageIcon ($targetFile, $nombreImagen);
        }
        $daoIntolerancia = new DaoIntolerancia();
        $idIntolerancia = $daoIntolerancia->crearIntolerancia($nombre, $nombreIngles, $nombreImagen, $recomendacion, $recomendacionIngles, $referencia, $usuario);
        $daoIntolerancia = null;

        header("Location: intolerancia.php");
        die();
    
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
                            <h4 class="page-title">Creaci&oacute;n de Intolerancia</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="intolerancia_crear.php" method="post" id="forma" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre (Ingl&eacute;s)</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Subir &iacute;cono</label>
                                            <input type="file" name="fileToUpload" value="" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Recomendaci&oacute;n </label>
                                            <textarea name="recomendacion" id="recomendacion" class="form-control"  rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Recomendaci&oacute;n [ingl&eacute;s]</label>
                                            <textarea name="recomendacion_ing" id="recomendacion_ing" class="form-control"  rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Referencia bibliogr&aacute;fica </label>
                                            <input type="text" name="referencia" id="referencia" class="form-control" maxlength="512" />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div id="divError" class="row alert alert-danger"></div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-intolerancia" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
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
            $('#nav-health').addClass('active');
            $('#nombre').focus();
        });

        $('#volver').click(function() {
            location.href = 'intolerancia.php';
        });

        $("#nuevo-intolerancia").click(function() {
            $('#divError').hide();
            $(this).attr('disabled','disabled');
            $('#forma').submit();
        });

        </script>
    </body>
</html>