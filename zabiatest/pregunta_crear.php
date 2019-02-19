<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/dao_cuestionario.php');
    require('inc/funcion_cuestionario.php');

    if (isset($_POST["seccion_cuestionario"])) {
        $idSeccionCuestionario = $_POST["seccion_cuestionario"];
        $descripcion = $_POST["descripcion"];
        $descripcionIngles = $_POST["descripcion_ing"];
        $alternativo = $_POST["alternativo"];
        $alternativoIngles = $_POST["alternativo_ing"];
        $tipoRespuesta = $_POST["tipo_respuesta"];
        $codigo = $_POST["codigo"];
        $orden = $_POST["orden"];
        $datoEspecial = $_POST["dato_especial"];
        $columnaPresentacion = $_POST["presentacion_col"];
        $valorMinimo = $_POST["valor_minimo"];
        $valorMaximo = $_POST["valor_maximo"];
        $controlPresentacion = $_POST["presentacion"];
        $flagEspecial = $_POST["flag_especial"];
        $usuario = $_SESSION["U"];
        if (isset($_POST["es_requerido"])) {
            $esRequerido = $_POST["es_requerido"];
        } else {
            $esRequerido = 0;
        }
        if (isset($_POST["oculta_opcion"])) {
            $ocultaOpcion = $_POST["oculta_opcion"];
        } else {
            $ocultaOpcion = "0";
        }
        if (isset($_POST["risk_engine"])) {
            $riskEngine = $_POST["risk_engine"];
        } else {
            $riskEngine = "0";
        }
        if (isset($_POST["system_required"])) {
            $systemRequired = $_POST["system_required"];
        } else {
            $systemRequired = "0";
        }
        if ($descripcion != "") {
            $descripcion = str_replace("'", "''", $descripcion);
        }
        if ($descripcionIngles != "") {
            $descripcionIngles = str_replace("'", "''", $descripcionIngles);
        }
        if ($alternativo != "") {
            $alternativo = str_replace("'", "''", $alternativo);
        }
        if ($alternativoIngles != "") {
            $alternativoIngles = str_replace("'", "''", $alternativoIngles);
        }
        if ($codigo != "") {
            $codigo = str_replace("'", "''", $codigo);
        }
        if ($flagEspecial != "") {
            $flagEspecial = str_replace("'", "''", $flagEspecial);
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
        $idAfiliado = $_SESSION['AFILIADO_ID'];

        $daoCuestionario = new DaoCuestionario();

        $idPregunta = $daoCuestionario->crearPregunta($idSeccionCuestionario, $descripcion, $descripcionIngles, $alternativo, $alternativoIngles, $tipoRespuesta, $codigo, $orden, $datoEspecial, $columnaPresentacion, $esRequerido, $valorMinimo, $valorMaximo, $controlPresentacion, $ocultaOpcion, $riskEngine, $flagEspecial, $systemRequired, $usuario);

        grabarPreguntaQuestionnaireSetPorAfiliado($daoCuestionario, $idPregunta, $idAfiliado, $usuario);

        if ($systemRequired == "1") {
            grabarPreguntaQuestionnaireSet($daoCuestionario, $idPregunta, $systemRequired, $usuario);
        }

        $daoCuestionario = null;

        header("Location: pregunta.php");
        die();
    } else {
        $htmlTipoRespuesta = "";
        $htmlSeccion = "";
        $htmlDatoEspecial = "";
        $htmlColumnaPresentacion = "";
        $htmlControlPresentacion = "";
        $idAfiliado = $_SESSION['AFILIADO_ID'];
        $daoCuestionario = new DaoCuestionario();
        $arregloSeccion = $daoCuestionario->listarSeccionCuestionario($idAfiliado, LISTA_ACTIVO);
        foreach ($arregloSeccion as $item) {
            $htmlSeccion .= "<option value=\"" . $item['id_seccion_cuestionario'] . "\">" . $item['descripcion'] . "</option>";
        }
        $arregloTipoRespuesta = $daoCuestionario->listarTipoRespuesta();
        foreach ($arregloTipoRespuesta as $item) {
            $htmlTipoRespuesta .= "<option value=\"" . $item['cod_tipo_respuesta'] . "\">" . $item['descripcion'] . "</option>";
        }
        $daoCuestionario = null;
        foreach ($arregloDatoEspecial as $item) {
            $htmlDatoEspecial .= "<option value=\"" . $item['codigo'] . "\">" . $item['descripcion'] . "</option>";
        }
        foreach ($arregloColumnaPresentacion as $item) {
            $htmlColumnaPresentacion .= "<option value=\"" . $item['codigo'] . "\">" . $item['descripcion'] . "</option>";
        }
        foreach ($arregloControlPresentacion as $item) {
            $htmlControlPresentacion .= "<option value=\"" . $item['codigo'] . "\">" . $item['descripcion'] . "</option>";
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
                            <h4 class="page-title">Creaci&oacute;n de Pregunta de Salud - <?php echo $_SESSION['AFILIADO_NOMBRE']; ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="pregunta_crear.php" method="post" id="forma">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Secci&oacute;n cuestionario *</label>
                                            <select name="seccion_cuestionario" id="seccion_cuestionario" class="form-control">
                                                <option value="">[Seleccione]</option>
                                                <?php echo $htmlSeccion; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n *</label>
                                            <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="128" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n [Ingl&eacute;s]</label>
                                            <input type="text" name="descripcion_ing" id="descripcion_ing" class="form-control" maxlength="128" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Texto complementario (usado en placeholder)</label>
                                            <input type="text" name="alternativo" id="alternativo" class="form-control" maxlength="128" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Texto complementario [Ingl&eacute;s]</label>
                                            <input type="text" name="alternativo_ing" id="alternativo_ing" class="form-control" maxlength="128" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tipo respuesta *</label>
                                            <select name="tipo_respuesta" id="tipo_respuesta" class="form-control">
                                                <option value="">[Seleccione]</option>
                                                <?php echo $htmlTipoRespuesta; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 div-rango">
                                        <div class="form-group">
                                            <label>Valor m&iacute;nimo</label>
                                            <input type="number" name="valor_minimo" id="valor_minimo" class="form-control" step="1" min="0" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 div-rango">
                                        <div class="form-group">
                                            <label>Valor m&aacute;ximo</label>
                                            <input type="number" name="valor_maximo" id="valor_maximo" class="form-control" step="1" min="0" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="es_requerido" id="es_requerido" value="1" />
                                                <label for="es_requerido">Es requerido</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>C&oacute;digo</label>
                                            <input type="text" name="codigo" id="codigo" class="form-control" maxlength="8" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Orden</label>
                                            <input type="number" name="orden" id="orden" class="form-control" step="1" min="1" value="1" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                                            <label>Presentaci&oacute;n</label>
                                            <select name="presentacion_col" id="presentacion_col" class="form-control">
                                                <?php echo $htmlColumnaPresentacion; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" id="divControlPresentacion">
                                            <label>Presentaci&oacute;n del control</label>
                                            <select name="presentacion" id="presentacion" class="form-control">
                                                <?php echo $htmlControlPresentacion; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="oculta_opcion" id="oculta_opcion" value="1" />
                                                <label for="oculta_opcion">Oculta opciones</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="risk_engine" id="risk_engine" value="1" />
                                                <label for="risk_engine">Procesar risk engine</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>C&oacute;digo para Risk Engine</label>
                                            <input type="text" name="flag_especial" id="flag_especial" class="form-control" maxlength="64" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="system_required" id="system_required" value="1" />
                                                <label for="system_required">Pregunta obligatoria en todos los afiliados</label>
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
                $('#divControlPresentacion').hide();

                function configurarRango() {
                    $('.div-rango').hide();
                    if ($('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_NUMERO ?>' || $('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_DECIMAL ?>' ) {
                        $('.div-rango').show();
                        if ($('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_NUMERO ?>') {
                            $('.div-rango').find('input[type="number"]').attr('step', '1');
                        } else {
                            $('.div-rango').find('input[type="number"]').attr('step', '0.01');
                        }
                    }
                }

                function configurarControlPresentacion() {
                    $('#divControlPresentacion').hide();
                    if ($('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_MULTIPLE ?>' || $('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_UNICA ?>' ) {
                        $('#divControlPresentacion').show();
                        if ($('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_MULTIPLE ?>') {
                            $('#presentacion option[value="<?php echo CONTROL_PRESENTACION_HORIZONTAL ?>"]').prop("selected", false).hide();
                            $('#presentacion option[value="<?php echo CONTROL_PRESENTACION_ENLINEA ?>"]').prop("selected", true).show();
                        } else if ($('#tipo_respuesta').val() == '<?php echo TIPO_RESPUESTA_UNICA ?>') {
                            $('#presentacion option[value="<?php echo CONTROL_PRESENTACION_HORIZONTAL ?>"]').prop("selected", true).show();
                            $('#presentacion option[value="<?php echo CONTROL_PRESENTACION_ENLINEA ?>"]').prop("selected", false).hide();
                        }
                    }
                }

                $('#tipo_respuesta').change(function (){
                    configurarRango();
                    configurarControlPresentacion();
                });
            });

            $('#volver').click(function() {
                location.href = 'pregunta.php';
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