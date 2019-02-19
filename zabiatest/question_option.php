<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/constante_quiz.php');

    $html = "";
    if (isset($_POST['id_question'])) {
        $idQuiz = $_POST['id_quiz'];
        $idQuestion = $_POST['id_question'];
        $idQuestionOption = $_POST['id_question_option'];
        $descripcion = $_POST['descripcion'];
        $orden = $_POST['orden'];
        $correcta = $_POST['correcta'];
        $estado = $_POST['estado'];
        $usuario = $_SESSION["U"];
        if ($descripcion != '') $descripcion = str_replace("'", "''", $descripcion);
        if ($orden == '') $orden = 1;
        $cnx = new MySQL();
        if ($idQuestionOption > 0) {
            $execute = "CALL USP_EDIT_QUESTION_OPTION ($idQuestionOption, '$descripcion', '$correcta', $orden, '$estado', '$usuario')";
        } else {
            $execute = "CALL USP_CREA_QUESTION_OPTION ($idQuestion, '$descripcion', '$correcta', $orden, '$usuario', @p_id_question_option)";
        }
        $cnx->execute($execute);
        $cnx->close();
        $cnx = null;
        header("Location: question_option.php?id_quiz=$idQuiz&id_question=$idQuestion");
        die();
    } else if (isset($_GET['id_question']) && is_numeric($_GET['id_question'])) {
        $idQuiz = $_GET['id_quiz'];
        $idQuestion = $_GET['id_question'];
        $cnx = new MySQL();
        $query = "CALL USP_LIST_QUESTION_OPTION ($idQuestion)";
        $sql = $cnx->query($query);
        $sql->read();
        $html = '<ol class="dd-list">';
        while ($sql->next()) {
            $idQuestionOption = $sql->field('id_question_option');
            $descripcion = $sql->field('descripcion');
            $correcta = $sql->field('correcta');
            $html .= '<li class="dd-item dd3-item " data-id="' . $idQuestionOption . '"> <div class="dd-handle dd3-handle"></div> <div class="dd3-content ver-option" data-id="' . $idQuestionOption . '" style="cursor:pointer;';
            if ($correcta == OPTION_CORRECTA) {
                $html .= ' background-color:#DAEFEB;';
            }
            $html .= '">' . $descripcion  . ' </div> </li>';
        }
        $html .= '</ol>';
        $cnx->close();
        $cnx = new MySQL();
        $query = "CALL USP_OBTEN_QUESTION ($idQuestion)";
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            $descripcionPregunta = $sql->field('descripcion');
        }
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
                            <h4 class="page-title">Opciones de respuesta: <?php echo $descripcionPregunta; ?></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-4">
                            <div class="white-box">
                                <div class="myadmin-dd-empty dd" id="nestable2">
                                    <?php echo $html; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-8">
                            <div class="white-box">
                                <form action="question_option.php" method="post" id="forma" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Descripci&oacute;n</label>
                                                <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="64" />
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
                                                <label>Opci&oacute;n correcta?</label>
                                                <select name="correcta" id="correcta" class="form-control">
                                                    <option value="<?php echo OPTION_INCORRECTA; ?>">No</option>
                                                    <option value="<?php echo OPTION_CORRECTA; ?>">Si</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Registrado</label>
                                        </div>
                                        <div class="col-md-9" id="dato-registro"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Modificado</label>
                                        </div>
                                        <div class="col-md-9" id="dato-modifica"></div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                            &nbsp;&nbsp;
                                            <input type="button" id="limpiar" value="Limpiar" class="btn btn-default" />
                                            &nbsp;&nbsp;
                                            <input type="button" id="eliminar" value="Eliminar" class="btn btn-primary" />
                                            &nbsp;&nbsp;
                                            <input type="button" id="nuevo-question-option" value="Grabar" class="btn btn-success" />
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div id="mensaje" class="row alert alert-danger col-md-12"></div>
                                    </div>
                                    <input type="hidden" name="id_quiz" value="<?php echo $idQuiz; ?>" />
                                    <input type="hidden" name="id_question" value="<?php echo $idQuestion; ?>" />
                                    <input type="hidden" name="id_question_option" id="id_question_option" value="0" />
                                    <input type="hidden" name="estado" id="estado" value="1" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {

                limpiarForma();
                $('#nav-quiz').addClass('active');

                $('#nuevo-question-option').click(function() {
                    $('#mensaje').hide();
                    if ($('#descripcion').val().trim() == '') {
                        $('#mensaje').html('No ha ingresado descripción').show();
                    } else {
                        $(this).attr('disabled','disabled');
                        $('#forma').submit();
                    }
                });

                $('#eliminar').click(function() {
                    if (confirm('¿Está seguro?')) {
                        $('#mensaje').hide();
                        $('#estado').val('0');
                        $(this).attr('disabled','disabled');
                        $('#forma').submit();
                    }
                });

                $('#volver').click(function() {
                    location.href = 'question.php?id_quiz=<?php echo $idQuiz; ?>';
                });

                $('#limpiar').click(function() {
                    limpiarForma();
                });

                // Nestable
                var updateOutput = function(e) {
                    var list = e.length ? e : $(e.target),
                        output = list.data('output');
                    if (window.JSON) {
                        output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                    } else {
                        output.val('JSON browser support required for this demo.');
                    }
                    $.ajax({
                        method: "POST",
                        url: "service/savequestionoptionorder.php",
                        data: {
                            list: list.nestable('serialize')
                        },
                        success: function (result) {
                            console.log(result);
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown){
                        console.log("No se puede grabar el orden de la lista: " + errorThrown);
                    });
                };

                $('#nestable2').nestable({
                    group: 1
                }).on('change', updateOutput);
                updateOutput($('#nestable2').data('output', $('#nestable2-output')));

                $('.ver-option').click(function() {
                    limpiarForma();
                    var idQuestionOption = $(this).attr('data-id');
                    var param = { "questionOptionID" : idQuestionOption };
                    var paramJSON = JSON.stringify(param);
                    $.ajax({
                        type: 'POST',
                        url: 'service/getquestionoptionbyid.php',
                        data: paramJSON,
                        dataType: 'json',
                        error: function(errorResult) {
                            console.log('Ha ocurrido un error ' + errorResult.error());
                        },
                        success: function (result) {
                            console.log(result);
                            $('#id_question_option').val(result.data[0].questionOptionID);
                            $('#descripcion').val(result.data[0].description);
                            $('#orden').val(result.data[0].order);
                            $('#correcta').val(result.data[0].isRight);
                            $('#estado').val(result.data[0].active)
                            $('#dato-registro').html(result.data[0].registerUser + ' - ' + result.data[0].registerDate);
                            if (result.data[0].updateDate != null) {
                                $('#dato-modifica').html(result.data[0].updateUser + ' - ' + result.data[0].updateDate);
                            } else {
                                $('#dato-modifica').html('');
                            }
                            $('#eliminar').show();
                            $('#descripcion').focus();
                        }
                    });
                });

                function limpiarForma() {
                    $('#mensaje').hide();
                    $('#eliminar').hide();
                    $('#descripcion').val('');
                    $('#orden').val('');
                    $('#correcta').val('0');
                    $('#dato-registro').html('');
                    $('#dato-modifica').html('');
                }

            });

        </script>
    </body>
</html>