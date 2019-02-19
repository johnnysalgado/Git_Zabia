<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/dao_nutriente.php');

    if (isset($_GET["tcat"]) && ($_GET["tcat"] > 0)) {

        $idTipoNutriente = $_GET["tn"];
        $idTipoClase = $_GET["tcla"];
        $idTipoCategoria = $_GET["tcat"];
        $html = "";
        $daoNutriente = new DaoNutriente();

        $arreglo = $daoNutriente->listarFamiliaNutriente($idTipoCategoria);
        foreach($arreglo as $item) {
            $html .= "<tr data-id=\"" . $item['id_tipo_familia'] . "\">";
            $html .= "<td class=\"td-tipo-familia\">" . $item['id_tipo_familia'] . "</td>";
            $html .= "<td class=\"td-tipo-familia\">" . $item['nombre'] . "</td>";
            $html .= "<td class=\"td-tipo-familia\">" . $item['nombre_ing'] . "</td>";
            $html .= "<td class=\"td-tipo-subfamilia\"> <i class=\"glyphicon glyphicon-cog\"></i> </td>";
            $html .= "</tr>";
        }

        $tipoCategoria = "";
        $arregloT = $daoNutriente->obtenerCategoriaNutriente($idTipoCategoria);
        if (count($arregloT) > 0) {
            $tipoCategoria = $arregloT[0]["nombre"];
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
                            <h4 class="page-title">Tipo de familia para <?php echo $tipoCategoria; ?></h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo" value="Nuevo tipo familia" class="btn btn-primary nuevo" />
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
                                            <th> Tipo familia </th>
                                            <th> [En inglés] </th>
                                            <th> Tipo sub familia </th>
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
                                <input type="button" id="volver" value="Retornar a tipo categor&iacute;a" class="btn btn-default volver" />
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            var idTipoNutriente = <?php echo $idTipoNutriente; ?>;
            var idTipoClase = <?php echo $idTipoClase; ?>;
            var idTipoCategoria = <?php echo $idTipoCategoria; ?>;

            $('.td-tipo-familia').click(function() {
                var idTipoFamilia = $(this).closest('tr').attr('data-id');
                location.href = "tipo_familia_editar.php?tn=" + idTipoNutriente + "&tcla=" + idTipoClase + "&tcat=" + idTipoCategoria + "&id=" + idTipoFamilia;
            });

            $('.nuevo').click(function() {
                location.href = "tipo_familia_crear.php?tn=" + idTipoNutriente + "&tcla=" + idTipoClase + "&tcat=" + idTipoCategoria;
            });

            $('.td-tipo-subfamilia').click(function() {
                var idTipoFamilia = $(this).closest('tr').attr('data-id');
                location.href = "tipo_subfamilia.php?tn=" + idTipoNutriente + "&tcla=" + idTipoClase + "&tcat=" + idTipoCategoria + "&tfam=" + idTipoFamilia;
            });

            $(document).ready(function() {
                $('#tabla').DataTable({
                    "pageLength": 25
                });
                $('#nav-table').addClass('active');
            } );

            $('.volver').click(function() {
                location.href = "tipo_categoria.php?tn=" + idTipoNutriente + "&tcla=" + idTipoClase;
            });
        </script>
    </body>
</html>