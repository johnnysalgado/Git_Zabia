<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/dao_cuestionario.php');

    if (isset($_SESSION['AFILIADO_NOMBRE'])) {
        $nombreAfiliado = $_SESSION['AFILIADO_NOMBRE'];
        $afiliadoID = $_SESSION['AFILIADO_ID'];
    } else {
        header("Location: elegir_afiliado.php?" . urlencode(basename($_SERVER['REQUEST_URI'])));
        die();
    }

    $estadoAbuscar = 1;
    $seccionID = -1;
    if (isset($_GET['a'])) {
        $estadoAbuscar = strval($_GET['a']);
        $_SESSION['PEST'] = $estadoAbuscar;
    } else {
        if (isset($_SESSION['PEST'])) {
            $estadoAbuscar = $_SESSION['PEST'];
        }
    }
    if (isset($_GET['s'])) {
        $seccionID = strval($_GET['s']);
        $_SESSION['PSEC'] = $seccionID;
    } else {
        if (isset($_SESSION['PSEC'])) {
            $seccionID = $_SESSION['PSEC'];
        }
    }

    $html = "";
    $daoCuestionario = new DaoCuestionario();
    $arregloTipoRespuesta = $daoCuestionario->listarTipoRespuesta();
    $arregloPregunta = $daoCuestionario->listarPreguntaPorSeccion($estadoAbuscar, $seccionID, $afiliadoID );
    foreach ($arregloPregunta as $item) {
        $idPregunta = $item['id_pregunta'];
        $idSeccionCuestionario = $item['id_seccion_cuestionario'];
        $descripcion = $item['descripcion'];
        $descripcionIngles = $item['descripcion_ing'];
        $tipoRespuesta = $item['tipo_respuesta'];
        $orden = $item['orden'];
        $datoEspecial = $item['dato_especial'];
        $seccion = $item['seccion'];
        $clase = ($item['estado'] == 1) ? CLASE_ACTIVO : CLASE_INACTIVO;
        $html .= "<tr data-id=\"$idPregunta\" class=\"$clase\" data-especial=\"$datoEspecial\">";
        $html .= "<td class=\"td-pregunta\">$idPregunta</td>";
        $html .= "<td class=\"td-pregunta\">$seccion</td>";
        $html .= "<td class=\"td-pregunta\">$descripcion</td>";
        $html .= "<td class=\"td-pregunta\">$descripcionIngles</td>";
        $html .= "<td class=\"td-pregunta text-center\">$orden</td>";
        $html .= "<td class=\"td-opcion text-center\">";
        if ($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE || $tipoRespuesta == TIPO_RESPUESTA_UNICA || $datoEspecial == DATOESPECIAL_PRECONDICION || $datoEspecial == DATOESPECIAL_INTOLERANCIA_ALERGIA || $datoEspecial == DATOESPECIAL_TIPO_DIETA) {
            $html .= "<i class=\"glyphicon glyphicon-option-horizontal\"></i>";
        }
        $html .= "</td>";
        $html .= "<td class=\"td-mostrar text-center\"> <i class=\"glyphicon glyphicon-eye-open\"> </td>";
        $html .= "<td class=\"td-omitir text-center\"> <i class=\"glyphicon glyphicon-eye-close\"> </td>";
        $html .= "<td class=\"td-subpregunta text-center\"> <i class=\"glyphicon glyphicon-random\"> </td>";
        $html .= "</tr>";
    }

    $arregloSeccion = $daoCuestionario->listarSeccionCuestionario($afiliadoID, LISTA_ACTIVO);
    $htmlSeccion = "";
    foreach ($arregloSeccion as $item) {
        $idSeccion = $item["id_seccion_cuestionario"];
        $seccion = $item["descripcion"];
        $seccionIngles = $item["descripcion_ing"];
        $htmlSeccion .= "<option value=\"$idSeccion\"";
        if ($idSeccion == $seccionID) {
            $htmlSeccion .= " selected=\"selected\"";
        }
        $htmlSeccion .= ">$seccion / $seccionIngles </option>";
    }

    $daoCuestionario = null;

    function obtenerTipoRespuesta($arreglo, $tipo) {
        $descripcion = "";
        $filtrado = array_filter(
            $arreglo,
            function ($e) use (&$tipo) {
                return $e['cod_tipo_respuesta'] == $tipo;
            }
        );
        foreach ($filtrado as $item) {
            $descripcion = $item["descripcion"];
        }
        return $descripcion;
    }

    function obtenerDatoEspecial($arreglo, $codigo) {
        $descripcion = "";
        $filtrado = array_filter(
            $arreglo,
            function ($e) use (&$codigo) {
                return $e['codigo'] == $codigo;
            }
        );
        foreach ($filtrado as $item) {
            $descripcion = $item["descripcion"];
        }
        return $descripcion;
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
                            <h4 class="page-title">Preguntas de cuestionario de salud para <?php echo $nombreAfiliado; ?></h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nueva-pregunta" value="Nueva pregunta" class="btn btn-primary nueva-pregunta" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <input type="checkbox" name="estado_buscar" id="estado-buscar" <?php if ($estadoAbuscar == 1) { echo ' checked="checked"';} ?> />
                                            <label for="estado">Listar activos</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Secci&oacute;n</label>
                                        <select class="form-control" id="seccion" name="seccion">
                                            <option value="-1">[Todos]</option>
                                            <?php echo $htmlSeccion; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table id="table" class="table table-striped display" style="cursor:pointer;">
                                <thead>
                                    <tr>
                                        <th class="text-center"> ID </th>
                                        <th class="text-center"> Secci&oacute;n </th>
                                        <th class="text-center"> Descripci&oacute;n </th>
                                        <th class="text-center"> Descripci&oacute;n [Ingl&eacute;s]</th>
                                        <th class="text-center"> Orden </th>
                                        <th class="text-center"> Opci&oacute;n </th>
                                        <th class="text-center"> Mostrar S&iacute; </th>
                                        <th class="text-center"> Omitir S&iacute; </th>
                                        <th class="text-center"> Sub preguntas </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $('.td-pregunta').click(function() {
                var id_pregunta = $(this).closest('tr').attr('data-id');
                location.href = "pregunta_editar.php?id=" + id_pregunta;
            });

            $('.td-opcion').click(function() {
                var id_pregunta = $(this).closest('tr').attr('data-id');
                var dato_especial = $(this).closest('tr').attr('data-especial');
                if (dato_especial == "<?php echo DATOESPECIAL_PRECONDICION; ?>") {
                    location.href = "pregunta_enfermedad.php?id_pregunta=" + id_pregunta;
                } else if (dato_especial == "<?php echo DATOESPECIAL_INTOLERANCIA_ALERGIA; ?>") {
                    location.href = "pregunta_intolerancia.php?id_pregunta=" + id_pregunta;
                } else if (dato_especial == "<?php echo DATOESPECIAL_TIPO_DIETA; ?>") {
                    location.href = "pregunta_tipo_dieta.php?id_pregunta=" + id_pregunta;
                } else if (dato_especial == "<?php echo DATOESPECIAL_TIPO_ACTIVIDAD; ?>") {
                    location.href = "pregunta_tipo_actividad.php?id_pregunta=" + id_pregunta;
                } else if (dato_especial == "<?php echo DATOESPECIAL_TIPO_GENERO; ?>") {
                    location.href = "pregunta_tipo_genero.php?id_pregunta=" + id_pregunta;
                } else if (dato_especial == "<?php echo DATOESPECIAL_TRASTORNO_ESTOMACAL; ?>") {
                    location.href = "pregunta_trastorno_estomacal.php?id_pregunta=" + id_pregunta;
                } else {
                    location.href = "pregunta_opcion.php?id_pregunta=" + id_pregunta;
                }
            });

            $('.td-mostrar').click(function() {
                var id_pregunta = $(this).closest('tr').attr('data-id');
                location.href = "pregunta_mostrar.php?id=" + id_pregunta;
            });

            $('.td-omitir').click(function() {
                var id_pregunta = $(this).closest('tr').attr('data-id');
                location.href = "pregunta_omitir.php?id=" + id_pregunta;
            });

            $('.td-subpregunta').click(function() {
                var id_pregunta = $(this).closest('tr').attr('data-id');
                location.href = "subpregunta.php?id=" + id_pregunta;
            });

            $('.nueva-pregunta').click(function() {
                location.href = "pregunta_crear.php";
            });

            $(document).ready(function() {
                $('#table').DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "15%" },
                        { "width": "20%" },
                        { "width": "20%" },
                        { "width": "7%" },
                        { "width": "10%" },
                        { "width": "7%" },
                        { "width": "7%" },
                        { "width": "7%" }
                    ]
                    , "bSort": false
                    , "pageLength": 500
                });
                $('#nav-health').addClass('active');
                $('#nav-health-question').addClass('active');
            });

            $('#estado-buscar').click( function() {
                irConFiltros();
            });

            $('#seccion').change( function(){
                irConFiltros();
            });

            function irConFiltros() {
                var estadoABuscar = 0;
                if ($('#estado-buscar').is(':checked')) {
                    estadoABuscar = 1;
                }
                var seccionID = $('#seccion').val();
                location.href = "pregunta.php?a=" + estadoABuscar + "&s=" + seccionID;
            }
        </script>
    </body>
</html>