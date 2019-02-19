<?php
require('inc/sesion.php');
require('inc/configuracion.php');
require('inc/mysql.php');

$palabra = "";

if (isset($_POST["palabra"])) {

    $cnx = new MySQL();
    $resultado = 1;
    $htmlReceta = "";
    $htmlRestaurante = "";
    $htmlTip = "";
    $palabra = $_POST["palabra"];
    $palabra = str_replace("'", "''", $palabra);

    //receta
    $query = "SELECT a.nombre, a.nombre_ing, a.preparacion FROM plato a WHERE a.estado = 1 AND ( a.nombre LIKE '%" . $palabra . "%' OR a.nombre_ing LIKE '%" . $palabra . "%' OR a.preparacion LIKE '%" . $palabra . "%')";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $htmlReceta .= '<tr>';
        $htmlReceta .= '<td>' . $sql->field('nombre') . '</td>';
        $htmlReceta .= '<td>' . $sql->field('nombre_ing') . '</td>';
        $htmlReceta .= '<td>' . $sql->field('preparacion') . '</td>';
        $htmlReceta .= '</tr>';
    }

    //restaurante
    $query = "SELECT a.nombre, a.descripcion FROM comercio a WHERE a.estado = 1 AND ( a.nombre LIKE '%" . $palabra . "%' OR a.descripcion LIKE '%" . $palabra . "%')";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $htmlRestaurante .= '<tr>';
        $htmlRestaurante .= '<td>' . $sql->field('nombre') . '</td>';
        $htmlRestaurante .= '<td>' . $sql->field('descripcion') . '</td>';
        $htmlRestaurante .= '</tr>';
    }
    
    //tip
    $query = "SELECT a.titulo, a.detalle, a.tag FROM nota a WHERE a.estado = 1 AND ( a.titulo LIKE '%" . $palabra . "%' OR a.detalle LIKE '%" . $palabra . "%' OR a.tag LIKE '%" . $palabra . "%')";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $htmlTip .= '<tr>';
        $htmlTip .= '<td>' . $sql->field('titulo') . '</td>';
        $htmlTip .= '<td>' . $sql->field('detalle') . '</td>';
        $htmlTip .= '<td>' . $sql->field('tag') . '</td>';
        $htmlTip .= '</tr>';
    }

    $cnx = null;
}

?>
<!DOCTYPE html>
<html lang="es">
    <?php  require('inc/head.php'); ?>
    <style>
        .speech {border: 1px solid #DDD; width: 260px; padding: 0; margin: 0}
        .speech input {border: 0; width: 200px; display: inline-block; height: 30px;}
        .speech img {float: right; width: 40px }
    </style>
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
                            <h4 class="page-title">Prueba de voz</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="voz.php" method="post" id="labnol">
                                <div class="row">
                                    <div class="col-md-6 col.xs-6">
                                        <div class="speech">
                                            <input type="text" name="palabra" id="transcript" class="form-control" />
                                             <img onclick="startDictation()" src="//i.imgur.com/cHidSVu.gif" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="resultado">
                                <hr />
                                <h3><?php echo $palabra ?></h3>
                                <h4>Recetas </h4>
                                <div class="table-responsive">
                                    <table id="receta-table" class="table table-striped display">
                                        <thead>
                                            <tr>
                                                <th> Nombre </th>
                                                <th> Nombre (ing) </th>
                                                <th> Preparaci&oacute;n </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $htmlReceta; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <br />
                                <h4>Restaurantes </h4>
                                <div class="table-responsive">
                                    <table id="restaurante-table" class="table table-striped display">
                                        <thead>
                                            <tr>
                                                <th> Nombre </th>
                                                <th> Descripci&oacute;n </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $htmlRestaurante; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <br />
                                <h4>Tips </h4>
                                <div class="table-responsive">
                                    <table id="tip-table" class="table table-striped display">
                                        <thead>
                                            <tr>
                                                <th> ID </th>
                                                <th> Descripci&oacute;n </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $htmlTip; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
            <?php if ($palabra == "") { ?>
                $('#resultado').hide();
            <?php } else {?>
                $('#receta-table').DataTable({
                    "filter": false
                    , "bLengthChange": false|
                });
                $('#restaurante-table').DataTable({
                    "filter": false
                    , "bLengthChange": false|
                });
                $('#tip-table').DataTable({
                    "filter": false
                    , "bLengthChange": false|
                });
            <?php }?>
                $('#nav-fitbit').addClass('active');
            });
        </script>

        <script>
        function startDictation() {

            if (window.hasOwnProperty('webkitSpeechRecognition')) {

            var recognition = new webkitSpeechRecognition();

            recognition.continuous = false;
            recognition.interimResults = false;

            recognition.lang = "en-US";
            recognition.start();

            recognition.onresult = function(e) {
                document.getElementById('transcript').value
                                        = e.results[0][0].transcript;
                recognition.stop();
                document.getElementById('labnol').submit();
            };

            recognition.onerror = function(e) {
                recognition.stop();
            }

            }
        }
        </script>
    </body>
</html>