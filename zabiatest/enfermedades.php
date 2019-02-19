<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_enfermedad.php');
    require('inc/constante.php');

    $idTipoCategoriaPrecondicion = 0;
    if (!isset($_GET['a'])) {
        if (isset($_GET['tcp']) && is_numeric($_GET['tcp'])) {
            $idTipoCategoriaPrecondicion= $_GET['tcp'];
        } else {
            if (isset($_SESSION["E_TCP"])) {
                $idTipoCategoriaPrecondicion = $_SESSION["E_TCP"];
            }
        }
    }
    $_SESSION["E_TCP"] = $idTipoCategoriaPrecondicion;

    $daoEnfermedad = new DaoEnfermedad();
    $arregloEnfermedad = $daoEnfermedad->listarEnfermedad(-1, LENGUAJE_ESPANOL, $idTipoCategoriaPrecondicion);
    $arregloCategoria = $daoEnfermedad->listarTipoCategoriaPrecondicion(LISTA_ACTIVO, LENGUAJE_ESPANOL);
    $daoEnfermedad = null;

    $html = "";
    foreach ($arregloEnfermedad as $item) {
        $idEnfermedad = $item['id_enfermedad'];
        $nombre = $item['nombre'];
        $nombreIngles = $item['nombre_ing'];
        $tipoCategoriaPrecondicion = $item['tipo_categoria_precondicion'];
        $estado = $item['estado'];
        if ($estado == "1") {
            $estado = "Activo";
        } else {
            $estado = "Inactivo";
        }
   
        $html .= "<tr data-id=\"$idEnfermedad\" >";
        $html .= "<td class=\"td-enfermedad\">$idEnfermedad</td>";
        $html .= "<td class=\"td-enfermedad\">$tipoCategoriaPrecondicion </td>";
        $html .= "<td class=\"td-enfermedad\">$nombre</td>";
        $html .= "<td class=\"td-enfermedad\">$nombreIngles</td>";
        $html .= "<td class=\"td-nutriente text-center\"> <i class=\"glyphicon glyphicon-grain\"></i> </td>";
        $html .= "<td class=\"td-tipo-dieta text-center\"> <i class=\"glyphicon glyphicon-ban-circle\"></i> </td>";
        $html .= "</tr>";
    }

    $htmlCategoria = "";
    foreach($arregloCategoria as $item) {
        $idTCP = $item["id_tipo_categoria_precondicion"];
        $nombreTCP = $item["nombre"];
        $htmlCategoria .= "<option value=\"$idTCP\" ";
        if ($idTCP == $idTipoCategoriaPrecondicion) {
            $htmlCategoria .= " selected=\"selected\" ";
        }
        $htmlCategoria .= ">$nombreTCP</option>";
    }

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
                            <h4 class="page-title">Precondiciones</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-enfermedad" value="Nueva enfermedad" class="btn btn-primary nuevo-enfermedad" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="enfermedades.php" method="get" id="forma-buscar">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Categor&iacute;a</label>
                                            <select name="tcp" id="tcp" class="form-control">
                                                <option value="0">[Todos]</option>
                                            <?php echo $htmlCategoria; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-6 text-right">
                                        <br />
                                            <input type="button" id="buscar" value="Buscar" class="btn btn-default" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="enfermedad-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Categor&iacute;a </th>
                                            <th> Enfermedad </th>
                                            <th> Enfermedad (Ingl&eacute;s) </th>
                                            <th> Nutriente </th>
                                            <th> Tipo dieta </th>
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
            $('.td-enfermedad').click(function() {
                var id_enfermedad = $(this).closest('tr').attr('data-id');
                location.href = "enfermedad_editar.php?id=" + id_enfermedad;
            });

            $('.td-tipo-dieta').click(function() {
                var id_enfermedad = $(this).closest('tr').attr('data-id');
                location.href = "enfermedad_tipo_dieta.php?id=" + id_enfermedad;
            });

            $('.td-nutriente').click(function() {
                var id_enfermedad = $(this).closest('tr').attr('data-id');
                location.href = "enfermedad_nutriente.php?id=" + id_enfermedad;
            });

            $('.nuevo-enfermedad').click(function() {
                location.href = "enfermedad_crear.php";
            });

            $(document).ready(function() {
                $('#enfermedad-table').DataTable({"pageLength": 25});
                $('#nav-health').addClass('active');
            } );

            $("#buscar").click(function() {
                $('#forma-buscar').submit();
            });

        </script>
    </body>
</html>