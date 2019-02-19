<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
	require('inc/dao_intolerancia.php');
	require('inc/dao_insumo.php');

	if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
		 
		$idIntolerancia = $_GET["id"];
		
		$daoIntolerancia = new DaoIntolerancia();
		$intolerancia = "";
        $arreglo = $daoIntolerancia-> obtenerIntolerancia($idIntolerancia);
        if (count($arreglo) > 0) {
            $intolerancia = $arreglo[0]['nombre'];
        }
        $daoIntolerancia = null;
		
		$daoInsumo = new DaoInsumo();

		$htmlListaInsumos = "";
		$arregloListaInsumos = $daoInsumo-> listarInsumosPorIntolerancia($idIntolerancia);
		$cantidadInsumoIntolerancia = count($arregloListaInsumos);
		foreach ($arregloListaInsumos as $item) {
			$idInsumo  = $item['id_insumo'];
			$nombreInsumo  = $item['insumo'];
			$nombreIntolerancia  = $item['intolerancia'];
			$accion  = 'No aplica';
			if($item['accion'] != ''){
				$accion  = $item['accion'];
			}
			$htmlListaInsumos .= '<tr data-id="' . $idInsumo . '" >';
			$htmlListaInsumos .= '<td class="td-insumo-edit">' . $nombreInsumo . '</td>';
			$htmlListaInsumos .= '<td class="td-insumo-edit">' . $nombreIntolerancia . '</td>';
			$htmlListaInsumos .= '<td class="td-insumo-edit">' . $accion . '</td>';
			$htmlListaInsumos .= '<td class="td-insumo-edit" ><i class="fa fa-edit"></i></td>';
			$htmlListaInsumos .= '</tr>';
		}
		
		$daoInsumo = null; 
		
	} else {

		header("Location: intolerancia.php");
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
                        <div class="col-lg-6 col-md-6 col-sm6 col-xs-12">
                            <h4 class="page-title">Relaci&oacute;n de <?php echo $intolerancia; ?> con insumos</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
						   <input type="hidden" id="id_intolerancia" name="id_intolerancia" value="<?php echo $idIntolerancia;?>" />
							<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="myLargeModalLabel">Insumos</h4>
										</div>
                                        <div class="modal-body">
											<div class="table-responsive">
												<table id="tabla-insumo" class="table table-striped display" style="cursor:pointer;">
													<thead>
														<tr>
															<th> ID </th>
															<th> USDA </th>
															<th> Tipo Alimento</th>
															<th> Insumo </th>
															<th></th>
														</tr>
													</thead>
												</table>
											</div>                                           
                                        </div>
                                    </div>
									<input type="hidden" id="nutriente" name="nutriente" />
									<input type="hidden" id="beneficio" name="beneficio" />
									<input type="hidden" id="tipoAlimento" name="tipoAlimento" />
									<input type="hidden" id="orac" name="orac" />
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
							<div class="row">
								<div class="col-md-12 text-right">
									<input type="button" id="volver" value="Volver" class="btn btn-default" />
									&nbsp;&nbsp;
									<a href="javascript:;" class="btn btn-info" data-toggle="modal" data-target=".bs-example-modal-lg">Buscar Insumo</a>
								</div>
							</div>
                        </div>
						<div class="white-box">
							<div class="table-responsive">
								<table id="tabla-insumo-intolerancia" class="table table-striped display" style="cursor:pointer;">
									<thead>
										<tr>
											<th> Insumo </th>
											<th> Intolerancia </th>
											<th> Acción </th>
											<th></th>	
										</tr>
									</thead>
									<tbody>
										 <?php echo $htmlListaInsumos; ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="4">Registros: <?php echo $cantidadInsumoIntolerancia; ?></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
		<script type="text/javascript">
		    
			$('.td-insumo-edit').click(function() {
				var id_intolerancia = $("#id_intolerancia").val();
                var id_insumo = $(this).closest('tr').attr('data-id');
				location.href = "intolerancia_insumo_detalle.php?int=" + id_intolerancia + '&ins=' + id_insumo;
               
            });

			$('body').on('click', 'td.td-insumo', function() {
				var id_intolerancia = $("#id_intolerancia").val();
                var id_insumo = $(this).closest('tr').attr('data-id');
				location.href = "intolerancia_insumo_detalle.php?int=" + id_intolerancia + '&ins=' + id_insumo;
            });

			$('#volver').click(function() {
				location.href = "intolerancia.php";
			});

			$(document).ready(function() {
                $('#nav-health').addClass('active');
                var table = $('#tabla-insumo')
							.DataTable({
								"columns": [
									{ "width": "10%" },
									{ "width": "10%" },
									{ "width": "35%" },
									{ "width": "35%" },
									{ "width": "10%" }
								],
								"aoColumnDefs" : [ 
									{"aTargets" : [0], "sClass": "td-insumo text-center"},
									{"aTargets" : [1], "sClass": "td-insumo"},
									{"aTargets" : [2], "sClass": "td-insumo"},
									{"aTargets" : [3], "sClass": "td-insumo"},
									{"aTargets" : [4], "sClass": "td-insumo text-center", "data": null, "defaultContent": "<i class=\"glyphicon glyphicon-edit\"></i>"}
								],
								"createdRow": function (row, data, index) {
									$(row).attr('data-id', data[0])
								},
								"bLengthChange": false,
								"language": {
										"search":"Insumo : ",
										"lengthMenu": "Mostrar _MENU_ registros por p&aacute;gina",
										"zeroRecords": "No hay datos disponibles en la tabla",
										"info": "Mostrando _PAGE_ de _PAGES_",
										"infoEmpty": "No hay registros disponibles.",
										"infoFiltered": "(filtrado de _MAX_ registros totales)",
										"paginate": {
											"first":      "Primero",
											"last":       "Ultimo",
											"next":       "Siguiente",
											"previous":   "Anterior"
										}
								},
								"lengthMenu": [[25, 50, 100, 500], [25, 50, 100, 500]],
								"processing": true,
								"serverSide": true,
								"ajax": {
									"url": "service/getinputlisttodatatable.php",
									"data" : function ( d ) {
										d.nutriente = $('#nutriente').val(),
										d.beneficio = $('#beneficio').val(),
										d.tipoAlimento = $('#tipoAlimento').val(),
										d.orac = $('#orac').val()
									}
								}
							});
			});
		    
		</script>
    </body>
</html>