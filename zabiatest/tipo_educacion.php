
<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/constante.php');
    require('inc/dao_usuario.php');

    $html = "";
    $daoUsuario = new DaoUsuario();
    $estado = "NULL";
    $arreglo = $daoUsuario->listarTipoEducacion($estado, "");
    foreach($arreglo as $item) {
        $activo = $item['estado'];
        if ($activo == 1) {
            $clase = CLASE_ACTIVO;
        } else {
            $clase = CLASE_INACTIVO;
        }
        $html .= "<tr data-id=\"" . $item['id_tipo_educacion'] . "\" class=\"$clase\">";
        $html .= "<td >" . $item['id_tipo_educacion'] . "</td>";
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
                            <h4 class="page-title">Nivel de educaci&oacute;n</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo" value="Nuevo tipo" class="btn btn-primary nuevo" />
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
                                            <th> Nivel educaci&oacute;n </th>
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
                var idTipoEducacion = $(this).attr('data-id');
                location.href = "tipo_educacion_editar.php?id=" + idTipoEducacion;
            });

            $('.nuevo').click(function() {
                location.href = "tipo_educacion_crear.php";
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