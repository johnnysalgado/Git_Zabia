<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/dao_insumo.php');
    require('inc/dao_nutriente.php');

    $numeroPagina = "";
    $palabraSearch = "";
    $nutriente = "";
    $tipoAlimento = "";
    $beneficio = "";
    $orac = "";

    if (isset($_SESSION['INP'])) {
        $numeroPagina = $_SESSION['INP'];
    }
    if (isset($_SESSION['IPS'])) {
        $palabraSearch = $_SESSION['IPS'];
    }
    if (isset($_SESSION['ILP'])) {
        $longitudPaginacion = $_SESSION['ILP'];
    }
    if (isset($_SESSION['IN'])) {
        $nutriente = $_SESSION['IN'];
    }
    if (isset($_SESSION['ITP'])) {
        $tipoAlimento = $_SESSION['ITP'];
    }
    if (isset($_SESSION['IB'])) {
        $beneficio = $_SESSION['IB'];
    }
    if (isset($_SESSION['IO'])) {
        $orac = $_SESSION['IO'];
    }

    if (isset($_GET['a'])) {
        $longitudPaginacion = "";
        $numeroPagina = "";
        $palabraSearch = "";
        $nutriente = "";
        $tipoAlimento = "";
        $beneficio = "";
        $orac = "";
    }
    if ($longitudPaginacion == "") {
        $longitudPaginacion = 25;
    }

    $htmlNutriente = "";
    $htmlTipoAlimento = "";
    $htmlBeneficio = "";

    $daoNutriente = new DaoNutriente();
    $arregloNutriente = $daoNutriente->listarNutriente();
    foreach ($arregloNutriente as $item) {
        $id = $item['id_nutriente'];
        $nombre = $item['nombre'];
        $htmlNutriente .= "<option value=\"$id\"";
        if ($nutriente == $id) {
            $htmlNutriente .= " selected=\"selected\"";
        }
        $htmlNutriente .= ">$nombre</option>\n";
    }
    $daoNutriente = null;

    $daoInsumo = new DaoInsumo();

    $arregloTipoAlimento = $daoInsumo->listarTipoAlimento(LISTA_ACTIVO);
    foreach ($arregloTipoAlimento as $item) {
        $id = $item['id_tipo_alimento'];
        $nombre = $item['nombre'];
        $htmlTipoAlimento .= "<option value=\"$id\"";
        if ($tipoAlimento == $id) {
            $htmlTipoAlimento .= " selected=\"selected\"";
        }
        $htmlTipoAlimento .= ">$nombre</option>\n";
    }

    $arregloBeneficio = $daoInsumo->listarBeneficio();
    foreach ($arregloBeneficio as $item) {
        $id = $item['id_beneficio'];
        $nombre = $item['nombre'];
        $htmlBeneficio .= "<option value=\"$id\"";
        if ($beneficio == $id) {
            $htmlBeneficio .= " selected=\"selected\"";
        }
        $htmlBeneficio .= ">$nombre</option>\n";
    }

    $daoInsumo = null;
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
                            <h4 class="page-title">Insumos</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nuevo-insumo" value="Nuevo insumo" class="btn btn-primary nuevo-insumo" />
                        </div>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="service/getinputlisttodatatable.php" id="forma" method="get">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Nutriente</label>
                                            <select name="nutriente" id="nutriente" class="form-control">
                                                <option value="">[Todos]</option>
                                                <?php echo $htmlNutriente; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Tipo de alimento</label>
                                            <select name="tipoAlimento" id="tipoAlimento" class="form-control">
                                                <option value="">[Todos]</option>
                                                <?php echo $htmlTipoAlimento; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Beneficio</label>
                                            <select name="beneficio" id="beneficio" class="form-control">
                                                <option value="">[Todos]</option>
                                                <?php echo $htmlBeneficio; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>ORAC</label>
                                            <input type="number" name="orac" id="orac" class="form-control" step="0.0001" min="0" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 text-right">
                                        <div class="form-group">
                                            <input type="button" id="buscar-insumo" class="btn btn-default" value="Buscar" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br />
                            <div class="table-responsive">
                                <table id="insumo-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> USDA </th>
                                        <th> Tipo alimento </th>
                                        <th> Nombre </th>
                                        <th> Ingl&eacute;s </th>
                                        <th> Activo </th>
                                        <th> Imagen </th>
                                        <th> Nutrientes </th>
                                        <th> Medidas </th>
                                        <th> ORAC </th>
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
            $('body').on('click', 'td.td-insumo', function() {
                var id_insumo = $(this).closest('tr').attr('data-id');
                mapearConfiguracionDT();
                location.href = "insumo_editar.php?id=" + id_insumo;
            });

             $('body').on('click', 'td.td-nutriente', function() {
                var id_insumo = $(this).closest('tr').attr('data-id');
                mapearConfiguracionDT();
                location.href = "insumo_nutriente.php?id=" + id_insumo;
            });

             $('body').on('click', 'td.td-medida', function() {
                var id_insumo = $(this).closest('tr').attr('data-id');
                mapearConfiguracionDT();
                location.href = "insumo_medida.php?id=" + id_insumo;
            });

             $('body').on('click', 'td.td-antioxidante', function() {
                var id_insumo = $(this).closest('tr').attr('data-id');
                mapearConfiguracionDT();
                location.href = "insumo_orac.php?id=" + id_insumo;
            });

