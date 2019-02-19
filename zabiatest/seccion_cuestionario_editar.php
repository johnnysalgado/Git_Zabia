<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/dao_cuestionario.php');
    require('inc/funcion_cuestionario.php');

    if (isset($_POST["id_seccion_cuestionario"])) {
        $idSeccionCuestionario = $_POST["id_seccion_cuestionario"];
        $descripcion = $_POST["descripcion"];
        $descripcionIngles = $_POST["descripcion_ing"];
        $orden = $_POST["orden"];
        $iconoClase = $_POST["icono_clase"];
        $usuario = $_SESSION["U"];
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }
        if (isset($_POST["system_required"])) {
            $systemRequired = $_POST["system_required"];
        } else {
            $systemRequired = "0";
        }

        if ($descripcion != "") {
            $descripcion = str_replace("'", "''", $descripcion);
        }
        if ($descripcionIngles != "") {
            $descripcionIngles = str_replace("'", "''", $descripcionIngles);
        }
        if (trim($orden) == "") {
            $orden = 1;
        }
        $idAfiliado = $_SESSION['AFILIADO_ID'];

        $daoCuestionario = new DaoCuestionario();

        $idSeccion = $daoCuestionario->editarSeccionCuestionario($idSeccionCuestionario, $descripcion, $descripcionIngles, $orden, $iconoClase, $systemRequired, $estado, $usuario);

        grabarSeccionCuestionarioQuestionnaireSetPorAfiliado($daoCuestionario, $idSeccion, $idAfiliado, $usuario);

        if ($systemRequired == "1") {
            grabarSeccionCuestionarioQuestionnaireSet($daoCuestionario, $idSeccionCuestionario, $systemRequired, $usuario);
        }

        $daoCuestionario = null;

        header("Location: seccion_cuestionario.php");
        die();
    } else {
        $idSeccionCuestionario = $_GET['id'];
        if (is_numeric($idSeccionCuestionario)) {
            $descripcion = "";
            $descripcionIngles = "";
            $orden = 1;
            $systemRequired = 0;
            $htmlIconoClase = "";
            $daoCuestionario = new DaoCuestionario();
            $arregloSeccion = $daoCuestionario->obtenerSeccionCuestionario($idSeccionCuestionario);
            if (count($arregloSeccion) > 0) {
                $item = $arregloSeccion[0];
                $descripcion = $item['descripcion'];
                $descripcionIngles = $item['descripcion_ing'];
                $orden = $item['orden'];
                $iconoClase = $item['icono_clase'];
                $systemRequired = $item['system_required'];
                $estado = $item['estado'];
            }
            $daoCuestionario = null;
            foreach ($arregloSeccionIconoClase as $item) {
                $htmlIconoClase .= "<option value=\"" . $item['codigo'] . "\"";
                if ($iconoClase == $item['codigo']) {
                    $htmlIconoClase .= " selected=\"selected\"";
                }
                $htmlIconoClase .= ">" . $item['descripcion'] . "</option>";
            }
        } else {
            header("Location: seccion_cuestionario.php");
            die();
        }
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
                            <h4 class="page-title">Modificaci&oacute;n de Secci&oacute;n de cuestionario de Salud - <?php echo $_SESSION['AFILIADO_NOMBRE']; ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="seccion_cuestionario_editar.php" method="post" id="forma">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n</label>
                                            <input type="text" name="descripcion" id="descripcion" class="form-control" maxlength="64" value="<?php echo $descripcion; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n [Ingl&eacute;s]</label>
                                            <input type="text" name="descripcion_ing" id="descripcion_ing" class="form-control" maxlength="64" value="<?php echo $descripcionIngles; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Orden</label>
                                            <input type="number" name="orden" id="orden" class="form-control" step="1" min="1" value="<?php echo $orden; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="estado" id="estado" <?php if ($estado == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="estado">Activo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Icono clase</label>
                                            <select name="icono_clase" id="icono_clase" class="form-control">
                                                <option value="">[Seleccione]</option>
                                                <?php echo $htmlIconoClase; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="system_required" id="system_required" <?php if ($systemRequired == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="system_required">Secci&oacute;n obligatoria en todos los afiliados</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <div id="divError" class="row alert alert-danger"></div>
                                <input type="hidden" name="id_seccion_cuestionario" name="id_seccion_cuestionario" value="<?php echo $idSeccionCuestionario; ?>" />
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#divError').hide();
                $('#nav-health').addClass('active');
                $('#nav-health-question').addClass('active');
            });

            $('#volver').click(function() {
                location.href = 'seccion_cuestionario.php';
            });

            $('#grabar').click(function() {
                if ($.trim($('#descripcion').val()) == '') {
                    $('#divError').html('La descripción no puede estar vacía').show();
                } else {
                    $(this).attr('disabled','disabled');
                    $('#forma').submit();
                }
            });
        </script>
    </body>
</html>