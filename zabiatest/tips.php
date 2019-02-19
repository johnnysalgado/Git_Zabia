<?php
    require('inc/sesion.php');
    require('inc/constante_tip.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $query = "SELECT id_nota, titulo, detalle, tag, imagen, url_video, fecha_registro, estado FROM nota ORDER BY id_nota DESC";
    
    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while($sql->next()) {
        $idNota = $sql->field('id_nota');
        $titulo = $sql->field('titulo');
        $detalle = $sql->field('detalle');
        $imagen = $sql->field('imagen');
        $tag = $sql->field('tag');
        $urlVideo = $sql->field('url_video');
        $fechaRegistro = $sql->field('fecha_registro');
        $estado = $sql->field('estado');
        if ($estado == "1") {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
   
        $html .= '<tr data-id="' . $idNota . '" class="' . $estado . '">';
        $html .= '<td>' . $idNota . '</td>';
        $html .= '<td>' . $titulo . '</td>';
        $html .= '<td>' . $detalle . '</td>';
        $html .= '<td>' . $tag . '</td>';
        if ($imagen != '') {
            $html .= '<td> <img src="' . TIP_IMAGE_SHORT_PATH . $imagen . '" alt="" class="img-responsive thumbnail m-r-15" /> </td>';
        } else {
            $html .= '<td> </td>';
        }
        if ($urlVideo != '') {
            $html .= '<td> <div class="embed-responsive embed-responsive-16by9"> <iframe class="embed-responsive-item" src="' . $urlVideo . '" gesture="media" allow="encrypted-media" allowfullscreen></iframe> </div> </td>';
        } else {
            $html .= '<td> </td>';
        }
        $html .= '<td>' . $fechaRegistro . '</td>';
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
                            <h4 class="page-title">Tips</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nueva-nota" value="Nuevo tip" class="btn btn-primary nueva-nota" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <table id="tip-table" class="table table-striped display">
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> T&iacute;tulo </th>
                                        <th> Detalle </th>
                                        <th> Tags </th>
                                        <th> Imagen </th>
                                        <th> video </th>
                                        <th> Fec. </th>
                                        <th> Est. </th>
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
            $('.table > tbody > tr').click(function() {
                var id_nota = $(this).attr('data-id');
                location.href = "tip_editar.php?id=" + id_nota;
            });

            $('.nueva-nota').click(function() {
                location.href = "tip_crear.php";
            });

            $(document).ready(function() {
                $('#tip-table').DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "15%" },
                        { "width": "20%" },
                        { "width": "10%" },
                        { "width": "15%" },
                        { "width": "15%" },
                        { "width": "10%" },
                        { "width": "10%" }
                    ]
                });
                $('#nav-tip').addClass('active');
            });
        </script>
    </body>
</html>