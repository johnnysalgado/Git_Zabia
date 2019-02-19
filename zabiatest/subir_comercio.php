<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_comercio.php');
    require('inc/mysql.php');

    function existeImagen($file) {
        $exists = false;
        $file_headers = @get_headers($file);
        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $exists = false;
        }
        else {
            $exists = true;
        }
        return $exists;
    }

    function getRemoteFile($url, $timeout = 10) {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return ($file_contents) ? $file_contents : FALSE;
    }

    $mensajeError = '';
    $cnx = new MySQL();

    if (isset($_POST["hdnsubir"])) {

        set_time_limit(0);
        /** Include PHPExcel_IOFactory */
        require_once 'Classes/PHPExcel/IOFactory.php';

        $usuario = $_SESSION["U"];
        $uploadOk = 1;
        $mensajeError = '';
        $nombreArchivo = '';
        $rutaArchivo = '';

        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $nombreArchivo = basename($_FILES["fileToUpload"]["name"]);
            $rutaArchivo = EXCEL_COMERCIO_PATH . $nombreArchivo;
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
                $tipoComercio = str_replace("'", "''", $item["B"]);
                $nombre = str_replace("'", "''", $item["C"]);
                $telefono = str_replace("'", "''", $item["D"]);
                $direccion = str_replace("'", "''", $item["E"]);
                $ciudad = str_replace("'", "''", $item["F"]);
                $pais = str_replace("'", "''", $item["G"]);
                $latitud = str_replace("'", "''", $item["H"]);
                $longitud = str_replace("'", "''", $item["I"]);
                $descripcion = str_replace("'", "''", $item["J"]);
                $horario = str_replace("'", "''", $item["K"]);
                $reserva = str_replace("'", "''", $item["L"]);
                $imagen = $item["M"];
                $imagenMarker = $item["N"];
                $posSlash = strrpos($imagen, '/');
                $nombreImagen = substr($imagen, $posSlash + 1, strlen($imagen) - $posSlash + 1);
                $posSlash = strrpos($imagenMarker, '/');
                $nombreImagenMarker = substr($imagenMarker, $posSlash + 1, strlen($imagenMarker) - $posSlash + 1);
                $execute = "";
                if (strpos('T', $id_) == 0) {
                    $execute = "INSERT INTO comercio (tipo_comercio, nombre, telefono, direccion, pais, ciudad, latitud, longitud, descripcion, horario, reserva, imagen, imagen_marker, usuario_registro) VALUES ('" . $tipoComercio . "', '" . $nombre . "', '" . $telefono . "', '" . $direccion . "', '" . $pais . "', '" . $ciudad . "', '" . $latitud . "', '" . $longitud . "', '" . $descripcion . "', '" . $horario . "', '" . $reserva . "', '" . str_replace("'", "''", $nombreImagen) . "', '" . str_replace("'", "''", $nombreImagenMarker) . "', '" . $usuario . "');";
                } else {
                    $execute = "UPDATE comercio SET tipo_comercio = '" . $tipoComercio . "', nombre = '" . $nombre . "', telefono = '" . $telefono . "', direccion = '" . $direccion . "', pais = '" . $pais . "', ciudad = '" . $ciudad . "', latitud = '" . $latitud . "', longitud = '" . $longitud . "', descripcion = '" . $descripcion . "', horario = '" . $horario . "', reserva = '" . $reserva . "', imagen = '" . $nombreImagen . "', imagen_marker = '" . $nombreImagenMarker . "', usuario_modifica = '" . $usuario ."', fecha_modifica = CURRENT_TIMESTAMP WHERE id_comercio = " . $id_ . ";";
                }
                $cnx->execute($execute);
                $correctos++;
                if ($nombreImagen != '') {
                    $rutaImagen = COMERCIO_IMAGE_SHORT_PATH . $nombreImagen;
                    $contenido = getRemoteFile($imagen);
                    if ($contenido) {
                        file_put_contents($rutaImagen, $contenido);
                    }
                }
                if ($nombreImagenMarker != '') {
                    $rutaImagenMarker = COMERCIO_IMAGE_SHORT_PATH . $nombreImagenMarker;
                    echo $imagenMarker . "<hr/>";
                    $contenido = getRemoteFile($imagen);
                    if ($contenido) {
                        file_put_contents($rutaImagen, $contenido);
                    }
                }
            }

            $execute = "INSERT INTO upload (nombre_archivo, tipo, total, correcto, usuario_registro) VALUES ('" .  $nombreArchivo . "', '" . UPLOAD_TIPO_COMERCIO . "', " . $totalItems . ", " . $correctos . ", '" . $usuario . "');";
            $cnx->execute($execute);

        }
        header("Location: subir_comercio.php");
        $cnx = null;
        die();

    } else {

        $query = "SELECT tipo, fecha_registro, nombre_archivo, usuario_registro, total, correcto FROM upload WHERE tipo = '" . UPLOAD_TIPO_COMERCIO . "' ORDER BY tipo DESC";
        
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

    }

    $cnx = null;
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
                            <h4 class="page-title">Subir excel para Comercios</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="subir_comercio.php" method="post" enctype="multipart/form-data" name="forma" id="forma">
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
                $('#nav-place').addClass('active');
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