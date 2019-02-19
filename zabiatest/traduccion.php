<?php

    //Include The Database Connection File
    require('inc/configuracion.php');

    //Include The Database Connection File
    require('inc/mysql.php');

    require_once ('inc/GoogleTranslate.php');

    use \Statickidz\GoogleTranslate;
    $source = 'en';
    $target = 'es';

//    $query = "SELECT id_plato_insumo, nombre FROM plato_insumo WHERE nombre_ing is null limit 4000";
    
//    $query = "SELECT a.id_plato_insumo, a.nombre, a.nombre_ing FROM plato_insumo a INNER JOIN plato b ON a.id_plato = b.id_plato INNER JOIN imagerec_dish c ON b.id_ = c.id WHERE c.cuisine = 'peruvian' AND id_plato_insumo > 3722 LIMIT 4000";

    $query = "SELECT id_insumo_medida, unidad_ing FROM insumo_medida WHERE unidad = ''";

    $cnx = new MySQL();
    $sql = $cnx->query($query);
    $sql->read();
    $i = 0;
    $j = 0;
    $html = "";
    set_time_limit(2000);
    $trans = new GoogleTranslate();
    while($sql->next()) {
        $i = $i++;
        $id = $sql->field('id_insumo_medida');
        $nombre = $sql->field('unidad_ing');

        $text = $nombre;
        $result = $trans->translate($source, $target, $text);
        $result = str_replace('\'', '', $result);
    
        $html .= '<tr>';
        $html .= '<td>'.$id.'</td>';
        $html .= '<td>'.$nombre.'</td>';
        $html .= '<td>'.str_replace('\'', '', $result).'</td>';
        $html .= '</tr>';

        $sql_update = "UPDATE insumo_medida SET unidad='$result' WHERE id_insumo_medida=$id";
        $cnx->insert($sql_update);
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Nutrición</title>
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="../assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="../assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="../assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="../assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />
    </head>
    <body>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th> # </th>
                <th> Nombre origen</th>
                <th> Nombre salida</th>
            </tr>
            </thead>
            <tbody>
                <?php echo $html; ?>
            </tbody>
        </table>
        <!--[if lt IE 9]>
        <script src="../assets/global/plugins/respond.min.js"></script>
        <script src="../assets/global/plugins/excanvas.min.js"></script>
        <script src="../assets/global/plugins/ie8.fix.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="../assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="../assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="../assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="../assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
    </body>
</html>