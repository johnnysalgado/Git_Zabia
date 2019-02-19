<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_chat.php');
    require('inc/constante_cuestionario.php');

    if (isset($_POST["id_padre"])) {

        $idPadre = $_POST["id_padre"];
        $idFlujoChat = $_POST["id_flujochat"];
        $descripcion = $_POST["descripcion"];
        $descripcionIngles = $_POST["descripcion_ing"];
        $tipoFuncion = $_POST["tipo_funcion"];
        $tipoPresentacion = $_POST["tipo_presentacion"];
        $idPregunta = $_POST["id_pregunta"];
        $tag = $_POST["tag"];
        $cantidadRegistro = $_POST["cantidad_registro"];
        $usuario = $_SESSION["U"];
        if ($descripcion != "") {
            $descripcion = str_replace("'", "", $descripcion);
        } else {
            $descripcion = "";
        }
        if ($descripcionIngles != "") {
            $descripcionIngles = str_replace("'", "", $descripcionIngles);
        } else {
            $descripcionIngles = "";
        }
        if ($tag != "") {
            $tag = str_replace("'", "", $tag);
        } else {
            $tag = "";
        }
        if ($idPregunta == "") {
            $idPregunta = "-1";
        }
        if ($cantidadRegistro == "") {
            $cantidadRegistro = "0";
        }

        $cnx = new MySQL();
        $nombreImagen = "";
        $uploadOk = 1;
        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $nombreImagen = basename($_FILES["fileToUpload"]["name"]);
            $target_file = ICONO_IMAGE_PATH_FISICO . $nombreImagen;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $uploadOk = 1;
                } else {
                    echo "Hay error al subir la imagen.";
                    $uploadOk = 0;
                }
            } else {
                echo "Archivo no es una imagen.";
                $uploadOk = 0;
            }
        }
        if ($uploadOk == 0) {
            $nombreImagen = "";
        }

        if ($idFlujoChat == 0) {
            $query = "CALL USP_CREA_FLUJOCHAT ($idPadre, '$descripcion', '$descripcionIngles', '$tipoFuncion', '$tipoPresentacion', '$nombreImagen', $idPregunta, '$tag', $cantidadRegistro, '$usuario', @p_id_flujochat);";
        } else {
            $query = "CALL USP_EDIT_FLUJOCHAT ($idFlujoChat, '$descripcion', '$descripcionIngles', '$nombreImagen', '$tipoFuncion', '$tipoPresentacion', $idPregunta, '$tag', $cantidadRegistro, '$usuario');";
        }

        $cnx->execute($query);

        $cnx = null;
        header("Location: flujochat.php");
        die();
        
    }

    $cnx = new MySQL();

    //lista de tags
    $query = "SELECT nombre FROM tag WHERE estado = 1 UNION ALL SELECT nombre FROM nutriente WHERE estado = 1 UNION ALL SELECT nombre FROM enfermedad WHERE estado = 1 ORDER BY nombre";
    $sql = $cnx->query($query);
    $sql->read();
    $tagLista = "";
    while($sql->next()) {
        $tagx = $sql->field('nombre');
        $tagLista .= "'" . trim($tagx) . "', ";
    }
    if ($tagLista != "") {
        $tagLista = substr($tagLista, 0, -2);
    }

    //las preguntas salud
    $htmlPregunta = "";
    $query = "SELECT a.id_pregunta, a.descripcion, a.descripcion_ing, a.tipo_pregunta, a.tipo_respuesta, a.codigo, a.orden FROM pregunta a WHERE a.estado = 1 ORDER BY a.orden_tipo_pregunta, a.id_pregunta";
    $sql = $cnx->query($query);
    $sql->read();
    $tipoPreguntax = "";
    while($sql->next()) {
        $idPregunta = $sql->field('id_pregunta');
        $descripcionPregunta = $sql->field('descripcion_ing');
        $tipoPregunta = $sql->field('tipo_pregunta');
        $tipoRespuesta = $sql->field('tipo_respuesta');
        $orden = $sql->field('orden');
        if ($tipoPregunta != $tipoPreguntax) {
            $htmlPregunta .= '<tr >';
            $htmlPregunta .= '<td colspan="3"><h3> '.$tipoPregunta.'</h3></td>';
            $htmlPregunta .= '</tr>';
        } 
        $tipoPreguntax = $tipoPregunta;
        $htmlPregunta .= '<tr data-id="' . $idPregunta . '" data-des="' . $descripcionPregunta . '">';
        $htmlPregunta .= '<td style="width:5%" class="td-pregunta">' . $idPregunta . '</td>';
        $htmlPregunta .= '<td style="width:35%" class="td-pregunta">' . $descripcionPregunta . '</td>';
        $htmlPregunta .= '<td style="width:60%" class="td-pregunta">';
        $nombreInput = "pregunta_" . $idPregunta;
        if ($tipoRespuesta == TIPO_RESPUESTA_UNICA) {
            $query2 = "SELECT a.id_respuesta, a.descripcion, a.descripcion_ing FROM respuesta a WHERE a.estado = 1 AND a.id_pregunta = " . $idPregunta . " ORDER BY a.orden";
            $sql2 = $cnx->query($query2);
            $sql2->read();
            $htmlPregunta .= '<div class="row">';
            while($sql2->next()) {
                $idRespuesta = $sql2->field('id_respuesta');
                $descripcionRespuesta = $sql2->field('descripcion_ing');
                $htmlPregunta .= '<div class="col-md-4 col-xs-4">';
                $htmlPregunta .= $descripcionRespuesta;
                $htmlPregunta .= '</div>';
            }
            $htmlPregunta .= '</div>';
        } else if ($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE) {
            $query2 = "SELECT a.id_respuesta, a.descripcion, a.descripcion_ing FROM respuesta a WHERE a.estado = 1 AND a.id_pregunta = " . $idPregunta . " ORDER BY a.orden";
            $sql2 = $cnx->query($query2);
            $sql2->read();
            $htmlPregunta .= '<div class="row">';
            while($sql2->next()) {
                $idRespuesta = $sql2->field('id_respuesta');
                $descripcionRespuesta = $sql2->field('descripcion_ing');
                $htmlPregunta .= '<div class="col-md-4 col-xs-4">';
                $htmlPregunta .= $descripcionRespuesta;
                $htmlPregunta .= '</div>';
            }
            $htmlPregunta .= '</div>';
        }
        $htmlPregunta .= '</td>';
        $htmlPregunta .= '</tr>';
    }

    //todo el flujo chat
    $arregloData = array();
    $query = "CALL USP_LIST_FLUJOCHAT_TODO ();";
    $sql = $cnx->query($query);
    $sql->read();
    while ($sql->next())  {
        $idFlujoChat = $sql->field('id_flujochat');
        $idPadre = $sql->field('id_padre');
        $descripcion = $sql->field('descripcion');
        array_push($arregloData, array('id_padre' => $idPadre, 'id' => $idFlujoChat, 'descripcion' => $descripcion));
    }
    $html = getHtmlToArray($arregloData);

    $cnx = null;

    //lista de tipo función
    $htmlTipoFuncion = "<option value=\"\">[Seleccionar]</option>";
    foreach ($arregloTipoFuncion as $item) {
        $htmlTipoFuncion .= "<option value=\"$item\">$item</option>";
    }

    //lista de tipo presentación
    $htmlTipoPresentacion = "<option value=\"\">[Seleccionar]</option>";
    foreach ($arregloTipoPresentacion as $item) {
        $htmlTipoPresentacion .= "<option value=\"$item\">$item</option>";
    }

    function getHtmlToArray($arregloData, $parent_id = 0) {
        $html = '<ol class="dd-list">';
        foreach ($arregloData as $item) {
            if ($item["id_padre"] == $parent_id) {
                $html .= '<li class="dd-item dd3-item" data-id="' . $item["id"] . '"> <div class="dd-handle dd3-handle"></div> <div class="dd3-content ver-flujo" data-id="' . $item["id"]  . '" style="cursor:pointer;">' . $item["descripcion"]  . ' </div>';
                $html .= getHtmlToArray($arregloData, $item["id"] );
                $html .= '</li>';
            }
        }
        $html .= '</ol>';
        return $html;
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
                            <h4 class="page-title">Flujo del Chat</h4>
                        </div>
                    </div>
                    <!-- row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="white-box">
                                <div class="myadmin-dd-empty dd" id="nestable2">
                                    <?php echo $html; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="white-box">
                                <form action="flujochat.php" method="post" enctype="multipart/form-data" name="forma" id="forma">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Descripci&oacute;n (*)</label>
                                                <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Descripci&oacute;n (Ingl&eacute;s)</label>
                                                <textarea name="descripcion_ing" id="descripcion_ing" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Tipo funci&oacute;n (*)</label>
                                                <select id="tipo_funcion" name="tipo_funcion" class="form-control">
                                                    <?php echo $htmlTipoFuncion ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="fila-tipo_agrupacion">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Tipo presentaci&oacute;n</label>
                                                <select id="tipo_presentacion" name="tipo_presentacion" class="form-control">
                                                    <?php echo $htmlTipoPresentacion ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="fila-imagen">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Subir imagen</label>
                                                <input type="file" name="fileToUpload" class="form-control" />
                                                <div class="row" id="contenedor-imagen">
                                                    <div class="col-md-4">
                                                        <img src="" class="img-responsive thumbnail m-r-15"  alt="" id="img" />
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="checkbox" name="eliminar_imagen_marker" value="1" /> Eliminar imagen
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="fila-tag">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Tag</label>
                                                <input type="text" id="tag" name="tag" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="fila-pregunta">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Pregunta</label>
                                                <br/>
                                                <span id="pregunta_des"></span>
                                                <br />
                                                <input type="button" id="abrir-modal" value="Seleccionar pregunta" data-toggle="modal" class="btn btn-default" data-target="#pregunta-modal" />
                                                <input type="hidden" name="id_pregunta" id="id_pregunta" value="-1" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="fila-cantidad-registro">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Cantidad de registros a mostrar</label>
                                                <input type="number" id="cantidad_registro" name="cantidad_registro" class="form-control" min="0" max="10" step="1" />
                                            </div>
                                        </div>
                                    </div>
                                    <div id="mensaje" class="row alert alert-danger"></div>
                                    <br />
                                    <div class="row">
                                        <input type="hidden" name="id_padre" id="id_padre" value="<?php echo $idPadre; ?>" />
                                        <input type="hidden" name="id_flujochat" id="id_flujochat" value="0" />
                                        <input type="hidden" name="imagen" id="imagen" value="" />
                                        <div class="col-md-12 text-right">
                                            <input type="button" id="nuevo-hijo" value="Nuevo hijo" class="btn btn-default" />
                                            <input type="button" id="limpiar" value="Nuevo" class="btn btn-default" />
                                            <input type="button" id="nuevo-flujo" value="Grabar" class="btn btn-success" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="pregunta-modal" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Elegir pregunta de salud para relacionarla</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="height:350px; overflow-y:scroll">
                                <table class="table table-bordered table-hover table-click table-pregunta" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;"> ID </th>
                                            <th style="width: 35%;"> Pregunta </th>
                                            <th style="width: 60%;"> Respuestas </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $htmlPregunta ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="close-pregunta-modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <script type="text/javascript">
            var availableTags = [<?php echo $tagLista ?>];
            var availableTagsLower = $.map(availableTags, function(n,i){return n.toLowerCase();});

            $(document).ready(function() {
                $('#mensaje').hide();
                $('#contenedor-imagen').hide();
                $('#nuevo-hijo').hide();
                $('#fila-tag').hide();
                $('#fila-pregunta').hide();
                $('#fila-cantidad-registro').hide();

                // Nestable
                var updateOutput = function(e) {
                    var list = e.length ? e : $(e.target),
                        output = list.data('output');
                    if (window.JSON) {
                        output.val(window.JSON.stringify(list.nestable('serialize')));
                    } else {
                        output.val('JSON browser support required for this demo.');
                    }
                    $.ajax({
                        method: "POST",
                        url: "service/saveflowchatorder.php",
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

                $('#nuevo-flujo').click(function() {
                    $('#mensaje').hide();
                    if ($('#descripcion').val().trim() == '') {
                        $('#mensaje').html('No ha ingresado descripción').show();
                    } else if ($('#tipo_funcion').val().trim() == '') {
                        $('#mensaje').html('No ha seleccionado tipo').show();
                    } else {
                        if ($('#tipo_funcion').val() == '<?php echo TIPOCHAT_TIP; ?>') {
                            var tags = $.trim($('#tag').val());
                            if (tags.substr(tags.length - 1) == ',') {
                                tags = tags.substr(0, tags.length - 1);
                            }
                            $('#tag').val(tags);
                            if (tags != '') {
                                var arregloTag = tags.split(',');
                                var todosLosTagsExisten = true;
                                var tagsErrados = '';
                                if (arregloTag.length > 0) {
                                    $.each(arregloTag, function(index, value) {
                                        value = $.trim(value);
                                        if (jQuery.inArray(value.toLowerCase(), availableTagsLower) > -1) {
                                            todosLosTagsExisten &= true;
                                        } else {
                                            todosLosTagsExisten &= false;
                                            tagsErrados += '"' + value + '" ';
                                        }
                                    });
                                    if (!todosLosTagsExisten) {
                                        $('#mensaje').html('El (los) tag(s): ' + tagsErrados + ' no está(n) en la lista');
                                        $('#mensaje').show();
                                        return;
                                    } else {
                                        $(this).attr('disabled','disabled');
                                    }
                                }
                            } else {
                                $('#mensaje').html('No ha elegido algún tag');
                                $('#mensaje').show();
                                return;
                            }
                        } else if ($('#tipo_funcion').val() == '<?php echo TIPOCHAT_PREGUNTA; ?>') {
                            if ($('#pregunta_id').val() == '') {
                                $('#mensaje').html('No ha elegido alguna pregunta');
                                $('#mensaje').show();
                                return;
                            }
                        }
                        $('#forma').submit();
                    }
                });

                $('.ver-flujo').click(function() {
                    $('#fila-tipo_agrupacion').show();
                    $('#contenedor-imagen').hide();
                    $('#nuevo-hijo').hide();
                    $('#mensaje').hide();
                    var idFlujoChat = $(this).attr('data-id');
                    var param = { "flowchatID" : idFlujoChat };
                    var paramJSON = JSON.stringify(param);
                    $.ajax({
                        type: 'POST',
                        url: 'service/getflowchatbyid.php',
                        data: paramJSON,
                        dataType: 'json',
                        error: function(errorResult) {
                            console.log('Ha ocurrido un error ' + errorResult.error());
                        },
                        success: function (result) {
                            console.log(result);
                            $('#descripcion').val(result.data[0].description);
                            $('#descripcion_ing').val(result.data[0].descriptionEnglish);
                            $('#tipo_funcion').val(result.data[0].functionType);
                            $('#tipo_presentacion').val(result.data[0].presentation);
                            $('#id_flujochat').val(result.data[0].flowChatID);
                            $('#id_padre').val(result.data[0].parentID);
                            $('#id_pregunta').val(result.data[0].healthQuestionID);
                            $('#pregunta_des').html(result.data[0].healthQuestionDescription);
                            $('#tag').val(result.data[0].tag);
                            $('#cantidad_registro').val(result.data[0].recordNumber);
                            if (result.data[0].image != '') {
                                $('#img').attr('src', result.data[0].urlImage)
                                $('#contenedor-imagen').show();
                                $('#imagen').val(result.data[0].image);
                            } else {
                                $('#img').attr('src', '')
                                $('#contenedor-imagen').hide();
                                $('#imagen').val('');
                            }
                            $('#nuevo-hijo').show();
                            $('#tipo_funcion').trigger('change');
                            $('#descripcion').focus();
                        }
                    });
                });

            });

        </script>

        <script type="text/javascript">
            $(document).ready(function() {

                $('#limpiar').click(function() {
                    $('#descripcion').val('');
                    $('#descripcion_ing').val('');
                    $('#tipo_funcion').val('');
                    $('#tipo_presentacion').val('');
                    $('#id_flujochat').val(0);
                    $('#id_padre').val(0);
                    $('#id_pregunta').val(-1);
                    $('#img').attr('src', '')
                    $('#imagen').val('');
                    $('#tag').val('');
                    $('#cantidad_registro').val(0);
                    $('#contenedor-imagen').hide();
                    $('#fila-tag').hide();
                    $('#fila-pregunta').hide();
                    $('#fila-cantidad-registro').hide();
                    $('#mensaje').hide();
                });

                $('#nuevo-hijo').click(function() {
                    $('#descripcion').val('');
                    $('#descripcion_ing').val('');
                    $('#tipo_funcion').val('');
                    $('#tipo_presentacion').val('');
                    $('#id_padre').val($('#id_flujochat').val());
                    $('#id_flujochat').val(0);
                    $('#id_pregunta').val(-1);
                    $('#img').attr('src', '')
                    $('#tag').val('');
                    $('#cantidad_registro').val(0);
                    $('#imagen').val('');
                    $('#contenedor-imagen').hide();
                    $('#fila-tag').hide();
                    $('#fila-pregunta').hide();
                    $('#fila-cantidad-registro').hide();
                    $('#mensaje').hide();
                });

                $( function() {
                    function split( val ) {
                        return val.split( /,\s*/ );
                    }
                    function extractLast( term ) {
                        return split( term ).pop();
                    }

                    $( "#tag" ).on( "keydown", function( event ) {
                    if ( event.keyCode === $.ui.keyCode.TAB &&
                        $( this ).autocomplete( "instance" ).menu.active ) {
                        event.preventDefault();
                    }
                    })
                    .autocomplete({
                    minLength: 0,
                    source: function( request, response ) {
                        // delegate back to autocomplete, but extract the last term
                        response( $.ui.autocomplete.filter(
                        availableTags, extractLast( request.term ) ) );
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function( event, ui ) {
                        var terms = split( this.value );
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push( ui.item.value );
                        // add placeholder to get the comma-and-space at the end
                        terms.push( "" );
                        this.value = terms.join( ", " );
                        return false;
                    }
                    });
                });

                $('#tipo_funcion').change(function() {
                    $('#fila-pregunta').hide();
                    $('#fila-tag').hide();
                    $('#fila-cantidad-registro').hide();
                    $('#mensaje').hide();
                    if ($(this).val() == '<?php echo TIPOCHAT_TIP; ?>') {
                        $('#fila-tag').show();
                        $('#fila-cantidad-registro').show();
                    } else if ($(this).val() == '<?php echo TIPOCHAT_RESTAURANTE; ?>') {
                        $('#fila-cantidad-registro').show();
                    } else if ($(this).val() == '<?php echo TIPOCHAT_RECETA; ?>') {
                        $('#fila-cantidad-registro').show();
                    } else if ($(this).val() == '<?php echo TIPOCHAT_NUTRIENTE; ?>') {
                        $('#fila-cantidad-registro').show();
                    } else if ($(this).val() == '<?php echo TIPOCHAT_PREGUNTA; ?>') {
                        $('#fila-pregunta').show();
                    }
                });

                $('.td-pregunta').click(function() {
                    var pregunta_id = $(this).closest('tr').attr('data-id');
                    var pregunta_des = $(this).closest('tr').attr('data-des');
                    $('#id_pregunta').val(pregunta_id);
                    $('#pregunta_des').html(pregunta_des);
                    $('#close-pregunta-modal').trigger('click');
                });

            });
        </script>

    </body>
</html>