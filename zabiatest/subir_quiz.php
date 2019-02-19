<?php
    date_default_timezone_set("America/Lima");
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante.php');
    require('inc/constante_quiz.php');
    require('inc/constante_cuestionario.php');
    require('inc/functions.php');
    require('vendor/autoload.php');
    require('inc/templates/base.php');
    require('inc/dao_quiz.php');
    require('inc/dao_upload.php');

    $mensajeError = '';

    if (isset($_POST["hdnsubir"])) {

        set_time_limit(0);
        /** Include PHPExcel_IOFactory */
        require_once 'Classes/PHPExcel/IOFactory.php';

        $usuario = $_SESSION["U"];
        $mensajeError = '';
        $nombreArchivo = '';
        $rutaArchivo = '';

        existeFolderCrea(QUIZ_EXCEL_PATH);
        $arrayTag = array();

        $rutaArchivo = grabaExcelDesdeFormulario("fileToUpload", QUIZ_EXCEL_PATH, $mensajeError);

        if ($rutaArchivo != "") {
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
            $googleDriveService = new Google_Service_Drive($client);

            existeFolderCrea(QUIZ_IMAGE_PATH);
            existeFolderCrea(QUESTION_IMAGE_PATH);

            $daoQuiz = new DaoQuiz();

            $idQuiz = 0;
            $contadorQuestion = 0;
            foreach ($sheetData as $item) {
                $tipoFila = $item["A"];
                $id_ = $item["B"];
                $id_ = trim($id_);
                if ( ($id_ != "") && ctype_alnum($id_) ) {
                    if ($tipoFila == TIPO_FILA_QUIZ) {
                        $contadorQuestion = 0;
                        $descripcionQuiz = str_replace("'", "''", $item["C"]);
                        $imagen = str_replace("'", "''", $item["D"]);
                        if (strpos('T', $id_) == 0) {
                            //quiz crear
                            $idQuiz = $daoQuiz->crearQuiz($descripcionQuiz, "", $usuario);
                            if (trim($imagen) != "") {
                                $nombreImagen = "";
                                $posHttp = strrpos($imagen, 'http');
                                if ($posHttp > -1) {
                                    $nombreImagen = grabarImagenDesdeURL($imagen, $idQuiz, QUIZ_IMAGE_PATH);
                                } else {
                                    $nombreImagen = grabarImagenDesdeGoogleDrive($googleDriveService, $imagen, $idQuiz, QUIZ_IMAGE_PATH);
                                }
                                $daoQuiz->actualizarImagenQuiz($idQuiz, $nombreImagen);
                            }
                        } else {
                            //quiz editar
                            $idQuiz = $id_;
                            if (trim($imagen) != "") {
                                $nombreImagen = "";
                                $posHttp = strrpos($imagen, 'http');
                                if ($posHttp > -1) {
                                    $nombreImagen = grabarImagenDesdeURL($imagen, $idQuiz, QUIZ_IMAGE_PATH);
                                } else {
                                    $nombreImagen = grabarImagenDesdeGoogleDrive($googleDriveService, $imagen, $idQuiz, QUIZ_IMAGE_PATH);
                                }
                                $daoQuiz->editarQuiz($idQuiz, $nombre, $nombreImagen, 1, $usuario);
                            }
                        }
                    } else if ($tipoFila == TIPO_FILA_QUESTION) {
                        $contadorQuestion++;
                        $descripcionQuestion = str_replace("'", "''", $item["C"]);
                        $explicacion = str_replace("'", "''", $item["I"]);
                        if (strpos('T', $id_) == 0) {
                            //question crear
                            $idQuestion = $daoQuiz->crearQuestion($idQuiz, $descripcionQuestion, $contadorQuestion, TIPO_RESPUESTA_UNICA, $explicacion, $usuario);
                        } else {
                            //question editar
                            $idQuestion = $id_;
                            $daoQuiz->editarQuestion($idQuestion, $descripcionQuestion, $contadorQuestion, TIPO_RESPUESTA_UNICA, $explicacion, $usuario);
                        }
                        //question option
                        $option1 = str_replace("'", "''", $item["D"]);
                        $option2 = str_replace("'", "''", $item["E"]);
                        $option3 = str_replace("'", "''", $item["F"]);
                        $option4 = str_replace("'", "''", $item["G"]);
                        $optionCorrecta = str_replace("'", "''", $item["H"]);
                        if (trim($option1) != "") {
                            $daoQuiz->crearActualizarOption($idQuestion, $option1, OPTION_INCORRECTA, 1, 1, $usuario);
                        }
                        if (trim($option2) != "") {
                            $daoQuiz->crearActualizarOption($idQuestion, $option2, OPTION_INCORRECTA, 1, 2, $usuario);
                        }
                        if (trim($option3) != "") {
                            $daoQuiz->crearActualizarOption($idQuestion, $option3, OPTION_INCORRECTA, 1, 3, $usuario);
                        }
                        if (trim($option4) != "") {
                            $daoQuiz->crearActualizarOption($idQuestion, $option4, OPTION_INCORRECTA, 1, 4, $usuario);
                        }
                        $daoQuiz->crearActualizarOption($idQuestion, $optionCorrecta, OPTION_CORRECTA, 1, -1, $usuario);
                    }
                    //cantidad de correctos
                    $correctos++;
                }
            }

            //graba la subida del excel
            $daoUpload = new DaoUpload();
            $daoUpload->crearUpload($nombreArchivo, UPLOAD_QUIZ, $totalItems, $correctos, $usuario);
            $daoUpload = null;

        }

        header("Location: subir_quiz.php?mensaje=$mensajeError");
        die();

    } else {
        $mensajeError = "";
        if (isset($_GET['mensaje'])) {
            $mensajeError = $_GET['mensaje'];
        }
        $html = "";
        $daoUpload = new DaoUpload();
        $arreglo = $daoUpload->listarUpload(UPLOAD_QUIZ);
        foreach($arreglo as $item) {
            $html .= '<tr>';
            $html .= '<td>' . $item['tipo'] . '</td>';
            $html .= '<td>' . $item['fecha_registro'] . '</td>';
            $html .= '<td>' . $item['nombre_archivo'] . '</td>';
            $html .= '<td class="text.center">' . $item['total'] . '</td>';
            $html .= '<td class="text.center">' . $item['correcto'] . '</td>';
            $html .= '<td>' . $item['usuario_registro'] . '</td>';
            $html .= '</tr>';
        }
        $daoUpload = null;
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
                            <h4 class="page-title">Subir excel para Quiz</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="subir_quiz.php" method="post" enctype="multipart/form-data" name="forma" id="forma">
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
                                <?php if ($mensajeError != "") {
                                    echo "<div class=\"row alert alert-danger\">$mensajeError</div>";
                                }?>
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
                $('#nav-quiz').addClass('active');
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