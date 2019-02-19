<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/dao_nutriente.php');

    if (isset($_GET["tn"]) && ($_GET["tn"] > 0)) {

        $idTipoNutriente = $_GET["tn"];
        $html = "";
        $daoNutriente = new DaoNutriente();

        $arreglo = $daoNutriente->listarClaseNutriente($idTipoNutriente);
        foreach($arreglo as $item) {
            $html .= "<tr data-id=\"" . $item['id_tipo_clase'] . "\">";
            $html .= "<td class=\"td-tipo-clase\">" . $item['id_tipo_clase'] . "</td>";
            $html .= "<td class=\"td-tipo-clase\">" . $item['nombre'] . "</td>";
            $html .= "<td class=\"td-tipo-clase\">" . $item['nombre_ing'] . "</td>";
            $html .= "<td class=\"td-tipo-categoria\"> <i class=\"glyphicon glyphicon-cog\"></i> </td>";
            $html .= "</tr>";
        }

        $tipoNutriente = "";
        $arregloTN = $daoNutriente->obtenerTipoNutriente($idTipoNutriente);
        if (count($arregloTN) > 0) {
            $tipoNutriente = $arregloTN[0]["nombre"];
        }

        $daoNutriente = null;

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
                            <h4 class="page-title">Tipo de clase para <?php echo $tipoNutriente; ?></h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo" value="Nuevo tipo clase" class="btn btn-primary nuevo" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="tabla" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Tipo clase </th>
                                            <th> [En inglés] </th>
                                            <th> Tipo categor&iacute;a </th>
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
                                <input type="button" id="volver" value="Retornar a tipo nutriente" class="btn btn-default volver" />
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            var idTipoNutriente = <?php echo $idTipoNutriente; ?>;

            $('.td-tipo-clase').click(function() {
                var idTipoClase = $(this).closest('tr').attr('data-id');
                location.href = "tipo_clase_editar.php?tn=" + idTipoNutriente + "&id=" + idTipoClase;
            });

            $('.nuevo').click(function() {
                location.href = "tipo_clase_crear.php?tn=" + idTipoNutriente;
            });

            $('.td-tipo-categoria').click(function() {
                var idTipoClase = $(this).closest('tr').attr('data-id');
                location.href = "tipo_categoria.php?tn=" + idTipoNutriente + "&tcla=" + idTipoClase;
            });

            $(document).ready(function() {
                $('#tabla').DataTable({
                    "pageLength": 25
                });
                $('#nav-table').addClass('active');
            } );

            $('.volver').click(function() {
                location.href = "tipo_nutriente.php";
            });
        </script>
    </body>
</html>