<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/constante_nutriente.php');
    require('inc/mysql.php');
    require('inc/dao_insumo.php');
    require('inc/dao_nutriente.php');

    if (isset($_POST["nombre"])) {
        $nombre = $_POST["nombre"];
        $nombreIngles = $_POST["nombre_ing"];
        $unidad = $_POST["unidad"];
        $cotaInferior = $_POST["cota_inferior"];
        $cotaSuperior = $_POST["cota_superior"];
        $rdi = $_POST["rdi"];
        $rda = $_POST["rda"];
        $ea = $_POST["ea"];
        $idTipoNutriente = $_POST["tipo_nutriente"];
        $idTipoClase = $_POST["tipo_clase"];
        $idTipoCategoria = $_POST["tipo_categoria"];
        $idTipoFamilia = $_POST["tipo_familia"];
        $idTipoSubfamilia = $_POST["tipo_subfamilia"];
        $codigoExterno = $_POST["codigo_externo"];
        $aporte = $_POST["aporte"];
        $flagEsencial = $_POST["flag_esencial"];
        $recomendacion = $_POST["recomendacion"];
        $recomendacionIngles = $_POST["recomendacion_ing"];
        $referencia = $_POST["referencia"];
        $usuario = $_SESSION["U"];
        $beneficios = [];
        if (isset($_POST["beneficio"])) {
            $beneficios = $_POST["beneficio"];
        }
        if ($nombre != "") {
            $nombre = str_replace("'", "''", $nombre);
        }
        if ($nombreIngles != "") {
            $nombreIngles = str_replace("'", "''", $nombreIngles);
        }
        if ($unidad != "") {
            $unidad = str_replace("'", "''", $unidad);
        }
        if ($cotaInferior == "") {
            $cotaInferior = "0";
        }
        if ($cotaSuperior == "") {
            $cotaSuperior = "0";
        }
        if ($rdi == "") {
            $rdi = "0";
        }
        if ($rda == "") {
            $rda = "0";
        }
        if ($ea == "") {
            $ea = "0";
        }
        if ($idTipoNutriente == "") {
            $idTipoNutriente = "null";
        }
        if ($idTipoClase == "") {
            $idTipoClase = "null";
        }
        if ($idTipoCategoria == "") {
            $idTipoCategoria = "null";
        }
        if ($idTipoFamilia == "") {
            $idTipoFamilia = "null";
        }
        if ($idTipoSubfamilia == "") {
            $idTipoSubfamilia = "null";
        }
        if ($codigoExterno == "") {
            $codigoExterno = "0";
        }
        if ($recomendacion != "") {
            $recomendacion = str_replace("'", "''", $recomendacion);
        }
        if ($recomendacionIngles != "") {
            $recomendacionIngles = str_replace("'", "''", $recomendacionIngles);
        }
        if ($referencia != "") {
            $referencia = str_replace("'", "''", $referencia);
        }

        $daoNutriente = new DaoNutriente();
        $idNutriente = $daoNutriente->crearNutriente($nombre, $nombreIngles, $unidad, $cotaInferior, $cotaSuperior, $aporte, $rdi, $rda, $ea, $idTipoNutriente, $idTipoClase, $idTipoCategoria, $idTipoFamilia, $idTipoSubfamilia, $codigoExterno, $flagEsencial, $recomendacion, $recomendacionIngles, $referencia, $usuario);
        $daoNutriente = null;

        $cnx = new MySQL();
        //beneficios
        foreach ($beneficios as $beneficio) {
            $query = "INSERT INTO nutriente_beneficio (id_nutriente, id_beneficio, usuario_registro) VALUES (" . $idNutriente . ", " . $beneficio . ", '". $usuario . "');";
            $cnx->execute($query);
        }
        $cnx->close();
        $cnx = null;
        header("Location: nutrientes.php");
        die();
    
    } else {

        $htmlBeneficio = "";
        $daoInsumo = new DaoInsumo();
        $arregloBeneficio = $daoInsumo->listarBeneficio();
        foreach ($arregloBeneficio as $item) {
            $beneficio = $item['nombre'];
            $idBeneficio = $item['id_beneficio'];
            $htmlBeneficio .= '<div class="col-md-3"> <input type="checkbox" name="beneficio[]" value="' . $idBeneficio . '" /> <label>' . $beneficio . '</label> </div>';
        }
        $daoInsumo = null;

        $htmlTipoNutriente = "";
        $daoNutriente = new DaoNutriente();
        $arregloTipoNutriente = $daoNutriente->listarTipoNutriente();
        foreach ($arregloTipoNutriente as $item) {
            $idTN = $item['id_tipo_nutriente'];
            $htmlTipoNutriente .= " <option value=\"$idTN\">" . $item['nombre'] . "</option> ";
        }
        $daoNutriente = null;

        $htmlEsencial = "";
        foreach ($arregloNutrienteEsencialGrabar as $item) {
            $flag = $item['codigo'];
            $htmlEsencial .= " <option value=\"$flag\">" . $item['descripcion'] . "</option> ";
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
                            <h4 class="page-title">Creaci&oacute;n de Nutriente</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="nutriente_crear.php" method="post" name="forma_nutriente" id="forma-nutriente">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre ingl&eacute;s</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="64" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unidad</label>
                                            <input type="text" name="unidad" id="unidad" class="form-control" maxlength="32" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cota inferior</label>
                                            <input type="number" name="cota_inferior" id="cota_inferior" class="form-control" min="0" value="" step="0.0001" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cota superior</label>
                                            <input type="number" name="cota_superior" id="cota_superior" class="form-control" min="0" value="" step="0.0001" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>RDI</label>
                                            <input type="number" name="rdi" id="rdi" class="form-control" min="0" value="" step="0.0001" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>RDA</label>
                                            <input type="number" name="rda" id="rda" class="form-control" min="0" value="" step="0.0001" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>EA</label>
                                            <input type="number" name="ea" id="ea" class="form-control" min="0" value="" step="0.0001" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipo nutriente</label>
                                            <select name="tipo_nutriente" id="tipo_nutriente" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                            <?php echo $htmlTipoNutriente; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Clase</label>
                                            <select name="tipo_clase" id="tipo_clase" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Categor&iacute;a</label>
                                            <select name="tipo_categoria" id="tipo_categoria" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Familia</label>
                                            <select name="tipo_familia" id="tipo_familia" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sub familia</label>
                                            <select name="tipo_subfamilia" id="tipo_subfamilia" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Aporte</label>
                                            <select name="aporte" class="form-control">
                                                <option value="positivo">Positivo</option>
                                                <option value="negativo">Negativo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Esencial</label>
                                            <select name="flag_esencial" id="flag_esencial" class="form-control">
                                                <?php echo $htmlEsencial; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Recomendaci&oacute;n</label>
                                            <textarea name="recomendacion" id="recomendacion" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Recomendaci&oacute;n [Ingl&eacute;s]</label>
                                            <textarea name="recomendacion_ing" id="recomendacion_ing" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Referencia bibliogr&aacute;fica</label>
                                            <input type="text" name="referencia" id="referencia" class="form-control" maxlength="512" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>C&oacute;digo USDA</label>
                                            <input type="number" name="codigo_externo" id="codigo_externo" class="form-control" min="0" value=">" step="1" />
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Beneficios</label>
                                            <div class="checkbox checkbox-success">
                                                <?php echo $htmlBeneficio;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-md-6 alert alert-info">
                                        * Ning&uacute;n cambio har&aacute; efecto a menos que se de clic en "Grabar".
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="nuevo-nutriente" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <div id="mensaje-nombre-vacio" class="row alert alert-danger">* El nombre no debe estar vac&iacute;o</div>
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
                $('#mensaje-nombre-vacio').hide();
            });

            $('#volver').click(function() {
                location.href = 'nutrientes.php';
            });

            $('#nuevo-nutriente').click(function() {
                $('#mensaje-nombre-vacio').hide();
                if ($.trim($('#nombre').val()) == '') {
                    $('#mensaje-nombre-vacio').show();
                } else {
                    $(this).attr('disabled','disabled');
                    $('#forma-nutriente').submit();
                }
            });

            $('#tipo_nutriente').change(function() {
                incializaTipoClase();
                incializaTipoCategoria();
                incializaTipoFamilia();
                incializaTipoSubfamilia();
                var sel_opn = $('#tipo_clase'); 
                var param = { "nutrientTypeID" : $(this).val() };
                var paramJSON = JSON.stringify(param);
                $.ajax({
                    type: 'POST',
                    url: 'service/getnutrientclass.php',
                    data: paramJSON,
                    dataType: 'json',
                    error: function(errorResult) {
                        console.log('Ha ocurrido un error ' + errorResult.responseText);
                    },
                    success: function (result) {
                        console.log(result);
                        var items = result.data;
                        $.each(items, function(key, text) {
                            var option = new Option(text.name, text.nutrientClassID);
                            sel_opn.append($(option));
                        });
                    }
                });
            })

            $('#tipo_clase').change(function() {
                incializaTipoCategoria();
                incializaTipoFamilia();
                incializaTipoSubfamilia();
                var sel_opn = $('#tipo_categoria'); 
                var param = { "nutrientClassID" : $(this).val() };
                var paramJSON = JSON.stringify(param);
                $.ajax({
                    type: 'POST',
                    url: 'service/getnutrientcategory.php',
                    data: paramJSON,
                    dataType: 'json',
                    error: function(errorResult) {
                        console.log('Ha ocurrido un error ' + errorResult.responseText);
                    },
                    success: function (result) {
                        console.log(result);
                        var items = result.data;
                        $.each(items, function(key, text) {
                            var option = new Option(text.name, text.nutrientCategoryID);
                            sel_opn.append($(option));
                        });
                    }
                });
            })

            $('#tipo_categoria').change(function() {
                incializaTipoFamilia();
                incializaTipoSubfamilia();
                var sel_opn = $('#tipo_familia'); 
                var param = { "nutrientCategoryID" : $(this).val() };
                var paramJSON = JSON.stringify(param);
                $.ajax({
                    type: 'POST',
                    url: 'service/getnutrientfamily.php',
                    data: paramJSON,
                    dataType: 'json',
                    error: function(errorResult) {
                        console.log('Ha ocurrido un error ' + errorResult.responseText);
                    },
                    success: function (result) {
                        console.log(result);
                        var items = result.data;
                        $.each(items, function(key, text) {
                            var option = new Option(text.name, text.nutrientFamilyID);
                            sel_opn.append($(option));
                        });
                    }
                });
            })

            $('#tipo_familia').change(function() {
                incializaTipoSubfamilia();
                var sel_opn = $('#tipo_subfamilia'); 
                var param = { "nutrientFamilyID" : $(this).val() };
                var paramJSON = JSON.stringify(param);
                $.ajax({
                    type: 'POST',
                    url: 'service/getnutrientsubfamily.php',
                    data: paramJSON,
                    dataType: 'json',
                    error: function(errorResult) {
                        console.log('Ha ocurrido un error ' + errorResult.responseText);
                    },
                    success: function (result) {
                        console.log(result);
                        var items = result.data;
                        $.each(items, function(key, text) {
                            var option = new Option(text.name, text.nutrientSubfamilyID);
                            sel_opn.append($(option));
                        });
                    }
                });
            })

            function incializaTipoClase() {
                var sel_opn = $('#tipo_clase'); 
                $('option', sel_opn).remove();
                var option = new Option("[Seleccionar]", "");
                sel_opn.append($(option));
            }

            function incializaTipoCategoria() {
                var sel_opn = $('#tipo_categoria'); 
                $('option', sel_opn).remove();
                var option = new Option("[Seleccionar]", "");
                sel_opn.append($(option));
            }

            function incializaTipoFamilia() {
                var sel_opn = $('#tipo_familia'); 
                $('option', sel_opn).remove();
                var option = new Option("[Seleccionar]", "");
                sel_opn.append($(option));
            }

            function incializaTipoSubfamilia() {
                var sel_opn = $('#tipo_subfamilia'); 
                $('option', sel_opn).remove();
                var option = new Option("[Seleccionar]", "");
                sel_opn.append($(option));
            }
        </script>
    </body>
</html>
<?php
    $cnx = null;
?>