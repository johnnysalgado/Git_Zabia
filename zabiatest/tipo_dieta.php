<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $query = "SELECT id_tipo_dieta, nombre, nombre_ing, estado FROM tipo_dieta ORDER BY nombre DESC";
    
    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while($sql->next()) {
        $idTipoDieta = $sql->field('id_tipo_dieta');
        $nombre = $sql->field('nombre');
        $nombreIngles = $sql->field('nombre_ing');
        $estado = $sql->field('estado');
        if ($estado == "1") {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
   
        $html .= '<tr data-id="' . $idTipoDieta . '" class="' . $estado . '">';
        $html .= '<td>' . $idTipoDieta . '</td>';
        $html .= '<td>' . $nombre . '</td>';
        $html .= '<td>' . $nombreIngles . '</td>';
        $html .= '<td>' . $estado . '</td>';
        $html .= '</tr>';
    }

    $cnx = null;
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
                        <div class="col-lg-6 col-md-6 col-sm6 col-xs-12">
                            <h4 class="page-title">Tipos de dieta</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-tipo-dieta" value="Nuevo tipo de dieta" class="btn btn-primary nuevo-tipo-dieta" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="tipo-dieta-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> tipo dieta </th>
                                            <th> tipo dieta (Ingl&eacute;s)</th>
                                            <th> Estado </th>
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
                var id_tipo_dieta = $(this).attr('data-id');
                location.href = "tipo_dieta_editar.php?id=" + id_tipo_dieta;
            });

            $('.nuevo-tipo-dieta').click(function() {
                location.href = "tipo_dieta_crear.php";
            });

            $(document).ready(function() {
                $('#tipo-dieta-table').DataTable();
                $('#nav-tabla').addClass('active');
            } );
        </script>
    </body>
</html>