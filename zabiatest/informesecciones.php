<?php
    require('inc/sesion.php');
    require('inc/constante_informe.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $query = "SELECT id_notainforme, titulo, orden, fecha_registro, estado FROM notainforme WHERE tipo_notainforme = '" . TIPO_NOTA_SECCION . "' ORDER BY orden ASC";
    
    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while($sql->next()) {
        $idNotaInforme = $sql->field('id_notainforme');
        $titulo = $sql->field('titulo');
        $orden = $sql->field('orden');
        $estado = $sql->field('estado');
        if ($estado == "1") {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
   
        $html .= '<tr data-id="' . $idNotaInforme . '" class="' . $estado . '">';
        $html .= '<td class="td-editar">' . $idNotaInforme . '</td>';
        $html .= '<td class="td-editar">' . $titulo . '</td>';
        $html .= '<td class="td-editar">' . $orden . '</td>';
        $html .= '<td class="td-editar">' . $estado . '</td>';
        $html .= '<td class="td-padre"> <i class="glyphicon glyphicon-list-alt"></i> </td>';
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
                            <h4 class="page-title">Secciones de Informe</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nueva-seccion" value="Nueva secci&oacute;n" class="btn btn-primary nueva-seccion" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <table id="seccion-table" class="table table-striped display" style="cursor:pointer;">
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> T&iacute;tulo </th>
                                        <th> N&deg; orden </th>
                                        <th> Estado </th>
                                        <th> Notas </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $('.td-editar').click(function() {
                var id_seccion = $(this).closest('tr').attr('data-id');
                location.href = "informeseccion_editar.php?id=" + id_seccion;
            });

            $('.td-padre').click(function() {
                var id_seccion = $(this).closest('tr').attr('data-id');
                location.href = "informenotas.php?idSeccion=" + id_seccion;
            });

            $('.nueva-seccion').click(function() {
                location.href = "informeseccion_crear.php";
            });

            $(document).ready(function() {
                $('#seccion-table').DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "65%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" }
                    ]
                });
                $('#nav-informe').addClass('active');
            });
        </script>
    </body>
</html>