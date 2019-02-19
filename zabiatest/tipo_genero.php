<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/dao_usuario.php');
    require('inc/constante.php');

    $html = "";
    $daoUsuario = new DaoUsuario();
    $arreglo = $daoUsuario->listarTipoGenero(-1, LENGUAJE_ESPANOL);
    foreach($arreglo as $item) {
        $idTipoGenero = $item['id_tipo_genero'];
        $nombre = $item['nombre'];
        $nombreIngles = $item['nombre_ing'];
        $clase = ($item["estado"] == LISTA_ACTIVO ) ? CLASE_ACTIVO : CLASE_INACTIVO;
        $html .= "<tr data-id=\"$idTipoGenero\" class=\"$clase\">";
        $html .= "<td > $idTipoGenero </td>";
        $html .= "<td > $nombre </td>";
        $html .= "<td > $nombreIngles </td>";
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
                            <h4 class="page-title">G&eacute;nero</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo" value="Nuevo género" class="btn btn-primary nuevo" />
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
                                            <th> G&eacute;nero </th>
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
                var idTipoGenero = $(this).attr('data-id');
                location.href = "tipo_genero_editar.php?id=" + idTipoGenero;
            });

            $('.nuevo').click(function() {
                location.href = "tipo_genero_crear.php";
            });

            $(document).ready(function() {
                $('#tabla').DataTable();
                $('#nav-table').addClass('active');
            } );
        </script>
    </body>
</html>