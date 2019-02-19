<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/dao_cuestionario.php');

    $usuario = $_SESSION["U"];
    $html = "";
    if (isset($_POST['id_pregunta'])) {
        $idPregunta = $_POST['id_pregunta'];
        $estadoABuscar = $_POST['estado_buscar'];
        $idPreguntaTrastornoEstomacal = $_POST['id_pregunta_trastorno_estomacal'];
        $orden = $_POST['orden'];
        $estado = $_POST['estado'];
        if (isset($_POST["none_"])) {
            $none = $_POST["none_"];
        } else {
            $none = "0";
        }
        if ($orden == '') $orden = 1;
        $daoCuestionario = new DaoCuestionario();
        $daoCuestionario->editarPreguntaTrastornoEstomacal($idPreguntaTrastornoEstomacal, $orden, $none, $estado, $usuario);
        $daoCuestionario = null;
        header("Location: pregunta_trastorno_estomacal.php?id_pregunta=$idPregunta&e=$estadoABuscar");
        die();
    } else if (isset($_GET['id_pregunta']) && is_numeric($_GET['id_pregunta'])) {
        $idPregunta = $_GET['id_pregunta'];
        $estadoABuscar = "";
        if (isset($_GET["e"])) {
            $estadoABuscar = $_GET["e"];
        }
        if ($estadoABuscar == "") {
            $estadoABuscar = LISTA_ACTIVO;
        }
        if ($estadoABuscar == LISTA_ACTIVO) {
            $textoEstadoAlterno = "Inactivos";
        } else {
            $textoEstadoAlterno = "Activos";
        }
        if (isset($_GET["on"])) {
            $obtenerNuevos = $_GET["on"];
        } else {
            $obtenerNuevos = "";
        }
        $daoCuestionario = new DaoCuestionario();
        if ($obtenerNuevos == "1") {
            $daoCuestionario->poblarPreguntaTrastornoEstomacal($idPregunta, $usuario);
        }
        $arregloPreguntaTrastornoEstomacal = $daoCuestionario->listarPreguntaTrastornoEstomacal($idPregunta, $estadoABuscar);
        if (count($arregloPreguntaTrastornoEstomacal) == 0 && $estadoABuscar == 1) {
            $daoCuestionario->poblarPreguntaTrastornoEstomacal($idPregunta, $usuario);
            $arregloPreguntaTrastornoEstomacal = $daoCuestionario->listarPreguntaTrastornoEstomacal($idPregunta, LISTA_ACTIVO);
        }
        $html = "<ol class=\"dd-list\">";
        foreach ($arregloPreguntaTrastornoEstomacal as $item) {
            $idPreguntaTrastornoEstomacal = $item['id_pregunta_trastorno_estomacal'];
            $descripcion = $item['nombre'] . " / " . $item['nombre_ing'];
            $estado = $item['estado'];
            if ($estado == 1) {
                $clase = CLASE_ACTIVO;
            } else {
                $clase = CLASE_INACTIVO;
            }
            $html .= "<li class=\"dd-item dd3-item\" data-id=\"$idPreguntaTrastornoEstomacal\"> <div class=\"dd-handle dd3-handle $clase\"> </div>  <div class=\"dd3-content\" data-id=\"$idPreguntaTrastornoEstomacal\"> <input type=\"checkbox\" name=\"chk_eliminar[]\" value=\"$idPreguntaTrastornoEstomacal\" /> <span style=\"cursor:pointer;\" class=\"ver-option\" data-id=\"$idPreguntaTrastornoEstomacal\"> $descripcion </span> </div> </li>";
        }
        $html .= "</ol>";
        $descripcionPregunta = "";
        $arregloPregunta = $daoCuestionario->obtenerPregunta($idPregunta);
        if (count($arregloPregunta) > 0) {
            $descripcionPregunta = $arregloPregunta[0]['descripcion'];
        }
        $daoCuestionario = null;
    } else {
        header("Location: pregunta.php");
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
                            <h4 class="page-title">Opciones de respuesta: <?php echo $descripcionPregunta; ?></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="white-box">
                                <form action="pregunta_trastorno_estomacal_eliminar.php" method="post" id="forma-eliminar">
                                    <div class="myadmin-dd-empty dd" id="nestable2">
                                        <?php echo $html; ?>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <input type="hidden" name="id_pregunta_eliminar" value="<?php echo $idPregunta; ?>" />
                                            <input type="hidden" name="estado_buscar_eliminar" value="<?php echo $estadoABuscar; ?>" />
                                            <input type="button" id="obtener-nuevo" value="Obtener nuevos" class="btn btn-default" />
                                            &nbsp;&nbsp;
                                            <input type="button" id="listar-todo" value="Listar <?php echo $textoEstadoAlterno; ?>" class="btn btn-default" />
                                            &nbsp;&nbsp;
                                            <input type="submit" id="eliminar-todo" value="<?php if ($estadoABuscar == LISTA_ACTIVO) { echo 'Eliminar'; } else { echo  'Activar'; } ?>" 
                                            class="btn btn-primary" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-8">
                            <div class="white-box">
                                <form action="pregunta_trastorno_estomacal.php" method="post" id="forma">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Descripci&oacute;n</label>
                                                <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="128" readonly="readonly" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Descripci&oacute;n [ingl&eacute;s]</label>
                                                <input type="text" name="descripcion_ing" id="descripcion_ing" class="form-control" maxlength="128" readonly="readonly" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>N&deg; orden</label>
                                                <input type="number" name="orden" id="orden" class="form-control" step="1" min="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <input type="checkbox" name="none_" id="none_" value="1" />
                                                <label for="none_">None</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Registrado</label>
                                        </div>
                                        <div class="col-md-9" id="dato-registro"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Modificado</label>
                                        </div>
                                        <div class="col-md-9" id="dato-modifica"></div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                            &nbsp;&nbsp;
                                            <input type="button" id="limpiar" value="Limpiar" class="btn btn-default" />
                                            &nbsp;&nbsp;
                                            <input type="button" id="eliminar" value="Eliminar" class="btn btn-primary" />
                                            &nbsp;&nbsp;
                                            <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div id="mensaje" class="row alert alert-danger col-md-12"></div>
                                    </div>
                                    <input type="hidden" name="id_pregunta" value="<?php echo $idPregunta; ?>" />
                                    <input type="hidden" name="estado_buscar" value="<?php echo $estadoABuscar; ?>" />
                                    <input type="hidden" name="id_pregunta_trastorno_estomacal" id="id_pregunta_trastorno_estomacal" value="0" />
                                    <input type="hidden" name="estado" id="estado" value="1" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {

                <?php 
                if ($estadoABuscar == LISTA_ACTIVO) {
                    echo "$('#eliminar').attr('value','Inactivar');\n";
                } else {
                    echo "$('#eliminar').attr('value','Activar');\n";
                } 
                ?>
                limpiarForma();
                $('#nav-health').addClass('active');
                $('#nav-health-question').addClass('active');

                $('#grabar').click(function() {
                    $('#mensaje').hide();
                    $(this).attr('disabled','disabled');
                    $('#forma').submit();
                });

                $('#eliminar').click(function() {
                    if (confirm('¿Está seguro?')) {
                        $('#mensaje').hide();
                    <?php 
                    if ($estadoABuscar == LISTA_ACTIVO) {
                        echo "$('#estado').val('0');\n";
                    } else {
                        echo "$('#estado').val('1');\n";
                    } 
                    ?>
                        $(this).attr('disabled','disabled');
                        $('#forma').submit();
                    }
                });

                $('#volver').click(function() {
                    location.href = 'pregunta.php';
                });

                $('#limpiar').click(function() {
                    limpiarForma();
                });

                // Nestable
                var updateOutput = function(e) {
                    var list = e.length ? e : $(e.target),
                        output = list.data('output');
                    console.log(list.nestable('serialize'));
                    if (window.JSON) {
                        output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                    } else {
                        output.val('JSON browser support required for this demo.');
                    }
                    $.ajax({
                        method: "POST",
                        url: "service/savehealthstomachdisturbanceorder.php",
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

                $('.ver-option').click(function() {
                    limpiarForma();
                    var idPreguntaTranstornoEstomacal = $(this).attr('data-id');
                    var param = { "healthStomachDisturbanceID" : idPreguntaTranstornoEstomacal };
                    var paramJSON = JSON.stringify(param);
                    $.ajax({
                        type: 'POST',
                        url: 'service/gethealthstomachdisturbancebyid.php',
                        data: paramJSON,
                        dataType: 'json',
                        error: function(errorResult) {
                            console.log('Ha ocurrido un error ' + errorResult.responseText);
                        },
                        success: function (result) {
                            console.log(result);
                            $('#id_pregunta_trastorno_estomacal').val(result.data[0].healthStomachDisturbanceID);
                            $('#descripcion').val(result.data[0].description);
                            $('#descripcion_ing').val(result.data[0].descriptionEnglish);
                            $('#orden').val(result.data[0].order);
                            $('#estado').val(result.data[0].active)
                            $('#none_').prop('checked', (result.data[0].none == 1)? true : false);
                            $('#dato-registro').html(result.data[0].registerUser + ' - ' + result.data[0].registerDate);
                            if (result.data[0].updateDate != null) {
                                $('#dato-modifica').html(result.data[0].updateUser + ' - ' + result.data[0].updateDate);
                            } else {
                                $('#dato-modifica').html('');
                            }
                            $('#eliminar').show();
                            $('#grabar').show();
                        }
                    });
                });

                function limpiarForma() {
                    $('#mensaje').hide();
                    $('#eliminar').hide();
                    $('#grabar').hide();
                    $('#descripcion').val('');
                    $('#descripcion_ing').val('');
                    $('#orden').val('0');
                    $('#orden').focus();
                    $('#none_').prop('checked', false);
                    $('#id_pregunta_trastorno_estomacal').val('0');
                    $('#dato-registro').html('');
                    $('#dato-modifica').html('');
                }

                $('#listar-todo').click( function () {
                    <?php 
                    if ($estadoABuscar == LISTA_ACTIVO) {
                        echo "var estadoABuscar = \"" . LISTA_INACTIVO . "\";\n";
                    } else {
                        echo "var estadoABuscar = \"" . LISTA_ACTIVO . "\";\n";
                    } 
                    ?>
                    location.href = "pregunta_trastorno_estomacal.php?id_pregunta=<?php echo $idPregunta; ?>&e=" + estadoABuscar;
                });

                $('#obtener-nuevo').click( function() {
                    location.href = "pregunta_trastorno_estomacal.php?id_pregunta=<?php echo $idPregunta; ?>&e=<?php echo $estadoABuscar; ?>&on=1";
                });

            });

        </script>
    </body>
</html>