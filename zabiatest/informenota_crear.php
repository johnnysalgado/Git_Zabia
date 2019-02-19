<?php
    require('inc/sesion.php');
    require('inc/constante_informe.php');
    require('inc/constante_cuestionario.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["titulo"])) {

        $titulo = $_POST["titulo"];
        $parrafo = $_POST["parrafo"];
        $orden = $_POST["orden"];
        $idSeccion = $_POST["id_seccion"];
        $usuario = $_SESSION["U"];
        $cuestionario = [];
        if (isset($_POST["cuestionario"])) {
            $cuestionarios = $_POST["cuestionario"];
        }

        if ($orden == "") {
            $orden = 0;
        }
        if ($titulo != "") {
            $titulo = str_replace("'", "''", $titulo);
        }
        if ($parrafo != "") {
            $parrafo = str_replace("'", "''", $parrafo);
        }

        $query = "INSERT INTO notainforme (titulo, parrafo, id_notainforme_padre, tipo_notainforme, orden, usuario_registro) VALUES ('" . $titulo . "', '" . $parrafo . "', " . $idSeccion . ", '" . TIPO_NOTA_NOTA . "', " . $orden . ", '" . $usuario . "');";

        $idNotaInforme = $cnx->insert($query);

        $file_count = count($_FILES["fileToUpload"]['tmp_name']);
        for ($i = 0; $i < $file_count; $i++) {
            $tmpFilePath = $_FILES["fileToUpload"]["tmp_name"][$i];
            if ($tmpFilePath != "") {
                $mt = microtime(true);
                $mt =  $mt * 1000;
                $ticks = (string) $mt * 10;
                $target_dir = REPORT_IMAGE_SHORT_PATH;
                $nombreImagen = $ticks . basename($_FILES["fileToUpload"]["name"][$i]);
                $target_file = REPORT_IMAGE_SHORT_PATH . $nombreImagen;
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
                    $cnx->insert("INSERT INTO notainforme_imagen (id_notainforme, nombre, usuario_registro) VALUES (" . $idNotaInforme . ", '" . $nombreImagen . "', '" . $usuario . "')");
                }
            }
        }

        //cuestionario
        foreach ($cuestionarios as $cuestionario) {
            if (is_numeric($cuestionario)) {
                $query = "INSERT INTO notainforme_cuestionario (id_notainforme, id_respuesta, estado, usuario_registro) VALUES (" . $idNotaInforme . ", " . $cuestionario . ", 1, '". $usuario . "');";
            } else {
                $query = "INSERT INTO notainforme_cuestionario (id_notainforme, codigo_especial, estado, usuario_registro) VALUES (" . $idNotaInforme . ", '" . $cuestionario . "', 1, '". $usuario . "');";
            }
            $cnx->execute($query);
        }

        $cnx = null;
        header("Location: informenotas.php?idSeccion=" . $idSeccion);
        die();
    
    } else {

        $idSeccion = $_GET["idSeccion"];
        //relación con respuestas de cuestionario
        $query = "SELECT a.descripcion_ing pregunta, a.tipo_pregunta, b.id_respuesta, b.descripcion_ing respuesta FROM pregunta a INNER JOIN respuesta b ON a.id_pregunta = b.id_pregunta WHERE a.estado = 1 AND b.estado = 1 ORDER BY a.orden_tipo_pregunta, a.orden, b.orden";
        $htmlCuestionario = "";
        $sql = $cnx->query($query);
        $sql->read();
        $preguntax = "";
        $tipoPreguntax = "";
        $contadorRespuesta = 0;
        $contadorPregunta = 0;
        while ($sql->next()) {
            $pregunta = $sql->field('pregunta');
            $idRespuesta = $sql->field('id_respuesta');
            $respuesta = $sql->field('respuesta');
            $tipoPregunta = $sql->field('tipo_pregunta');
            if ($pregunta != $preguntax) {
                $contadorPregunta ++;
                if ($contadorRespuesta > 0) {
                    $htmlCuestionario .= '</div>';            
                }
                if ($tipoPregunta != $tipoPreguntax) {
                    $htmlCuestionario .= '<h4>' . $tipoPregunta . '</h4>';
                }
                $htmlCuestionario .= '<h5>' . $contadorPregunta . '.- ' . $pregunta . '</h5> <div class="row">';
            }
            $htmlCuestionario .= '<div class="col-md-3 col-xs-3"> <div class="checkbox checkbox-success"> <input type="checkbox" name="cuestionario[]" value="' . $idRespuesta . '" /> <label>' . $respuesta . '</label> </div> </div>';
            $preguntax = $pregunta;
            $tipoPreguntax = $tipoPregunta;
            $contadorRespuesta ++;
        }
        $htmlCuestionario .= '</div>';

        //Especiales - IMC
        $query = "SELECT a.codigo_especial, a.descripcion FROM especial a WHERE a.estado = 1 AND a.codigo_especial LIKE 'imc_%' ORDER BY a.orden";
        $htmlCuestionario .= '<h4>IMC</h4> <div class="row">';
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $codigoEspecial = $sql->field('codigo_especial');
            $descripcion = $sql->field('descripcion');
            $htmlCuestionario .= '<div class="col-md-3 col-xs-3"> <div class="checkbox checkbox-success"> <input type="checkbox" name="cuestionario[]" value="' . $codigoEspecial . '" /> <label>' . $descripcion . '</label> </div> </div>';
        }
         $htmlCuestionario .= '</div>';

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
                            <h4 class="page-title">Creaci&oacute;n de Nota de Informe</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="informenota_crear.php" method="post" enctype="multipart/form-data" id="forma-nota">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>T&iacute;tulo</label>
                                            <input type="text" name="titulo" id="titulo" class="form-control" maxlength="256" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>P&aacute;rrafo</label>
                                            <textarea name="parrafo" id="parrafo" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Subir imagen</label>
                                            <input type="file" name="fileToUpload[]" multiple class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>N&deg; orden</label>
                                            <input type="number" name="orden" id="orden" value="" class="form-control" step="1" min="0" />
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <h4>Relaci&oacute;n con Reporte de Salud</h4>
                                <?php echo $htmlCuestionario; ?>
                                <br />
                                <div id="divError" class="row alert alert-danger"></div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nueva-nota" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <input type="hidden" id="id_seccion" name="id_seccion" value="<?php echo $idSeccion;?>" />
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
        $(document).ready(function () {
            $('#divError').hide();
            $('#nav-informe').addClass('active');
        });

        $('#volver').click(function() {
            location.href = 'informenotas.php?idSeccion=' + $('#id_seccion').val();
        });

        $("#nueva-nota").click(function() {
            $('#divError').hide();
            $(this).attr('disabled','disabled');
            $('#forma-nota').submit();
        });
        </script>
    </body>
</html>