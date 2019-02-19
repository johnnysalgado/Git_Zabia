<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_enfermedad.php');
    require('inc/constante.php');

    if (isset($_POST["id_enfermedad"])) {

        $idEnfermedad = $_POST["id_enfermedad"];
        $usuario = $_SESSION["U"];
        $tipoDietas = [];
        if (isset($_POST["tipo_dieta"])) {
            $tipoDietas = $_POST["tipo_dieta"];
        }

        $daoEnfermedad = new DaoEnfermedad();
        $daoEnfermedad->eliminarPrecondicionTipoDieta($idEnfermedad, $usuario);

        foreach($tipoDietas as $idtTipoDieta) {
            $daoEnfermedad->editarPrecondicionTipoDieta($idEnfermedad, $idtTipoDieta, $usuario);
        }

        $daoEnfermedad = null;

        header("Location: enfermedades.php");
        die();
    
    } else {

        $idEnfermedad = $_GET["id"];

        if (is_numeric($idEnfermedad) && $idEnfermedad > 0 ) {

            $precondicion = "";
            //tipo dieta
            $daoEnfermedad = new DaoEnfermedad();
            $arregloTipoDieta = $daoEnfermedad->listarTipoDietaPorPrecondicion($idEnfermedad, LENGUAJE_ESPANOL);
            $arregloEnfermedad = $daoEnfermedad->obtenerEnfermedad($idEnfermedad);
            $daoEnfermedad = null;
            $cantidadColumna = 3;
            $contadorColumna = 0;
            $htmlTipoDieta = "";
            foreach ($arregloTipoDieta as $item) {
                $idEnfermedadTipoDieta = $item['id_enfermedad_tipo_dieta'];
                $idTipoDieta = $item['id_tipo_dieta'];
                $tipoDieta = $item['tipo_dieta'];
                $estado = $item['estado'];
                if ($contadorColumna == 0) {
                    $htmlTipoDieta .= "<div class\"row\"> ";
                }
                $htmlTipoDieta .= "<div class=\"col-md-4\"> <input type=\"checkbox\" name=\"tipo_dieta[]\" value=\"$idTipoDieta\" ";
                if ($idEnfermedadTipoDieta > 0) {
                    $htmlTipoDieta .= " checked=\"checked\" ";
                }
                $htmlTipoDieta .= " /> <label>$tipoDieta</label>  </div>";
                $contadorColumna ++;
                if ($contadorColumna == $cantidadColumna) {
                    $htmlTipoDieta .= " </div> <br /> <br />";
                    $contadorColumna = 0;
                }
            }
            if ($contadorColumna < $cantidadColumna) {
                $htmlTipoDieta .= " </div> ";
            }
            if (count($arregloEnfermedad) > 0) {
                $precondicion = $arregloEnfermedad[0]['nombre'];
            }

        } else {
            header("Location: enfermedades.php");
            die();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <?php  require('inc/head.php'); ?>
    <body>
        <!-- Preloader -->
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
                            <h4 class="page-title">Tipo de dietas para <?php echo $precondicion; ?> </h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="enfermedad_tipo_dieta.php" method="post" name="forma" id="forma">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="checkbox checkbox-success">
                                                <?php echo $htmlTipoDieta;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <input type="hidden" name="id_enfermedad" value="<?php echo $idEnfermedad?>" />
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#nav-health').addClass('active');
            });

            $('#volver').click(function() {
                location.href = 'enfermedades.php';
            });

            $('#nuevo').click(function() {
                $('#forma').submit();
            });

        </script>
    </body>
</html>
<?php
    $cnx = null;
?>