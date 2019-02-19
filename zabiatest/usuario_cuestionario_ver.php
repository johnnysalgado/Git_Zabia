<?php
    require('inc/sesion.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/dao_bienestar.php');

    $user = "";
    $correo = "";
    if (isset($_GET["id"])) {
        $user = $_GET["id"];
        if ($user != "") {
            $user = str_replace("'", "", $user);
        }
        if ($user > 0) {
            $query = "SELECT email FROM usuario WHERE id_usuario = " . $user;
            $cnx = new MySQL();
            $sql = $cnx->query($query);
            $sql->read();
            if ($sql->next()) {
                $correo = $sql->field('email');
            }
            $cnx = null;
            //bienestar porcentaje
            $htmlBienestar = "";
            $daoBienestar = new DaoBienestar();
            $porcentajeSalud = $daoBienestar->obtenerPorcentajeBienestarTotal($user);
            $arregloBienestar = $daoBienestar->listarBienestar();
            foreach($arregloBienestar as $item) {
                $idBienestar = $item['id_bienestar'];
                $bienestar = $item['nombre'];
                $porcentaje = $daoBienestar->obtenerPorcentajeBienestarPorBienestar($user, $idBienestar);
                $htmlBienestar .= "<div class=\"col-md-3 col-xs-3\"> <div class=\"form-group\"> <label>$bienestar: </label> </div> </div> <div class=\"col-md-3 col-xs-3\"> <div class=\"form-group\"> <span>" . number_format($porcentaje, 2, '.', '') . " %</span> </div> </div>";
            }
            $daoBienestar = null;
       } else {
            header("Location: usuarios.php");
            die();
        }
    } else {
        header("Location: usuarios.php");
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="page-title">Cuestionario de salud de <?php echo $correo ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
<?php
    //cuestionario de datos personales
    $queryJson = array(
        "user" =>  $user,
        "questionType" => ""
    );
    $urlCuestionario = URL_API_ZABIA_CUESTIONARIO_LISTA;
    $curl = curl_init($urlCuestionario);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($queryJson));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    $results = json_decode($result, true);
    curl_close($curl);
    //var_dump($result);
    $tituloSeccion = "";
    $tituloSeccionx = "";
    $numero = 0;
    foreach ($results["data"] as $item) {
        $respuesta = "";
        $tituloSeccion = $item["question_type"];
//        if ($tituloSeccion == TIPO_DATO_PERSONAL) {
//            $tituloSeccion = "Personal";
//        }
        $numero ++;        
        if ($tituloSeccion != $tituloSeccionx) {
            $numero = 1;
        ?>
                                <hr />
                                <h3><?php echo $tituloSeccion; ?></h3>
<?php
        }
        $tituloSeccionx = $tituloSeccion;
        if ($item["answer_type"] == TIPO_RESPUESTA_FECHA ||
            $item["answer_type"] == TIPO_RESPUESTA_TEXTO ||
            $item["answer_type"] == TIPO_RESPUESTA_NUMERO ||
            $item["answer_type"] == TIPO_RESPUESTA_DECIMAL ) {
            if (count($item["user_answer"]) > 0) {
                if ($item["question_code"] == CODIGO_PREGUNTA_PAIS) {
                    $respuesta = $item["user_answer"][0]["user_answer_complement"];
                } else {
                    $respuesta = $item["user_answer"][0]["user_answer"];
                }
            }
        } else if ($item["answer_type"] == TIPO_RESPUESTA_UNICA) {
            if (count($item["user_answer"]) > 0) {
                $respuesta = $item["user_answer"][0]["user_answer"];
            }
        } else if ($item["answer_type"] == TIPO_RESPUESTA_MULTIPLE) {
            if (count($item["user_answer"]) > 0) {
                foreach ($item["user_answer"] as $userAnswer) {
                    $userAnswerUser_Answer = $userAnswer["user_answer"];
                    if ($userAnswerUser_Answer != null && $userAnswerUser_Answer != "") {
                        $respuesta .= "<li>" . $userAnswer["user_answer"] . "</li>";
                    }
                }
            }
        }

?>
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label><?php echo $numero . ". " . $item    ["question_description"] ?> </label>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <div class="form-group">
                                        <span><?php echo $respuesta ?></span>
                                    </div>
                                </div>
                            </div>
<?php } ?>
                            <hr/>
                            <div class="row">
                                <div class="col-md-3 col-xs-3">
                                    <div class="form-group">
                                        <label>Porcentaje de salud: </label>
                                    </div>
                                </div>
                                <div class="col-md-9 col-xs-9">
                                    <div class="form-group">
                                        <span><?php echo number_format($porcentajeSalud, 2, '.', '') ?> %</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <?php echo $htmlBienestar; ?>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#nav-user').addClass('active');
            });

            $('#volver').click(function() {
                location.href = 'usuarios.php';
            });
        </script>
    </body>
</html>