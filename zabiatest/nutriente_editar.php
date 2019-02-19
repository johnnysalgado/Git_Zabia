<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/constante_nutriente.php');
    require('inc/mysql.php');
    require('inc/dao_nutriente.php');

    if (isset($_POST["id_nutriente"])) {
        $idNutriente= $_POST["id_nutriente"];
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
        if (isset($_POST["estado"])) {
            $estado = $_POST["estado"];
        } else {
            $estado = "0";
        }
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
        if ($estado == "") {
            $estado = "0";
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
        $resultado = $daoNutriente->editarNutriente($idNutriente, $nombre, $nombreIngles, $unidad, $cotaInferior, $cotaSuperior, $aporte, $rdi, $rda, $ea, $idTipoNutriente, $idTipoClase, $idTipoCategoria, $idTipoFamilia, $idTipoSubfamilia, $codigoExterno, $flagEsencial, $recomendacion, $recomendacionIngles, $referencia, $estado, $usuario);
        $daoNutriente = null;

        $cnx = new MySQL();
        //beneficios
        $query = "UPDATE nutriente_beneficio SET estado = 0 WHERE id_nutriente = " . $idNutriente;
        $cnx->execute($query);
        foreach ($beneficios as $beneficio) {
            $query = "SELECT id_nutriente_beneficio FROM nutriente_beneficio WHERE id_beneficio = " . $beneficio . " AND id_nutriente = " . $idNutriente;
            $sql = $cnx->query($query);
            $sql->read();
            if ($sql->count() > 0) {
                $query = "UPDATE nutriente_beneficio SET estado = 1, usuario_modifica = '" . $usuario . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_beneficio = " . $beneficio . " AND id_nutriente = " . $idNutriente;
            } else {
                $query = "INSERT INTO nutriente_beneficio (id_nutriente, id_beneficio, usuario_registro) VALUES (" . $idNutriente . ", " . $beneficio . ", '". $usuario . "');";
            }
            $cnx->execute($query);
        }
        $cnx->close();
        $cnx = null;
        header("Location: nutrientes.php");
        die();
    
    } else {

        $idNutriente= $_GET["id"];

        if ($idNutriente!= "" && $idNutriente!= "0") {

            //beneficios
            $cnx = new MySQL();
            $query = "SELECT a.nombre, a.id_beneficio, b.id_nutriente_beneficio FROM beneficio a LEFT OUTER JOIN nutriente_beneficio b ON a.id_beneficio = b.id_beneficio AND b.estado = 1 AND b.id_nutriente = " . $idNutriente. " WHERE ( a.estado = 1 ) ORDER BY nombre";
            $sql = $cnx->query($query);
            $sql->read();
            $htmlBeneficio = "";
            while($sql->next()) {
                $beneficio = $sql->field('nombre');
                $idBeneficio = $sql->field('id_beneficio');
                $idNutrienteBeneficio = $sql->field('id_nutriente_beneficio');
                $htmlBeneficio .= '<div class="col-md-3"> <input type="checkbox" name="beneficio[]" value="' . $idBeneficio . '"';
                if ($idNutrienteBeneficio > 0) {
                    $htmlBeneficio .= ' checked="checked"';
                }
                $htmlBeneficio .= '/> <label>' . $beneficio . '</label>  </div>';
            }
            $cnx->close();
            $cnx = null;

            $daoNutriente = new DaoNutriente();
            $arreglo = $daoNutriente->obtenerNutriente($idNutriente);
            if (count($arreglo) > 0) {
                $item = $arreglo[0];
                $nombre = $item['nombre'];
                $nombreIngles = $item['nombre_ing'];
                $unidad = $item['unidad'];
                $cotaInferior = $item['cota_inferior'];
                $cotaSuperior = $item['cota_superior'];
                $aporte = $item['aporte'];
                $rdi = $item['rdi'];
                $rda = $item['rda'];
                $ea = $item['ea'];
                $idTipoNutriente = $item['id_tipo_nutriente'];
                $idTipoClase = $item['id_tipo_clase'];
                $idTipoCategoria = $item['id_tipo_categoria'];
                $idTipoFamilia = $item['id_tipo_familia'];
                $idTipoSubfamilia = $item['id_tipo_subfamilia'];
                $codigoExterno = $item['codigo_externo'];
                $flagEsencial = $item['flag_esencial'];
                $recomendacion = $item['recomendacion'];
                $recomendacionIngles = $item['recomendacion_ing'];
                $referencia = $item['referencia'];
                $estado = $item['estado'];
                $usuarioRegistro = $item['usuario_registro'];
                $fechaRegistro = $item['fecha_registro'];
                $usuarioModifica = $item['usuario_modifica'];
                $fechaModifica = $item['fecha_modifica'];
            }

            $htmlTipoNutriente = "";
            $arregloTipoNutriente = $daoNutriente->listarTipoNutriente();
            foreach ($arregloTipoNutriente as $item) {
                $idTN = $item['id_tipo_nutriente'];
                $htmlTipoNutriente .= " <option value=\"$idTN\"";
                if ($idTN == $idTipoNutriente) {
                    $htmlTipoNutriente .= " selected=\"selected\"";
                }
                $htmlTipoNutriente .= ">" . $item['nombre'] . "</option> ";
            } 

            $htmlClase = "";
            if ($idTipoNutriente != 0 && $idTipoNutriente != null) {
                $arregloClase = $daoNutriente->listarClaseNutriente($idTipoNutriente);
                foreach ($arregloClase as $item) {
                    $idC = $item['id_tipo_clase'];
                    $htmlClase .= " <option value=\"$idC\"";
                    if ($idC == $idTipoClase) {
                        $htmlClase .= " selected=\"selected\"";
                    }
                    $htmlClase .= ">" . $item['nombre'] . "</option> ";
                } 
            }

            $htmlCategoria = "";
            if ($idTipoClase != 0 && $idTipoClase != null) {
                $arregloCategoria = $daoNutriente->listarCategoriaNutriente($idTipoClase);
                foreach ($arregloCategoria as $item) {
                    $idCa = $item['id_tipo_categoria'];
                    $htmlCategoria .= " <option value=\"$idCa\"";
                    if ($idCa == $idTipoCategoria) {
                        $htmlCategoria .= " selected=\"selected\"";
                    }
                    $htmlCategoria .= ">" . $item['nombre'] . "</option> ";
                } 
            }

            $htmlFamilia = "";
            if ($idTipoCategoria != 0 && $idTipoCategoria != null) {
                $arregloFamilia = $daoNutriente->listarFamiliaNutriente($idTipoCategoria);
                foreach ($arregloFamilia as $item) {
                    $idF = $item['id_tipo_familia'];
                    $htmlFamilia .= " <option value=\"$idF\"";
                    if ($idF == $idTipoFamilia) {
                        $htmlFamilia .= " selected=\"selected\"";
                    }
                    $htmlFamilia .= ">" . $item['nombre'] . "</option> ";
                } 
            }

            $htmlSubfamilia = "";
            if ($idTipoFamilia != 0 && $idTipoFamilia != null) {
                $arregloSubfamilia = $daoNutriente->listarSubfamiliaNutriente($idTipoFamilia);
                foreach ($arregloSubfamilia as $item) {
                    $idS = $item['id_tipo_subfamilia'];
                    $htmlSubfamilia .= " <option value=\"$idS\"";
                    if ($idS == $idTipoSubfamilia) {
                        $htmlSubfamilia .= " selected=\"selected\"";
                    }
                    $htmlSubfamilia .= ">" . $item['nombre'] . "</option> ";
                } 
            }

            $daoNutriente = null;

            $htmlEsencial = "";
            foreach ($arregloNutrienteEsencialGrabar as $item) {
                $flag = $item['codigo'];
                $htmlEsencial .= " <option value=\"$flag\"";
                if ($flag == $flagEsencial) {
                    $htmlEsencial .= " selected=\"selected\"";
                }
                $htmlEsencial .= ">" . $item['descripcion'] . "</option> ";
            } 

        } else {
            header("Location: nutrientes.php");
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
                            <h4 class="page-title">Modificaci&oacute;n de Nutriente</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="nutriente_editar.php" method="post" name="forma_nutriente" id="forma-nutriente">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" value="<?php echo $nombre ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre ingl&eacute;s</label>
                                            <input type="text" name="nombre_ing" id="nombre_ing" class="form-control" maxlength="64" value="<?php echo $nombreIngles ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unidad</label>
                                            <input type="text" name="unidad" id="unidad" class="form-control" maxlength="32" value="<?php echo $unidad ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cota inferior</label>
                                            <input type="number" name="cota_inferior" id="cota_inferior" class="form-control" min="0" value="<?php echo $cotaInferior ?>" step="0.0001" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cota superior</label>
                                            <input type="number" name="cota_superior" id="cota_superior" class="form-control" min="0" value="<?php echo $cotaSuperior ?>" step="0.0001" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>RDI</label>
                                            <input type="number" name="rdi" id="rdi" class="form-control" min="0" value="<?php echo $rdi ?>" step="0.0001" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>RDA</label>
                                            <input type="number" name="rda" id="rda" class="form-control" min="0" value="<?php echo $rda ?>" step="0.0001" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>EA</label>
                                            <input type="number" name="ea" id="ea" class="form-control" min="0" value="<?php echo $ea ?>" step="0.0001" />
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
                                                <?php echo $htmlClase; ?>
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
                                                <?php echo $htmlCategoria; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Familia</label>
                                            <select name="tipo_familia" id="tipo_familia" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                                <?php echo $htmlFamilia; ?>
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
                                                <?php echo $htmlSubfamilia; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Aporte</label>
                                            <select name="aporte" class="form-control">
                                                <option value="negativo" <?php if ($aporte == APORTE_NEGATIVO) { echo "selected = \"selected\" "; } ?> >Negativo</option>
                                                <option value="positivo" <?php if ($aporte == APORTE_POSITIVO) { echo "selected = \"selected\" "; } ?>>Positivo</option>
                                            </select>
                                        </div>
                                    </div>
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
                                            <textarea name="recomendacion" id="recomendacion" class="form-control" rows="4"><?php echo $recomendacion; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Recomendaci&oacute;n [Ingl&eacute;s]</label>
                                            <textarea name="recomendacion_ing" id="recomendacion_ing" class="form-control" rows="4"><?php echo $recomendacionIngles; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Referencia bibliogr&aacute;fica</label>
                                            <input type="text" name="referencia" id="referencia" class="form-control" maxlength="512" value="<?php echo $referencia; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>C&oacute;digo USDA</label>
                                            <input type="number" name="codigo_externo" id="codigo_externo" class="form-control" min="0" value="<?php echo $codigoExterno ?>" step="1" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="estado" id="estado" <?php if ($estado == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="estado">Activo</label>
                                            </div>
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
                                <br/>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Registrado</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo ($usuarioRegistro . ' - ' . $fechaRegistro); ?> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Modificado</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo ($usuarioModifica . ' - ' . $fechaModifica); ?> 
                                    </div>
                                </div>
                                <br />
                                <input type="hidden" name="id_nutriente" value="<?php echo $idNutriente?>" />
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