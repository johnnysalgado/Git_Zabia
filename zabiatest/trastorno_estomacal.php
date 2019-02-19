<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/dao_trastorno_estomacal.php');

    $html = "";
    $daoTrastornoEstomacal = new DaoTrastornoEstomacal();
    $arreglo = $daoTrastornoEstomacal->listarTrastornoEstomacal();
    foreach($arreglo as $item) {
        $idTrastornoEstomacal = $item['id_trastorno_estomacal'];
        $nombre = $item['nombre'];
        $nombreIngles = $item['nombre_ing'];
        $html .= "<tr data-id=\"$idTrastornoEstomacal\">";
        $html .= "<td class=\"td-trastorno-estomacal text-center\">$idTrastornoEstomacal</td>";
        $html .= "<td class=\"td-trastorno-estomacal\">$nombre</td>";
        $html .= "<td class=\"td-trastorno-estomacal\">$nombreIngles</td>";
        $html .= "<td class=\"td-insumo text-center\"> <i class=\"glyphicon glyphicon-certificate \"> </i> </td>";
        $html .= "<td class=\"td-tipo text-center\"><i class=\"fa fa-bullseye\"></i></td>";
		$html .= "</tr>";
    }
    $daoTrastornoEstomacal = null;

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
                            <h4 class="page-title">Trastorno estomacal</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-trastorno-estomacal" value="Nuevo trastorno estomacal" class="btn btn-primary nuevo-trastorno-estomacal" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="trastorno-estomacal-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Trastorno estomacal </th>
                                            <th> Trastorno estomacal (Ingl&eacute;s) </th>
                                            <th> Insumos </th>
											<th> Tipo Alimento </th>
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
            $('.td-trastorno-estomacal').click(function() {
                var id_trastorno_estomacal = $(this).closest('tr').attr('data-id');
                location.href = "trastorno_estomacal_editar.php?id=" + id_trastorno_estomacal;
            });

            $('.nuevo-trastorno-estomacal').click(function() {
                location.href = "trastorno_estomacal_crear.php";
            });

            $('.td-insumo').click(function() {
                var id_trastorno_estomacal = $(this).closest('tr').attr('data-id');
                location.href = "trastorno_estomacal_insumo.php?id=" + id_trastorno_estomacal;
            });
			$('.td-tipo').click(function() {
                var id_trastorno_estomacal = $(this).closest('tr').attr('data-id');
                location.href = "trastorno_estomacal_tipo_alimento.php?id=" + id_trastorno_estomacal;
            });

            $(document).ready(function() {
                $('#trastorno-estomacal-table').DataTable({
                    "pageLength": 25
                });
                $('#nav-health').addClass('active');
            } );
        </script>
    </body>
</html>