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
    if (isset($_GET['a'])) {
        $estadoAbuscar = strval($_GET['a']);
        $_SESSION['SEST'] = $estadoAbuscar;
    } else {
        $estadoAbuscar = $_SESSION['SEST'];
    }

    $html = "";
    $daoCuestionario = new DaoCuestionario();
    $arregloSeccion = $daoCuestionario->listarSeccionCuestionario($afiliadoID, $estadoAbuscar);
    $daoCuestionario = null;
    foreach ($arregloSeccion as $item) {
        $idSeccionCuestionario = $item['id_seccion_cuestionario'];
        $descripcion = $item['descripcion'];
        $descripcionIngles = $item['descripcion_ing'];
        $orden = $item['orden'];
        $estado = $item['estado'];
        $clase = ($estado == 1) ? CLASE_ACTIVO : CLASE_INACTIVO;
        $html .= "<tr data-id=\"$idSeccionCuestionario\" class=\"$clase\">";
        $html .= "<td class=\"td-seccion\">$idSeccionCuestionario</td>";
        $html .= "<td class=\"td-seccion\">$descripcion</td>";
        $html .= "<td class=\"td-seccion\">$descripcionIngles</td>";
        $html .= "<td class=\"td-seccion\">$orden</td>";
        $html .= "<td class=\"td-mostrar\"> <i class=\"glyphicon glyphicon-eye-open\"></i> </td>";
        $html .= "<td class=\"td-omitir\"> <i class=\"glyphicon glyphicon-eye-close\"></i> </td>";
        if ($estado == 1) {
            $html .= "<td class=\"td-seccion\"> <i class=\"glyphicon glyphicon-ok\"></i> </td>";
        } else {
            $html .= "<td class=\"td-seccion\"> <i class=\"glyphicon glyphicon-remove\"></i> </td>";
        }
        $html .= "</tr>";
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
                            <h4 class="page-title">Secci&oacute;n de cuestionario de salud para <?php echo $nombreAfiliado; ?></h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nueva-seccion" value="Nueva secci&oacute;n" class="btn btn-primary nueva-seccion" />
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
                            <table id="tip-table" class="table table-striped display" style="cursor:pointer;">
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> Descripci&oacute;n </th>
                                        <th> Descripci&oacute;n [Ingl&eacute;s] </th>
                                        <th> Orden </th>
                                        <th> Mostrar S&iacute; </th>
                                        <th> Omitir S&iacute; </th>
                                        <th> Activo </th>
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
            $('.td-seccion').click(function() {
                var id_seccion_cuestionario = $(this).closest('tr').attr('data-id');
                location.href = "seccion_cuestionario_editar.php?id=" + id_seccion_cuestionario;
            });

            $('.td-mostrar').click(function() {
                var id_seccion_cuestionario = $(this).closest('tr').attr('data-id');
                location.href = "seccion_cuestionario_mostrar.php?id=" + id_seccion_cuestionario;
            });

            $('.td-omitir').click(function() {
                var id_seccion_cuestionario = $(this).closest('tr').attr('data-id');
                location.href = "seccion_cuestionario_omitir.php?id=" + id_seccion_cuestionario;
            });


            $('.nueva-seccion').click(function() {
                location.href = "seccion_cuestionario_crear.php";
            });

            $(document).ready(function() {
                $('#tip-table').DataTable({
                    "columns": [
                        { "width": "10%" },
                        { "width": "25%" },
                        { "width": "25%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" }
                    ]
                    , "bSort": false
                });
                $('#nav-health').addClass('active');
                $('#nav-health-question').addClass('active');
            });

            $('#estado-buscar').click( function() {
                irConFiltros();
            });

            $('#afiliado').change( function(){
                irConFiltros();
            });

            function irConFiltros() {
                var estadoABuscar = 0;
                if ($('#estado-buscar').is(':checked')) {
                    estadoABuscar = 1;
                }
                var afiliadoID = $('#afiliado').val();
                location.href = "seccion_cuestionario.php?a=" + estadoABuscar;
            }

        </script>
    </body>
</html>