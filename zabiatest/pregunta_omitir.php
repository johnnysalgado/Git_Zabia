<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_cuestionario.php');
    require('inc/functions.php');
    require('inc/constante_cuestionario.php');

    if (isset($_POST["id_pregunta"])) {
        $idPregunta = $_POST["id_pregunta"];
        $arregloRespuestaAfecta = [];
        $arregloPreguntaRespuestaAfecta = [];
        $usuario = $_SESSION["U"];
        if (isset($_POST["respuesta_afecta"])) {
            $arregloRespuestaAfecta = $_POST["respuesta_afecta"];
            foreach ($arregloRespuestaAfecta as $item) {
                $afectas = explode("_", $item);
                $idPreguntaAfecta = $afectas[0];
                $idRespuestaAfecta = $afectas[1];
                array_push($arregloPreguntaRespuestaAfecta, array('id_pregunta_afecta' => $idPreguntaAfecta, 'id_respuesta_afecta' => $idRespuestaAfecta));
            }
        }
        $daoCuestionario = new DaoCuestionario();
        $idSeccion = $daoCuestionario->actualizarPreguntaOmitir($idPregunta, $arregloPreguntaRespuestaAfecta, $usuario);
        $daoCuestionario = null;
        header("Location: pregunta.php");
        die();
    } else {
        $idPregunta = $_GET['id'];
        if (is_numeric($idPregunta)) {
            $descripcion = "";
            $daoCuestionario = new DaoCuestionario();
            $arregloPregunta = $daoCuestionario->obtenerPregunta($idPregunta);
            if (count($arregloPregunta) > 0) {
                $descripcion = $arregloPregunta[0]['descripcion'];
            }
            $arregloPreguntaRespuesta = $daoCuestionario->listarPreguntaRespuesta(DATOESPECIAL_PRECONDICION, DATOESPECIAL_INTOLERANCIA_ALERGIA, DATOESPECIAL_TIPO_DIETA,$idPregunta);
            $arregloPreguntaRespuestaAfecta = $daoCuestionario->listarPreguntaEnlazadaOmitir($idPregunta);
            $htmlPreguntaRespuesta = obtenerHtmlListaPreguntaRespuesta($arregloPreguntaRespuesta, $arregloPreguntaRespuestaAfecta);
            $daoCuestionario = null;
        } else {
            header("Location: pregunta.php");
            die();
        }
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
                            <h4 class="page-title">Omitir pregunta: <?php echo $descripcion; ?>, Si se activa las siguientes respuestas:</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="pregunta_omitir.php" method="post" id="forma">
                                <?php echo $htmlPreguntaRespuesta; ?>
                                <br/>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <input type="hidden" name="id_pregunta" name="id_pregunta" value="<?php echo $idPregunta; ?>" />
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#divError').hide();
                $('#nav-health').addClass('active');
                $('#nav-health-question').addClass('active');
            });

            $('#volver').click(function() {
                location.href = 'pregunta.php';
            });

            $('#grabar').click(function() {
                $(this).attr('disabled','disabled');
                $('#forma').submit();
            });
        </script>
    </body>
</html>