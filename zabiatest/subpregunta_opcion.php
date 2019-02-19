<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/constante_cuestionario.php');
    require('inc/dao_cuestionario.php');
    require('inc/dao_bienestar.php');

    $html = "";
    if (isset($_POST['id_subpregunta'])) {
        $idPregunta = $_POST['id_pregunta'];
        $idSubpregunta = $_POST['id_subpregunta'];
        $idSubrespuesta = $_POST['id_subrespuesta'];
        $descripcion = $_POST['descripcion'];
        $descripcionIngles = $_POST['descripcion_ing'];
        $orden = $_POST['orden'];
        if ($orden == "0") {
            $orden = 1;
        }
        $estado = $_POST['estado'];
        $usuario = $_SESSION["U"];
        if ($descripcion != '') $descripcion = str_replace("'", "''", $descripcion);
        if ($descripcionIngles != '') $descripcionIngles = str_replace("'", "''", $descripcionIngles);
        if ($orden == '') $orden = 1;
        $daoCuestionario = new DaoCuestionario();
        $idSubrespuesta = $daoCuestionario->crearActualizarSubrespuesta($idSubpregunta, $idSubrespuesta, $descripcion, $descripcionIngles, $orden, $estado, $usuario);
        $daoCuestionario = null;
        header("Location: subpregunta_opcion.php?id=$idPregunta&ids=$idSubpregunta");
        die();
    } else if (isset($_GET['ids']) && is_numeric($_GET['ids'])) {
        $idPregunta = $_GET['id'];
        $idSubpregunta = $_GET['ids'];
        $daoCuestionario = new DaoCuestionario();
        $arregloRespuesta = $daoCuestionario->listarSubrespuesta($idSubpregunta);
        $html = '<ol class="dd-list">';
        foreach ($arregloRespuesta as $item) {
            $idSubrespuesta = $item['id_subrespuesta'];
            $descripcion = $item['descripcion'];
            $html .= "<li class=\"dd-item dd3-item\" data-id=\"$idSubrespuesta\"> <div class=\"dd-handle dd3-handle\"></div> <div class=\"dd3-content ver-option\" data-id=\"$idSubrespuesta\" style=\"cursor:pointer;\">$descripcion</div> </li>";
        }
        $html .= '</ol>';
        $descripcionSubpregunta = "";
        $arregloSubpregunta = $daoCuestionario->obtenerSubpregunta($idSubpregunta);
        if (count($arregloSubpregunta) > 0) {
            $descripcionSubpregunta = $arregloSubpregunta[0]['descripcion'];
        }
        $daoCuestionario = null;
    } else {
        header("Location: subpregunta.php?id=$idPregunta");
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
                            <h4 class="page-title">Opciones de respuesta: <?php echo $descripcionSubpregunta; ?></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="white-box">
                                <div class="myadmin-dd-empty dd" id="nestable2">
                                    <?php echo $html; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-8">
                            <div class="white-box">
                                <form action="subpregunta_opcion.php" method="post" id="forma">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Descripci&oacute;n</label>
                                                <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="128" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Descripci&oacute;n [ingl&eacute;s]</label>
                                                <input type="text" name="descripcion_ing" id="descripcion_ing" class="form-control" maxlength="128" />
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
                                            <input type="button" id="nueva-respuesta" value="Grabar" class="btn btn-success" />
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div id="mensaje" class="row alert alert-danger col-md-12"></div>
                                    </div>
                                    <input type="hidden" name="id_subpregunta" value="<?php echo $idSubpregunta; ?>" />
                                    <input type="hidden" name="id_pregunta" value="<?php echo $idPregunta; ?>" />
                                    <input type="hidden" name="id_subrespuesta" id="id_subrespuesta" value="0" />
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

                limpiarForma();
                $('#nav-health').addClass('active');
                $('#nav-health-question').addClass('active');

                $('#nueva-respuesta').click(function() {
                    $('#mensaje').hide();
                    if ($('#descripcion').val().trim() == '') {
                        $('#mensaje').html('No ha ingresado descripción').show();
                    } else {
                        $(this).attr('disabled','disabled');
                        $('#forma').submit();
                    }
                });

                $('#eliminar').click(function() {
                    if (confirm('¿Está seguro?')) {
                        $('#mensaje').hide();
                        $('#estado').val('0');
                        $(this).attr('disabled','disabled');
                        $('#forma').submit();
                    }
                });

                $('#volver').click(function() {
                    location.href = 'subpregunta.php?id=<?php echo $idPregunta; ?>';
                });

                $('#limpiar').click(function() {
                    limpiarForma();
                });

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
                        url: "service/savehealthoptionsubquestionorder.php",
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
                    var idSubrespuesta = $(this).attr('data-id');
                    var param = { "healthOptionID" : idSubrespuesta };
                    var paramJSON = JSON.stringify(param);
                    $.ajax({
                        type: 'POST',
                        url: 'service/gethealthoptionsubquestionbyid.php',
                        data: paramJSON,
                        dataType: 'json',
                        error: function(errorResult) {
                            console.log('Ha ocurrido un error ' + errorResult.responseText);
                        },
                        success: function (result) {
                            $('#id_subrespuesta').val(result.data[0].healthOptionID);
                            $('#descripcion').val(result.data[0].description);
                            $('#descripcion_ing').val(result.data[0].descriptionEnglish);
                            $('#orden').val(result.data[0].order);
                            $('#estado').val(result.data[0].active)
                            $('#dato-registro').html(result.data[0].registerUser + ' - ' + result.data[0].registerDate);
                            if (result.data[0].updateDate != null) {
                                $('#dato-modifica').html(result.data[0].updateUser + ' - ' + result.data[0].updateDate);
                            } else {
                                $('#dato-modifica').html('');
                            }
                            $('#eliminar').show();
                            $('#descripcion').focus();
                        }
                    });
                });

                function limpiarForma() {
                    $('#mensaje').hide();
                    $('#eliminar').hide();
                    $('#descripcion').val('');
                    $('#descripcion_ing').val('');
                    $('#orden').val('0');
                    $('#dato-registro').html('');
                    $('#dato-modifica').html('');
                    $('#id_subrespuesta').val('0');
                }

            });

        </script>
    </body>
</html>