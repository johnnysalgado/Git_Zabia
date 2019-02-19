<?php
    require('inc/sesion.php');
    require('inc/constante_informe.php');
    require('inc/constante_cuestionario.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["id_notainforme"])) {

        $idNotaInforme = $_POST["id_notainforme"];
        $titulo = $_POST["titulo"];
        $parrafo = $_POST["parrafo"];
        $orden = $_POST["orden"];
        $idSeccion = $_POST["id_seccion"];
        $usuario = $_SESSION["U"];
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }
        $cuestionario = [];
        if (isset($_POST["cuestionario"])) {
            $cuestionarios = $_POST["cuestionario"];
        }

        if ($titulo != "") {
            $titulo = str_replace("'", "''", $titulo);
        }
        if ($parrafo != "") {
            $parrafo = str_replace("'", "''", $parrafo);
        }

        $query = "UPDATE notainforme SET titulo = '" . $titulo . "', parrafo = '" . $parrafo . "', orden = " . $orden . ", estado = " . $estado . ", usuario_modifica = '" . $usuario . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_notainforme = " . $idNotaInforme;

        $cnx->execute($query);

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
        $query = "UPDATE notainforme_cuestionario SET estado = 0 WHERE id_notainforme = " . $idNotaInforme;
        $cnx->execute($query);
        foreach ($cuestionarios as $cuestionario) {
            if (is_numeric($cuestionario)) {
                $query = "SELECT id_notainforme_cuestionario FROM notainforme_cuestionario WHERE id_respuesta = " . $cuestionario . " AND id_notainforme = " . $idNotaInforme;
                $existe = false;
                $sql = $cnx->query($query);
                $sql->read();
                if ($sql->count() > 0) {
                    $query = "UPDATE notainforme_cuestionario SET estado = 1, usuario_modifica = '" . $usuario . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_respuesta = " . $cuestionario . " AND id_notainforme = " . $idNotaInforme;
                } else {
                    $query = "INSERT INTO notainforme_cuestionario (id_notainforme, id_respuesta, estado, usuario_registro) VALUES (" . $idNotaInforme . ", " . $cuestionario . ", 1, '". $usuario . "');";
                }
                $cnx->execute($query);
            } else {
                $query = "SELECT id_notainforme_cuestionario FROM notainforme_cuestionario WHERE codigo_especial = '" . $cuestionario . "' AND id_notainforme = " . $idNotaInforme;
                $existe = false;
                $sql = $cnx->query($query);
                $sql->read();
                if ($sql->count() > 0) {
                    $query = "UPDATE notainforme_cuestionario SET estado = 1, usuario_modifica = '" . $usuario . "', fecha_modifica = CURRENT_TIMESTAMP WHERE codigo_especial = '" . $cuestionario . "' AND id_notainforme = " . $idNotaInforme;
                } else {
                    $query = "INSERT INTO notainforme_cuestionario (id_notainforme, codigo_especial, estado, usuario_registro) VALUES (" . $idNotaInforme . ", '" . $cuestionario . "', 1, '". $usuario . "');";
                }
                $cnx->execute($query);
            }
        }

        $cnx = null;
        header("Location: informenotas.php?idSeccion=" . $idSeccion);
        die();
    
    } else {

        $idNotaInforme = $_GET["id"];
        $query = "SELECT id_notainforme_padre, titulo, parrafo, orden, fecha_registro, usuario_registro, fecha_modifica, usuario_modifica, estado FROM notainforme WHERE id_notainforme = " . $idNotaInforme;
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            $idSeccion = $sql->field('id_notainforme_padre');
            $titulo = $sql->field('titulo');
            $parrafo = $sql->field('parrafo');
            $orden = $sql->field('orden');
            $estado = $sql->field('estado');
            $usuarioRegistro = $sql->field('usuario_registro');
            $fechaRegistro = $sql->field('fecha_registro');
            $usuarioModifica = $sql->field('usuario_modifica');
            $fechaModifica = $sql->field('fecha_modifica');
        }
        //imágenes
        $query = "SELECT id_notainforme_imagen, nombre FROM notainforme_imagen WHERE id_notainforme = " . $idNotaInforme . " AND estado = 1";
        $htmlImagen = "";
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $idImagen = $sql->field('id_notainforme_imagen');
            $nombreImagen = $sql->field('nombre');
            $htmlImagen .= '<div class="col-md-3" id="div_' . $idImagen . '">  <img src="' . REPORT_IMAGE_SHORT_PATH . $nombreImagen .'" class="img-responsive thumbnail m-r-15 ver-imagen" role="button" alt="" data-image="' . $nombreImagen . '" data-id="div_' . $idImagen . '" /> </div>';
        }

        //relación con respuestas de cuestionario
        $query = "SELECT a.descripcion_ing pregunta, b.id_respuesta, b.descripcion_ing respuesta, a.tipo_pregunta, c.id_notainforme_cuestionario FROM pregunta a INNER JOIN respuesta b ON a.id_pregunta = b.id_pregunta LEFT OUTER JOIN notainforme_cuestionario c ON b.id_respuesta = c.id_respuesta AND c.estado = 1 AND c.id_notainforme = " . $idNotaInforme . " WHERE a.estado = 1 AND b.estado = 1 ORDER BY a.orden_tipo_pregunta, a.orden, b.orden";
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
            $idNotaInformeCuestionario = $sql->field('id_notainforme_cuestionario');
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
            $htmlCuestionario .= '<div class="col-md-3 col-xs-3"> <div class="checkbox checkbox-success"> <input type="checkbox" name="cuestionario[]" value="' . $idRespuesta . '"';
            if ($idNotaInformeCuestionario != 0) {
                $htmlCuestionario .= ' checked="checked"';
            }
            $htmlCuestionario .= ' /> <label>' . $respuesta . '</label> </div> </div>';
            $preguntax = $pregunta;
            $tipoPreguntax = $tipoPregunta;
            $contadorRespuesta ++;
        }
        $htmlCuestionario .= '</div>';

        //Especiales - IMC
        $query = "SELECT a.codigo_especial, a.descripcion, b.id_notainforme_cuestionario FROM especial a LEFT OUTER JOIN notainforme_cuestionario b ON a.codigo_especial = b.codigo_especial AND b.estado = 1 AND b.id_notainforme = " . $idNotaInforme . " WHERE a.estado = 1 AND a.codigo_especial LIKE 'imc_%' ORDER BY a.orden";
        $htmlCuestionario .= '<h4>IMC</h4> <div class="row">';
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $codigoEspecial = $sql->field('codigo_especial');
            $descripcion = $sql->field('descripcion');
            $idNotaInformeCuestionario = $sql->field('id_notainforme_cuestionario');
            $htmlCuestionario .= '<div class="col-md-3 col-xs-3"> <div class="checkbox checkbox-success"> <input type="checkbox" name="cuestionario[]" value="' . $codigoEspecial . '"';
            if ($idNotaInformeCuestionario != 0) {
                $htmlCuestionario .= ' checked="checked"';
            }
            $htmlCuestionario .= ' /> <label>' . $descripcion . '</label> </div> </div>';
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
                            <h4 class="page-title">Modificaci&oacute;n de Nota de Informe</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="informenota_editar.php" method="post" enctype="multipart/form-data" id="forma-nota">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>T&iacute;tulo</label>
                                            <input type="text" name="titulo" id="titulo" class="form-control" maxlength="256" value="<?php echo $titulo; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>P&aacute;rrafo</label>
                                            <textarea name="parrafo" id="parrafo" class="form-control" rows="4"><?php echo $parrafo; ?></textarea>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php echo $htmlImagen; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>N&deg; orden</label>
                                            <input type="number" name="orden" id="orden"  value="<?php echo $orden; ?>" class="form-control" step="1" min="0" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <br/>
                                                <input type="checkbox" name="estado" id="estado" <?php if ($estado == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="estado">Activo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <h3>Relaci&oacute;n con Reporte de Salud</h3>
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
                                <input type="hidden" id="id_notainforme" name="id_notainforme" value="<?php echo $idNotaInforme;?>" />
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <input type="button" id="abrir-modal" value="abrir modal" data-toggle="modal" data-target="#imagen-modal" style="display:none;"/>
        <div class="modal fade" tabindex="-1" role="dialog" id="imagen-modal" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Imagen</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <img src="" class="img-responsive thumbnail m-r-15" alt="" id="ver-imagen-modal" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-eliminar-imagen" id="button-eliminar-imagen" >Eliminar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="close-imagen">Cerrar</button>
                        <input type="hidden" name="imagen_eliminar" id="imagen_eliminar" value="" />
                        <input type="hidden" name="div_eliminar" id="div_eliminar" value="" />
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
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
            $('#forma-nota').submit();
        });

        $('body').on('click', '.ver-imagen', function() {
            $('#ver-imagen-modal').attr('src', $(this).attr('src'));
            $('#imagen_eliminar').val($(this).attr('data-image'));
            $('#div_eliminar').val($(this).attr('data-id'));
            $('#abrir-modal').trigger('click');
        });

        $('body').on('click', '.btn-eliminar-imagen', function () {
            if (confirm('¿Está seguro de eliminar la imagen?')) {
                var nombreImagen = $('#imagen_eliminar').val();
                var divImagen = $('#div_eliminar').val();
                var param = { "idReport" : "<?php echo $idNotaInforme; ?>"
                                , "user" : "<?php echo $_SESSION["U"]; ?>"
                                , "image" : nombreImagen};
                var paramJSON = JSON.stringify(param);
                $.ajax({
                    type: 'POST',
                    url: 'service/deleteimagereport.php',
                    data: paramJSON,
                    dataType: 'json',
                    success: function (result) {
                        if (result.status == '1') {
                        console.log('resul entra : '+ result.status);
                            $('#' + divImagen).hide();
                        }
                        $('#close-imagen').trigger('click');
                    }
                });
            }
        });
        </script>
    </body>
</html>