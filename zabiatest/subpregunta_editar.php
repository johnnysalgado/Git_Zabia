<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante_cuestionario.php');
    require('inc/dao_cuestionario.php');

    if (isset($_POST["id_subpregunta"])) {
        $idPregunta = $_POST["id_pregunta"];
        $idSubpregunta = $_POST["id_subpregunta"];
        $descripcion = $_POST["descripcion"];
        $descripcionIngles = $_POST["descripcion_ing"];
        $tipoRespuesta = $_POST["tipo_respuesta"];
        $orden = $_POST["orden"];
        $datoEspecial = $_POST["dato_especial"];
        $valorMinimo = $_POST["valor_minimo"];
        $valorMaximo = $_POST["valor_maximo"];
        $usuario = $_SESSION["U"];
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }
        if ($descripcion != "") {
            $descripcion = str_replace("'", "''", $descripcion);
        }
        if ($descripcionIngles != "") {
            $descripcionIngles = str_replace("'", "''", $descripcionIngles);
        }
        if (trim($orden) == "") {
            $orden = 1;
        }
        if (trim($valorMinimo) == "") {
            $valorMinimo = 0;
        }
        if (trim($valorMaximo) == "") {
            $valorMaximo = 0;
        }

        $daoCuestionario = new DaoCuestionario();
        $daoCuestionario->editarSubpregunta($idSubpregunta, $descripcion, $descripcionIngles, $tipoRespuesta, $orden, $datoEspecial, $valorMinimo, $valorMaximo, $estado, $usuario);
        $daoCuestionario = null;

        header("Location: subpregunta.php?id=$idPregunta");
        die();
    } else {
        $idPregunta = $_GET['id'];
        $idSubpregunta = $_GET['ids'];
        if (is_numeric($idSubpregunta)) {
            $descripcion = "";
            $descripcionIngles = "";
            $tipoRespuesta = "";
            $orden = 1;
            $htmlTipoRespuesta = "";
            $htmlDatoEspecial = "";
            $valorMinimo = 0;
            $valorMaximo = 0;
            $daoCuestionario = new DaoCuestionario();
            $arreglo = $daoCuestionario->obtenerSubpregunta($idSubpregunta);
            if (count($arreglo) > 0) {
                $item = $arreglo[0];
                $descripcion = $item['descripcion'];
                $descripcionIngles = $item['descripcion_ing'];
                $tipoRespuesta = $item['tipo_respuesta'];
                $orden = $item['orden'];
                $datoEspecial = $item['dato_especial'];
                $valorMinimo = $item['valor_minimo'];
                $valorMaximo = $item['valor_maximo'];
                $estado = $item['estado'];
                if ($tipoRespuesta == TIPO_RESPUESTA_NUMERO) {
                    $valorMinimo = round($valorMinimo, 0);
                    $valorMaximo = round($valorMaximo, 0);
                }
            }
            $arregloTipoRespuesta = $daoCuestionario->listarTipoRespuesta();
            foreach ($arregloTipoRespuesta as $item) {
                if ($item['cod_tipo_respuesta'] != TIPO_RESPUESTA_MULTIPLE) {
                    $htmlTipoRespuesta .= "<option value=\"" . $item['cod_tipo_respuesta'] . "\"";
                    if ($tipoRespuesta == $item['cod_tipo_respuesta']) {
                        $htmlTipoRespuesta .= " selected=\"selected\"";
                    }
                    $htmlTipoRespuesta .= ">" . $item['descripcion'] . "</option>";
                }
            }
            $daoCuestionario = null;
            foreach ($arregloDatoEspecial as $item) {
                $htmlDatoEspecial .= "<option value=\"" . $item['codigo'] . "\"";
                if ($datoEspecial == $item['codigo']) {
                    $htmlDatoEspecial .= " selected=\"selected\"";
                }
                $htmlDatoEspecial .= ">" . $item['descripcion'] . "</option>";
            }
        } else {
            header("Location: subpregunta.php?id=$idPregunta");
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
                            <h4 class="page-title">Modificaci&oacute;n de Sub pregunta</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="subpregunta_editar.php" method="post" id="forma">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n *</label>
                                            <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="128" value="<?php echo $descripcion; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n [Ingl&eacute;s]</label>
                                            <input type="text" name="descripcion_ing" id="descripcion_ing" class="form-control" maxlength="128" value="<?php echo $descripcionIngles; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tipo respuesta</label>
                                            <select name="tipo_respuesta" id="tipo_respuesta" class="form-control">
                                                <option value="">[Seleccione]</option>
                                                <?php echo $htmlTipoRespuesta; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 div-rango">
                                        <div class="form-group">
                                            <label>Valor m&iacute;nimo</label>
                                            <input type="number" name="valor_minimo" id="valor_minimo" class="form-control" step="1" min="0" value="<?php echo $valorMinimo; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 div-rango">
                                        <div class="form-group">
                                            <label>Valor m&aacute;ximo</label>
                                            <input type="number" name="valor_maximo" id="valor_maximo" class="form-control" step="1" min="0" value="<?php echo $valorMaximo; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Orden</label>
                                            <input type="number" name="orden" id="orden" class="form-control" step="1" min="1" value="<?php echo $orden; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Dato especial</label>
                                            <select name="dato_especial" id="dato_especial" class="form-control">
                                                <option value="">[Seleccione]</option>
                                                <?php echo $htmlDatoEspecial; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="estado" id="estado" <?php if ($estado == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="estado">Activo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <div id="divError" class="row alert alert-danger"></div>
                                <input type="hidden" name="id_pregunta" name="id_pregunta" value="<?php echo $idPregunta; ?>" />
                                <input type="hidden" name="id_subpregunta" name="id_subpregunta" value="<?php echo $idSubpregunta; ?>" />
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
                $('.div-rango').hide();

                function configurarRango() {
                    $('.div-rango').hide();
                    if ($('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_NUMERO ?>' || $('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_DECIMAL ?>' ) {
                        $('.div-rango').show();
                        if ($('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_NUMERO ?>') {
                            $('#presentacion').find('input[type="number"]').attr('step', '1');
                        } else {
                            $('#presentacion').find('input[type="number"]').attr('step', '0.01');
                        }
                    }
                }

                configurarRango();

                $('#tipo_respuesta').change(function (){
                    configurarRango();
                });

            });

            $('#volver').click(function() {
                location.href = 'subpregunta.php?id=<?php echo $idPregunta; ?>';
            });

            $('#grabar').click(function() {
                $('#divError').html('').hide();
                if ($('#tipo_respuesta').val() != '<?php echo TIPO_RESPUESTA_NUMERO ?>' && $    ('#tipo_respuesta').val() != '<?php echo TIPO_RESPUESTA_DECIMAL ?>' ) {
                    $('#valor_minimo').val('');
                    $('#valor_maximo').val('');
                }
                if ($.trim($('#descripcion').val()) == '') {
                    $('#divError').html('La descripción no puede estar vacía').show();
                } else if ($('#seccion_cuestionario').val() == '') {
                    $('#divError').html('La sección no puede estar vacía').show();
                } else if ($('#tipo_respuesta').val() == '') {
                    $('#divError').html('El tipo de respuesta no puede estar vacío').show();
                } else if ($('#valor_minimo').val() != '' && $('#valor_maximo').val() != '') {
                    var valorMinimo = parseFloat($('#valor_minimo').val());
                    var valorMaximo = parseFloat($('#valor_maximo').val());
                    if (valorMinimo > valorMaximo ) {
                        $('#divError').html('El rango de valores está errado').show();
                    } else {
                        $(this).attr('disabled','disabled');
                        $('#forma').submit();
                    }
                } else {
                    $(this).attr('disabled','disabled');
                    $('#forma').submit();
                }
            });

        </script>
    </body>
</html>