/*
             $('body').on('click', 'td.td-resumen', function() {
                var id_insumo = $(this).closest('tr').attr('data-id');
                mapearConfiguracionDT();
                location.href = "insumo_resumen.php?id=" + id_insumo;
            });
*/
            $('.nuevo-insumo').click(function() {
                mapearConfiguracionDT();
                location.href = "insumo_crear.php";
            });

            $('#insumo-table').change(function (e) {
                mapearConfiguracionDT();
                table.fnDraw();
            });

            $(document).ready(function() {
                $('#nav-ingredient').addClass('active');

                var table = $('#insumo-table')
                .DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "5%" },
                        { "width": "15%" },
                        { "width": "15%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" },
                        { "width": "10%" }
                    ],
                    "aoColumnDefs" : [ 
                        {"aTargets" : [0], "sClass": "td-insumo text-center"},
                        {"aTargets" : [1], "sClass": "td-insumo text-center"},
                        {"aTargets" : [2], "sClass": "td-insumo"},
                        {"aTargets" : [3], "sClass": "td-insumo"},
                        {"aTargets" : [4], "sClass": "td-insumo"},
                        {"aTargets" : [5], "sClass": "td-insumo text-center"}, 
                        {"aTargets" : [6], "sClass": "td-insumo text-center"},
                        {"aTargets" : [7], "sClass": "td-nutriente text-center", "data": null, "defaultContent": "<i class=\"glyphicon glyphicon-leaf\"></i>"},
                        {"aTargets" : [8], "sClass": "td-medida text-center", "data": null, "defaultContent": "<i class=\"glyphicon glyphicon-cog\"></i>"},
                        {"aTargets" : [9], "sClass": "td-antioxidante text-center", "data": null, "defaultContent": "<i class=\"glyphicon glyphicon-tint\"></i>"}
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
                        "url": "service/getinputlisttodatatable.php?",
                        "data" : function ( d ) {
                            d.nutriente = $('#nutriente').val(),
                            d.beneficio = $('#beneficio').val(),
                            d.tipoAlimento = $('#tipoAlimento').val(),
                            d.orac = $('#orac').val()
                        }
                    }
                });

                $('#buscar-insumo').click(function() {
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

            });

        </script>

        <script type="text/javascript">
            function mapearConfiguracionDT() {
                var numeroPagina = $('#numero-pagina').val();
                var palabraSearch = $('#palabra-search').val();
                var longitudPaginacion = $('#longitud-paginacion').val();
                var nutriente = $('#nutriente').val();
                var tipoAlimento = $('#tipoAlimento').val();
                var beneficio = $('#beneficio').val();
                var orac = $('#orac').val();
                var param = {
                    "numeroPagina": numeroPagina
                    , "palabraSearch": palabraSearch
                    , "longitudPaginacion": longitudPaginacion
                    , "nutriente": nutriente
                    , "tipoAlimento": tipoAlimento
                    , "beneficio": beneficio
                    , "orac": orac
                };
                var paramJSON = JSON.stringify(param);
                $.ajax({
                    type: 'POST',
                    url: 'service/setsessioninsumopage.php',
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
    </body>
</html>