<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
	require('inc/constante_enfermedad.php');
	require('inc/dao_trastorno_estomacal.php');
	require('inc/dao_insumo.php');

	if (isset($_POST["tes"]) && isset($_POST["ins"]) && isset($_POST["accion"])) {
		
		$idTrastornoEstomacal = $_POST["tes"];
		$idInsumo = $_POST["ins"];
		$accion = $_POST["accion"];
		$usuario = $_SESSION["U"];

		$prioridad = 1;
		if ($accion == ACCION_RESTRINGIR) {
			$prioridad = -1;
		} else if ($accion == ACCION_ELIMINAR) {
			$prioridad = -2;
		} else if ($accion == ACCION_AUMENTAR) {
			$prioridad = 2;
		}
		
		$daoInsumo = new DaoInsumo();
		$daoInsumo->grabarTrastornoEstomacalPorInsumo($idTrastornoEstomacal, $idInsumo, $accion, $prioridad, $usuario);
		
		$daoInsumo = null;
		
		 header("Location: trastorno_estomacal_insumo.php?id=$idTrastornoEstomacal");
         die();
		 
	}else if ((isset($_GET["tes"]) && is_numeric($_GET["tes"])) && (isset($_GET["ins"]) && is_numeric($_GET["ins"]))){
		 
		$idTrastornoEstomacal = $_GET["tes"];
		
		$daoTrastornoEstomacal = new DaoTrastornoEstomacal();
		$trastornoEstomacal = "";
        $arreglo = $daoTrastornoEstomacal-> obtenerTrastornoEstomacal($idTrastornoEstomacal);
        if (count($arreglo) > 0) {
            $trastornoEstomacal = $arreglo[0]['nombre'];
        }
        $daoTrastornoEstomacal = null;
		
		$idInsumo = $_GET["ins"];
		$daoInsumo = new DaoInsumo();
		$insumo = "";
        $arreglo = $daoInsumo-> obtenerInsumo($idInsumo);
        if (count($arreglo) > 0) {
            $insumo = $arreglo[0]['nombre'];
        }
		$accion = "";
		$arregloInsumoTrastornoEstomacal = $daoInsumo-> obtenerInsumoPorTrastornoEstomacal ($idTrastornoEstomacal, $idInsumo);
		if (count($arregloInsumoTrastornoEstomacal) > 0) {
			$accion = $arregloInsumoTrastornoEstomacal[0]['accion'];			
		}
        $daoInsumo = null;		
				
	} else {

		header("Location: trastorno_estomacal.php");
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
                        <div class="col-md-12">
                            <h4 class="page-title">Relaci&oacute;n de <?php echo $trastornoEstomacal; ?> con <?php echo $insumo; ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                       	<div class="white-box">
                            <form action="trastorno_estomacal_insumo_detalle.php" method="post" id="form-save">
								<input type="hidden" id="tes" name="tes" value="<?php echo $idTrastornoEstomacal;?>" />
								<input type="hidden" id="ins" name="ins" value="<?php echo $idInsumo;?>" />
								<input type="hidden" id="accion" name="accion" value="<?php echo $accion;?>" />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Insumo :</label>
                                            <h4><?php echo $insumo; ?></h4>
                                        </div>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-md-12">
									    <label>Acción :</label>
                                        <div class="radio radio-success">
											<div class="col-md-3"> 
												<input type="radio" name="accion" id="accion_eliminar" value="eliminar">
												<label>Elimina</label>
											</div>
											<div class="col-md-3">
												<input type="radio" name="accion" id="accion_noaplica" value="">
												<label>No aplica</label>
											</div>
										</div>
                                    </div>
								</div>
								<div class="row">
                                    <div class="col-md-12 mt-5">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
							</form>
						</div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
		<script type="text/javascript">
		    $(".btn-success").click(function() {
                $('#form-save').submit();
			});

			$('#volver').click(function() {
				var id_trastorno_estomacal = $("#int").val();
                location.href = 'trastorno_estomacal_insumo.php?id=' + id_trastorno_estomacal;
            });	

			$("input[name='accion']").click(function() {
				$("#accion").val(this.value)
			})			

			$(document).ready(function() {
                $('#nav-health').addClass('active');
				var accion = $("#accion").val() === '' ? 'noaplica':$("#accion").val();
				$('#accion_' + accion ).prop("checked", true);
			});
		    
		</script>
    </body>
</html>