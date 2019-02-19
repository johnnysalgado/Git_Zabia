<?php 
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante_clima.php');
    require('inc/functions.php');

    $pais = "";
    $ciudad = "";

if (isset($_POST["pais"])) {

    $pais = $_POST["pais"];
    $ciudad = $_POST["ciudad"];
    $ciudadPais = $ciudad . "," . $pais;

    $hoy = date('Y-m-d');

    //clima
    $urlClima = sprintf(WEATHER_URL_CLIMA, $ciudadPais);
    $resultClima = file_get_contents($urlClima);
    $resultsClima = json_decode($resultClima, true);
//    var_dump($resultsClima);

    $lat = "";
    $lon = "";
    $weatherMain = "";
    $weatherDescription = "";
    $mainTemp = "";
    $mainPressure = "";
    $mainHumidity = "";
    $mainTempMin = "";
    $mainTempMax = "";
    $mainSeaLevel = "";
    $windSpeed = "";
    $winDeg = "";

    if (isset($resultsClima["coord"])) {
        $lat = $resultsClima["coord"]["lat"];
        $lon = $resultsClima["coord"]["lon"];
    }
    if (isset($resultsClima["weather"])) {
        foreach ($resultsClima["weather"] as $item ) {
            $weatherMain = $item["main"];
            $weatherDescription = $item["description"];
        }
    }
    if (isset($resultsClima["main"])) {
        $mainTemp = $resultsClima["main"]["temp"];
        $mainPressure = $resultsClima["main"]["pressure"];
        $mainHumidity = $resultsClima["main"]["humidity"];
        $mainTempMin = $resultsClima["main"]["temp_min"];
        $mainTempMax = $resultsClima["main"]["temp_max"];
    }
    if (isset($resultsClima["wind"])) {
        $windSpeed = $resultsClima["wind"]["speed"];
        $winDeg = $resultsClima["wind"]["deg"];
    }

    //uv
    $urlUV = sprintf(WEATHER_URL_UV, $lat, $lon);
    $resultUV = file_get_contents($urlUV);
    $resultsUV = json_decode($resultUV, true);
    //var_dump($resultsUV);
    $htmlUV = "";
    foreach($resultsUV as $item) {
        $htmlUV .= '<div class="col-md-4 col-xs-4">';
        $htmlUV .= '<label>' . date2TXT(substr($item["date_iso"], 0, strpos($item["date_iso"], 'T'))) . '</label>';
        $htmlUV .= ': ' . $item["value"];
        $htmlUV .= '</div>';
    }
    $htmlPolucion = "";
    //polución
    $urlPolucion = sprintf(WEATHER_URL_POLUCION, $lat, $lon, $hoy);
    //$urlPolucion = "http://api.openweathermap.org/pollution/v1/co/0.00,10.00/2018-03-02Z.json?appid=0ea74650bbb1e70bb2265d2ce0d5984f";
    /*
    try {
        $resultPolucion = file_get_contents($urlPolucion);
        $resultsPolucion = json_decode($resultPolucion, true);
        //var_dump($resultsPolucion);
        $fechaPolucion = "";
        if (isset($resultsPolucion["time"])) {
            $fechaPolucion = $resultsPolucion["time"];
            $fechaPolucion = date2TXT(substr($resultsPolucion["time"], 0, strpos($resultsPolucion["time"], 'T'))) . ' ' . substr($resultsPolucion["time"], strpos($resultsPolucion["time"], 'T') + 1, strlen($resultsPolucion["time"])- (strpos($resultsPolucion["time"], 'T') + 2) );
        }
        if (isset($resultsPolucion["data"])) {
            foreach ($resultsPolucion["data"] as $item) {
                $htmlPolucion .= '<tr>';
                $htmlPolucion .= '<td>' . $fechaPolucion . '</td>';
                $htmlPolucion .= '<td>' . $item["value"] . '</td>';
                $htmlPolucion .= '<td>' . $item["pressure"] . ' hPa. </td>';
                $htmlPolucion .= '<td>' . $item["precision"] . '</td>';
                $htmlPolucion .= '</tr>';
            }
        }
    } catch(Exception $e){

    }
    */
}
    $cnx = new MySQL();

    $htmlPais = "";
    $query = "SELECT cod_pais, nombre FROM pais WHERE estado = 1 ORDER BY nombre";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $codigoPais = $sql->field('cod_pais');
        $nombrePais = $sql->field('nombre');
        $htmlPais .= "<option value=\"" . $codigoPais . "\" ";
        if ($pais == $codigoPais) {
            $htmlPais .= " selected=\"selected\"";
        }
        $htmlPais .= " >" . $nombrePais . "</option>";
    }
    $cnx = null;
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
                            <h4 class="page-title">Conexi&oacute;n a Clima</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="clima_api_conexion.php" method="post" id="forma-clima">
                                <div class="row">
                                    <div class="col-md-4 col-xs-4">
                                        <div class="form-group">
                                            <label>Pa&iacute;s</label>
                                            <select id="pais" name="pais" class="form-control">
                                                <option value="">[Seleccionar]</option>
                                                <?php echo $htmlPais; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <div class="form-group">
                                            <label>Ciudad</label>
                                            <input id="ciudad" name="ciudad" class="form-control" value="<?php echo $ciudad ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-2 text-right">
                                        <label>  </label>
                                        <input type="button" id="traer-clima" value="Ver clima" class="btn btn-success form-control" />
                                    </div>
                                </div>
                                <div id="divError" class="row alert alert-danger"></div>
                            </form>
                            <hr/>
                            <div id="div-clima">
                                <h4>Datos del Clima</h4>
                                <div class="row">
                                    <div class="col-md-4 col-xs-4">
                                        <label>Latitud</label>: <?php echo $lat; ?>
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <label>Longitud</label>: <?php echo $lon; ?>
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <label>Clima</label>: <?php echo $weatherDescription; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-xs-4">
                                        <label>Temperatura</label>: <?php echo $mainTemp; ?>&deg; C.
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <label>Temperatura m&iacute;nima</label>: <?php echo $mainTempMin; ?>&deg; C.
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <label>Temperatura m&aacute;xima</label>: <?php echo $mainTempMax; ?>&deg; C.
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-xs-4">
                                        <label>Presi&oacute;n</label>: <?php echo $mainPressure; ?> hPa.
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <label>Humedad</label>: <?php echo $mainHumidity; ?>%.
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <label>Vientos</label>: <?php echo $windSpeed; ?> Km/h a <?php echo $winDeg; ?>&deg;.
                                    </div>
                                </div>
                                <hr />
                                <h4>Rayos UV</h4>
                                <div class="row">
                                    <?php echo $htmlUV; ?>
                                </div>
                                <?php if ($htmlPolucion != "") {?>
                                <hr />
                                <h4>Poluci&oacute;n</h4>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="enfermedad-table" class="table table-striped display">
                                            <thead>
                                                <tr>
                                                    <th> Fecha / hora de medida </th>
                                                    <th> Mon&oacute;xido de carbono </th>
                                                    <th> Presi&oacute;n </th>
                                                    <th> Precisi&oacute;n </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $htmlPolucion; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#divError').hide();
                $('#nav-fitbit').addClass('active');
                if ('<?php echo $pais ?>' != '') {
                    $('#div-clima').show();
                } else {
                    $('#div-clima').hide();
                }
            });

            $("#traer-clima").click(function() {
                $('#divError').hide();
                $('#divError').html('');
                var flag = 1;
                if ($('#pais').val() == "") {
                    flag = 0;
                    $('#divError').append('Debe seleccionar País. ');
                }
                if ($('#ciudad').val() == "") {
                    flag = 0;
                    $('#divError').append('Debe ingresar Ciudad. ');
                }
                if (flag == 1) {
                    $('#forma-clima').submit();
                } else {
                    $('#divError').show();
                }
            });
        </script>
    </body>
</html>