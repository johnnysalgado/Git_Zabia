<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/constante_quiz.php');

    if (isset($_POST["nombre"])) {
        $nombre = $_POST["nombre"];
        $usuario = $_SESSION["U"];

        if ($nombre != "") {
            $nombre = str_replace("'", "''", $nombre);
        }

        existeFolderCrea(QUIZ_IMAGE_PATH);

        $cnx = new MySQL();
        $execute = "CALL USP_CREA_QUIZ ('$nombre', '', '$usuario', @p_id_quiz);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_quiz AS id_quiz");
        $sql->read();
        if ($sql->next()) {
            $idQuiz = $sql->field('id_quiz');
        }
        $nombreArchivo = grabarImagenDesdeFormulario($idQuiz, "fileToUpload", QUIZ_IMAGE_PATH, "0");
        if ($nombreArchivo != "") {
            $execute = "UPDATE quiz SET imagen = '" . $nombreArchivo . "' WHERE id_quiz = " . $idQuiz;
            $cnx->execute($execute);
        }
        $cnx->close();
        $cnx = null;

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
                        <div class="col-lg-6 col-md-6 col-sm6 col-xs-12">
                            <h4 class="page-title">Creaci&oacute;n de Cuestionario</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="quiz_crear.php" method="post" id="forma-quiz" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Cuestionario</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Subir imagen</label>
                                            <input type="file" name="fileToUpload" value="" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-quiz" value="Grabar" class="btn btn-success" />
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