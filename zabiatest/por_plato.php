<?php
    //Include The Database Connection File
    require('inc/configuracion.php');

    //Include The Database Connection File
    require('inc/mysql.php');

    $query = "SELECT id_plato, nombre, nombre_ing FROM plato WHERE estado = 1 ORDER BY nombre";
    
    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $i = 0;
    $j = 0;
    $html = "";
    while($sql->next()) {
        $i++;
        $id = $sql->field('id_plato');
        $nombre = $sql->field('nombre');
        $nombreIngles = $sql->field('nombre_ing');
   
        $html .= '<tr data-id="'.$id.'">';
        $html .= '<td>'.$i.'</td>';
        $html .= '<td>'.$nombre.'</td>';
        $html .= '<td>'.$nombreIngles.'</td>';
        $html .= '</tr>';
    }

    $cnx = null;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Nutrición</title>
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout/css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout/css/site.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />
    </head>
    <body>
        <div class="container">
            <h1>Platos</h1>
            <div class="row">
                <div class="col-md-10 col-xs-10">
                    <table class="table table-bordered table-hover table-click">
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> Plato </th>
                            <th> (en inglés) </th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php echo $html; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--[if lt IE 9]>
        <script src="assets/global/plugins/respond.min.js"></script>
        <script src="assets/global/plugins/excanvas.min.js"></script>
        <script src="assets/global/plugins/ie8.fix.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $('.table > tbody > tr').click(function() {
                var id_plato = $(this).attr('data-id');
                location.href = "plato.php?id=" + id_plato;
            });
        </script>
    </body>
</html>