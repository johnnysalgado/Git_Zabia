<?php
    require('inc/sesion.php');
    require('inc/constante_tip.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["titulo"])) {

        $titulo = $_POST["titulo"];
        $detalle = $_POST["detalle"];
        $tag = $_POST["tag"];
        $urlVideo = $_POST["url_video"];
        $beneficios = $_POST["beneficio"];
        $usuario = $_SESSION["U"];

        $uploadOk = 1;
        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $mt = microtime(true);
            $mt =  $mt*1000; //microsecs
            $ticks = (string)$mt*10;
            $target_dir = TIP_IMAGE_SHORT_PATH;
            $nombreImagen = $ticks . basename($_FILES["fileToUpload"]["name"]);
            $target_file = $target_dir . $nombreImagen;
            $uploadOk = 1;

            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
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
        } else {
            $nombreImagen = $_POST["imagen"];
        }

        if ($uploadOk == 1) {
            if ($titulo != "") {
                $titulo = str_replace("'", "''", $titulo);
            }
            if ($detalle != "") {
                $detalle = str_replace("'", "''", $detalle);
            }
            if ($tag != "") {
                $tag = str_replace("'", "''", $tag);
            }
            if ($urlVideo != "") {
                $urlVideo = str_replace("'", "''", $urlVideo);
            }

            $query = "INSERT INTO nota (titulo, detalle, tag, imagen, url_video, estado, usuario_registro) VALUES ('" . $titulo . "', '" . $detalle . "', '" . $tag . "', '" . $nombreImagen . "', '" . $urlVideo . "', '1', '". $usuario . "');";

            $idNota = $cnx->insert($query);

            foreach ($beneficios as $beneficio) {
                $query = "INSERT INTO nota_beneficio (id_nota, id_beneficio, estado, usuario_registro) VALUES (" . $idNota . ", " . $beneficio . ", '1', '". $usuario . "');";
                $cnx->insert($query);
            }

            $cnx = null;
            header("Location: tips.php");
            die();
        }
    
    } else {

        $query = "SELECT id_beneficio, nombre FROM beneficio WHERE estado = '1' ORDER BY nombre";
        $sql = $cnx->query($query);
        $sql->read();
        $html = "";
        while($sql->next()) {
            $idBeneficio = $sql->field('id_beneficio');
            $nombre = $sql->field('nombre');
    
            $html .= '<div class="col-md-3"> <input type="checkbox" name="beneficio[]" id="beneficio" value="' . $idBeneficio . '" class=""> <label>' . $nombre . '</label> </div>';
        }

        $query = "SELECT nombre FROM tag WHERE estado = 1 UNION ALL SELECT nombre FROM nutriente WHERE estado = 1 UNION ALL SELECT nombre FROM enfermedad WHERE estado = 1 ORDER BY nombre";
        $sql = $cnx->query($query);
        $sql->read();
        $tagLista = "";
        while($sql->next()) {
            $nombre = $sql->field('nombre');
            $tagLista .= "'" . trim($nombre) . "', ";
        }
        if ($tagLista != "") {
            $tagLista = substr($tagLista, 0, -2);
        }
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
                            <h4 class="page-title">Creaci&oacute;n de Tip</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="tip_crear.php" method="post" id="form-tip">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>T&iacute;tulo</label>
                                            <input type="text" name="titulo" id="titulo" class="form-control" maxlength="256" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Detalle</label>
                                            <textarea name="detalle" id="detalle" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Etiquetas</label>
                                            <span>Colocar las etiquetas (tags) separadas por comas</span>
                                            <input type="text" name="tag" id="tag" class="form-control" maxlength="512" />
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Enlace video</label>
                                            <input type="text" name="url_video" value="" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Beneficios</label>
                                            <div class="checkbox checkbox-success">
                                                <?php echo $html?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div id="divError" class="row alert alert-danger"></div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-tip" value="Grabar" class="btn btn-success" />
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
        var availableTags = [<?php echo $tagLista ?>];
        var availableTagsLower = $.map(availableTags, function(n,i){return n.toLowerCase();});

        $(document).ready(function () {
            $('#divError').hide();
            $('#nav-tip').addClass('active');
        });

        $('#volver').click(function() {
            location.href = 'tips.php';
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

        $("#nuevo-tip").click(function() {
            var tags = $.trim($('#tag').val());
            if (tags.substr(tags.length - 1) == ',') {
                tags = tags.substr(0, tags.length - 1);
            }
            $('#tag').val(tags);
            $('divError').hide();
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
                        $('#divError').html('El (los) tag(s): ' + tagsErrados + ' no está(n) en la lista');
                        $('#divError').show();
                        return;
                    } else {
                        $(this).attr('disabled','disabled');
                        $('#form-tip').submit();
                    }
                }
            } else {
                $('#divError').html('No ha elegido algún tag');
                $('#divError').show();
                return;
            }
        });
        </script>
    </body>
</html>