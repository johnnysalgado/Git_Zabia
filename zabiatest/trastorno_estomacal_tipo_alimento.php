<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
	require('inc/constante_enfermedad.php');
	require('inc/dao_trastorno_estomacal.php');

	if (isset($_POST["id"])) {
		
		$idTrastornoEstomacal = $_POST["id"];
        $usuario = $_SESSION["U"];
       
        $daoTrastornoEstomacal = new DaoTrastornoEstomacal();
        foreach ($_POST as $param_name => $param_val) {
            if (strpos($param_name, "_") > -1) {
                $pos = strpos($param_name, "_");
				$idTipoAlimento = substr($param_name, $pos + 1, strlen($param_name) - $pos - 1);
                if (is_numeric($idTipoAlimento)) {
                    $prioridad = 1;
                    if ($param_val == ACCION_RESTRINGIR) {
                        $prioridad = -1;
                    } else if ($param_val == ACCION_ELIMINAR) {
                        $prioridad = -2;
                    } else if ($param_val == ACCION_AUMENTAR) {
                        $prioridad = 2;
                    }
                    $daoTrastornoEstomacal->grabarTrastornoEstomacalTipoAlimento($idTrastornoEstomacal, $idTipoAlimento, $param_val, $prioridad, $usuario);
                }
            }
        }
        $daoTrastornoEstomacal = null;

        header("Location: trastorno_estomacal.php");
        die();
		 
	}else if (isset($_GET["id"]) && is_numeric($_GET["id"])){
		 
		$idTrastornoEstomacal = $_GET["id"];
		
		$daoTrastornoEstomacal = new DaoTrastornoEstomacal();
		$trastornoEstomacal = "";
        $arreglo = $daoTrastornoEstomacal-> obtenerTrastornoEstomacal($idTrastornoEstomacal);
        if (count($arreglo) > 0) {
            $trastornoEstomacal = $arreglo[0]['nombre'];
        }     
		
		$htmlTipoAlimento = "";
		$arregloTipoAlimento = $daoTrastornoEstomacal-> listarTipoAlimentosPorTrastornoEstomacal ($idTrastornoEstomacal);
		
		foreach ($arregloTipoAlimento as $item) {
			
			$idTipoAlimento = $item['id_tipo_alimento'];
			$nombreTipoAlimento = $item['tipo_alimento'];
			 $accion = $item['accion'];
            if ($accion == null) $accion = "";
            $htmlTipoAlimento .= "<div class=\"row\"> <div class=\"col-md-5\"> <span class=\"text-uppercase\">$nombreTipoAlimento</span> </div> ";
            $htmlTipoAlimento .= " <div class=\"col-md-7\"> <div class=\"radio radio-success\">";
            $htmlTipoAlimento .= "<div class=\"col-md-3\"> <input type=\"radio\" name=\"tipoAlimento_$idTipoAlimento\" id=\"tipoAlimento_$idTipoAlimento\" ";
            if ($accion == ACCION_ELIMINAR) {
                $htmlTipoAlimento .= " checked=\"checked\"";
            }
            $htmlTipoAlimento .= " value=\"" . ACCION_ELIMINAR . "\" /> <label>Elimina</label> </div>";
            $htmlTipoAlimento .= "<div class=\"col-md-3\"> <input type=\"radio\" name=\"tipoAlimento_$idTipoAlimento\" id=\"tipoAlimento_$idTipoAlimento\" ";
            if ($accion == ACCION_NOAPLICA) {
                $htmlTipoAlimento .= " checked=\"checked\"";
            }
            $htmlTipoAlimento .= " value=\"" . ACCION_NOAPLICA . "\" /> <label>No aplica</label> </div>";
            $htmlTipoAlimento .= " </div> </div>";
            $htmlTipoAlimento .= " </div>";
            $htmlTipoAlimento .= " <hr />";
        }
		
		$daoTrastornoEstomacal = null;
		 
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="page-title">Relaci&oacute;n de <?php echo $trastornoEstomacal; ?> con tipos de alimentos</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">                            
                            <form action="trastorno_estomacal_tipo_alimento.php" method="post" id="frm-tipo">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
								<hr/>
								<?php echo $htmlTipoAlimento; ?>
                                
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                        <input type="button" id="grabar" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <input type="hidden" id="id" name="id" value="<?php echo $idTrastornoEstomacal;?>" />
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
		<script type="text/javascript">
		
			$(".btn-success").click(function() {
                $('#frm-tipo').submit();
			});
			
			$('.btn-default').click(function() {
                location.href = 'trastorno_estomacal.php';
            });
		
			$(document).ready(function() {
                $('#nav-health').addClass('active');
			});

		</script>
    </body>
</html>