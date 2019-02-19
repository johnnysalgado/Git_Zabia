<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/dao_insumo.php');
    require('inc/constante.php');

    $html = "";
    $daoInsumo = new DaoInsumo();
    $arreglo = $daoInsumo->listarTipoAlimento(LISTA_ACTIVO);
    foreach($arreglo as $item) {
        $html .= "<tr data-id=\"" . $item['id_tipo_alimento'] . "\">";
        $html .= "<td >" . $item['id_tipo_alimento'] . "</td>";
        $html .= "<td >" . $item['nombre'] . "</td>";
        $html .= "<td >" . $item['nombre_ing'] . "</td>";
        $html .= "</tr>";
    }
    $daoInsumo = null;

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
                            <h4 class="page-title">Tipo de alimento</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo" value="Nuevo tipo alimento" class="btn btn-primary nuevo" />
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
                                            <th> Tipo alimento </th>
                                            <th> [En inglés] </th>
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
            $('.table > tbody > tr').click(function() {
                var idTipoAlimento = $(this).attr('data-id');
                location.href = "tipo_alimento_editar.php?id=" + idTipoAlimento;
            });

            $('.nuevo').click(function() {
                location.href = "tipo_alimento_crear.php";
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