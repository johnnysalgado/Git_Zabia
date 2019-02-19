<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/constante_cuestionario.php');
    require('inc/constante_quiz.php');

    if (isset($_POST["id_question"])) {
        $idQuestion = $_POST["id_question"];
        $idQuiz = $_POST["id_quiz"];
        $descripcion = $_POST["descripcion"];
        $orden = $_POST["orden"];
        $tipoRespuesta = $_POST["tipo_respuesta"];
        $explicacion = $_POST["explicacion"];
        $usuario = $_SESSION["U"];

        if ($descripcion != "") {
            $descripcion = str_replace("'", "''", $descripcion);
        }
        if ($orden == "" || !is_numeric($orden)) {
            $orden = 1;
        }
        if (isset($_POST["eliminar_imagen"])) {
            $eliminarImagen = $_POST["eliminar_imagen"];
        } else {
            $eliminarImagen = "0";
        }

        existeFolderCrea(QUESTION_IMAGE_PATH);
        $nombreArchivo = grabarImagenDesdeFormulario($idQuestion, "fileToUpload", QUESTION_IMAGE_PATH, $eliminarImagen);

        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_QUESTION ($idQuestion, '$descripcion', '$nombreArchivo', $orden, '$tipoRespuesta', '$explicacion', '$usuario');";
        $cnx->execute($execute);
        $cnx->close();
        $cnx = null;
        header("Location: question.php?id_quiz=" . $idQuiz);
        die();
    } else if (isset($_GET['id_question']) && is_numeric($_GET['id_question'])) {
        $idQuiz = $_GET['id_quiz'];
        $idQuestion = $_GET['id_question'];
        $cnx = new MySQL();
        $query = "CALL USP_OBTEN_QUESTION ($idQuestion)";
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            $descripcion = $sql->field('descripcion');
            $imagen = $sql->field('imagen');
            $orden = $sql->field('orden');
            $tipoRespuesta = $sql->field('cod_tipo_respuesta');
            $explicacion = $sql->field('explicacion');
            $estado = $sql->field('estado');
            $usuarioRegistro = $sql->field('usuario_registro');
            $fechaRegistro = $sql->field('fecha_registro');
            $usuarioModifica = $sql->field('usuario_modifica');
            $fechaModifica = $sql->field('fecha_modifica');
        }
        $cnx->close();
        $cnx = new MySQL();
        $htmlTipoRespuesta = getHtmlTipoRespuesta($cnx, $tipoRespuesta);
        $cnx->close();
        $cnx = null;
    } else {
        header("Location: quiz.php");
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
                            <h4 class="page-title">Modificaci&oacute;n de pregunta</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="question_editar.php" method="post" id="forma-question" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n</label>
                                            <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="128" value="<?php echo $descripcion; ?>" />
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
                                            <img src="<?php echo QUESTION_IMAGE_PATH . $imagen ?>" class="img-responsive thumbnail m-r-15" alt="" />
                                            &nbsp;&nbsp;
                                            <input type="checkbox" name="eliminar_imagen" value="1" /> Eliminar imagen
                                            <?php }?>
                                            <input type="hidden" name="imagen" value="<?php echo $imagen; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>N&deg; orden</label>
                                            <input type="number" name="orden" id="orden" class="form-control" step="1" min="0" value="<?php echo $orden; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipo de respuesta</label>
                                            <select id="tipo_respuesta" name="tipo_respuesta" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                                <?php echo $htmlTipoRespuesta; ?>
                                            </select>
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
                                            <label>Explicaci&oacute;n</label>
                                            <textarea name="explicacion" id="explicacion" class="form-control" rows="4"/><?php echo $explicacion ?></textarea>
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
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-question" value="Grabar" class="btn btn-success" />
                                    </div>
                                    <div id="mensaje-nombre-vacio" class="row alert alert-danger"></div>
                                </div>
                                <input type="hidden" name="id_quiz" value="<?php echo $idQuiz; ?>" />
                                <input type="hidden" name="id_question" value="<?php echo $idQuestion; ?>" />
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
                location.href = 'question.php?id_quiz=<?php echo $idQuiz; ?>';
            });

            $('#nuevo-question').click(function() {
                $('#mensaje-nombre-vacio').hide();
                if ($.trim($('#descripcion').val()) == '') {
                    $('#mensaje-nombre-vacio').html('La descripción no puede estar vacía').show();
                } else if ($('#tipo_respuesta').val() == '') {
                    $('#mensaje-nombre-vacio').html('El tipo de respuesta no puede estar vacío').show();
                } else {
                    $(this).attr('disabled','disabled');
                    $('#forma-question').submit();
                }
            });
        </script>
    </body>
</html>