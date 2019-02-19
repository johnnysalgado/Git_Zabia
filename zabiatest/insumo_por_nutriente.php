<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $nutriente = "";
    $html = "";

    $cnx = new MySQL();

    if (isset($_POST["nutriente"])) {
        $nutriente = $_POST["nutriente"];
        $query = "SELECT a.id_insumo, a.nombre as insumo, b.cantidad, b.unidad FROM insumo a INNER JOIN insumo_nutriente b ON a.id_insumo = b.id_insumo WHERE b.id_nutriente = " . $nutriente . " ORDER BY insumo";
        $sql = $cnx->query($query);
        $sql->read();
        $html = "";
        while($sql->next()) {
            $idInsumo = $sql->field('id_insumo');
            $nombre = $sql->field('insumo');
            $cantidad = $sql->field('cantidad');
            $unidad = $sql->field('unidad');
            $html .= "<tr> <td> " . $idInsumo . " </td>" ;
            $html .= "<td> " . $nombre . " </td>";
            $html .= "<td> " . round($cantidad, 4) . " " . $unidad . " </td>";
            $html .= "</tr>";
        }
    }

    $query = "SELECT id_nutriente, nombre FROM nutriente ORDER BY nombre";
    
    $sql = $cnx->query($query);
    $sql->read();
    $htmlNutriente = "";
    while($sql->next()) {
        $idNutriente = $sql->field('id_nutriente');
        $nombre = $sql->field('nombre');
        $htmlNutriente .= "<option value=\"" . $idNutriente . "\"";
        if ($nutriente == $idNutriente) {
            $htmlNutriente .= " selected=\"selected\"";
        }
        $htmlNutriente .= ">" . $nombre . '</option>';
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
                            <h4 class="page-title">Insumos por nutrientes</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <form action="insumo_por_nutriente.php" method="post" id="insumo-nutriente">
                        <div class="row">
                            <div class="col-md-4 col-xs-4">
                                <select name="nutriente" id="nutriente" class="form-control">
                                    <option value="">[Seleccionar Nutriente]</option>
                                    <?php echo $htmlNutriente; ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-2">&nbsp;</div>
                            <div class="col-md-3 col-xs-3 text-danger mensaje-nutriente">&nbsp;</div>
                            <div class="col-md-1 col-xs-1 text_right">
                                <input type="button" id="buscar-insumo" value="Buscar" class="btn btn-primary" />
                            </div>
                        </div>
                    </form>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                            <?php if ($html != "") { ?>
                                <table id="insumo-table" class="table table-striped display">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Insumo </th>
                                            <th> Cantidad por 100 gr. </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $html; ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#insumo-table').DataTable();
                $('#nav-ingredient').addClass('active');
            });

            $('#buscar-insumo').click(function() {
                $(".mensaje-nutriente").html("");
                if ($("#nutriente").val().trim() == "") {
                     $(".mensaje-nutriente").html("* Debe elegir un nutriente");
                } else {
                    $("#insumo-nutriente").submit();
                }
            });
        </script>
    </body>
</html>