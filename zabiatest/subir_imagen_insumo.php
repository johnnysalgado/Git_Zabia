<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/functions.php');
    require('inc/mysql.php');

    $mensajeError = '';

    if (isset($_POST["hdnsubir"])) {

        set_time_limit(0);
        require_once 'Classes/PHPExcel/IOFactory.php';

        $usuario = $_SESSION["U"];
        $uploadOk = 1;
        $mensajeError = '';
        $nombreArchivo = '';
        $rutaArchivo = '';

        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $nombreArchivo = basename($_FILES["fileToUpload"]["name"]);
            $rutaArchivo = EXCEL_INSUMO_PATH . $nombreArchivo;
            $uploadOk = 1;
            $fileType = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
            if ($fileType == 'xlsx') {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $rutaArchivo)) {
                    $uploadOk = 1;
                } else {
                    $mensajeError = "Hay error al subir el archivo.";
                    $uploadOk = 0;
                }
            } else {
                $mensajeError =  "El archivo no es Excel > 2007.";
                $uploadOk = 0;
            }
        }

        if ($uploadOk == 1) {
            if (!file_exists($rutaArchivo)) {
                $mensajeError = "No se encontró archivo: " . $nombreArchivo;
            }
            $objPHPExcel = PHPExcel_IOFactory::load($rutaArchivo);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            //var_dump($sheetData);
            $totalItems = count($sheetData);
            $correctos = 0;

            foreach ($sheetData as $item) {
                $id_ = $item["A"];
                $id_ = trim($id_);
                $imagen = $item["G"];
                $posSlash = strrpos($imagen, '/');
                $nombreImagen = substr($imagen, $posSlash + 1, strlen($imagen) - $posSlash + 1);
                if ($nombreImagen != '') {
                    $contenido = getRemoteFile($imagen);
                    if ($contenido) {
                        $nombreImagen = obtieneNombreImagen($nombreImagen, $id_);
                        $rutaImagen = INSUMO_IMAGE_SHORT_PATH . $nombreImagen;
                        file_put_contents($rutaImagen, $contenido);
                        $correctos++;
                    } else {
                        $nombreImagen = "";
                    }
                    $execute = "UPDATE insumo SET imagen = '" . $nombreImagen . "', fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario ."' WHERE id_insumo=" . $id_;
                    $cnx = new MySQL();
                    $cnx->execute($execute);
                    $cnx = null;
                }
            }

            $execute = "INSERT INTO upload (nombre_archivo, tipo, total, correcto, usuario_registro) VALUES ('" .  $nombreArchivo . "', '" . UPLOAD_INSUMO_IMAGEN . "', " . $totalItems . ", " . $correctos . ", '" . $usuario . "');";
            $cnx = new MySQL();
            $cnx->execute($execute);
            $cnx = null;

        }
        header("Location: subir_imagen_insumo.php");
        $cnx = null;
        die();

    } else {

        $cnx = new MySQL();
        $query = "SELECT tipo, fecha_registro, nombre_archivo, usuario_registro, total, correcto FROM upload WHERE tipo = '" . UPLOAD_INSUMO_IMAGEN . "' ORDER BY tipo";
        
        $sql = $cnx->query($query);
        $sql->read();
        $html = "";
        while($sql->next()) {
            $html .= '<tr>';
            $html .= '<td>' . $sql->field('tipo') . '</td>';
            $html .= '<td>' . $sql->field('fecha_registro') . '</td>';
            $html .= '<td>' . $sql->field('nombre_archivo') . '</td>';
            $html .= '<td class="text.center">' . $sql->field('total') . '</td>';
            $html .= '<td class="text.center">' . $sql->field('correcto') . '</td>';
            $html .= '<td>' . $sql->field('usuario_registro') . '</td>';
            $html .= '</tr>';
        }
        $cnx = null;

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
                            <h4 class="page-title">Subir excel para Im&aacute;genes de Insumo</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="subir_imagen_insumo.php" method="post" enctype="multipart/form-data" name="forma" id="forma">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Subir imagen</label>
                                            <input type="file" name="fileToUpload" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <input type="hidden" name="hdnsubir" value="1" />
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <i class="fa fa-spinner fa-spin" style="font-size:24px" class="hide" id="spiner"></i>
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="button" id="boton-subir" value="Subir" class="btn btn-success" />
                                    </div>
                                </div>
                                <div id="mensaje-error" class="row alert alert-danger"></div>
                            </form>
                            <br />
                            <div class="table-responsive">
                                <table id="upload-table" class="table table-striped display">
                                    <thead>
                                        <tr>
                                            <th> Entidad </th>
                                            <th> Fecha </th>
                                            <th> Archivo </th>
                                            <th> Registros </th>
                                            <th> Procesados </th>
                                            <th> Usuario </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $html; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#nav-ingredient').addClass('active');
                $('#upload-table').DataTable();
                $('#mensaje-error').hide();
                $('#spiner').hide();
                <?php if ($mensajeError != '') {
                    echo "$('#mensaje-error').html(" . $mensajeError . ");";
                    echo "$('#mensaje-error').show()";
                } ?>
            });

            $('#boton-subir').click(function() {
                $(this).attr('disabled','disabled');
                $('#forma').submit();
                $('#spiner').show();
            });
        </script>
    </body>
</html>
<?php
    $cnx = null;
?>