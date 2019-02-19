<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante.php');
    require('inc/constante_receta.php');
    require('inc/constante_label.php');
    require('inc/mysql.php');

    $cnx = new MySQL();

    if (isset($_POST["id_label"])) {

        $idLabel = $_POST["id_label"];
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];
        $gramo = $_POST["cantidad_gramo"];
        $codigoBarra = $_POST["codigo_barra"];
        $estatus = $_POST["estatus"];
        $volver = $_POST["volver"];
        $contadorInsumo = $_POST["contador_insumo"];
        $contadorNutriente = $_POST["contador_nutriente"];
        $usuarioEdit = $_SESSION["U"];
        if ($nombre != "") {
            $nombre = str_replace("'", "''", $nombre);
        }
        if ($descripcion != "") {
            $descripcion = str_replace("'", "''", $descripcion);
        }
        if (!($gramo != "" && is_numeric($gramo))) {
            $gramo = 0;
        }
        if ($codigoBarra != "") {
            $codigoBarra = str_replace("'", "''", $codigoBarra);
        }
        if ($estatus != "") {
            $estatus = str_replace("'", "''", $estatus);
        }

        $allowed_ext= array('jpg','jpeg','png','gif');
        $arregloImagen = array();
        $base64 = "";
        $type = "";
        //imagen principal
        $eliminar = isset($_POST["imagen_principal_eliminar"]) ? 1 : 0;
        $idImagen = $_POST["imagen_principal_id"];
        if ($_FILES["uploadPrincipal"]["tmp_name"] != "") {
            $file_name =$_FILES['uploadPrincipal']['name'];
            $file_tmp = $_FILES['uploadPrincipal']['tmp_name'];
            $file_ext = strtolower(end(explode('.', $file_name)));
            $type = pathinfo($file_name, PATHINFO_EXTENSION);
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        array_push($arregloImagen, array('image64' => $base64, 'type' => $type, 'concept' => CONCEPTO_PRINCIPAL, 'imageID' => $idImagen, 'delete' => $eliminar));
        //imagen ingrediente
        $base64 = "";
        $type = "";
        $eliminar = isset($_POST["imagen_ingrediente_eliminar"]) ? 1 : 0;
        $idImagen = $_POST["imagen_ingrediente_id"];
        if ($_FILES["uploadIngrediente"]["tmp_name"] != "") {
            $file_name =$_FILES['uploadIngrediente']['name'];
            $file_tmp = $_FILES['uploadIngrediente']['tmp_name'];
            $file_ext = strtolower(end(explode('.', $file_name)));
            $type = pathinfo($file_name, PATHINFO_EXTENSION);
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        array_push($arregloImagen, array('image64' => $base64, 'type' => $type, 'concept' => CONCEPTO_INGREDIENTE, 'imageID' => $idImagen, 'delete' => $eliminar));
        //imagen nutricional
        $base64 = "";
        $type = "";
        $eliminar = isset($_POST["imagen_nutricional_eliminar"]) ? 1 : 0;
        $idImagen = $_POST["imagen_nutricional_id"];
        if ($_FILES["uploadNutricional"]["tmp_name"] != "") {
            $file_name =$_FILES['uploadNutricional']['name'];
            $file_tmp = $_FILES['uploadNutricional']['tmp_name'];
            $file_ext = strtolower(end(explode('.', $file_name)));
            $type = pathinfo($file_name, PATHINFO_EXTENSION);
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        array_push($arregloImagen, array('image64' => $base64, 'type' => $type, 'concept' => CONCEPTO_VALOR_NUTRICIONAL, 'imageID' => $idImagen, 'delete' => $eliminar));
        //imagen código barra
        $base64 = "";
        $type = "";
        $eliminar = isset($_POST["imagen_codigo_barra_eliminar"]) ? 1 : 0;
        $idImagen = $_POST["imagen_codigo_barra_id"];
        if ($_FILES["uploadCodigoBarra"]["tmp_name"] != "") {
            $file_name =$_FILES['uploadCodigoBarra']['name'];
            $file_tmp = $_FILES['uploadCodigoBarra']['tmp_name'];
            $file_ext = strtolower(end(explode('.', $file_name)));
            $type = pathinfo($file_name, PATHINFO_EXTENSION);
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        array_push($arregloImagen, array('image64' => $base64, 'type' => $type, 'concept' => CONCEPTO_CODIGO_BARRA, 'imageID' => $idImagen, 'delete' => $eliminar));

        //grabar imagen
        $queryJson = array(
            "user" =>  0,
            "name" => $nombre,
            "labelID" => $idLabel,
            "description" => $descripcion,
            "gram" => $gramo,
            "barrCode" => $codigoBarra,
            "status" => $estatus,
            "images" => $arregloImagen,
            "userEdit" =>  $usuarioEdit
        );
        $urlGraba = URL_API_LABEL_GRABA_IMAGEN;
        $curlGraba = curl_init($urlGraba);
        curl_setopt($curlGraba, CURLOPT_POST, true);
        curl_setopt($curlGraba, CURLOPT_POSTFIELDS, json_encode($queryJson));
        curl_setopt($curlGraba, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlGraba, CURLOPT_SSL_VERIFYPEER, false);
        $resultGraba = curl_exec($curlGraba);
        $resultsGraba = json_decode($resultGraba, true);
        curl_close($curlGraba);
        //var_dump($resultGraba);

        if ($resultsGraba["status"] == "1") {
            //grabar insumos
            $query = "UPDATE label_insumo SET estado = 0, usuario_modifica = '" . $usuarioEdit . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_label = " . $idLabel;
            $cnx->execute($query);
            for ($i = 0; $i < $contadorInsumo; $i++) {
                if (isset($_POST["id_label_insumo" . $i])) {
                    $insumoCantidad = $_POST["insumo_cantidad" . $i];
                    if ($insumoCantidad == "") {
                        $insumoCantidad = 0;
                    }
                    $insumoUnidad = $_POST["insumo_unidad" . $i];
                    $insumoLabelID = $_POST["id_label_insumo" . $i];
                    $insumoID = $_POST["insumo_id" . $i];
                    if ($insumoID == '' ) {
                        $insumoID = "NULL";
                    }
                    if ($insumoLabelID != "0") {
                        $query = "UPDATE label_insumo SET id_insumo = " . $insumoID . ", cantidad = " . $insumoCantidad . ", unidad = '" . $insumoUnidad . "', estado = 1, usuario_modifica = '" . $usuarioEdit . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_plato_insumo = " . $insumoPlatoID ;
                    } else {
                        $query = "INSERT INTO label_insumo (id_label, id_insumo, cantidad, unidad, usuario_registro) VALUES (" . $idLabel . ", " . $insumoID . ", " . $insumoCantidad . ", '" . $insumoUnidad . "', '" . $usuarioEdit . "')";
                    }
                    //echo "query: " . $query . "<br/>";
                    $cnx->insert($query);
                }
            }

            //grabar nutriente
            $query = "UPDATE label_nutriente SET estado = 0, usuario_modifica = '" . $usuarioEdit . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_label = " . $idLabel;
            $cnx->execute($query);
            for ($i = 0; $i < $contadorNutriente; $i++) {
                if (isset($_POST["id_label_nutriente" . $i])) {
                    $nutrienteID = $_POST["nutriente_id" . $i];
                    $nutrienteCantidad = $_POST["nutriente_cantidad" . $i];
                    if ($nutrienteCantidad == "") {
                        $nutrienteCantidad = 0;
                    }
                    $nutrienteUnidad = $_POST["nutriente_unidad" . $i];
                    $nutrienteLabelID = $_POST["id_label_nutriente" . $i];
                    if ($nutrienteID != '' ) {
                        if ($nutrienteLabelID != "0") {
                            $query = "UPDATE label_nutriente SET id_nutriente = " . $nutrienteID . ", cantidad = " . $nutrienteCantidad . ", unidad = '" . $nutrienteUnidad . "', estado = 1, usuario_modifica = '" . $usuarioEdit . "', fecha_modifica = CURRENT_TIMESTAMP WHERE id_label_nutriente = " . $nutrienteLabelID ;
                        } else {
                            $query = "INSERT INTO label_nutriente (id_label, id_nutriente, cantidad, unidad, usuario_registro) VALUES (" . $idLabel . ", " . $nutrienteID . ", " . $nutrienteCantidad . ", '" . $nutrienteUnidad . "', '" . $usuarioEdit . "')";
                        }
                    //echo "query: " . $query . "<br/>";
                        $cnx->insert($query);
                    }
                }
            }
        }

        $cnx = null;
        //die();
        if (volver == "1") {
            header("Location: labels.php");
        } else {
            header("Location: label_editar.php?id=" . $idLabel);
        }

    }

    if (isset($_GET["id"])) {

        $idLabel = $_GET["id"];
        $nombre = "";
        $estatus = "";
        $descripcion = "";
        $codigoBarra = "";
        $imagenPrincipal = "";
        $imagenPrincipalRuta = "";
        $imagenPrincipalId = 0;
        $imagenIngrediente = "";
        $imagenIngredienteRuta = "";
        $imagenIngredienteId = 0;
        $imagenNutricional = "";
        $imagenNutricionalRuta = "";
        $imagenNutricionalId = 0;
        $imagenCodigoBarra = "";
        $imagenCodigoBarraRuta = "";
        $imagenCodigoBarraId = 0;
        $usuarioRegistro = "";
        $fechaRegistro = "";
        $usuarioModifica = "";
        $fechaModifica = "";
        $cantidadGramo = 0;
        $htmlTipoAlimento = "";
        $contadorInsumo = 0;
        $contadorNutriente = 0;

        $consulta = " SELECT nombre_producto, descripcion, gramo, estatus, fecha_registro, usuario_registro, fecha_modifica, usuario_modifica FROM label WHERE id_label = " . $idLabel;
        $sql_query = $cnx->query($consulta);
        $sql_query->read();
        if ($sql_query->next()) {
            $nombre = $sql_query->field('nombre_producto');
            $descripcion = $sql_query->field('descripcion');
            $cantidadGramo = $sql_query->field('gramo');
            $estatus = $sql_query->field('estatus');
            $usuarioRegistro = $sql_query->field('usuario_registro');
            $fechaRegistro = $sql_query->field('fecha_registro');
            $usuarioModifica = $sql_query->field('usuario_modifica');
            $fechaModifica = $sql_query->field('fecha_modifica');
            //imágenes
            $consulta2 = "SELECT id_label_imagen, concepto, imagen FROM label_imagen WHERE id_label = " . $idLabel . " AND estado = 1";
            $sql_query2 = $cnx->query($consulta2);
            $sql_query2->read();
            while ($sql_query2->next()) {
                $idLabelImagen = $sql_query2->field('id_label_imagen');
                $concepto = $sql_query2->field('concepto');
                $imagen = $sql_query2->field('imagen');
                $imagenRuta = LABEL_IMAGE_PATH . $imagen;
                switch ($concepto) {
                    case CONCEPTO_PRINCIPAL:
                        $imagenPrincipal = $imagen;
                        $imagenPrincipalRuta = $imagenRuta;
                        $imagenPrincipalId = $idLabelImagen;
                        break;                        
                    case CONCEPTO_INGREDIENTE:
                        $imagenIngrediente = $imagen;
                        $imagenIngredienteRuta = $imagenRuta;
                        $imagenIngredienteId = $idLabelImagen;
                        break;
                    case CONCEPTO_VALOR_NUTRICIONAL:
                        $imagenNutricional = $imagen;
                        $imagenNutricionalRuta = $imagenRuta;
                        $imagenNutricionalId = $idLabelImagen;
                        break;
                    case CONCEPTO_CODIGO_BARRA:
                        $imagenCodigoBarra = $imagen;
                        $imagenCodigoBarraRuta = $imagenRuta;
                        $imagenCodigoBarraId = $idLabelImagen;
                        break;
                }
            }
        }

        //tipo alimento
        $consulta = " SELECT id_tipo_alimento, nombre FROM tipo_alimento WHERE estado = 1 ORDER BY nombre";
        $sql_query = $cnx->query($consulta);
        $sql_query->read();
        while ($sql_query->next()) {
            $idTipoAlimento = $sql_query->field('id_tipo_alimento');
            $nombreTipoAlimento = $sql_query->field('nombre');
            $htmlTipoAlimento .= '<option value="' . $idTipoAlimento . '">' . $nombreTipoAlimento . '</option>';
        }

        //nutriente
        $nutrienteArray = array();
        $consulta = "SELECT id_nutriente, nombre FROM nutriente WHERE estado = 1 ORDER BY nombre";
        $sql_query = $cnx->query($consulta);
        $sql_query->read();
        while ($sql_query->next()) {
            $idNutriente = $sql_query->field('id_nutriente');
            $nombreNutriente = $sql_query->field('nombre');
            array_push($nutrienteArray, array('id' => $idNutriente, 'nombre' => $nombreNutriente));
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
                        <div class="col-lg-6 col-md-6 col-sm6 col-xs-12">
                            <h4 class="page-title">Subir imagen de producto</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="label_editar.php" method="post" id="forma-label" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo CONCEPTO_PRINCIPAL; ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="file" name="uploadPrincipal" value="" class="form-control" accept="image/*" capture />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" name="imagen_principal_id" value="<?php echo $imagenPrincipalId; ?>" />
                                        <?php 
                                        if ($imagenPrincipal != "") {
                                            echo '<img src="' . $imagenPrincipalRuta . '" alt="" class="img-responsive thumbnail m-r-15" />';    
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php if ($imagenPrincipal != "") {?>
                                        <input type="checkbox" name="imagen_principal_eliminar" value="1" /> Eliminar imagen
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo CONCEPTO_INGREDIENTE; ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="file" name="uploadIngrediente" value="" class="form-control" accept="image/*" capture />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" name="imagen_ingrediente_id" value="<?php echo $imagenIngredienteId; ?>" />
                                        <?php 
                                        if ($imagenIngrediente != "") {
                                            echo '<img src="' . $imagenIngredienteRuta . '" alt="" class="img-responsive thumbnail m-r-15" />';    
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php if ($imagenIngrediente != "") {?>
                                        <input type="checkbox" name="imagen_ingrediente_eliminar" value="1" /> Eliminar imagen
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo CONCEPTO_VALOR_NUTRICIONAL; ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="file" name="uploadNutricional" value="" class="form-control" accept="image/*" capture />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" name="imagen_nutricional_id" value="<?php echo $imagenNutricionalId; ?>" />
                                        <?php 
                                        if ($imagenNutricional != "") {
                                            echo '<img src="' . $imagenNutricionalRuta . '" alt="" class="img-responsive thumbnail m-r-15" />';    
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php if ($imagenNutricional != "") {?>
                                        <input type="checkbox" name="imagen_nutricional_eliminar" value="1" /> Eliminar imagen
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo CONCEPTO_CODIGO_BARRA; ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="file" name="uploadCodigoBarra" value="" class="form-control" accept="image/*" capture />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" name="imagen_codigo_barra_id" value="<?php echo $imagenCodigoBarraId; ?>" />
                                        <?php 
                                        if ($imagenCodigoBarra != "") {
                                            echo '<img src="' . $imagenCodigoBarraRuta . '" alt="" class="img-responsive thumbnail m-r-15" />';    
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php if ($imagenCodigoBarra != "") {?>
                                        <input type="checkbox" name="imagen_codigo_barra_eliminar" value="1" /> Eliminar imagen
                                        <?php }?>
                                    </div>
                                </div>
                                <input type="hidden" name="id_label" value="<?php echo $idLabel; ?>" />
                                <hr />
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="64" value="<?php echo $nombre ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Descripci&oacute;n</label>
                                            <textarea name="descripcion" id="descripcion" class="form-control"><?php echo $descripcion; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Gramos</label>
                                            <input type="number" name="cantidad_gramo" id="cantidad-gramo" value="<?php echo $cantidadGramo; ?>" step="0.01" min="0" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>C&oacute;digo barra</label>
                                            <input type="text" name="codigo_barra" id="codigo-barra" value="<?php echo $codigoBarra; ?>" class="form-control" maxlength="64" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Estatus nuevo</label>
                                            <select name="estatus" id="estatus" class="form-control">
                                                <?php 
                                                foreach ($arrayStatusLabel as $item) {
                                                    echo '<option value="' . $item;
                                                    if (strtoupper($item) == strtoupper($estatus)) {
                                                        echo '" selected="selected';
                                                    }
                                                    echo '">' . $item . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <h3>Ingredientes</h3>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Insumo (RS28 / Minsa)</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Cantidad</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Unidad</label>
                                    </div>
                                    <div class="col-md-2">&nbsp;</div>
                                </div>
                                <div id="insumo-contenedor">
<?php
    $query = "SELECT a.id_label_insumo, a.id_insumo, a.cantidad, a.unidad, b.nombre As insumo_maestra FROM label_insumo a LEFT OUTER JOIN insumo b ON a.id_insumo = b.id_insumo WHERE a.id_label = " . $idLabel . " AND a.estado = 1 ORDER BY a.id_label_insumo";
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    $contadorInsumo = 0;
    while($sql->next()) {
        $idLabelInsumo = $sql->field('id_label_insumo');
        $idInsumo = $sql->field('id_insumo');
        $cantidad = $sql->field('cantidad');
        $unidad = $sql->field('unidad');
        $insumoMaestra = $sql->field('insumo_maestra');
?>
                                    <div class="row" id="div<?php echo $contadorInsumo; ?>">
                                        <div class="col-md-6 col-xs-6">
                                            <input type="text" name="insumo_maestra<?php echo $contadorInsumo; ?>" id="insumo_maestra<?php echo $contadorInsumo; ?>" value="<?php echo $insumoMaestra; ?>"  class="form-control" disabled="disabled" />
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <input type="number" name="insumo_cantidad<?php echo $contadorInsumo; ?>" value="<?php echo round($cantidad, 2); ?>" step="0.01" class="form-control" />
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <select name="insumo_unidad<?php echo $contadorInsumo; ?>" class="form-control">
                                                <option value="">[Seleccionar]</option>
<?php
    foreach($arregloUnidad as $unidad_) {
        echo "<option value=\"" . $unidad_ . "\" ";
        if ($unidad == $unidad_) {
            echo " selected=\"selected\"";
        }
        echo ">" . $unidad_ . "</option>"; 
    }
?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-2 text-center">
                                            <i class="glyphicon glyphicon-search buscar-insumo" data-cont="<?php echo $contadorInsumo; ?>" title="Buscar insumo"></i>
                                            &nbsp;&nbsp;
                                            <i class="glyphicon glyphicon-trash eliminar-insumo" data-cont="<?php echo $contadorInsumo; ?>" title="Eliminar insumo"></i>
                                            <input type="hidden" name="id_label_insumo<?php echo $contadorInsumo; ?>" value="<?php echo $idLabelInsumo; ?>" />
                                            <input type="hidden" name="insumo_id<?php echo $contadorInsumo; ?>" id="insumo_id<?php echo $contadorInsumo; ?>" value="<?php echo $idInsumo; ?>" />
                                        </div>
                                    </div>
                                    <hr id="hr<?php echo $contadorInsumo; ?>" />
<?php
        $contadorInsumo ++;
}
?>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="adicionar-insumo" class="btn btn-default" value="Adicionar ingrediente" />
                                    </div>
                                </div>
                                <h3>Valor nutricional</h3>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nutriente</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Cantidad</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Unidad</label>
                                    </div>
                                    <div class="col-md-2">&nbsp;</div>
                                </div>
                                <div id="nutriente-contenedor">
<?php
    $query = "SELECT a.id_label_nutriente, a.id_nutriente, a.cantidad, a.unidad, b.nombre AS nutriente_maestra FROM label_nutriente a INNER JOIN nutriente b ON a.id_nutriente = b.id_nutriente WHERE a.id_label = " . $idLabel . " AND a.estado = 1 ORDER BY a.id_label_nutriente";
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    $contadorInsumo = 0;
    while($sql->next()) {
        $idLabelNutriente = $sql->field('id_label_nutriente');
        $idNutriente = $sql->field('id_nutriente');
        $cantidad = $sql->field('cantidad');
        $unidad = $sql->field('unidad');
        $nutrienteMaestra = $sql->field('nutriente_maestra');
?>
                                    <div class="row" id="divn<?php echo $contadorNutriente; ?>">
                                        <div class="col-md-6 col-xs-6">
                                            <select name="nutriente_id<?php echo $contadorNutriente; ?>"  class="form-control">
                                                <option value="">[Seleccionar]</option>
                                                <?php 
                                                foreach ($nutrienteArray as $item) {
                                                    echo '<option value="' . $item["id"];
                                                    if ($idNutriente == $item["id"]) {
                                                        echo '" selected="selected';
                                                    }
                                                    echo '">' . $item["nombre"] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <input type="number" name="nutriente_cantidad<?php echo $contadorNutriente; ?>" value="<?php echo round($cantidad, 2); ?>" step="0.01" class="form-control" />
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <select name="nutriente_unidad<?php echo $contadorNutriente; ?>" class="form-control">
                                                <option value="">[Seleccionar]</option>
<?php
    foreach($arregloUnidad as $unidad_) {
        echo "<option value=\"" . $unidad_ . "\" ";
        if ($unidad == $unidad_) {
            echo " selected=\"selected\"";
        }
        echo ">" . $unidad_ . "</option>"; 
    }
?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-2 text-center">
                                            <i class="glyphicon glyphicon-trash eliminar-nutriente" data-cont="<?php echo $contadorNutriente; ?>" title="Eliminar nutriente"></i>
                                            <input type="hidden" name="id_label_nutriente<?php echo $contadorNutriente; ?>" value="<?php echo $idLabelNutriente; ?>" />
                                        </div>
                                    </div>
                                    <hr id="hrn<?php echo $contadorNutriente; ?>" />
<?php
        $contadorNutriente ++;
}
?>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="adicionar-nutriente" class="btn btn-default" value="Adicionar nutriente" />
                                    </div>
                                </div>
                                <br />
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
                                <input type="hidden" name="contador_insumo" id="contador_insumo" value="<?php echo $contadorInsumo ?>" />
                                <input type="hidden" name="contador_nutriente" id="contador_nutriente" value="<?php echo $contadorNutriente ?>" />
                                <input type="hidden" name="id_label" id="id_label" value="<?php echo $idLabel ?>" />
                                <input type="hidden" name="volver" id="hdn_volver" value="0" />
                            </form>
                            <div class="row">
                                <div class="col-md-6 alert alert-info">
                                    * Ning&uacute;n cambio har&aacute; efecto a menos que se de clic en "Grabar".
                                </div>
                                <div class="col-md-6 text-right">
                                    <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                    <input type="button" value="Grabar y seguir" class="btn btn-default nueva-imagen" data-volver="0" />
                                    <input type="button" value="Grabar y volver" class="btn btn-success nueva-imagen" data-vovler="1" />
                                    <input type="button" id="abrir-buscar-modal" value="abrir modal" data-toggle="modal" data-target="#buscar-modal" style="display:none;"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="buscar-modal" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Buscar insumo en maestra (RS28 / Minsa)</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                    <label>Insumo</label>
                                    <input type="text" name="buscar_palabra_insumo" id="buscar-palabra-insumo" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                    <label>Tipo alimento</label>
                                    <select name="buscar_insumo_tipo_alimento" id="buscar-insumo-tipo-alimento" class="form-control">
                                        <option value="">[Todos]</option>
                                        <?php echo $htmlTipoAlimento; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p>&nbsp;</p>
                        <div class="row" id="buscar-insumo-resultado" style="height:200px; overflow-y:scroll">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover table-click table-buscar-insumo" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;"> ID </th>
                                            <th style="width: 30%;"> Nombre </th>
                                            <th style="width: 30%;"> Nombre (Ingl&eacute;s) </th>
                                            <th style="width: 20%;"> Tipo alimento </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-resultado"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="close-buscar-insumo">Close</button>
                        <button type="button" class="btn btn-primary" id="buscar-buscar-insumo" >Buscar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <script type="text/javascript">
            var contadorInsumo = <?php echo $contadorInsumo ?>;
            var insumoElegido = -1;

            $('#volver').click(function() {
                location.href = 'index.php';
            });

            $('.nueva-imagen').click(function() {
                $(this).attr('disabled','disabled');
                $('#hdn_volver').val($(this).attr('data-volver'));
                $('#forma-label').submit();
            });

            $('body').on('click', '.eliminar-insumo', function() {
                insumoElegido = $(this).attr('data-cont');
                $('#div' + insumoElegido).remove();
                $('#hr' + insumoElegido).remove();
            });

            $('body').on('click', '.buscar-insumo', function() {
                insumoElegido = $(this).attr('data-cont');
                $('#abrir-buscar-modal').trigger('click');
            });

            $('#buscar-buscar-insumo').click(function() {
                $('#tbody-resultado').find('tr').remove().end();
                if ($('#buscar-palabra-insumo').val() != '' || $('#buscar-insumo-tipo-alimento').val() != '') {
                    var param = { "ingredient" : $('#buscar-palabra-insumo').val(), "foodType" : $('#buscar-insumo-tipo-alimento').val()};
                    var paramJSON = JSON.stringify(param);
                    $.ajax({
                        type: 'POST',
                        url: 'service/getmasteringredient.php',
                        data: paramJSON,
                        dataType: 'json',
                        success: function (result) {
                            if (result.data.length > 0) {
                                $.each(result.data, function (index, element) {
                                    var tr = $('<tr />');
                                    var td1 = $('<td />');
                                    var td2 = $('<td />');
                                    var td3 = $('<td />');
                                    var td4 = $('<td />');
                                    td1.html(element.ingredientID);
                                    td2.html(element.name);
                                    td3.html(element.name_eng);
                                    td4.html(element.food_type);
                                    tr.append(td1);
                                    tr.append(td2);
                                    tr.append(td3);
                                    tr.append(td4);
                                    $('#tbody-resultado').append(tr);
                                });
                                $('.table-buscar-insumo > tbody > tr').click(function() {
                                    var id_insumo = $(this).children('td:first').text();
                                    var nombre_insumo = $(this).find('td:eq(1)').text();
                                    $('#insumo_id' + insumoElegido).val(id_insumo);
                                    $('#insumo_maestra' + insumoElegido).val(nombre_insumo);
                                    $('#close-buscar-insumo').trigger('click');
                                });
                            } else {
                            }
                        }
                    });
                }
            });

            $('#adicionar-insumo').click(function () {
                var html = '<div class="row" id="div' + contadorInsumo + '">';
                html += '<div class="col-md-6 col-xs-6">';
                html += ' <input type="text" name="insumo_maestra' + contadorInsumo + '" id="insumo_maestra' + contadorInsumo + '" value=""  class="form-control" disabled="disabled" />';
                html += '</div>';
                html += '<div class="col-md-2 col-xs-2">';
                html += ' <input type="number" name="insumo_cantidad' + contadorInsumo + '" value="" step="0.01" min="0" class="form-control" />';
                html += '</div>';
                html += '<div class="col-md-2 col-xs-2">';
                html += ' <select name="insumo_unidad' + contadorInsumo + '" class="form-control">';
                html += '  <option value="">[Seleccionar]</option>';
<?php
    foreach($arregloUnidad as $unidad_) {
        echo "html += '  <option value=\"" . $unidad_ . "\">" . $unidad_ . "</option>';"; 
    }
?>
                html += '  </select>';
                html += '</div>';
                html += '<div class="col-md-2 col-xs-2 text-center">';
                html += ' <i class="glyphicon glyphicon-search buscar-insumo" data-cont="' + contadorInsumo + '" title="Buscar insumo"></i>&nbsp;&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-trash eliminar-insumo" data-cont="' + contadorInsumo + '" title="Eliminar insumo"></i>';
                html += ' <input type="hidden" name="id_label_insumo' + contadorInsumo + '" value="0" /> <input type="hidden" name="insumo_id' + contadorInsumo + '" id="insumo_id' + contadorInsumo + '" value="" />';
                html += '</div>';
                html += '</div>';
                html += '<hr id="hr' + contadorInsumo + '" />';
                $('#insumo-contenedor').append(html);
                contadorInsumo ++;
                $('#contador_insumo').val(contadorInsumo);
            });

        </script>
        <script type="text/javascript">
            var contadorNutriente = <?php echo $contadorNutriente ?>;
            var nutrienteElegido = -1;

            $(document).ready(function() {
                $('#nav-label').addClass('active');
            });

            $('body').on('click', '.eliminar-nutriente', function() {
                nutrienteElegido = $(this).attr('data-cont');
                $('#divn' + nutrienteElegido).remove();
                $('#hrn' + nutrienteElegido).remove();
            });

            $('#adicionar-nutriente').click(function () {
                var html = '<div class="row" id="divn' + contadorNutriente + '">';
                html += '<div class="col-md-6 col-xs-6">';
                html += ' <select name="nutriente_id' + contadorNutriente + '" class="form-control">';
                html += ' <option value="">[Seleccionar]</option>';
<?php 
    foreach ($nutrienteArray as $item) {
        echo "html += '<option value=\"" . $item["id"] . "\">" . $item["nombre"] . "</option>';";
    }
    ?>
                html += '</select>';
                html += '</div>';
                html += '<div class="col-md-2 col-xs-2">';
                html += ' <input type="number" name="nutriente_cantidad' + contadorNutriente + '" value="" step="0.01" min="0" class="form-control" />';
                html += '</div>';
                html += '<div class="col-md-2 col-xs-2">';
                html += ' <select name="nutriente_unidad' + contadorNutriente + '" class="form-control">';
                html += '  <option value="">[Seleccionar]</option>';
<?php
    foreach($arregloUnidad as $unidad_) {
        echo "html += '  <option value=\"" . $unidad_ . "\">" . $unidad_ . "</option>';"; 
    }
?>
                html += '  </select>';
                html += '</div>';
                html += '<div class="col-md-2 col-xs-2 text-center">';
                html += ' <i class="glyphicon glyphicon-trash eliminar-nutriente" data-cont="' + contadorNutriente + '" title="Eliminar nutriente"></i>';
                html += ' <input type="hidden" name="id_label_nutriente' + contadorNutriente + '" value="0" />';
                html += '</div>';
                html += '</div>';
                html += '<hr id="hrn' + contadorNutriente + '" />';
                $('#nutriente-contenedor').append(html);
                contadorNutriente ++;
                $('#contador_nutriente').val(contadorNutriente);
            });
        </script>
    </body>
</html>
<?php
    $cnx = null;
?>