<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
	require('inc/constante_enfermedad.php');
	require('inc/dao_intolerancia.php');
	require('inc/dao_insumo.php');

	if (isset($_POST["int"]) && isset($_POST["ins"]) && isset($_POST["accion"])) {
		
		$idIntolerancia = $_POST["int"];
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
		$daoInsumo->grabarIntoleranciaXInsumo($idIntolerancia, $idInsumo, $accion, $prioridad, $usuario);
		
		$daoInsumo = null;
		
		 header("Location: intolerancia_insumo.php?id=$idIntolerancia");
         die();
		 
	}else if ((isset($_GET["int"]) && is_numeric($_GET["int"])) && (isset($_GET["ins"]) && is_numeric($_GET["ins"]))){
		 
		$idIntolerancia = $_GET["int"];
		
		$daoIntolerancia = new DaoIntolerancia();
		$intolerancia = "";
        $arreglo = $daoIntolerancia-> obtenerIntolerancia($idIntolerancia);
        if (count($arreglo) > 0) {
            $intolerancia = $arreglo[0]['nombre'];
        }
        $daoIntolerancia = null;
		
		$idInsumo = $_GET["ins"];
		$daoInsumo = new DaoInsumo();
		$insumo = "";
        $arreglo = $daoInsumo-> obtenerInsumo($idInsumo);
        if (count($arreglo) > 0) {
            $insumo = $arreglo[0]['nombre'];
        }
		
		$accion = "";
		$arregloInsumoInto = $daoInsumo-> obtenerInsumoPorIntolerancia($idIntolerancia,$idInsumo);
		if (count($arregloInsumoInto) > 0) {
			$accion = $arregloInsumoInto[0]['accion'];			
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
                        <div class="col-md-12">
                            <h4 class="page-title">Relaci&oacute;n de <?php echo $intolerancia; ?> con <?php echo $insumo; ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                       	<div class="white-box">
                            <form action="intolerancia_insumo_detalle.php" method="post" id="form-save">
								<input type="hidden" id="int" name="int" value="<?php echo $idIntolerancia;?>" />
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
				var id_intolerancia = $("#int").val();
                location.href = 'intolerancia_insumo.php?id=' + id_intolerancia;
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