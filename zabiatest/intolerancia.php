<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/dao_intolerancia.php');

    $html = "";
    $daoIntolerancia = new DaoIntolerancia();
    $arreglo = $daoIntolerancia->listarIntolerancia();
    foreach($arreglo as $item) {
        $idIntolerancia = $item['id_intolerancia'];
        $nombre = $item['nombre'];
        $nombreIngles = $item['nombre_ing'];
        $html .= "<tr data-id=\"$idIntolerancia\">";
        $html .= "<td class=\"td-intolerancia text-center\">$idIntolerancia</td>";
        $html .= "<td class=\"td-intolerancia\">$nombre</td>";
        $html .= "<td class=\"td-intolerancia\">$nombreIngles</td>";
        $html .= "<td class=\"td-insumo text-center\"> <i class=\"glyphicon glyphicon-certificate \"> </i> </td>";
        $html .= "<td class=\"td-tipo text-center\"><i class=\"fa fa-bullseye\"></i></td>";
		$html .= "</tr>";
    }
    $daoIntolerancia = null;

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
                            <h4 class="page-title">Intolerancias</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-intolerancia" value="Nueva intolerancia" class="btn btn-primary nuevo-intolerancia" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="intolerancia-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Intolerancia </th>
                                            <th> Intolerancia (Ingl&eacute;s) </th>
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
            $('.td-intolerancia').click(function() {
                var id_intolerancia = $(this).closest('tr').attr('data-id');
                location.href = "intolerancia_editar.php?id=" + id_intolerancia;
            });

            $('.nuevo-intolerancia').click(function() {
                location.href = "intolerancia_crear.php";
            });

            $('.td-insumo').click(function() {
                var id_intolerancia = $(this).closest('tr').attr('data-id');
                location.href = "intolerancia_insumo.php?id=" + id_intolerancia;
            });
			$('.td-tipo').click(function() {
                var id_intolerancia = $(this).closest('tr').attr('data-id');
                location.href = "intolerancia_tipo_alimento.php?id=" + id_intolerancia;
            });

            $(document).ready(function() {
                $('#intolerancia-table').DataTable({
                    "pageLength": 25
                });
                $('#nav-health').addClass('active');
            } );
        </script>
    </body>
</html>