<?php
    require('inc/sesion.php');
    require('constante_dir.php');
    require('inc/configuracion.php');
    require('inc/constante_receta.php');
    require('inc/constante_aws.php');
    require('inc/mysql.php');
    require('Classes/ChilkatS3.php');
    require('Classes/ImageResize.php');
    require('inc/funcion_imagen.php');

    $cnx = new MySQL();

    if (isset($_POST["nombre"])) {

        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        $preparacion = $_POST["preparacion"];
        $porcion = $_POST["porcion"];
        $tiempo = $_POST["tiempo"];
        $codigoPais = $_POST["pais"];
        $idRegion = $_POST["region"];
        $dificultad = $_POST["dificultad"];
        $contadorInsumo = $_POST["contador_insumo"];
        $tipoDietas = [];
        $tipoCocinas = [];
        $tipoPlatos = [];
        if (isset($_POST["tipo_dieta"])) {
            $tipoDietas = $_POST["tipo_dieta"];
        }
        if (isset($_POST["tipo_cocina"])) {
            $tipoCocinas = $_POST["tipo_cocina"];
        }
        if (isset($_POST["tipo_plato"])) {
            $tipoPlatos = $_POST["tipo_plato"];
        }
        if (isset($_POST["post"])) {
            $top = $_POST["top"];
        } else {
            $top = 0;
        }
        $usuario =  $_SESSION["U"];

        $nombreImagen = $_POST["imagen"];
        $uploadOk = 1;
        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $mt = microtime(true);
            $mt =  $mt*1000; //microsecs
            $ticks = (string)$mt*10;
            $nombreImagen = $ticks . basename($_FILES["fileToUpload"]["name"]);
            $target_file = RECIPE_IMAGE_SHORT_PATH . $nombreImagen;
            $uploadOk = 1;

            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    //crear imágenes en tamaños y pasarlo a AWS S3
                    setImageRecipe ($target_file, $nombreImagen);
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

            if ($nombre != "") {
                $nombre = str_replace("'", "''", $nombre);
            }
            if ($nombreIngles != "") {
                $nombreIngles = str_replace("'", "''", $nombreIngles);
            }
            if ($preparacion != "") {
                $preparacion = str_replace("'", "''", $preparacion);
            }
            if ($porcion == "") {
                $porcion = 0;
            }
            if ($tiempo == "") {
                $tiempo = 0;
            }
            if ($idRegion == "") {
                $idRegion = "NULL";
            }

            $query = "INSERT INTO plato (nombre, nombre_ing, imagen, top, porcion, preparacion, tiempo, estado, cod_pais, id_region, dificultad, usuario_registro) VALUES ('" . $nombre . "', '$nombreIngles', '" . $nombreImagen . "', " . $top . ", " . $porcion . ", '" . $preparacion . "', " . $tiempo . ", 1, '" . $codigoPais . "', " . $idRegion . ", '" . $dificultad . "', '" .  $usuario . "' );";
            //echo $query;
            $idPlato = $cnx->insert($query);

            for ($i = 0; $i < $contadorInsumo; $i++) {
                if (isset($_POST["id_plato_insumo" . $i])) {
                    $insumoDescripcion = $_POST["insumo_descripcion" . $i];
                    $insumoNombre = $_POST["insumo_nombre" . $i];
                    $insumoCantidad = $_POST["insumo_cantidad" . $i];
                    $insumoUnidad = $_POST["insumo_unidad" . $i];
                    $insumoID = $_POST["insumo_id" . $i];
                    if ($insumoID == '' ) {
                        $insumoID = "NULL";
                    }
                    if ($insumoDescripcion != "") {
                        $insumoDescripcion = str_replace("'", "''", $insumoDescripcion);
                    }
                    if ($insumoNombre != "") {
                        $insumoNombre = str_replace("'", "''", $insumoNombre);
                    }
                    $query = "INSERT INTO plato_insumo (id_plato, id_insumo, descripcion, nombre, cantidad, unidad, estado, usuario_registro, fecha_registro) VALUES (" . $idPlato . ", " . $insumoID . ", '" . $insumoDescripcion . "', '" . $insumoNombre . "', " . $insumoCantidad . ", '" . $insumoUnidad . "', 1, '" . $usuario . "', CURRENT_TIMESTAMP);";
                    $cnx->insert($query);
                }
            }

            //tipo dieta
            foreach ($tipoDietas as $tipoDieta) {
                $query = "INSERT INTO plato_tipo_dieta (id_plato, id_tipo_dieta, estado, usuario_registro) VALUES (" . $idPlato . ", " . $tipoDieta . ", 1, '". $usuario . "');";
                $cnx->execute($query);
            }

            //tipo cocina
            foreach ($tipoCocinas as $tipoCocina) {
                $query = "INSERT INTO plato_tipo_cocina (id_plato, id_tipo_cocina, estado, usuario_registro) VALUES (" . $idPlato . ", " . $tipoCocina . ", 1, '". $usuario . "');";
                $cnx->execute($query);
            }

            //tipo plato
            foreach ($tipoPlatos as $tipoPlato) {
                $query = "INSERT INTO plato_tipo_plato (id_plato, id_tipo_plato, estado, usuario_registro) VALUES (" . $idPlato . ", " . $tipoPlato . ", 1, '". $usuario . "');";
                $cnx->execute($query);
            }
            
            $cnx = null;
            header("Location: receta_calcular.php?id=" . $idPlato);
            die();
        }
    } else {

        //tipo dieta
        $query = "SELECT a.nombre, a.id_tipo_dieta FROM tipo_dieta a WHERE ( a.estado = 1 ) ORDER BY nombre";
        $sql = $cnx->query($query);
        $sql->read();
        $htmlTipoDieta = "";
        while($sql->next()) {
            $tipo = $sql->field('nombre');
            $idTipoDieta = $sql->field('id_tipo_dieta');
            $htmlTipoDieta .= '<div class="col-md-3"> <input type="checkbox" name="tipo_dieta[]" value="' . $idTipoDieta . '" /> <label>' . $tipo . '</label>  </div>';
        }

        //tipo cocina
        $query = "SELECT a.nombre, a.id_tipo_cocina FROM tipo_cocina a WHERE ( a.estado = 1 ) ORDER BY nombre";
        $sql = $cnx->query($query);
        $sql->read();
        $htmlTipoCocina = "";
        while($sql->next()) {
            $tipo = $sql->field('nombre');
            $idTipoCocina = $sql->field('id_tipo_cocina');
            $htmlTipoCocina .= '<div class="col-md-3"> <input type="checkbox" name="tipo_cocina[]" value="' . $idTipoCocina . '" /> <label>' . $tipo . '</label>  </div>';
        }

        //tipo plato
        $query = "SELECT a.nombre, a.id_tipo_plato FROM tipo_plato a WHERE ( a.estado = 1 ) ORDER BY nombre";
        $sql = $cnx->query($query);
        $sql->read();
        $htmlTipoPlato = "";
        while($sql->next()) {
            $tipo = $sql->field('nombre');
            $idTipoPlato = $sql->field('id_tipo_plato');
            $htmlTipoPlato .= '<div class="col-md-3"> <input type="checkbox" name="tipo_plato[]" value="' . $idTipoPlato . '" /> <label>' . $tipo . '</label>  </div>';
        }

        //país
        $query = "SELECT a.nombre, a.cod_pais FROM pais a WHERE ( a.estado = 1 ) ORDER BY a.nombre";
        $sql = $cnx->query($query);
        $sql->read();
        $htmlPais = "";
        while($sql->next()) {
            $pais = $sql->field('nombre');
            $codPais = $sql->field('cod_pais');
            $htmlPais .= '<option value="' . $codPais . '"> ' . $pais . '</option>';
        }

        //dificultad
        $query = "SELECT nombre FROM dificultad WHERE ( estado = 1 )";
        $sql = $cnx->query($query);
        $sql->read();
        $htmlDificultad = "";
        while($sql->next()) {
            $dificultadx = $sql->field('nombre');
            $htmlDificultad .= '<option value="' . $dificultadx . '"> ' . $dificultadx . '</option>';
        }

        //tipo alimento
        $htmlTipoAlimento = "";
        $consulta = " SELECT id_tipo_alimento, nombre FROM tipo_alimento WHERE estado = 1 ORDER BY nombre";
        $sql_query = $cnx->query($consulta);
        $sql_query->read();
        while ($sql_query->next()) {
            $idTipoAlimento = $sql_query->field('id_tipo_alimento');
            $nombreTipoAlimento = $sql_query->field('nombre');
            $htmlTipoAlimento .= '<option value="' . $idTipoAlimento . '">' . $nombreTipoAlimento . '</option>';
        }
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
                            <h4 class="page-title">Creaci&oacute;n de Recetas</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="receta_crear.php" method="post" id="forma-receta" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre [Ingl&eacute;s]</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="64" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Preparaci&oacute;n</label>
                                            <textarea name="preparacion" id="preparacion" class="form-control" rows="10"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Porci&oacute;n</label>
                                            <input type="number" name="porcion" id="porcion" class="form-control" value="" step="1" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tiempo (minutos)</label>
                                            <input type="number" name="tiempo" id="tiempo" class="form-control" value="" step="1" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Top</label>
                                            <div class="radio-list">
                                                <label class="radio-inline p-0">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="top" id="top-si" value="1" />
                                                        <label for="top-si">Si</label>
                                                    </div>
                                                </label>
                                                <label class="radio-inline">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="top" id="top-no" value="0" />
                                                        <label for="top-no">No</label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Imagen</label>
                                            <input type="file" name="fileToUpload" value="" class="form-control" />
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pa&iacute;s</label>
                                            <select name="pais" id="pais" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                                <?php echo $htmlPais ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Regi&oacute;n</label>
                                            <select name="region" id="region" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dificultad</label>
                                            <select name="dificultad" id="dificultad" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                                <?php echo $htmlDificultad ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tipo dieta</label>
                                            <div class="checkbox checkbox-success">
                                                <?php echo $htmlTipoDieta;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tipo cocina</label>
                                            <div class="checkbox checkbox-success">
                                                <?php echo $htmlTipoCocina;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tipo plato</label>
                                            <div class="checkbox checkbox-success">
                                                <?php echo $htmlTipoPlato;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <h3>Ingredientes</h2>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Insumo (RS28 / Minsa)</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Descripci&oacute;n</label><br />
                                    </div>
                                    <div class="col-md-2">
                                        <label>Nombre</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label>Cantidad</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Unidad</label>
                                    </div>
                                    <div class="col-md-1">&nbsp;</div>
                                </div>
                                <div id="insumo-contenedor"></div>
                                <div class="row">
                                    <div class="col-md-12  text-right">
                                        <input type="button" id="adicionar-insumo" class="btn btn-default" value="Adicionar ingrediente" />
                                    </div>
                                </div>
                                <br />
                                <input type="hidden" name="contador_insumo" id="contador_insumo" value="0" />
                                <div class="row">
                                    <div class="col-md-6 alert alert-info">
                                        * Ning&uacute;n cambio har&aacute; efecto a menos que se de clic en "Grabar".
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-plato" value="Grabar" class="btn btn-success" />
                                        <input type="button" id="abrir-modal" value="abrir modal" data-toggle="modal" data-target="#buscar-modal" style="display:none;"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="buscar-modal" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Buscar insumo en maestra (RS28 / Minsa)</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                    <label>Insumo</label>
                                    <input type="text" name="buscar_palabra_insumo" id="buscar-palabra-insumo" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                    <label>Tipo alimento</label>
                                    <select name="buscar_insumo_tipo_alimento" id="buscar-insumo-tipo-alimento" class="form-control">
                                        <option value="">[Todos]</option>
                                        <?php echo $htmlTipoAlimento; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p>&nbsp;</p>
                        <div class="row" id="buscar-insumo-resultado" style="height:200px; overflow-y:scroll">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover table-click table-buscar-insumo" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;"> ID </th>
                                            <th style="width: 30%;"> Nombre </th>
                                            <th style="width: 30%;"> Nombre (Ingl&eacute;s) </th>
                                            <th style="width: 20%;"> Tipo alimento </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-resultado"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="close-buscar-insumo">Close</button>
                        <button type="button" class="btn btn-primary" id="buscar-buscar-insumo" >Buscar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <script type="text/javascript">
            var contadorInsumo = 0;
            var insumoElegido = -1;

            $('#volver').click(function() {
                location.href = 'recetas.php';
            });

            $('#nuevo-plato').click(function() {
                $(this).attr('disabled','disabled');
                $('#forma-receta').submit();
            });

            $('body').on('click', '.eliminar-insumo', function() {
                insumoElegido = $(this).attr('data-cont');
                $('#div' + insumoElegido).remove();
                $('#hr' + insumoElegido).remove();
            });

            $('body').on('click', '.buscar-insumo', function() {
                insumoElegido = $(this).attr('data-cont');
                $('#abrir-modal').trigger('click');
            });

            $('#buscar-buscar-insumo').click(function() {
                $('#tbody-resultado').find('tr').remove().end();
                if ($('#buscar-palabra-insumo').val() != '' || $('#buscar-insumo-tipo-alimento').val() != '') {
                    var param = { "ingredient" : $('#buscar-palabra-insumo').val(), "foodType" : $('#buscar-insumo-tipo-alimento').val()};
                    var paramJSON = JSON.stringify(param);
                    $.ajax({
                        type: 'POST',
                        url: 'service/getmasteringredient.php',
                        data: paramJSON,
                        dataType: 'json',
                        success: function (result) {
                            if (result.data.length > 0) {
                                $.each(result.data, function (index, element) {
                                    var tr = $('<tr />');
                                    var td1 = $('<td />');
                                    var td2 = $('<td />');
                                    var td3 = $('<td />');
                                    var td4 = $('<td />');
                                    td1.html(element.ingredientID);
                                    td2.html(element.name);
                                    td3.html(element.name_eng);
                                    td4.html(element.food_type);
                                    tr.append(td1);
                                    tr.append(td2);
                                    tr.append(td3);
                                    tr.append(td4);
                                    $('#tbody-resultado').append(tr);
                                });
                                $('.table-buscar-insumo > tbody > tr').click(function() {
                                    var id_insumo = $(this).children('td:first').text();
                                    var nombre_insumo = $(this).find('td:eq(1)').text();
                                    $('#insumo_id' + insumoElegido).val(id_insumo);
                                    $('#insumo_maestra' + insumoElegido).val(nombre_insumo);
                                    $('#close-buscar-insumo').trigger('click');
                                });
                            } else {
                            }
                        }
                    });
                }
            });

            $('#adicionar-insumo').click(function () {
                var html = '<div class="row" id="div' + contadorInsumo + '">';
                html += '<div class="col-md-3">'
                html += ' <input type="text" name="insumo_maestra' + contadorInsumo + '" id="insumo_maestra' + contadorInsumo + '" value=""  class="form-control" disabled="disabled" />';
                html += '</div>';
                html += '<div class="col-md-3">';
                html += ' <input type="text" name="insumo_descripcion' + contadorInsumo + '" value="" maxlength="128" class="form-control" />';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += ' <input type="text" name="insumo_nombre' + contadorInsumo + '" value="" maxlength="128" class="form-control" />';
                html += '</div>';
                html += '<div class="col-md-1">';
                html += ' <input type="number" name="insumo_cantidad' + contadorInsumo + '" value="" step="0.01" class="form-control" />';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += ' <select name="insumo_unidad' + contadorInsumo + '" class="form-control">';
                html += '  <option value="">[Seleccionar]</option>';
<?php
    foreach($arregloUnidad as $unidad_) {
        echo "html += '  <option value=\"" . $unidad_ . "\">" . $unidad_ . "</option>';"; 
    }
?>
                html += '  </select>';
                html += '</div>';
                html += '<div class="col-md-1">';
                html += ' <i class="glyphicon glyphicon-search buscar-insumo" data-cont="' + contadorInsumo + '" title="Buscar insumo"></i>&nbsp;&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-trash eliminar-insumo" data-cont="' + contadorInsumo + '" title="Eliminar insumo"></i>';
                html += ' <input type="hidden" name="id_plato_insumo' + contadorInsumo + '" value="0" /> <input type="hidden" name="insumo_id' + contadorInsumo + '" id="insumo_id' + contadorInsumo + '" value="" />';
                html += '</div>';
                html += '</div>';
                html += '<hr id="hr' + contadorInsumo + '" />';
                $('#insumo-contenedor').append(html);
                contadorInsumo ++;
                $('#contador_insumo').val(contadorInsumo);
            });

            $(document).ready(function() {
                $('#nav-recipe').addClass('active');
            });
        </script>
        <script type="text/javascript">
            $('#pais').change(function() {
                $('#region').find('option').remove().end();
                var option = $('<option/>');
                option.attr({ 'value': '' }).text('[Seleccionar]');
                $('#region').append(option);
                if ($(this).val() != '') {
                    var param = { "country" : $(this).val()};
                    var paramJSON = JSON.stringify(param);
                    $.ajax({
                        type: 'POST',
                        url: 'service/getregionbycountry.php',
                        data: paramJSON,
                        dataType: 'json',
                        success: function (result) {
                            if (result.data.length > 0) {
                                $.each(result.data, function (index, element) {
                                    option = $('<option/>');
                                    option.attr({ 'value': element.regionID }).text(element.name);
                                    $('#region').append(option);
                                });
                            } else {
                            }
                        }
                    });
                }
            });
        </script>
    </body>
</html>
<?php
    $cnx = null;
?>