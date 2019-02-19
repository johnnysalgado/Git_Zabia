<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $query = "SELECT id_tag, nombre, tipo_tag, estado FROM tag ORDER BY nombre DESC";
    
    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while($sql->next()) {
        $idTag = $sql->field('id_tag');
        $nombre = $sql->field('nombre');
        $tipoTag = $sql->field('tipo_tag');
        $estado = $sql->field('estado');
        if ($estado == "1") {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
   
        $html .= '<tr data-id="' . $idTag . '" class="' . $estado . '">';
        $html .= '<td>' . $idTag . '</td>';
        $html .= '<td>' . $nombre . '</td>';
        $html .= '<td>' . $tipoTag . '</td>';
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
                            <h4 class="page-title">Tags</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-tag" value="Nuevo tag" class="btn btn-primary nuevo-tag" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="tag-table" class="table table-striped display">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Tag </th>
                                            <th> Tipo tag </th>
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
                var id_tag = $(this).attr('data-id');
                location.href = "tag_editar.php?id=" + id_tag;
            });

            $('.nuevo-tag').click(function() {
                location.href = "tag_crear.php";
            });

            $(document).ready(function() {
                $('#tag-table').DataTable();
                $('#nav-tag').addClass('active');
            } );
        </script>
    </body>
</html>