<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_enfermedad.php');
    require('inc/constante_nutriente.php');
    require('inc/dao_enfermedad.php');
    require('inc/dao_nutriente.php');

    if (isset($_POST["id_enfermedad"])) {

        $idEnfermedad = $_POST["id_enfermedad"];
        $usuario = $_SESSION["U"];
       
        if (isset($_POST["hdn_id_nutriente"])) {
            $daoEnfermedad = new DaoEnfermedad();
            $arregloIds = $_POST["hdn_id_nutriente"];
            foreach ($arregloIds as $item) {
                $idNutriente = $item;
                $prioridad = PRIORIDAD_NORMAL;
                $flagEliminar = 0;
                $valorEliminar = 0;
                $flagRestringir = 0;
                $valorRestringir = 0;
                $flagAumentar = 0;
                $flagNormal = 0;
                $flags = $_POST["flag_$idNutriente"];
                $flag1 = "";
                $flag2 = "";
                if (count($flags) > 0) {
                    $flag1 = $flags[0];
                    if (count($flags) > 1) {
                        $flag2 = $flags[1];
                    }
                }
                switch ($flag1) {
                    case ACCION_ELIMINAR:
                        $flagEliminar = 1;
                        break;
                    case ACCION_RESTRINGIR:
                        $flagRestringir = 1;
                        break;
                    case ACCION_AUMENTAR:
                        $flagAumentar = 1;
                        break;
                    default:
                        $flagNormal = 1;
                        break;
                }
                switch ($flag2) {
                    case ACCION_ELIMINAR:
                        $flagEliminar = 1;
                        break;
                    case ACCION_RESTRINGIR:
                        $flagRestringir = 1;
                        break;
                }
                $unidad = $_POST["unidad_$idNutriente"];
                if (isset($_POST["valor_eliminar_$idNutriente"]) && $_POST["valor_eliminar_$idNutriente"] != "") {
                    $valorEliminar = (float) $_POST["valor_eliminar_$idNutriente"];
                }
                if (isset($_POST["valor_restringir_$idNutriente"]) && $_POST["valor_restringir_$idNutriente"] != "") {
                    $valorRestringir = (float) $_POST["valor_restringir_$idNutriente"];
                }
                $daoEnfermedad->grabarEnfermedadNutriente($idEnfermedad, $idNutriente, $unidad, $flagRestringir, $valorRestringir, $flagEliminar, $valorEliminar, $flagAumentar, $flagNormal, $usuario);
            }
            //tabla plana
            $daoEnfermedad->actualizarTablaPlanaInsumoNutrientePrecondicion($idEnfermedad, $usuario);
            $daoEnfermedad = null;
        }

        header("Location: enfermedades.php");
        die();
    
    } else if (isset($_GET["id"]) && is_numeric($_GET["id"])){

        $idEnfermedad = $_GET["id"];
        $idTipoNutriente = "";
        $idTipoClase = "";
        $idTipoCategoria = "";
        $idTipoFamilia = "";
        $idTipoSubfamilia = "";
        $flagEsencial = "";
        $nombreNutriente = "";
        $flagAplica = 1;

        if (isset($_GET["tnut"])) {
            $idTipoNutriente = $_GET["tnut"]; 
        }
        if (isset($_GET["tcla"])) {
            $idTipoClase = $_GET["tcla"]; 
        }
        if (isset($_GET["tcat"])) {
            $idTipoCategoria = $_GET["tcat"]; 
        }
        if (isset($_GET["tfam"])) {
            $idTipoFamilia = $_GET["tfam"]; 
        }
        if (isset($_GET["tsfam"])) {
            $idTipoSubfamilia = $_GET["tsfam"]; 
        }
        if (isset($_GET["fese"])) {
            $flagEsencial = $_GET["fese"]; 
        }
        if (isset($_GET["nnut"])) {
            $nombreNutriente = $_GET["nnut"]; 
        }
        if (isset($_GET["fapl"])) {
            $flagAplica = $_GET["fapl"]; 
        }

        $daoNutriente = new DaoNutriente();
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

        $htmlFlagEsencial = "";
        foreach ($arregloNutrienteEsencialBusqueda as $item) {
            $flag = $item['codigo'];
            $htmlFlagEsencial .= " <option value=\"$flag\"";
            if ($flag == $flagEsencial) {
                $htmlFlagEsencial .= " selected=\"selected\"";
            }
            $htmlFlagEsencial .= ">" . $item['descripcion'] . "</option> ";
        } 

        $htmlAplica = "<option value=\"-1\"";
        if ($flagAplica == -1) {
            $htmlAplica .= " selected=\"selected\"";
        }
        $htmlAplica .= "> [Todos] </option>";
        $htmlAplica .= " <option value=\"1\"";
        if ($flagAplica == 1) {
            $htmlAplica .= " selected=\"selected\"";
        }
        $htmlAplica .= "> Ver solo los que aplican</option>";
        $htmlAplica .= " <option value=\"0\"";
        if ($flagAplica == 0) {
            $htmlAplica .= " selected=\"selected\"";
        }
        $htmlAplica .= "> Ver los que NO aplican</option>";

        $daoNutriente = null;

        if ($idTipoNutriente == "") $idTipoNutriente = 0;
        if ($idTipoClase == "") $idTipoClase = 0;
        if ($idTipoCategoria == "") $idTipoCategoria = 0;
        if ($idTipoFamilia == "") $idTipoFamilia = 0;
        if ($idTipoSubfamilia == "") $idTipoSubfamilia = 0;
        if ($flagAplica == "") $flagAplica = 1;

        //lista de unidad
        $daoNutriente = new DaoNutriente();
        $arregloUnidad = $daoNutriente->listarUnidad(LISTA_ACTIVO, LENGUAJE_ESPANOL);
        $daoNutriente = null;

        $daoEnfermedad = new DaoEnfermedad();
        $arreglo = $daoEnfermedad->listarNutrientePorEnfermedad($idEnfermedad, $idTipoNutriente, $idTipoClase, $idTipoCategoria, $idTipoFamilia, $idTipoSubfamilia, $flagEsencial, $nombreNutriente, $flagAplica);
        $htmlNutriente = "";
        $contadorItems = count($arreglo);
        foreach ($arreglo as $item) {
            $idNutriente = $item['id_nutriente'];
            $nutriente = $item['nutriente'];
            $tipoNutriente = $item['tipo_nutriente'] != null ? $item['tipo_nutriente'] : "[No asignado]";
            $tipoClase = $item['tipo_clase'] != null ? $item['tipo_clase'] : "[No asignado]";
            $unidad = $item['unidad'];
            $flagRestringir = $item['flag_restringir'];
            $valorRestringir = $item['valor_restringir'];
            $flagEliminar = $item['flag_eliminar'];
            $valorEliminar = $item['valor_eliminar'];
            $flagAumentar = $item['flag_aumentar'];
            $flagNormal = $item['flag_normal'];
            $flagEsencialBD = $item['flag_esencial'];
            $flagRDI = $item['flag_rdi'];

            // nutriente
            $htmlNutriente .= "<tr> <td> $nutriente / $tipoNutriente / $tipoClase ";
            $htmlNutriente .= " <input type=\"hidden\" name=\"hdn_id_nutriente[]\" value=\"$idNutriente\" /> </td> ";
            // unidad
            $htmlNutriente .= " <td class=\"text-center\"> ";
            $htmlNutriente .= " <select id=\"unidad_$idNutriente\" name=\"unidad_$idNutriente\" class=\"form-control\" >";
            $htmlNutriente .= " <option value=\"\"> [...] </option>";
            foreach ($arregloUnidad as $itemUnidad) {
                $unidadBD = $itemUnidad["unidad"];
                $htmlNutriente .= " <option value=\"$unidadBD\"";
                if ($unidadBD == $unidad) {
                    $htmlNutriente .= " selected=\"selected\" ";
                }
                $htmlNutriente .= "> $unidadBD </option>";
            }
            $htmlNutriente .= " </select> ";
            $htmlNutriente .= " </td> ";
            // flag - valor restringir
            $htmlNutriente .= " <td class=\"text-center\"> ";
            $htmlNutriente .= " <div class=\"col-md-2 col-xs-2\"> <input type=\"checkbox\" name=\"flag_$idNutriente" . "[]\" id=\"flag_restringir_$idNutriente\" value=\"" . ACCION_RESTRINGIR . "\" data-id=\"$idNutriente\" class=\"check-flag\" ";
            if ($flagRestringir == 1) {
                $htmlNutriente .= " checked=\"checked\"";
            }
            $htmlNutriente .= " /> </div> ";
            $htmlNutriente .= " <div class=\"col-md-10 col-xs-10\"> <input type=\"number\" value=\"$valorRestringir\" name=\"valor_restringir_$idNutriente\" id=\"valor_restringir_$idNutriente\" step=\"0.01\" min=\"0\" data-id=\"$idNutriente\" class=\"form-control check-range\" ";
            if ($flagAumentar == 1 || $flagNormal == 1) {
                $htmlNutriente .= " disabled=\"disabled\"";
            }
            $htmlNutriente .= " /> </div>";
            $htmlNutriente .= " </td> ";
            // flag - valor eliminar
            $htmlNutriente .= " <td class=\"text-center\">";
            $htmlNutriente .= " <div class=\"col-md-2 col-xs-2\"> <input type=\"checkbox\" name=\"flag_$idNutriente" . "[]\" id=\"flag_eliminar_$idNutriente\" value=\"" . ACCION_ELIMINAR . "\" data-id=\"$idNutriente\" class=\"check-flag";
            if ($flagEsencialBD == 1) { //si es esencial se esconde el eliminar.. y su valor va a 0.
                $htmlNutriente .= " hide";
                $valorEliminar = 0;
            } else {
                if ($flagRDI) {
                    $htmlNutriente .= " hide";
                    $valorEliminar = 0;
                }
            }
            $htmlNutriente .= "\" ";
            if ($flagEliminar == 1) {
                $htmlNutriente .= " checked=\"checked\"";
            }
            if ($flagAumentar == 1 || $flagNormal == 1) {
                $htmlNutriente .= " disabled=\"disabled\"";
            }
            $htmlNutriente .= " /> </div> ";
            $htmlNutriente .= " <div class=\"col-md-10 col-xs-10\"> <input type=\"number\" value=\"$valorEliminar\" name=\"valor_eliminar_$idNutriente\" id=\"valor_eliminar_$idNutriente\" step=\"0.01\" min=\"0\" data-id=\"$idNutriente\" class=\"form-control check-range";
            if ($flagEsencialBD == 1) {
                $htmlNutriente .= " hide";
            } else {
                if ($flagRDI) {
                    $htmlNutriente .= " hide";
                }
            }
            $htmlNutriente .= "\" ";
            if ($flagAumentar == 1 || $flagNormal == 1) {
                $htmlNutriente .= " disabled=\"disabled\"";
            }
            $htmlNutriente .= " /> </div>";
            $htmlNutriente .= " </td> ";
            // flag aumentar
            $htmlNutriente .= " <td class=\"text-center\">";
            $htmlNutriente .= " <input type=\"radio\" name=\"flag_$idNutriente" . "[]\" id=\"flag_aumentar_$idNutriente\" value=\"" . ACCION_AUMENTAR . "\" data-id=\"$idNutriente\" class=\"check-flag\" ";
            if ($flagAumentar == 1) {
                $htmlNutriente .= " checked=\"checked\"";
            }
            $htmlNutriente .= " /> ";
            $htmlNutriente .= " </td> ";
            // flag no aplica
            $htmlNutriente .= " <td class=\"text-center\">";
            $htmlNutriente .= " <input type=\"radio\" name=\"flag_$idNutriente" . "[]\" id=\"flag_no_aplica_$idNutriente\" value=\"" . ACCION_NOAPLICA . "\" data-id=\"$idNutriente\" class=\"check-flag\" ";
            if ($flagNormal == 1) {
                $htmlNutriente .= " checked=\"checked\"";
            }
            $htmlNutriente .= " /> ";
            $htmlNutriente .= " </td> ";
        }

        $enfermedad = "";
        $arreglo = $daoEnfermedad->obtenerEnfermedad($idEnfermedad);
        if (count($arreglo) > 0) {
            $enfermedad = $arreglo[0]['nombre'];
        }

        $daoEnfermedad = null;

    } else {

        header("Location: enfermedades.php");
        die();

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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="page-title">Relaci&oacute;n de <?php echo $enfermedad; ?> con nutrientes</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="enfermedad_nutriente.php" method="get" id="forma-buscar">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipo nutriente</label>
                                            <select name="tnut" id="tnut" class="form-control">
                                                <option value="">[Todos]</option>
                                            <?php echo $htmlTipoNutriente; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Clase</label>
                                            <select name="tcla" id="tcla" class="form-control">
                                                <option value="">[Todos]</option>
                                                <?php echo $htmlClase; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Categor&iacute;a</label>
                                            <select name="tcat" id="tcat" class="form-control">
                                                <option value="">[Todos]</option>
                                                <?php echo $htmlCategoria; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Familia</label>
                                            <select name="tfam" id="tfam" class="form-control">
                                                <option value="">[Todos]</option>
                                                <?php echo $htmlFamilia; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sub familia</label>
                                            <select name="tsfam" id="tsfam" class="form-control">
                                                <option value="">[Todos]</option>
                                                <?php echo $htmlSubfamilia; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Esenciales</label>
                                            <select name="fese" id="fese" class="form-control">
                                                <option value="">[Todos]</option>
                                                <?php echo $htmlFlagEsencial; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nutriente</label>
                                            <input type="text" name="nnut" id="nnut" class="form-control" value="<?php echo $nombreNutriente ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Aplica</label>
                                            <select name="fapl" id="fapl" class="form-control">
                                                <?php echo $htmlAplica; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12 text-right">
                                            <input type="button" id="buscar" value="Buscar" class="btn btn-default" />
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="id" name="id" value="<?php echo $idEnfermedad;?>" />
                            </form>
                            <form action="enfermedad_nutriente.php" method="post" id="forma">
                                <div class="table-responsive">
                                    <table id="nutriente-table" class="table table-striped display">
                                        <thead>
                                            <tr>
                                                <th class="text-center"> Nutriente / Tipo / Clase </th>
                                                <th class="text-center"> Unidad </th>
                                                <th class="text-center"> Restringir </th>
                                                <th class="text-center"> Eliminar </th>
                                                <th class="text-center"> Aumentar </th>
                                                <th class="text-center"> No aplica </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $htmlNutriente; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                    <?php if ($contadorItems > 0) {?>
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                    <?php }?>
                                    </div>
                                </div>
                                <input type="hidden" id="id_enfermedad" name="id_enfermedad" value="<?php echo $idEnfermedad;?>" />
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#nav-health').addClass('active');
                var table = $('#nutriente-table')
                            .DataTable({
                                "columns": [
                                    { "width": "30%" },
                                    { "width": "10%" },
                                    { "width": "17%" },
                                    { "width": "17%" },
                                    { "width": "13%" },
                                    { "width": "13%" }
                                ]
                                , "bSort": false
                                , "bPaginate": false
                                , "bFilter": false
                            })
            });

            $('#volver').click(function() {
                location.href = 'enfermedades.php';
            });

            $("#buscar").click(function() {
                $('#forma-buscar').submit();
            });

            $("#grabar").click(function() {
                $('#forma').submit();
            });

            $('#tnut').change(function() {
                incializaTipoClase();
                incializaTipoCategoria();
                incializaTipoFamilia();
                incializaTipoSubfamilia();
                if ($(this).val() != '') {
                    var sel_opn = $('#tcla'); 
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
                }
            })

            $('#tcla').change(function() {
                incializaTipoCategoria();
                incializaTipoFamilia();
                incializaTipoSubfamilia();
                if ($(this).val() != '') {
                    var sel_opn = $('#tcat'); 
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
                }
            })

            $('#tcat').change(function() {
                incializaTipoFamilia();
                incializaTipoSubfamilia();
                if ($(this).val() != '') {
                    var sel_opn = $('#tfam'); 
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
                }
            })

            $('#tfam').change(function() {
                incializaTipoSubfamilia();
                if ($(this).val() != '') {
                    var sel_opn = $('#tsfam'); 
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
                }
            })

            function incializaTipoClase() {
                var sel_opn = $('#tcla'); 
                $('option', sel_opn).remove();
                var option = new Option("[Todos]", "");
                sel_opn.append($(option));
            }

            function incializaTipoCategoria() {
                var sel_opn = $('#tcat'); 
                $('option', sel_opn).remove();
                var option = new Option("[Todos]", "");
                sel_opn.append($(option));
            }

            function incializaTipoFamilia() {
                var sel_opn = $('#tfam'); 
                $('option', sel_opn).remove();
                var option = new Option("[Todos]", "");
                sel_opn.append($(option));
            }

            function incializaTipoSubfamilia() {
                var sel_opn = $('#tsfam'); 
                $('option', sel_opn).remove();
                var option = new Option("[Todos]", "");
                sel_opn.append($(option));
            }
        </script>

        <script type="text/javascript">
            $('.check-flag').click(function() {
                var id_nutriente = $(this).attr('data-id');
                $('#valor_eliminar_' + id_nutriente).prop('disabled', false);
                $('#valor_restringir_' + id_nutriente).prop('disabled', false);
                if ($(this).is(':radio')) {
                    $('#valor_eliminar_' + id_nutriente).prop('disabled', true);
                    $('#valor_restringir_' + id_nutriente).prop('disabled', true);
                    $('#flag_eliminar_' + id_nutriente).prop('checked', false);
                    $('#flag_restringir_' + id_nutriente).prop('checked', false);
                } else {
                    $('#flag_aumentar_' + id_nutriente).prop('checked', false);
                    $('#flag_no_aplica_' + id_nutriente).prop('checked', false);
                }
            });

            $('.check-range').blur(function () {
                var id_nutriente = $(this).attr('data-id');
                var flagRestringir = $('#flag_restringir_' + id_nutriente).prop('checked');
                var flagEliminar = $('#flag_eliminar_' + id_nutriente).prop('checked');
                if (flagRestringir && flagEliminar) {
                    var valorMinimo = parseInt($('#valor_restringir_' + id_nutriente).val(), 10);
                    var valorMaximo = parseInt($('#valor_eliminar_' + id_nutriente).val(), 10);
                    if (valorMinimo > valorMaximo) {
                        $.toast({
                            heading: 'Error en el rango',
                            text: 'El rango de valores está errado.',
                            position: 'top-right',
                            loaderBg:'#ff6849',
                            icon: 'error',
                            hideAfter: 3000, 
                            stack: 6
                        });
                    }
                }
            });
        </script>
    </body>
</html>