<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/dao_nutriente.php');

    $html = "";
    $daoNutriente = new DaoNutriente();
    $arreglo = $daoNutriente->listarTipoNutriente();
    foreach($arreglo as $item) {
        $html .= "<tr data-id=\"" . $item['id_tipo_nutriente'] . "\">";
        $html .= "<td class=\"td-tipo-nutriente\">" . $item['id_tipo_nutriente'] . "</td>";
        $html .= "<td class=\"td-tipo-nutriente\">" . $item['nombre'] . "</td>";
        $html .= "<td class=\"td-tipo-nutriente\">" . $item['nombre_ing'] . "</td>";
        $html .= "<td class=\"td-tipo-clase\"> <i class=\"glyphicon glyphicon-cog\"></i> </td>";
        $html .= "</tr>";
    }
    $daoNutriente = null;

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
                            <h4 class="page-title">Tipo de nutriente</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo" value="Nuevo tipo nutriente" class="btn btn-primary nuevo" />
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
                                            <th> Tipo nutriente </th>
                                            <th> [En inglés] </th>
                                            <th> Tipo clase </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $html; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $('.td-tipo-nutriente').click(function() {
                var idTipoNutriente = $(this).closest('tr').attr('data-id');
                location.href = "tipo_nutriente_editar.php?id=" + idTipoNutriente;
            });

            $('.nuevo').click(function() {
                location.href = "tipo_nutriente_crear.php";
            });

            $('.td-tipo-clase').click(function() {
                var idTipoNutriente = $(this).closest('tr').attr('data-id');
                location.href = "tipo_clase.php?tn=" + idTipoNutriente;
            });

            $(document).ready(function() {
                $('#tabla').DataTable({
                    "pageLength": 25
                });
                $('#nav-table').addClass('active');
            } );
        </script>
    </body>
</html>