<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/dao_nutriente.php');

    if (isset($_GET["tcla"]) && ($_GET["tcla"] > 0)) {

        $idTipoNutriente = $_GET["tn"];
        $idTipoClase = $_GET["tcla"];
        $html = "";
        $daoNutriente = new DaoNutriente();

        $arreglo = $daoNutriente->listarCategoriaNutriente($idTipoClase);
        foreach($arreglo as $item) {
            $html .= "<tr data-id=\"" . $item['id_tipo_categoria'] . "\">";
            $html .= "<td class=\"td-tipo-categoria\">" . $item['id_tipo_categoria'] . "</td>";
            $html .= "<td class=\"td-tipo-categoria\">" . $item['nombre'] . "</td>";
            $html .= "<td class=\"td-tipo-categoria\">" . $item['nombre_ing'] . "</td>";
            $html .= "<td class=\"td-tipo-familia\"> <i class=\"glyphicon glyphicon-cog\"></i> </td>";
            $html .= "</tr>";
        }

        $tipoClase = "";
        $arregloT = $daoNutriente->obtenerClaseNutriente($idTipoClase);
        if (count($arregloT) > 0) {
            $tipoClase = $arregloT[0]["nombre"];
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
                            <h4 class="page-title">Tipo de categor&iacute;a para <?php echo $tipoClase; ?></h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo" value="Nuevo tipo categor&iacute;a" class="btn btn-primary nuevo" />
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
                                            <th> Tipo categor&iacute;a </th>
                                            <th> [En inglés] </th>
                                            <th> Tipo familia </th>
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
                                <input type="button" id="volver" value="Retornar a tipo clase" class="btn btn-default volver" />
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

            $('.td-tipo-categoria').click(function() {
                var idTipoCategoria = $(this).closest('tr').attr('data-id');
                location.href = "tipo_categoria_editar.php?tn=" + idTipoNutriente + "&tcla=" + idTipoClase + "&id=" + idTipoCategoria;
            });

            $('.nuevo').click(function() {
                location.href = "tipo_categoria_crear.php?tn=" + idTipoNutriente + "&tcla=" + idTipoClase;
            });

            $('.td-tipo-familia').click(function() {
                var idTipoCategoria = $(this).closest('tr').attr('data-id');
                location.href = "tipo_familia.php?tn=" + idTipoNutriente + "&tcla=" + idTipoClase + "&tcat=" + idTipoCategoria;
            });

            $(document).ready(function() {
                $('#tabla').DataTable({
                    "pageLength": 25
                });
                $('#nav-table').addClass('active');
            } );

            $('.volver').click(function() {
                location.href = "tipo_clase.php?tn=" + idTipoNutriente;
            });
        </script>
    </body>
</html>