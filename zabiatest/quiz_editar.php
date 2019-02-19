<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/constante_quiz.php');

    $cnx = new MySQL();

    if (isset($_POST["id_quiz"])) {
        $nombre = $_POST["nombre"];
        $idQuiz = $_POST["id_quiz"];
        $usuario = $_SESSION["U"];

        if ($nombre != "") {
            $nombre = str_replace("'", "''", $nombre);
        }
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }
        if (isset($_POST["eliminar_imagen"])) {
            $eliminarImagen = $_POST["eliminar_imagen"];
        } else {
            $eliminarImagen = "0";
        }

        existeFolderCrea(QUIZ_IMAGE_PATH);
        $nombreArchivo = grabarImagenDesdeFormulario($idQuiz, "fileToUpload", QUIZ_IMAGE_PATH, $eliminarImagen);

        $execute = "CALL USP_EDIT_QUIZ ($idQuiz, '$nombre', '$nombreArchivo', $estado, '$usuario');"; 
        $cnx->execute($execute);

        header("Location: quiz.php");
        die();
    } else if (isset($_GET["id"])) {
        $idQuiz = $_GET["id"];
        $query = "CALL USP_OBTEN_QUIZ(" . $idQuiz . ");";
        $sql = $cnx->query($query);
        $sql->read();
        $html = "";
        if ($sql->next()) {
            $idQuiz = $sql->field("id_quiz");
            $nombre = $sql->field('nombre');
            $imagen = $sql->field('imagen');
            $estado = $sql->field('estado');
            $usuarioRegistro = $sql->field('usuario_registro');
            $fechaRegistro = $sql->field('fecha_registro');
            $usuarioModifica = $sql->field('usuario_modifica');
            $fechaModifica = $sql->field('fecha_modifica');
        }
    } else {

        header("Location: quiz.php");
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="page-title">Modificaci&oacute;n de Cuestionario</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="quiz_editar.php" method="post" id="forma-quiz" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Cuestionario</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" value="<?php echo $nombre; ?>" />
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
                                            <img src="<?php echo QUIZ_IMAGE_PATH . $imagen ?>" class="img-responsive thumbnail m-r-15" alt="" />
                                            &nbsp;&nbsp;
                                            <input type="checkbox" name="eliminar_imagen" value="1" /> Eliminar imagen
                                            <?php }?>
                                            <input type="hidden" name="imagen" value="<?php echo $imagen; ?>" />
                                        </div>
                                    </div>
                                </div>
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
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-quiz" value="Grabar" class="btn btn-success" />
                                        <input type="hidden" name="id_quiz" value="<?php echo $idQuiz; ?>" />
                                    </div>
                                    <div id="mensaje-nombre-vacio" class="row alert alert-danger">* El nombre no debe estar vac&iacute;o</div>
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
                $('#mensaje-nombre-vacio').hide();
                $('#nav-quiz').addClass('active');
            } );

            $('#volver').click(function() {
                location.href = 'quiz.php';
            });

            $('#nuevo-quiz').click(function() {
                $('#mensaje-nombre-vacio').hide();
                if ($.trim($('#nombre').val()) == '') {
                    $('#mensaje-nombre-vacio').show();
                } else {
                    $(this).attr('disabled','disabled');
                    $('#forma-quiz').submit();
                }
            });
        </script>
    </body>
</html>