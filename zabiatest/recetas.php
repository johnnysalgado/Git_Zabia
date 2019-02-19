<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante_receta.php');
    require('inc/constante.php');
    
    $query = "SELECT a.id_plato, a.nombre, a.imagen, a.porcion, a.tiempo, a.precio, a.megusta, a.top, a.estado FROM plato a ORDER BY a.nombre";

    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while($sql->next()) {
        $idPlato = $sql->field('id_plato');
        $nombre = $sql->field('nombre');
        $imagen = $sql->field('imagen');
        $porcion = $sql->field('porcion');
        $precio = $sql->field('precio');
        $megusta = $sql->field('megusta');
        $top = $sql->field('top');
        $estado = $sql->field('estado');
        if ($estado == 1) {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
        if ($top == "1") {
            $top = "Si";
        } else {
            $top = "No";
        }
   
        $html .= '<tr data-id="' . $idPlato . '" class="' . $estado . '">';
        $html .= '<td class="td-receta text-center">' . $idPlato . '</td>';
        $html .= '<td class="td-receta">' . $nombre . '</td>';
        $html .= '<td class="td-receta text-center">' . $porcion . '</td>';
        if ($imagen != '') {
            $html .= '<td class="td-receta"> <img src="' . BASE_REMOTE_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . PREFIX_PLATE_TYPE_IMAGE_SMALL . "/" . PREFIX_DIET_TYPE_IMAGE_SMALL ."_" . $imagen . '" alt="" class="img-responsive thumbnail m-r-15" /> </td>';
        } else {
            $html .= '<td class="td-receta"> </td>';
        }
        $html .= '<td class="td-receta text-right">' . round($precio, 2) . '</td>';
        $html .= '<td class="td-receta text-center">' . $megusta . '</td>';
        $html .= '<td class="td-receta text-center">' . $top . '</td>';
        $html .= '<td class="td-receta text-center">' . $estado . '</td>';
        $html .= '<td class="td-nutricion text-center"> <i class="glyphicon glyphicon-leaf"></i></td>';
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
                            <h4 class="page-title">Recetas</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nueva-receta" value="Nueva receta" class="btn btn-primary nueva-receta" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="receta-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> Nombre </th>
                                        <th> Porci&oacute;n </th>
                                        <th> Imagen </th>
                                        <th> Precio </th>
                                        <th> Fav. </th>
                                        <th> Top </th>
                                        <th> Est. </th>
                                        <th> Nutrici&oacute;n </th>
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
            $('.td-receta').click(function() {
                var id_receta = $(this).closest('tr').attr('data-id');
                location.href = "receta_editar.php?id=" + id_receta;
            });

            $('.td-nutricion').click(function() {
                var id_receta = $(this).closest('tr').attr('data-id');
                location.href = "plato.php?id=" + id_receta;
            });

            $('.nueva-receta').click(function() {
                location.href = "receta_crear.php";
            });

            $(document).ready(function() {
                $('#receta-table').DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "25%" },
                        { "width": "10%" },
                        { "width": "15%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "5%" }
                    ]
                });
                $('#nav-recipe').addClass('active');
            } );
        </script>
    </body>
</html>