<?php
    require('inc/sesion.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_enfermedad.php');
    require('inc/dao_intolerancia.php');
    
    $daoEnfermedad = new DaoEnfermedad();
    $arregloEnfermedad = $daoEnfermedad->listarEnfermedad();
    $daoEnfermedad = null;
    $daoIntolerancia = new DaoIntolerancia();
    $arregloIntolerancia = $daoIntolerancia->listarIntolerancia();
    $daoIntolerancia = null;

    $conn = new MySQL();
    $html = "";
    $i = 0;
    $query = "SELECT a.id_pregunta, a.descripcion, a.descripcion_ing, b.descripcion AS tipo_pregunta, a.tipo_respuesta, a.codigo, a.dato_especial, a.orden FROM pregunta a INNER JOIN seccion_cuestionario b ON a.id_seccion_cuestionario = b.id_seccion_cuestionario WHERE a.estado = 1 ORDER BY b.orden, a.orden";
    $sql = $conn->query($query);
    $sql->read();
    $tipoPreguntax = "";
    while($sql->next()) {
        $idPregunta = $sql->field('id_pregunta');
//        $descripcionPregunta = $sql->field('descripcion_ing');
        $descripcionPregunta = $sql->field('descripcion');
        $tipoPregunta = $sql->field('tipo_pregunta');
        $tipoRespuesta = $sql->field('tipo_respuesta');
        $codigo = $sql->field('codigo');
        $datoEspecial = $sql->field('dato_especial');
        $orden = $sql->field('orden');
        $i++;
        if ($tipoPregunta != $tipoPreguntax) {
            $html .= '<tr >';
            $html .= '<td colspan="3"><h3> '.$tipoPregunta.'</h3></td>';
            $html .= '</tr>';
        } 
        $tipoPreguntax = $tipoPregunta;
        $html .= '<tr >';
        $html .= '<td style="width:5%">'.$i.'</td>';
        $html .= '<td style="width:30%">'.$descripcionPregunta.'</td>';
        $html .= '<td style="width:65%">';
        $nombreInput = "pregunta_" . $idPregunta;
        if ($datoEspecial == DATOESPECIAL_PAIS) {
            $query2 = "SELECT a.cod_pais, a.nombre FROM pais a WHERE a.estado = 1 ORDER BY a.nombre";
            $sql2 = $conn->query($query2);
            $sql2->read();
            $html .= '<select name="' . $nombreInput . '" class="form-control"> <option value="">[Select]</option>';
            while ($sql2->next()) {
                $idRespuesta = $sql2->field('cod_pais');
                $descripcionRespuesta = $sql2->field('nombre');
                $html .= '<option value="' . $idRespuesta . '">' . $descripcionRespuesta . '</option>';
            }
            $html .= '</select>';
        } else if ($datoEspecial == DATOESPECIAL_PRECONDICION) {
            $html .= '<div class="row">';
            foreach ($arregloEnfermedad as $item) {
                $idEnfermedad = $item['id_enfermedad'];
                $enfermedad = $item['nombre'];
                $html .= '<div class="col-md-3 col-xs-3">';
                $html .= '<input type="checkbox" name="' . $nombreInput . '" value="' . $idEnfermedad . '" /> ' . $enfermedad;
                $html .= '</div>';
            }
            $html .= '</div>';
        } else if ($datoEspecial == DATOESPECIAL_INTOLERANCIA_ALERGIA) {
            $html .= '<div class="row">';
            foreach ($arregloIntolerancia as $item) {
                $idIntolerancia = $item['id_intolerancia'];
                $intolerancia = $item['nombre'];
                $html .= '<div class="col-md-3 col-xs-3">';
                $html .= '<input type="checkbox" name="' . $nombreInput . '" value="' . $idIntolerancia . '" /> ' . $intolerancia;
                $html .= '</div>';
            }
            $html .= '</div>';
        } else if ($datoEspecial == DATOESPECIAL_TIPO_DIETA) {
            $html .= '<div class="row">';
            $query3 = "SELECT id_tipo_dieta, nombre as respuesta FROM tipo_dieta WHERE estado = 1 ORDER BY nombre";
            $sql3 = $conn->query($query3);
            $sql3->read();
            while($sql3->next()) {
                $idTipoDieta = $sql3->field('id_tipo_dieta');
                $tipoDieta = $sql3->field('respuesta');
                $html .= '<div class="col-md-3 col-xs-3">';
                $html .= '<input type="checkbox" name="' . $nombreInput . '" value="' . $idTipoDieta . '" /> ' . $tipoDieta;
                $html .= '</div>';
            }
            $html .= '</div>';
        } else {
            if ($tipoRespuesta == TIPO_RESPUESTA_NUMERO) {
                $html .= '<input type="number" name="' . $nombreInput . '" step="1" min="0" class="form-control" />';
            } else if ($tipoRespuesta == TIPO_RESPUESTA_DECIMAL) {
                $html .= '<input type="number" name="' . $nombreInput . '" step="0.01" min="0" class="form-control" />';
            } else if ($tipoRespuesta == TIPO_RESPUESTA_TEXTO) {
                $html .= '<input type="text" name="' . $nombreInput . '" class="form-control" />';
            } else if ($tipoRespuesta == TIPO_RESPUESTA_FECHA) {
                $html .= '<input type="date" name="' . $nombreInput . '" class="form-control" />';
            } else if ($tipoRespuesta == TIPO_RESPUESTA_UNICA) {
                $query2 = "SELECT a.id_respuesta, a.descripcion, a.descripcion_ing FROM respuesta a WHERE a.estado = 1 AND a.id_pregunta = " . $idPregunta . " ORDER BY a.orden";
                $sql2 = $conn->query($query2);
                $sql2->read();
                $html .= '<div class="row">';
                while($sql2->next()) {
                    $idRespuesta = $sql2->field('id_respuesta');
                    $descripcionRespuesta = $sql2->field('descripcion');
                    $html .= '<div class="col-md-3 col-xs-3">';
                    $html .= '<input type="radio" name="' . $nombreInput . '" value="' . $idRespuesta . '" /> ' . $descripcionRespuesta;
                    $html .= '</div>';
                }
                $html .= '</div>';
            } else if ($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE) {
                $query2 = "SELECT a.id_respuesta, a.descripcion, a.descripcion_ing FROM respuesta a WHERE a.estado = 1 AND a.id_pregunta = " . $idPregunta . " ORDER BY a.orden";
                $sql2 = $conn->query($query2);
                $sql2->read();
                $html .= '<div class="row">';
                while($sql2->next()) {
                    $idRespuesta = $sql2->field('id_respuesta');
                    $descripcionRespuesta = $sql2->field('descripcion');
                    $html .= '<div class="col-md-3 col-xs-3">';
                    $html .= '<input type="checkbox" name="' . $nombreInput . '" value="' . $idRespuesta . '" /> ' . $descripcionRespuesta;
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
        }
        $html .= '</td>';
        $html .= '</tr>';
    }

    $conn->close();
    $conn = null;

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
                            <h4 class="page-title">Prueba de Cuestionario de Salud</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="cuestionario_grabar.php" method="post" id="forma-cuestionario">
                                <table class="table table-bordered">
                                <?php echo $html ?>
                                </table>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="submit" id="nueva-seccion" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
        $(document).ready(function () {
            $('#nav-cuestionario').addClass('active');
        });

        </script>
    </body>
</html>