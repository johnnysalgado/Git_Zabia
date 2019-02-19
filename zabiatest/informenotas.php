<?php
    require('inc/sesion.php');
    require('inc/constante_tip.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    if (isset($_GET["idSeccion"])) {
        $idSeccion = $_GET["idSeccion"];
        $idSeccion = str_replace("'", "", $idSeccion);
    } else {
        header("Location: informesecciones.php");
        die();
    }

    $cnx = new MySQL();

    $query = "SELECT titulo FROM notainforme WHERE id_notainforme = " . $idSeccion;
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    if ($sql->next()) {
        $seccion = $sql->field('titulo');
    }
    
    $query = "SELECT id_notainforme, titulo, parrafo, orden, fecha_registro, estado FROM notainforme WHERE id_notainforme_padre = " . $idSeccion . " ORDER BY orden ASC";
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while($sql->next()) {
        $idNotaInforme = $sql->field('id_notainforme');
        $titulo = $sql->field('titulo');
        $parrafo = $sql->field('parrafo');
        $orden = $sql->field('orden');
        $fechaRegistro = $sql->field('fecha_registro');
        $estado = $sql->field('estado');
        if ($estado == "1") {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
        $html .= '<tr data-id="' . $idNotaInforme . '" class="' . $estado . '">';
        $html .= '<td class="td-editar">' . $idNotaInforme . '</td>';
        $html .= '<td class="td-editar">' . $titulo . '</td>';
        $html .= '<td class="td-editar">' . $parrafo . '</td>';
        $html .= '<td class="td-editar">' . $orden . '</td>';
        $html .= '<td class="td-editar">' . $estado . '</td>';
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="page-title">Notas de Informe [<?php echo $seccion ?>]</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="volver" value="Volver a Secciones" class="btn btn-default volver" />
                            &nbsp;&nbsp;
                            <input type="button" id="nueva-nota" value="Nueva nota de informe" class="btn btn-primary nueva-nota" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <table id="nota-table" class="table table-striped display" style="cursor:pointer;">
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> T&iacute;tulo </th>
                                        <th> P&aacute;rrafo </th>
                                        <th> N&deg; orden </th>
                                        <th> Estado </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="id_seccion" name="id_seccion" value="<?php echo $idSeccion;?>" />
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $('.td-editar').click(function() {
                var id_nota = $(this).closest('tr').attr('data-id');
                location.href = "informenota_editar.php?id=" + id_nota;
            });

            $('.nueva-nota').click(function() {
                location.href = "informenota_crear.php?idSeccion=" + $('#id_seccion').val();
            });

            $('.volver').click(function() {
                location.href = "informesecciones.php";
            });

            $(document).ready(function() {
                $('#nota-table').DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "35%" },
                        { "width": "40%" },
                        { "width": "10%" },
                        { "width": "10%" }
                    ]
                });
                $('#nav-informe').addClass('active');
            });
        </script>
    </body>
</html>