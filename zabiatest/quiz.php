<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $query = "CALL USP_LIST_QUIZ();";
    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while ($sql->next()) {
        $idQuiz = $sql->field("id_quiz");
        $nombre = $sql->field('nombre');
        $html .= '<tr data-id="' . $idQuiz . '">';
        $html .= '<td class="td-quiz">' . $idQuiz . '</td>';
        $html .= '<td class="td-quiz">' . $nombre . '</td>';
        $html .= '<td class="td-question"> <i class="glyphicon glyphicon-question-sign"></i> </td>';
        $html .= '</tr>';
    }
    $cnx->close();
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
                        <div class="col-lg-6 col-md-6 col-sm6 col-xs-12">
                            <h4 class="page-title">Lista de Cuestionarios</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-quiz" value="Nuevo cuestionario" class="btn btn-primary nuevo-quiz" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="quiz-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Cuestionario </th>
                                            <th> Preguntas </th>
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
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $('.td-quiz').click(function() {
                var id_quiz = $(this).closest('tr').attr('data-id');
                location.href = "quiz_editar.php?id=" + id_quiz;
            });

            $('.td-question').click(function() {
                var id_quiz = $(this).closest('tr').attr('data-id');
                location.href = "question.php?a=1&id_quiz=" + id_quiz;
            });

            $('.nuevo-quiz').click(function() {
                location.href = "quiz_crear.php";
            });

            $(document).ready(function() {
                $('#quiz-table').DataTable();
                $('#nav-quiz').addClass('active');
            } );
        </script>
    </body>
</html>