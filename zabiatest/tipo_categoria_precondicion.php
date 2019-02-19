<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/dao_enfermedad.php');
    require('inc/constante.php');

    $html = "";
    $daoEnfermedad = new DaoEnfermedad();
    $arreglo = $daoEnfermedad->listarTipoCategoriaPrecondicion(-1, LENGUAJE_ESPANOL);
    foreach($arreglo as $item) {
        $estado = $item["estado"];
        if ($estado == LISTA_ACTIVO) {
            $clase = CLASE_ACTIVO;
        } else {
            $clase = CLASE_INACTIVO;
        }
        $html .= "<tr data-id=\"" . $item['id_tipo_categoria_precondicion'] . "\">";
        $html .= "<td >" . $item['id_tipo_categoria_precondicion'] . "</td>";
        $html .= "<td >" . $item['nombre'] . "</td>";
        $html .= "<td >" . $item['nombre_ing'] . "</td>";
        $html .= "</tr>";
    }
    $daoEnfermedad = null;

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
                            <h4 class="page-title">Categor&iacute;a de precondici&oacute;n</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo" value="Nueva categoría" class="btn btn-primary nuevo" />
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
                                            <th> Categor&iacute;a </th>
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
                var idTipoCategoriaPrecondicion = $(this).attr('data-id');
                location.href = "tipo_categoria_precondicion_editar.php?id=" + idTipoCategoriaPrecondicion;
            });

            $('.nuevo').click(function() {
                location.href = "tipo_categoria_precondicion_crear.php";
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