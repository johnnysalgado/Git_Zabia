<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/dao_cuestionario.php');

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

    $estadoAbuscar = 1;
    if (isset($_GET['a'])) {
        $estadoAbuscar = strval($_GET['a']);
    }
    if (isset($_GET['id'])) {
        $idPregunta = strval($_GET['id']);
    }
    if (is_numeric($idPregunta)) {
        $html = "";
        $daoCuestionario = new DaoCuestionario();
        $arregloTipoRespuesta = $daoCuestionario->listarTipoRespuesta();
        $arregloSubpregunta = $daoCuestionario->listarSubpregunta($idPregunta, $estadoAbuscar);
        $cantidadSubpregunta = count($arregloSubpregunta);
        foreach ($arregloSubpregunta as $item) {
            $idSubpregunta = $item['id_subpregunta'];
            $descripcion = $item['descripcion'];
            $descripcionIngles = $item['descripcion_ing'];
            $tipoRespuesta = $item['tipo_respuesta'];
            $orden = $item['orden'];
            $datoEspecial = $item['dato_especial'];
            $clase = ($item['estado'] == 1) ? CLASE_ACTIVO : CLASE_INACTIVO;
            $html .= "<tr data-id=\"$idSubpregunta\" class=\"$clase\">";
            $html .= "<td class=\"td-pregunta\">$idSubpregunta</td>";
            $html .= "<td class=\"td-pregunta\">$descripcion</td>";
            $html .= "<td class=\"td-pregunta\">$descripcionIngles</td>";
            $html .= "<td class=\"td-pregunta\">$orden</td>";
            $html .= "<td class=\"td-opcion\">";
            if (($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE || $tipoRespuesta == TIPO_RESPUESTA_UNICA) && $datoEspecial == "") {
                $html .= "<i class=\"glyphicon glyphicon-option-horizontal\"></i>";
            }
            $html .= "</td>";
            $html .= "</tr>";
        }

        $arregloPregunta = $daoCuestionario->obtenerPregunta($idPregunta);
        $tituloPregunta = "";
        if ( count($arregloPregunta) > 0 ) {
            $item = $arregloPregunta[0];
            $tituloPregunta = $item["descripcion"] . "[" . $item["descripcion_ing"] . "]";
        }

        $daoCuestionario = null;

        if ($estadoAbuscar == 1 && $cantidadSubpregunta > 1) {
            $styleBotonNueva = "style=\"display:none\"";
        } else {
            $styleBotonNueva = "";
        }

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
                            <h4 class="page-title">Sub preguntas para: <?php echo $tituloPregunta; ?></h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" value="Volver" class="btn btn-default volver" />
                            &nbsp;&nbsp;
                            <input type="button" id="nueva" value="Nueva sub pregunta" class="btn btn-primary nueva" <?php echo $styleBotonNueva; ?> />
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
                            </div>
                            <table id="table" class="table table-striped display" style="cursor:pointer;">
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> Descripci&oacute;n </th>
                                        <th> Descripci&oacute;n [Ingl&eacute;s]</th>
                                        <th> Orden </th>
                                        <th> Opciones </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" value="Volver" class="btn btn-default volver" />
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $('.td-pregunta').click(function() {
                var id_subpregunta = $(this).closest('tr').attr('data-id');
                location.href = "subpregunta_editar.php?id=<?php echo $idPregunta; ?>&ids=" + id_subpregunta;
            });

            $('.td-opcion').click(function() {
                var id_subpregunta = $(this).closest('tr').attr('data-id');
                location.href = "subpregunta_opcion.php?id=<?php echo $idPregunta; ?>&ids=" + id_subpregunta;
            });

            $('.nueva').click(function() {
                location.href = "subpregunta_crear.php?id=<?php echo $idPregunta; ?>";
            });

            $(document).ready(function() {
                $('#table').DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "35%" },
                        { "width": "35%" },
                        { "width": "15%" },
                        { "width": "10%" }
                    ]
                });
                $('#nav-health').addClass('active');
                $('#nav-health-question').addClass('active');
            });

            $('#estado-buscar').click( function() {
                irConFiltros();
            });

            function irConFiltros() {
                var estadoABuscar = 0;
                if ($('#estado-buscar').is(':checked')) {
                    estadoABuscar = 1;
                }
                location.href = "subpregunta.php?a=" + estadoABuscar + '&id=<?php echo $idPregunta; ?>';
            }

            $('.volver').click( function (){
                location.href = 'pregunta.php';
            })
        </script>
    </body>
</html>