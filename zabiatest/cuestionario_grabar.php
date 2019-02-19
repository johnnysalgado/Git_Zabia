<?php
    require('inc/sesion.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/constante_informe.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    
    $cnx = new MySQL();

//    var_dump($_POST);
  
    $idRespuestaTexto = "";
    $peso = 0;
    $talla = 0;
    $query = "SELECT a.id_pregunta, a.codigo, a.tipo_respuesta FROM pregunta a WHERE a.estado = 1 ORDER BY a.id_pregunta";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $idPregunta = $sql->field('id_pregunta');
        $codigo = $sql->field('codigo');
        $tipoRespuesta = $sql->field('tipo_respuesta');
        $nombreInput = "pregunta_" . $idPregunta;
        if (isset($_POST[$nombreInput])) {
            if ($tipoRespuesta == TIPO_RESPUESTA_UNICA || $tipoRespuesta == TIPO_RESPUESTA_MULTIPLE) {
                if ($_POST[$nombreInput] != "") {
                    $idRespuestaTexto .= $_POST[$nombreInput] . ', ';
                }
            } else {
                if ($codigo == CODIGO_PREGUNTA_PESO) {
                    $peso = $_POST[$nombreInput];
                    if ($peso == '') {
                        $peso = 0;
                    }
                } else if ($codigo == CODIGO_PREGUNTA_TALLA) {
                    $talla = $_POST[$nombreInput];
                    if ($talla == '') {
                        $talla = 0;
                    }
                }
            }
        }
    }

    if ($idRespuestaTexto != "") {
        $idRespuestaTexto = substr($idRespuestaTexto, 0, strlen($idRespuestaTexto) - 2);
    }
    //calcular IMC
    if ($talla != 0) {
        $imc = $peso / ($talla * $talla);
        $imc = round($imc, 2);
    } else {
        $imc = 0;
    }

    $idBeneficioTexto = "";
    if ($imc > 30) {
        $idBeneficioTexto = ID_BENEFICIO_PREVENCION_ENFERMEDAD . ", " . ID_BENEFICIO_PERDIDA_PESO;
    } else if ($imc > 25) {
        $idBeneficioTexto = ID_BENEFICIO_PERDIDA_PESO;
    }

    //Beneficios
    $htmlBeneficio = "";
    if ($idRespuestaTexto != "" || $idBeneficioTexto != "") {

        $query = "SELECT DISTINCT a.id_beneficio, a.nombre FROM beneficio a INNER JOIN respuesta_beneficio b ON a.id_beneficio = b.id_beneficio WHERE b.estado = 1 AND a.estado = 1";
        if ($idRespuestaTexto != "") {
            $query .= " AND ( b.id_respuesta IN (" . $idRespuestaTexto . ") )";
        }
        if ($idBeneficioTexto != "") {
            if ($idRespuestaTexto != "") {
                $query .= " OR ( b.id_beneficio IN (" . $idBeneficioTexto . ") ) ";
            } else {
                $query .= " AND ( b.id_beneficio IN (" . $idBeneficioTexto . ") ) ";
            }
        }
        $query .= " ORDER BY a.nombre";
        $sql = $cnx->query($query);
        $sql->read();
        while($sql->next()) {
            $beneficio = $sql->field('nombre');
            $htmlBeneficio .= '<div class="row" >';
            $htmlBeneficio .= '<div class="col-md-6 col-xs-6"> <li>' . $beneficio . '</li> </div>';
            $htmlBeneficio .= '</div>';
        }
    }

    //Sugerencias
    if ($idRespuestaTexto != "") {
        $query = "SELECT DISTINCT descripcion, tipo_sugerencia FROM sugerencia WHERE estado = 1 AND ( id_respuesta IN (" . $idRespuestaTexto . ") )";
        if ($imc > 0) {
            $query .= " OR ( id_sugerencia IN ( SELECT id_sugerencia FROM sugerencia WHERE estado = 1 AND codigo_especial = '";
            if ($imc > 30) {
                $query .= IMC_MAY30;
            } else if ($imc > 25) {
                $query .= IMC_MAY25;
            } else {
                $query .= IMC_MEN25;
            }
            $query .= "' ) )";
        }
        $query .= " ORDER BY tipo_sugerencia";
    } else {
        $query = "SELECT DISTINCT descripcion, tipo_sugerencia FROM sugerencia WHERE estado = 1 AND id_respuesta IS NULL AND codigo_especial IS NULL ORDER BY tipo_sugerencia";
    }

    $htmlSugerencia = "";
    $tipoSugerenciax = "";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $sugerencia = $sql->field('descripcion');
        $tipoSugerencia = $sql->field('tipo_sugerencia');
        if ($tipoSugerencia != $tipoSugerenciax) {
            $htmlSugerencia .= '<div class="row" >';
            $htmlSugerencia .= '<div class="col-md-6 col-xs-6"> <h5>' . ucwords($tipoSugerencia) . '</h5> </div>';
            $htmlSugerencia .= '</div>';
        }
        $htmlSugerencia .= '<div class="row" >';
        $htmlSugerencia .= '<div class="col-md-6 col-xs-6"> <li>' . $sugerencia . '</li> </div>';
        $htmlSugerencia .= '</div>';
        $tipoSugerenciax = $tipoSugerencia;
    }

    //pdf
    $urlHtml = '';
    if ($idRespuestaTexto != "") {
        $query = "SELECT DISTINCT a.id_notainforme, a.titulo AS titulo_seccion, a.parrafo AS parrafo_seccion FROM notainforme a INNER JOIN notainforme b ON a.id_notainforme = b.id_notainforme_padre WHERE a.estado = 1 AND b.estado = 1 AND b.id_notainforme IN ( SELECT DISTINCT id_notainforme FROM notainforme_cuestionario WHERE id_respuesta IN (" . $idRespuestaTexto . ")";
        if ($imc > 0) {
            $query .= " OR ( codigo_especial = '";
            if ($imc > 30) {
                $query .= IMC_MAY30;
            } else if ($imc > 25) {
                $query .= IMC_MAY25;
            } else {
                $query .= IMC_MEN25;
            }
            $query .= "' )";
        }
        $query .= " ) ORDER BY a.orden";

        $mt = microtime(true);
        $mt =  $mt*1000;
        $ticks = (string)$mt*10;
        $urlHtml = PDF_SHORT_PATH . 'informe' . $ticks . '.html';

        $htmlInforme = '<!DOCTYPE html>';
        $htmlInforme .= ' <html lang="es">';
        $htmlInforme .= ' <head> <title>PDF - Zabia Report</title> ';
        $htmlInforme .= ' <style> ';
        $htmlInforme .= ' img {display: block; margin-left: auto; margin-right: auto; width: 40%; border: solid 2px green; }';
        $htmlInforme .= ' span {font-family: verdana; font-size: 14px; }';
        $htmlInforme .= ' .imagen {padding: 10px 5px 5px 5px; }';
        $htmlInforme .= ' </style> ';
        $htmlInforme .= ' </head>';
        $htmlInforme .= ' <body>';
        $htmlInforme .= ' <center> <h1 style="font-family:verdana;"> Reporte de prueba </h1> </center>';
        $sql = $cnx->query($query);
        $sql->read();
        while($sql->next()) {
            $idSeccion = $sql->field('id_notainforme');
            $tituloSeccion = $sql->field('titulo_seccion');
            $parrafoSeccion = $sql->field('parrafo_seccion');
            $htmlInforme .= ' <h2 style="font-family:verdana;">' . $tituloSeccion . '</h2>';
            if ($parrafoSeccion != '') {
                $htmlInforme .= ' <div> <span>' . $parrafoSeccion . '</span> </div>';
            }
            $query = "SELECT DISTINCT a.id_notainforme, a.titulo AS titulo_nota, a.parrafo AS parrafo_nota FROM notainforme a WHERE a.estado = 1 AND a.id_notainforme_padre = " . $idSeccion . " AND a.id_notainforme IN ( SELECT DISTINCT id_notainforme FROM notainforme_cuestionario WHERE id_respuesta IN (" . $idRespuestaTexto . ")";
            if ($imc > 0) {
                $query .= " OR ( codigo_especial = '";
                if ($imc > 30) {
                    $query .= IMC_MAY30;
                } else if ($imc > 25) {
                    $query .= IMC_MAY25;
                } else {
                    $query .= IMC_MEN25;
                }
                $query .= "' )";
            }
            $query .= " ) ORDER BY a.orden";
            $sql2 = $cnx->query($query);
            $sql2->read();
            while($sql2->next()) {
                $idNota = $sql2->field('id_notainforme');
                $tituloNota = $sql2->field('titulo_nota');
                $parrafoNota = $sql2->field('parrafo_nota');
                $htmlInforme .= ' <h3 style="font-family: verdana;">' . $tituloNota . '</h3>';
                if ($parrafoNota != '') {
                    $htmlInforme .= ' <div> <span>' . $parrafoNota . '</span> </div>';
                }
                $query = "SELECT a.nombre FROM notainforme_imagen a WHERE a.estado = 1 AND a.id_notainforme = " . $idNota;
                $sql3 = $cnx->query($query);
                $sql3->read();
                while($sql3->next()) {
                    $imagen = BASE_PATH . REPORT_IMAGE_SHORT_PATH . $sql3->field('nombre');
                    $htmlInforme .= ' <div class="imagen"> <img src="' . $imagen . '" alt="" /> </div>';
                }
            }
        }
        $htmlInforme .= ' </body>';
        $htmlInforme .= ' </html>';

        $archivo = fopen($urlHtml, 'a');
        fputs($archivo, $htmlInforme);
        fclose($archivo);
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
                        <div class="col-lg-6 col-md-6 col-sm6 col-xs-12">
                            <h4 class="page-title">Prueba de Cuestionario de Salud</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h4>IMC : <?php echo $imc; ?></h4>
                            <hr />
                            <h4>Sugerencias:</h4>
                            <?php echo $htmlSugerencia ?>
                            <hr />
                            <h4>Beneficios</h4>
                            <?php echo $htmlBeneficio ?>
                            <hr />
                            <div class="row">
                                <div class="col-md-12 col-xs-12 text-right">
                                    <button class="btn btn-success btn-ver-pdf">Ver PDF</button>
                                </div>
                            </div>
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

        $('.btn-ver-pdf').click(function() {
            location.href = 'cuestionario_pdf.php?url_html=<?php echo $urlHtml; ?>';
        });
        </script>
    </body>
</html>
