<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_nutriente.php');
    require('inc/dao_nutriente.php');

    $longitudPaginacion = "";
    $numeroPagina = "";
    $palabraSearch = "";
    $idTipoNutriente = "";
    $idTipoClase = "";
    $idTipoCategoria = "";
    $idTipoFamilia = "";
    $idTipoSubfamilia = "";
    $flagEsencial = "";
    $estadoBuscar = LISTA_ACTIVO;
 
    if (!isset($_GET['a'])) {
        if (isset($_SESSION['NNP'])) {
            $numeroPagina = $_SESSION['NNP'];
        }
        if (isset($_SESSION['NPS'])) {
            $palabraSearch = $_SESSION['NPS'];
        }
        if (isset($_SESSION['NLP'])) {
            $longitudPaginacion = $_SESSION['NLP'];
        }
        if (isset($_SESSION['NTNUT'])) {
            $idTipoNutriente = $_SESSION['NTNUT'];
        }
        if (isset($_SESSION['NTCLA'])) {
            $idTipoClase = $_SESSION['NTCLA'];
        }
        if (isset($_SESSION['NTCAT'])) {
            $idTipoCategoria = $_SESSION['NTCAT'];
        }
        if (isset($_SESSION['NTFAM'])) {
            $idTipoFamilia = $_SESSION['NTFAM'];
        }
        if (isset($_SESSION['NTSFAM'])) {
            $idTipoSubfamilia = $_SESSION['NTSFAM'];
        }
        if (isset($_SESSION['NFE'])) {
            $flagEsencial = $_SESSION['NFE'];
        }
        if (isset($_SESSION['NEST'])) {
            $estadoBuscar = $_SESSION['NEST'];
        }
    }

    if ($longitudPaginacion == "") {
        $longitudPaginacion = 25;
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

    $daoNutriente = null;

    $htmlFlagEsencial = "";
    foreach ($arregloNutrienteEsencialBusqueda as $item) {
        $flag = $item['codigo'];
        $htmlFlagEsencial .= " <option value=\"$flag\"";
        if ($flag == $flagEsencial) {
            $htmlFlagEsencial .= " selected=\"selected\"";
        }
        $htmlFlagEsencial .= ">" . $item['descripcion'] . "</option> ";
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
                        <div class="col-lg-6 col-md-6 col-sm6 col-xs-12">
                            <h4 class="page-title">Nutrientes</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-nutriente" value="Nuevo nutriente" class="btn btn-primary nuevo-nutriente" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="service/getnutrientlisttodatatable.php" id="forma" method="get">
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" name="est" id="est" <?php if ($estadoBuscar == 1) { echo ' checked="checked"';} ?> value="1" />
                                                <label for="estado">Activos</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 text-right">
                                        <div class="form-group">
                                            <input type="button" id="buscar-nutriente" class="btn btn-default" value="Buscar" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br />
                            <div class="table-responsive">
                                <table id="nutriente-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> Nombre </th>
                                        <th> Nombre [ing]</th>
                                        <th> Tipo </th>
                                        <th> Clase </th>
                                        <th> Categor&iacute;a </th>
                                        <th> Familia </th>
                                        <th> Sub familia </th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <input type="hidden" name="palabra_search" id="palabra-search" value="<?php echo $palabraSearch; ?>" />
        <input type="hidden" name="longitud_paginacion" id="longitud-paginacion" value="<?php echo $longitudPaginacion; ?>" />
        <input type="hidden" name="numero_pagina" id="numero-pagina" value="<?php echo $numeroPagina; ?>" />
        <script type="text/javascript">
             $('body').on('click', 'td.td-nutriente', function() {
                mapearConfiguracionDT();
                var id_nutriente = $(this).closest('tr').attr('data-id');
                location.href = "nutriente_editar.php?id=" + id_nutriente;
            });

            $('.nuevo-nutriente').click(function() {
                mapearConfiguracionDT();
                location.href = "nutriente_crear.php";
            });

            $('#nutriente-table').change(function (e) {
                mapearConfiguracionDT();
                table.fnDraw();
            });

            $(document).ready(function() {
                $('#nav-health').addClass('active');

                var table = $('#nutriente-table')
                .DataTable({
                    "columns": [
                        { "width": "10%" },
                        { "width": "20%" },
                        { "width": "20%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" }
                    ],
                    "aoColumnDefs" : [ 
                        {"aTargets" : [0], "sClass": "td-nutriente text-center"},
                        {"aTargets" : [1], "sClass": "td-nutriente"},
                        {"aTargets" : [2], "sClass": "td-nutriente"},
                        {"aTargets" : [3], "sClass": "td-nutriente text-center"}, 
                        {"aTargets" : [4], "sClass": "td-nutriente text-center"},
                        {"aTargets" : [5], "sClass": "td-nutriente text-center"},
                        {"aTargets" : [6], "sClass": "td-nutriente text-center"},
                        {"aTargets" : [7], "sClass": "td-nutriente text-center"}
                    ],
                    "createdRow": function (row, data, index) {
                        $(row).attr('data-id', data[0])
                    },
                    "lengthMenu": [[25, 50, 100, 500], [25, 50, 100, 500]],
                    "processing": true,
                    "serverSide": true,
                    <?php
                        if ($palabraSearch != "") {
                            echo "\"oSearch\": {\"sSearch\": \"" . $palabraSearch . "\"},\n";
                        }
                        if ($longitudPaginacion != "") {
                            echo "\"pageLength\": " . $longitudPaginacion . ",\n";
                        }
                        if ($numeroPagina != "") {
                            echo "\"displayStart\": " . ($numeroPagina * $longitudPaginacion) . ",\n";
                        }
                    ?>
                    "ajax": {
                        "url": "service/getnutrientlisttodatatable.php?",
                        "data" : function ( d ) {
                            d.tipoNutriente = $('#tnut').val(),
                            d.tipoClase = $('#tcla').val(),
                            d.tipoCategoria = $('#tcat').val(),
                            d.tipoFamilia = $('#tfam').val(),
                            d.tipoSubfamilia = $('#tsfam').val(),
                            d.flagEsencial = $('#fese').val(),
                            d.estadoBuscar = ($('#est').is(":checked")) ? "1" : "0"
                        }
                    }
                });

                $('#buscar-nutriente').click(function() {
                    table.draw();
                })

                table.on('page.dt', function () {
                    var info = table.page.info();
                    $('#numero-pagina').val(info.page);
                });

                table.on('search.dt', function () {
                    $('#palabra-search').val(table.search());
                });

                table.on('length.dt', function (e, settings, len) {
                    $('#longitud-paginacion').val(len);
                });

            } );
        </script>

        <script type="text/javascript">
            function mapearConfiguracionDT() {
                var numeroPagina = $('#numero-pagina').val();
                var palabraSearch = $('#palabra-search').val();
                var longitudPaginacion = $('#longitud-paginacion').val();
                var tipoNutriente = $('#tnut').val();
                var tipoClase = $('#tcla').val();
                var tipoCategoria = $('#tcat').val();
                var tipoFamilia = $('#tfam').val();
                var tipoSubfamilia = $('#tsfam').val();
                var flagEsencial = $('#fese').val();
                var estadoBuscar = ($('#est').is(":checked")) ? "1" : "0";
                var param = {
                    "numeroPagina": numeroPagina
                    , "palabraSearch": palabraSearch
                    , "longitudPaginacion": longitudPaginacion
                    , "tipoNutriente": tipoNutriente
                    , "tipoClase": tipoClase
                    , "tipoCategoria": tipoCategoria
                    , "tipoFamilia": tipoFamilia
                    , "tipoSubfamilia": tipoSubfamilia
                    , "flagEsencial": flagEsencial
                    , "estadoBuscar": estadoBuscar
                };
                var paramJSON = JSON.stringify(param);
                $.ajax({
                    type: 'POST',
                    url: 'service/setsessionnutrientpage.php',
                    data: paramJSON,
                    dataType: 'json',
                    error: function(errorResult) {
                        console.log('Ha ocurrido un error ' + errorResult.error());
                    },
                    success: function (result) {
                        console.log(result);
                    }
                });
            }
        </script>

        <script type="text/javascript">
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
    </body>
</html>