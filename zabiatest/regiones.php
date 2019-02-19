<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $codigoPais = "";
    if (isset($_GET["id"])) {
        $codigoPais = $_GET["id"];
    }
    if ($codigoPais != "") {
        $codigoPais = str_replace("'" , "", $codigoPais);
    } else {
        header("Location: paises.php");
        die();
    }
    //país
    $nombrePais = "";
    $query = "SELECT nombre FROM pais WHERE cod_pais = '" . $codigoPais . "'";
    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    if ($sql->next()) {
        $nombrePais = $sql->field("nombre");
    }

    $query = "SELECT id_region, cod_pais, nombre, estado FROM region WHERE cod_pais = '" . $codigoPais . "' ORDER BY nombre DESC";
    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while($sql->next()) {
        $idRegion = $sql->field("id_region");
        $codigoPais = $sql->field('cod_pais');
        $nombre = $sql->field('nombre');
        $estado = $sql->field('estado');
        if ($estado == "1") {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
        $html .= '<tr data-id="' . $idRegion . '" class="' . $estado . '">';
        $html .= '<td>' . $idRegion . '</td>';
        $html .= '<td>' . $nombre . '</td>';
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
                            <h4 class="page-title">Regiones para <?php echo $nombrePais; ?></h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-region" value="Nueva regi&oacute;n" class="btn btn-primary nuevo-region" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="region-table" class="table table-striped display">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
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
                    <br />
                    <input type="hidden" name="cod_pais" value="<?php echo $codigoPais ?>" />
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <input type="button" id="volver" value="Volver" class="btn btn-default" />
                        </div>
                    </div>
                    </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $('.table > tbody > tr').click(function() {
                var id_region = $(this).attr('data-id');
                location.href = "region_editar.php?cp=<?php echo $codigoPais ?>&id=" + id_region;
            });

            $('.nuevo-region').click(function() {
                location.href = "region_crear.php?cp=<?php echo $codigoPais ?>";
            });

            $('#volver').click(function() {
                location.href = 'paises.php';
            });

            $(document).ready(function() {
                $('#region-table').DataTable();
                $('#nav-tabla').addClass('active');
            } );
        </script>
    </body>
</html>