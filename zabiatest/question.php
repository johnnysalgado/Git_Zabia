<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_quiz.php');
    require('inc/constante_cuestionario.php');

    $numeroPagina = "";
    $palabraSearch = "";
    $longitudPaginacion = "";
    if (isset($_SESSION['INP'])) {
        $numeroPagina = $_SESSION['INP'];
    }
    if (isset($_SESSION['IPS'])) {
        $palabraSearch = $_SESSION['IPS'];
    }
    if (isset($_SESSION['ILP'])) {
        $longitudPaginacion = $_SESSION['ILP'];
    }
    if (isset($_GET['a'])) {
        $longitudPaginacion = "";
        $numeroPagina = "";
        $palabraSearch = "";
    }
    if ($longitudPaginacion == "") {
        $longitudPaginacion = PAGINACION_DEFECTO;
    }
    
    if (isset($_GET['id_quiz']) && is_numeric($_GET['id_quiz'])) {
        $idQuiz = $_GET['id_quiz'];
        $cnx = new MySQL();
        $query = "CALL USP_LIST_QUESTION($idQuiz);";
        $sql = $cnx->query($query);
        $sql->read();
        $html = "";
        while ($sql->next()) {
            $idQuestion = $sql->field("id_question");
            $descripcion = $sql->field('descripcion');
            $tipoRespuesta = $sql->field('cod_tipo_respuesta');
            $orden = $sql->field('orden');
            $html .= '<tr data-id="' . $idQuestion . '">';
            $html .= '<td class="td-question">' . $idQuestion . '</td>';
            $html .= '<td class="td-question">' . $descripcion . '</td>';
            $html .= '<td class="td-question">' . $orden . '</td>';
            $html .= '<td class="td-question-option">';
            if ($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE || $tipoRespuesta == TIPO_RESPUESTA_UNICA) {
                $html .= '<i class="glyphicon glyphicon-option-horizontal"></i>';
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
        $nombreCuestionario = "";
        $cnx->close();
        $cnx = new MySQL();
        $query = "CALL USP_OBTEN_QUIZ($idQuiz);";
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            $nombreCuestionario = $sql->field('nombre');
        }
        $cnx->close();
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
                            <h4 class="page-title">Lista de preguntas para: <?php echo $nombreCuestionario; ?></h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-question" value="Nueva pregunta" class="btn btn-primary nuevo-question" />
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="white-box">
                                <div class="table-responsive">
                                    <table id="question-table" class="table table-striped display" style="cursor:pointer;">
                                        <thead>
                                            <tr>
                                                <th> ID </th>
                                                <th> Pregunta </th>
                                                <th> Orden </th>
                                                <th> Opciones </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $html; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <input type="button" id="volver" value="Volver" class="btn btn-default">
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <?php require('inc/paginacion_hidden.php');?>
        <script type="text/javascript">
            $('.td-question').click(function() {
                var id_question = $(this).closest('tr').attr('data-id');
                mapearConfiguracionDT();
                location.href = "question_editar.php?id_quiz=<?php echo $idQuiz; ?>&id_question=" + id_question;
            });

            $('.td-question-option').click(function() {
                var id_question = $(this).closest('tr').attr('data-id');
                mapearConfiguracionDT();
                location.href = "question_option.php?id_quiz=<?php echo $idQuiz; ?>&id_question=" + id_question;
            });

            $('.nuevo-question').click(function() {
                mapearConfiguracionDT();
                location.href = "question_crear.php?id_quiz=<?php echo $idQuiz; ?>";
            });

            $('#volver').click(function() {
                mapearConfiguracionDT();
                location.href = "quiz.php";
            });

            $(document).ready(function() {
                $('#nav-quiz').addClass('active');
                var table = $('#question-table').DataTable();
                table.on('page.dt', function () {
                    var info = table.page.info();
                    $('#numero-pagina').val(info.page);
                });
                table.on('search.dt', function () {
                    $('#palabra-search').val(table.search());
                });
                table.on('length.dt', function (e, settings, len) {
                    $('#longitud-paginacion').val(len);
                });
            } );
        </script>
        <?php require('inc/paginacion_script.php');?>
    </body>
</html>