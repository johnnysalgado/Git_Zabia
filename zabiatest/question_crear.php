<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/constante_quiz.php');

    if (isset($_POST["id_quiz"])) {
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

        existeFolderCrea(QUESTION_IMAGE_PATH);

        $cnx = new MySQL();
        $execute = "CALL USP_CREA_QUESTION ($idQuiz, '$descripcion', '', $orden, '$tipoRespuesta', '$explicacion', '$usuario', @p_id_question);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_question AS id_question");
        $sql->read();
        if ($sql->next()) {
            $idQuestion = $sql->field('id_question');
        }
        $nombreArchivo = grabarImagenDesdeFormulario($idQuestion, "fileToUpload", QUESTION_IMAGE_PATH, "0");
        if ($nombreArchivo != "") {
            $execute = "UPDATE question SET imagen = '$nombreArchivo' WHERE id_question = $idQuestion";
            $cnx->execute($execute);
        }
        $cnx->close();
        $cnx = null;
        header("Location: question.php?id_quiz=" . $idQuiz);
        die();
    } else if (isset($_GET['id_quiz']) && is_numeric($_GET['id_quiz'])) {
        $idQuiz = $_GET['id_quiz'];
        $cnx = new MySQL();
        $htmlTipoRespuesta = getHtmlTipoRespuesta($cnx, "");
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
                            <h4 class="page-title">Creaci&oacute;n de pregunta</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="question_crear.php" method="post" id="forma-question" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n</label>
                                            <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="128" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Subir imagen</label>
                                            <input type="file" name="fileToUpload" value="" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>N&deg; orden</label>
                                            <input type="number" name="orden" id="orden" class="form-control" step="1" min="0" />
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Explicaci&oacute;n</label>
                                            <textarea name="explicacion" id="explicacion" class="form-control" rows="4"/></textarea>
                                        </div>
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