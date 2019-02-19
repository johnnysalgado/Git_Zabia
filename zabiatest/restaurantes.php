<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_comercio.php');
    require('inc/mysql.php');

    $query = "SELECT a.id_comercio, a.tipo_comercio, a.nombre, a.ciudad, a.imagen, a.carta, a.estado FROM comercio a WHERE tipo_comercio = '" . TIPO_COMERCIO_RESTAURANTE . "' ORDER BY nombre LIMIT 4000";

    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while($sql->next()) {
        $idComercio = $sql->field('id_comercio');
        $tipoComercio = $sql->field('tipo_comercio');
        $nombre = $sql->field('nombre');
        $ciudad = $sql->field('ciudad');
        $imagen = $sql->field('imagen');
        $carta = $sql->field('carta');
        $estado = $sql->field('estado');
        if ($estado == 1) {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
        $html .= '<tr data-id="' . $idComercio . '" class="' . $estado . '" data-carta="' . $carta . '">';
        $html .= '<td class="text-center td-comercio">' . $idComercio . '</td>';
        $html .= '<td class="td-comercio">' . $nombre . '</td>';
        $html .= '<td class="td-comercio">' . $ciudad . '</td>';
        if ($imagen != '') {
            $html .= '<td class="td-comercio"> <img src="' . COMERCIO_IMAGE_SHORT_PATH . $imagen . '" alt="" class="img-responsive thumbnail m-r-15" /> </td>';
        } else {
            $html .= '<td class="td-receta"> &nbsp; </td>';
        }
        $html .= '<td class="text-center td-comercio">' . $estado . '</td>';
        $html .= '<td class="text-center td-carta">';
        if ($carta != "") {
            $html .= '<i class="glyphicon glyphicon-cutlery" title="Cartas"></i>';
        }
        $html .= ' </td>';
        $html .= '</tr>';
    }

    $cnx = null;
?>
<!DOCTYPE html>
<html lang="es">
    <?php  require('inc/head.php'); ?>
    <body>
        <!-- Preloader -->
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
                            <h4 class="page-title">Restaurantes</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-comercio" value="Nuevo restaurante" class="btn btn-primary nuevo-comercio" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="comercio-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Nombre </th>
                                            <th> Ciudad </th>
                                            <th> Img. </th>
                                            <th> Est. </th>
                                            <th> Carta </th>
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
        <!-- /#wrapper -->
        <script type="text/javascript">
            $('.td-comercio').click(function() {
                var id_comercio = $(this).closest('tr').attr('data-id');
                location.href = "restaurante_editar.php?id=" + id_comercio;
            });

            $('.td-carta').click(function() {
                //var id_comercio = $(this).closest('tr').attr('data-id');
                var urlCarta = $(this).closest('tr').attr('data-carta');
                window.open("<?php echo CARTA_PDF_PATH; ?>" + urlCarta);
            });

            $('.nuevo-comercio').click(function() {
                location.href = "restaurante_crear.php";
            });

            $(document).ready(function() {
                $('#comercio-table').DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "35%" },
                        { "width": "15%" },
                        { "width": "20%" },
                        { "width": "15%" },
                        { "width": "10%" }
                    ]
                });
                $('#nav-place').addClass('active');
            } );
        </script>
    </body>
</html>