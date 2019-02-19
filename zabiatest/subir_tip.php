<?php
    date_default_timezone_set("America/Lima");
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_tip.php');
    require('inc/functions.php');
    require('vendor/autoload.php');
    require('inc/templates/base.php');

    function obtenerIDTipoPost($tipoPost, $cnx, $usuario) {
        if ($tipoPost != "") {
            $query = "SELECT id_tipo_post FROM tipo_post WHERE UPPER(nombre) = '" . strtoupper($tipoPost) . "'";
            $sql = $cnx->query($query);
            $sql->read();
            if ($sql->next()) {
                $idTipoPost =  $sql->field('id_tipo_post');
            } else {
                $execute = "INSERT INTO tipo_post (nombre, usuario_registro) VALUES ('" . $tipoPost . "', '" . $usuario . "')";
                $idTipoPost = $cnx->insert($execute);
            }
        } else {
            $tipoPost = "NULL";
        }
        return $idTipoPost;
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

        existeFolderCrea(TIP_PDF_PATH);
        existeFolderCrea(EXCEL_TIP_PATH);
        $arrayTag = array();

        if ($_FILES["fileToUpload"]["tmp_name"] != "") {
            $nombreArchivo = basename($_FILES["fileToUpload"]["name"]);
            $rutaArchivo = EXCEL_TIP_PATH . $nombreArchivo;
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
            $totalItems = count($sheetData);
            $correctos = 0;

            //Google Drive API
            putenv('GOOGLE_APPLICATION_CREDENTIALS=oauth-service.json');
            $client = new Google_Client();
            $client->addScope(Google_Service_Drive::DRIVE);
            $client->useApplicationDefaultCredentials();
            $service = new Google_Service_Drive($client);

            foreach ($sheetData as $item) {
                $id_ = $item["A"];
                $id_ = trim($id_);
                if ( ($id_ != "") && ctype_alnum($id_) ) {
                    $tipoPostD = str_replace("'", "''", $item["B"]);
                    $titulo = str_replace("'", "''", $item["C"]);
                    $detalle = str_replace("'", "''", $item["D"]);
                    $tags = str_replace("'", "''", $item["E"]);
                    $imagen = str_replace("'", "''", $item["F"]); //es el ID de file de Google Drive
                    $enlaceVideo = str_replace("'", "''", $item["G"]);
                    $fuente = str_replace("'", "''", $item["H"]);
                    $autor = str_replace("'", "''", $item["I"]);
                    $antiinflamatorio = str_replace("'", "''", $item["J"]);
                    $antioxidante = str_replace("'", "''", $item["K"]);
                    $belleza = str_replace("'", "''", $item["L"]);
                    $estadoAnimico = $item["M"];
                    $fuerza = $item["N"];
                    $memoria = $item["O"];
                    $perdidaPeso = $item["P"];
                    $prevencionEnfermedad = $item["Q"];
                    $idTipoPost = obtenerIDTipoPost($tipoPostD, $cnx, $usuario);
                    $execute = "";
                    if (strpos('T', $id_) == 0) {
                        $execute = "INSERT INTO nota (id_tipo_post, titulo, detalle, tag, url_video, fuente, autor, usuario_registro) VALUES (" . $idTipoPost . ", '" . $titulo . "', '" . $detalle . "', '" . $tags . "', '" . $enlaceVideo . "', '" . $fuente . "', '" . $autor . "', '" . $usuario . "');";
                        $id_ = $cnx->insert($execute);
                    } else {
                        $execute = "UPDATE nota SET id_tipo_post = " . $idtipoPost . ", titulo = '" . $titulo . "', detalle = '" . $detalle . "', tag = '" . $tag . "', url_video = '" . $enlaceVideo . "', fuente = '" . $fuente . "', autor = '" . $autor . "',usuario_modifica = '" . $usuario ."', fecha_modifica = CURRENT_TIMESTAMP WHERE id_nota = " . $id_ . ";";
                        $cnx->execute($execute);
                    }
                    //graba imagen
                    $nombreImagen = "";
                    if (trim($imagen) != "") {
                        //echo "imagen: " . $imagen . "<br/>";
                        $posHttp = strrpos($imagen, 'http');
                        //echo "posHttp: " . $posHttp . "<br/>";
                        if ($posHttp > -1) {
                            $nombreImagen = grabarImagenDesdeURL($imagen, $id_, TIP_IMAGE_SHORT_PATH);
                        } else {
                            $nombreImagen = grabarImagenDesdeGoogleDrive($service, $imagen, $id_, TIP_IMAGE_SHORT_PATH);
                        }
                        $execute = "UPDATE nota SET imagen = '" . $nombreImagen . "', fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario ."' WHERE id_nota = " . $id_;
                        $cnx->execute($execute);
                    }
                    //graba beneficios
                    //antiinflamatorio
                    $execute = "";
                    if (strtoupper($antiinflamatorio) == "X") {
                        $query = "SELECT id_nota_beneficio FROM nota_beneficio WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_ANTIINFLAMATORIO;
                        $sql = $cnx->query($query);
                        $sql->read();
                        if ($sql->next()) {
                            $execute = "UPDATE nota_beneficio SET estado = 1, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_ANTIINFLAMATORIO;
                        } else {
                            $execute = "INSERT INTO nota_beneficio (id_nota, id_beneficio, usuario_registro) VALUES (" . $id_ . ", " . ID_BENEFICIO_ANTIINFLAMATORIO . ", '" . $usuario . "')";
                        }
                    } else {
                        $execute = "UPDATE nota_beneficio SET estado = 0, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_ANTIINFLAMATORIO;
                    }
                    $cnx->execute($execute);
                    //antioxidante
                    $execute = "";
                    if (strtoupper($antioxidante) == "X") {
                        $query = "SELECT id_nota_beneficio FROM nota_beneficio WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_ANTIOXIDANTE;
                        $sql = $cnx->query($query);
                        $sql->read();
                        if ($sql->next()) {
                            $execute = "UPDATE nota_beneficio SET estado = 1, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_ANTIOXIDANTE;
                        } else {
                            $execute = "INSERT INTO nota_beneficio (id_nota, id_beneficio, usuario_registro) VALUES (" . $id_ . ", " . ID_BENEFICIO_ANTIOXIDANTE . ", '" . $usuario . "')";
                        }
                    } else {
                        $execute = "UPDATE nota_beneficio SET estado = 0, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_ANTIOXIDANTE;
                    }
                    $cnx->execute($execute);
                    //belleza
                    $execute = "";
                    if (strtoupper($belleza) == "X") {
                        $query = "SELECT id_nota_beneficio FROM nota_beneficio WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_BELLEZA;
                        $sql = $cnx->query($query);
                        $sql->read();
                        if ($sql->next()) {
                            $execute = "UPDATE nota_beneficio SET estado = 1, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_BELLEZA;
                        } else {
                            $execute = "INSERT INTO nota_beneficio (id_nota, id_beneficio, usuario_registro) VALUES (" . $id_ . ", " . ID_BENEFICIO_BELLEZA . ", '" . $usuario . "')";
                        }
                    } else {
                        $execute = "UPDATE nota_beneficio SET estado = 0, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_BELLEZA;
                    }
                    $cnx->execute($execute);
                    //estado anímico
                    $execute = "";
                    if (strtoupper($estadoAnimico) == "X") {
                        $query = "SELECT id_nota_beneficio FROM nota_beneficio WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_ESTADO_ANIMICO;
                        $sql = $cnx->query($query);
                        $sql->read();
                        if ($sql->next()) {
                            $execute = "UPDATE nota_beneficio SET estado = 1, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_ESTADO_ANIMICO;
                        } else {
                            $execute = "INSERT INTO nota_beneficio (id_nota, id_beneficio, usuario_registro) VALUES (" . $id_ . ", " . ID_BENEFICIO_ESTADO_ANIMICO . ", '" . $usuario . "')";
                        }
                    } else {
                        $execute = "UPDATE nota_beneficio SET estado = 0, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_ESTADO_ANIMICO;
                    }
                    $cnx->execute($execute);
                    //fuerza
                    $execute = "";
                    if (strtoupper($fuerza) == "X") {
                        $query = "SELECT id_nota_beneficio FROM nota_beneficio WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_FUERZA;
                        $sql = $cnx->query($query);
                        $sql->read();
                        if ($sql->next()) {
                            $execute = "UPDATE nota_beneficio SET estado = 1, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_FUERZA;
                        } else {
                            $execute = "INSERT INTO nota_beneficio (id_nota, id_beneficio, usuario_registro) VALUES (" . $id_ . ", " . ID_BENEFICIO_FUERZA . ", '" . $usuario . "')";
                        }
                    } else {
                        $execute = "UPDATE nota_beneficio SET estado = 0, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_FUERZA;
                    }
                    $cnx->execute($execute);
                    //memoria
                    $execute = "";
                    if (strtoupper($memoria) == "X") {
                        $query = "SELECT id_nota_beneficio FROM nota_beneficio WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_MEMORIA;
                        $sql = $cnx->query($query);
                        $sql->read();
                        if ($sql->next()) {
                            $execute = "UPDATE nota_beneficio SET estado = 1, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_MEMORIA;
                        } else {
                            $execute = "INSERT INTO nota_beneficio (id_nota, id_beneficio, usuario_registro) VALUES (" . $id_ . ", " . ID_BENEFICIO_MEMORIA . ", '" . $usuario . "')";
                        }
                    } else {
                        $execute = "UPDATE nota_beneficio SET estado = 0, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_MEMORIA;
                    }
                    $cnx->execute($execute);
                    //pérdida de peso
                    $execute = "";
                    if (strtoupper($perdidaPeso) == "X") {
                        $query = "SELECT id_nota_beneficio FROM nota_beneficio WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_PERDIDA_PESO;
                        $sql = $cnx->query($query);
                        $sql->read();
                        if ($sql->next()) {
                            $execute = "UPDATE nota_beneficio SET estado = 1, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_PERDIDA_PESO;
                        } else {
                            $execute = "INSERT INTO nota_beneficio (id_nota, id_beneficio, usuario_registro) VALUES (" . $id_ . ", " . ID_BENEFICIO_PERDIDA_PESO . ", '" . $usuario . "')";
                        }
                    } else {
                        $execute = "UPDATE nota_beneficio SET estado = 0, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_PERDIDA_PESO;
                    }
                    $cnx->execute($execute);
                    //pérdida de peso
                    $execute = "";
                    if (strtoupper($prevencionEnfermedad) == "X") {
                        $query = "SELECT id_nota_beneficio FROM nota_beneficio WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_PREVENCION_ENFERMEDAD;
                        $sql = $cnx->query($query);
                        $sql->read();
                        if ($sql->next()) {
                            $execute = "UPDATE nota_beneficio SET estado = 1, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_PREVENCION_ENFERMEDAD;
                        } else {
                            $execute = "INSERT INTO nota_beneficio (id_nota, id_beneficio, usuario_registro) VALUES (" . $id_ . ", " . ID_BENEFICIO_PREVENCION_ENFERMEDAD . ", '" . $usuario . "')";
                        }
                    } else {
                        $execute = "UPDATE nota_beneficio SET estado = 0, fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario . "' WHERE id_nota = " . $id_ . " AND id_beneficio = " . ID_BENEFICIO_PREVENCION_ENFERMEDAD;
                    }
                    $cnx->execute($execute);
                    //tags al arreglo
                    if ($tags != "") {
                        $tagsExplode = explode(",", $tags);
                        foreach($tagsExplode as $item) {
                            if (!in_array($item, $arrayTag)) {
                                array_push($arrayTag, trim($item));
                            }
                        }
                    }
                    //cantidad de correctos
                    $correctos++;
                }
            }

            //graba la subida del excel
            $execute = "INSERT INTO upload (nombre_archivo, tipo, total, correcto, usuario_registro) VALUES ('" .  $nombreArchivo . "', '" . UPLOAD_TIP . "', " . $totalItems . ", " . $correctos . ", '" . $usuario . "');";
            $cnx->execute($execute);

            //graba los tags.
            foreach ($arrayTag as $item) {
                $query = "SELECT id_tag FROM tag WHERE UPPER(nombre) = '" . strtoupper($item) . "'";
                $sql = $cnx->query($query);
                $sql->read();
                if (!$sql->next()) {
                    $execute = "INSERT INTO tag (nombre, usuario_registro) VALUES ('" . $item . "', '" . $usuario . "')";
                    $cnx->insert($execute);
                }
            }

        }
        header("Location: subir_tip.php");
        $cnx = null;
        die();

    } else {

        $query = "SELECT tipo, fecha_registro, nombre_archivo, usuario_registro, total, correcto FROM upload WHERE tipo = '" . UPLOAD_TIP . "' ORDER BY tipo DESC";
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
                            <h4 class="page-title">Subir excel para Tips</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="subir_tip.php" method="post" enctype="multipart/form-data" name="forma" id="forma">
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
                $('#nav-tip').addClass('active');
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