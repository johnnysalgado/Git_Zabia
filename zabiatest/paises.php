<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $query = "SELECT cod_pais, nombre, estado FROM pais ORDER BY nombre DESC";
    
    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while($sql->next()) {
        $codigoPais = $sql->field('cod_pais');
        $nombre = $sql->field('nombre');
        $estado = $sql->field('estado');
        if ($estado == "1") {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
   
        $html .= '<tr data-id="' . $codigoPais . '" class="' . $estado . '">';
        $html .= '<td class="td-pais">' . $codigoPais . '</td>';
        $html .= '<td class="td-pais">' . $nombre . '</td>';
        $html .= '<td class="td-region text-center"> <i class="glyphicon glyphicon-globe"></i></td>';
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
                            <h4 class="page-title">Pa&iacute;ses</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="pais-table" class="table table-striped display">
                                    <thead>
                                        <tr>
                                            <th> C&oacute;digo </th>
                                            <th> Pa&iacute; </th>
                                            <th> Regi&oacute;n</th>
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
            $('.td-pais').click(function() {
                var id_pais = $(this).closest('tr').attr('data-id');
                location.href = "pais_editar.php?id=" + id_pais;
            });

            $('.td-region').click(function() {
                var id_pais = $(this).closest('tr').attr('data-id');
                location.href = "regiones.php?id=" + id_pais;
            });

            $(document).ready(function() {
                $('#pais-table').DataTable();
                $('#nav-tabla').addClass('active');
            } );
        </script>
    </body>
</html>