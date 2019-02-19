<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/dao_afiliado.php');

    $idAfiliado = 0;
    if (isset($_POST['url'])) {
        $urlToGo = $_POST['url'];
        if (isset($_POST['afiliado'])) {
            $idAfiliado = $_POST['afiliado'];
            $_SESSION['AFILIADO_ID'] = $idAfiliado;
        }
        if (isset($_POST['afiliado_nombre'])) {
            $afiliadoNombre = strval($_POST['afiliado_nombre']);
            $_SESSION['AFILIADO_NOMBRE'] = $afiliadoNombre;
        }
        header("Location: $urlToGo");
        die();
    }

    if (isset($_GET['url'])) {
        $urlToGo = $_GET['url'];
    }

    $daoAfiliado = new DaoAfiliado();
    $arregloAfiliado = $daoAfiliado->listarAfiliado(LISTA_ACTIVO);
    $daoAfiliado = null;
    $htmlAfiliado = "";
    foreach ($arregloAfiliado as $item) {
        $id = $item["id_affiliates"];
        $afiliado = $item["name"];
        $htmlAfiliado .= "<option value=\"$id\"";
        if ($id == $idAfiliado) {
            $htmlAfiliado .= " selected=\"selected\"";
        }
        $htmlAfiliado .= ">$afiliado </option>";
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
                            <h4 class="page-title">Seleccionar Afiliado</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="elegir_afiliado.php" method="post" id="forma">
                                <div class="row">
                                    <div class="col-md-3 col-xs-3">
                                        &nbsp;
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <div class="form-group">
                                            <label>Afiliado</label>
                                            <select class="form-control" id="afiliado" name="afiliado">
                                                <option value="">[Seleccionar]</option>
                                                <?php echo $htmlAfiliado; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <br />
                                        <input type="hidden" name="url" id="url" value="<?php echo $urlToGo; ?>" />
                                        <input type="hidden" name="afiliado_nombre" id="afiliado_nombre" value="" />
                                        <input type="button" id="continuar" value="Continuar" class="btn btn-success" />
                                    </div>
                                </div>
                                <div id="divError" class="row alert alert-danger"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#divError').hide();
            });

            $('#continuar').click(function() {
                $('#divError').hide();
                if ($('#afiliado').val() == "") {
                    $('#divError').html('Debe seleccionar un afiliado').show();
                } else {
                    $('#afiliado_nombre').val($("#afiliado option:selected").text());
                    $(this).attr('disabled','disabled');
                    $('#forma').submit();
                }
            });
        </script>
    </body>
</html>