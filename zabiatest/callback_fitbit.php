<?php 
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/constante_fitbit.php');
    require('inc/functions.php');
    date_default_timezone_set("America/Lima");
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
                            <h4 class="page-title">Conexi&oacute;n a Fitbit</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
<?php
    $usuario = $_SESSION["U"];
    if (isset($_GET['code'])) {
        $code = $_GET["code"];
        $auth_header = array(
            "Authorization: Basic " . base64_encode(CLIENT_ID . ":" . CLIENT_SECRET)
            , "Content-Type: application/x-www-form-urlencoded"
        );
        $url = FITBIT_URL_TOKEN;
        $access_token_setttings = array(
            "code" =>  $code,
            "grant_type" => "authorization_code",
            "client_id" =>  CLIENT_ID,
            "expires_in" => EXPIRES_IN,
            "redirect_uri" => REDIRECT_URI
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $auth_header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($access_token_setttings));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);

        $results = json_decode($result, true);
        //var_dump($result);

        $acces_token = $results["access_token"];
        $refresh_token = $results["refresh_token"];
        $scope = $results["scope"];
        $token_type = $results["token_type"];
        $user_id = $results["user_id"];

        //perfil
        $urlProfile = FITBIT_URL_PROFILE;
        $authProfile = array(
            "Authorization: " . $token_type . " " . $acces_token
        );
        $curlProfile = curl_init($urlProfile);
        curl_setopt($curlProfile, CURLOPT_HTTPHEADER, $authProfile);
        curl_setopt($curlProfile, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlProfile, CURLOPT_SSL_VERIFYPEER, false);
        $resultProfile = curl_exec($curlProfile);
        curl_close($curlProfile);
        $resultsProfile = json_decode($resultProfile, true);
        //var_dump($resultProfile);
        
        $age = "";
        $autoStrideEnabled = "";
        $avatar = "";
        $avatar150 = "";
        $avatar640 = "";
        $averageDailySteps = "";
        $clockTimeDisplayFormat = "";
        $corporate = "";
        $corporateAdmin = "";
        $dateOfBirth = "";
        $displayName = "";
        $displayNameSetting = "";
        $distanceUnit = "";
        $encodedId = "";
        $features = "";
        $foodsLocale = "";
        $fullName = "";
        $gender = "";
        $glucoseUnit = "";
        $height = "";
        $heightUnit = "";
        $locale = "";
        $memberSince = "";
        $mfaEnabled = "";
        $offsetFromUTCMillis = "";
        $startDayOfWeek = "";
        $strideLengthRunning = "";
        $strideLengthRunningType = "";
        $strideLengthWalking = "";
        $strideLengthWalkingType = "";
        $swimUnit = "";
        $timezone = "";
        $topBadges = "";
        $waterUnit = "";
        $waterUnitName = "";
        $weight = "";
        $weightUnit = "";

        if ($resultsProfile["user"] != null) {
            $age = $resultsProfile["user"]["age"];
            if (isset($resultsProfile["user"]["autoStrideEnabled"])) {
                $autoStrideEnabled = $resultsProfile["user"]["autoStrideEnabled"];
            }
            $avatar = $resultsProfile["user"]["avatar"];
            $avatar150 = $resultsProfile["user"]["avatar150"];
            $avatar640 = $resultsProfile["user"]["avatar640"];
            $averageDailySteps = $resultsProfile["user"]["averageDailySteps"];
            $clockTimeDisplayFormat = $resultsProfile["user"]["clockTimeDisplayFormat"];
            $corporate = $resultsProfile["user"]["corporate"];
            $corporateAdmin = $resultsProfile["user"]["corporateAdmin"];
            $dateOfBirth = $resultsProfile["user"]["dateOfBirth"];
            $displayName = $resultsProfile["user"]["displayName"];
            $displayNameSetting = $resultsProfile["user"]["displayNameSetting"];
            $distanceUnit = $resultsProfile["user"]["distanceUnit"];
            $encodedId = $resultsProfile["user"]["encodedId"];
            if ($resultsProfile["user"]["features"] != null) {
                $features = $resultsProfile["user"]["features"]["exerciseGoal"];
            }
            $foodsLocale = $resultsProfile["user"]["foodsLocale"];
            $fullName = $resultsProfile["user"]["fullName"];
            $gender = $resultsProfile["user"]["gender"];
            $glucoseUnit = $resultsProfile["user"]["glucoseUnit"];
            $height = $resultsProfile["user"]["height"];
            $heightUnit = $resultsProfile["user"]["heightUnit"];
            $locale = $resultsProfile["user"]["locale"];
            $memberSince = $resultsProfile["user"]["memberSince"];
            $mfaEnabled = $resultsProfile["user"]["mfaEnabled"];
            $offsetFromUTCMillis = $resultsProfile["user"]["offsetFromUTCMillis"];
            $startDayOfWeek = $resultsProfile["user"]["startDayOfWeek"];
            $strideLengthRunning = $resultsProfile["user"]["strideLengthRunning"];
            $strideLengthRunningType = $resultsProfile["user"]["strideLengthRunningType"];
            $strideLengthWalking = $resultsProfile["user"]["strideLengthWalking"];
            $strideLengthWalkingType = $resultsProfile["user"]["strideLengthWalkingType"];
            $swimUnit = $resultsProfile["user"]["swimUnit"];
            $timezone = $resultsProfile["user"]["timezone"];
            $topBadges = $resultsProfile["user"]["topBadges"];
            $waterUnit = $resultsProfile["user"]["waterUnit"];
            $waterUnitName = $resultsProfile["user"]["waterUnitName"];
            $weight = $resultsProfile["user"]["weight"];
            $weightUnit = $resultsProfile["user"]["weightUnit"];
        }

        //actividades
        $hoy = date('Y-m-d');
        $urlActivities = sprintf(FITBIT_URL_ACTIVITY, $user_id, $hoy);
        $curlActivity = curl_init($urlActivities);
        curl_setopt($curlActivity, CURLOPT_HTTPHEADER, $authProfile);
        curl_setopt($curlActivity, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlActivity, CURLOPT_SSL_VERIFYPEER, false);
        $resultActivity = curl_exec($curlActivity);
        curl_close($curlActivity);
        $resultsActivity = json_decode($resultActivity, true);
        //var_dump($resultActivity);

        $activeMinutes = 0;
        $caloriesOut = 0;
        $distance = 0;
        $floors = 0;
        $steps = 0;
		$summaryActiveScore = 0;
		$summaryActivityCalories = 0;
		$summaryCaloriesBMR = 0;
		$summaryCaloriesOut = 0;
		$summaryElevation = 0;
		$summaryFairlyActiveMinutes = 0;
		$summaryFloors = 0;
		$summaryLightlyActiveMinutes = 0;
		$summaryMarginalCalories = 0;
		$summarySedentaryMinutes = 0;
		$summarySteps = 0;
        $summaryVeryActiveMinutes = 0;
        $summaryDistanceTotal = 0;
        $summaryDistanceTracker = 0;
        $summaryLoggedActivies = 0;
        $summaryVeryActivite = 0;
        $summaryModeratelyActive = 0;
        $summaryLightlyActive = 0;
        $summarySedentaryActive = 0;
        $summaryActiveMinutes = 0;
        $percentageActiveMinutes = 0;
        $percentageSteps = 0;
        $percentageCaloriesOut = 0;
        $percentageFloors = 0;
        $percentageDistance = 0;

        if (isset($resultsActivity["goals"])) {
            $activeMinutes = $resultsActivity["goals"]["activeMinutes"];
            $caloriesOut = $resultsActivity["goals"]["caloriesOut"];
            $distance = $resultsActivity["goals"]["distance"];
            if (isset($resultsActivity["goals"]["floors"])) {
                $floors = $resultsActivity["goals"]["floors"];
            }
            $steps = $resultsActivity["goals"]["steps"];
        }

        if (isset($resultsActivity["summary"])) {
            $summaryActiveScore = $resultsActivity["summary"]["activeScore"];
            $summaryActivityCalories = $resultsActivity["summary"]["activityCalories"];
            $summaryCaloriesBMR = $resultsActivity["summary"]["caloriesBMR"];
            $summaryCaloriesOut = $resultsActivity["summary"]["caloriesOut"];
            if (isset($resultsActivity["summary"]["elevation"])) {
                $summaryElevation = $resultsActivity["summary"]["elevation"];
            }
            $summaryFairlyActiveMinutes = $resultsActivity["summary"]["fairlyActiveMinutes"];
            if (isset($resultsActivity["summary"]["floors"])) {
                $summaryFloors = $resultsActivity["summary"]["floors"];
            }
            $summaryLightlyActiveMinutes = $resultsActivity["summary"]["lightlyActiveMinutes"];
            $summaryMarginalCalories = $resultsActivity["summary"]["marginalCalories"];
            $summarySedentaryMinutes = $resultsActivity["summary"]["sedentaryMinutes"];
            $summarySteps = $resultsActivity["summary"]["steps"];
            $summaryVeryActiveMinutes = $resultsActivity["summary"]["veryActiveMinutes"];
            if ($resultsActivity["summary"]["distances"] != null) {
                foreach ($resultsActivity["summary"]["distances"] as $item) {
                    $itemActivity = $item["activity"];
                    $itemDistance = $item["distance"];
                    if ($itemActivity == CODE_ACTIVITY_TOTAL) {
                        $summaryDistanceTotal = $itemDistance;
                    } else if ($itemActivity == CODE_ACTIVITY_TRACKER) {
                        $summaryDistanceTracker = $itemDistance;
                    } else if ($itemActivity == CODE_ACTIVITY_LOGGEDACTIVITIES) {
                        $summaryLoggedActivies = $itemDistance;
                    } else if ($itemActivity == CODE_ACTIVITY_VERYACTIVE) {
                        $summaryVeryActivity = $itemDistance;
                    } else if ($itemActivity == CODE_ACTIVITY_MODERATELYACTIVE) {
                        $summaryModeratelyActive = $itemDistance;
                    } else if ($itemActivity == CODE_ACTIVITY_LIGHTLYACTIVE) {
                        $summaryLightlyActive = $itemDistance;
                    } else if ($itemActivity == CODE_ACTIVITY_SEDENTARYACTIVE) {
                        $summarySedentaryActive = $itemDistance;
                    }
                }
            }
        }

        if ($activeMinutes > 0) {
            $summaryActiveMinutes = $summaryFairlyActiveMinutes + $summaryLightlyActiveMinutes + $summaryVeryActiveMinutes;
            $percentageActiveMinutes = round(($summaryActiveMinutes / $activeMinutes) * 100, 2);
        }
        if ($steps > 0) {
            $percentageSteps = round(($summarySteps / $steps) * 100, 2);
        }
        if ($caloriesOut > 0) {
            $percentageCaloriesOut = round(($summaryCaloriesOut / $caloriesOut) * 100, 2);
        }
        if ($floors > 0) {
            $percentageFloors = round(($summaryFloors / $floors) * 100, 2);
        }
        if ($distance > 0) {
            $percentageDistance = round(($summaryDistanceTotal / $distance) * 100, 2);
        }

        //sueño
        $hoy = date('Y-m-d');
        $urlSleep = sprintf(FITBIT_URL_SLEEP, $user_id, $hoy);
        $curlSleep = curl_init($urlSleep);
        curl_setopt($curlSleep, CURLOPT_HTTPHEADER, $authProfile);
        curl_setopt($curlSleep, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlSleep, CURLOPT_SSL_VERIFYPEER, false);
        $resultSleep = curl_exec($curlSleep);
        curl_close($curlSleep);
        $resultsSleep = json_decode($resultSleep, true);
        //var_dump($resultSleep);
        
        $htmlSuenioDetalle = "";
        if (isset($resultsSleep["sleep"])) {
            foreach($resultsSleep["sleep"] as $sleep) {
                $minutosDuracion = ($sleep["duration"] / 1000) / 60;
                $htmlSuenioDetalle = "<tr> ";
                $htmlSuenioDetalle .= " <td> <span class=\"label label-megna label-rounded\">"
                    . $sleep["dateOfSleep"]
                    . " </span> </td> <td class=\"txt-oflo text-center\"> "
                    . $minutosDuracion
                    . " </td> <td class=\"txt-oflo text-center\"> "
                    . $sleep["efficiency"]
                    . "% </td> <td class=\"txt-oflo text-center\"> "
                    . dividirDiaTiempoFitbit($sleep["startTime"])
                    . " </td> <td class=\"txt-oflo text-center\"> "
                    . dividirDiaTiempoFitbit($sleep["endTime"])
                    . " </td> <td class=\"txt-info text-center\"> "
                    . $sleep["minutesAsleep"]
                    . " </td> <td class=\"txt-info text-center\"> "
                    . $sleep["minutesAwake"]
                    . " </td> <td class=\"txt-oflo text-center\"> "
                    . $sleep["timeInBed"]
                    . " </td> </tr> ";
            }
        }

        $summaryTotalMinutesASleep = 0;
        $summaryTotalSleepRecords = 0;
        $summaryTimeInBed = 0;

        if (isset($resultsSleep["summary"])) {
            $summaryTotalMinutesASleep = $resultsSleep["summary"]["totalMinutesAsleep"];
            $summaryTotalSleepRecords = $resultsSleep["summary"]["totalSleepRecords"];
            $summaryTimeInBed = $resultsSleep["summary"]["totalTimeInBed"];
        }

        //actividades de un mes hacia atrás;
        $cnx = new MySQL();
		$historySummaryActiveScore = 0;
		$historySummaryActivityCalories = 0;
		$historySummaryCaloriesBMRH = 0;
		$historySummaryCaloriesOutH = 0;
		$historySummaryElevation = 0;
		$historySummaryFairlyActiveMinutes = 0;
		$historySummaryFloors = 0;
		$historySummaryLightlyActiveMinutes = 0;
		$historySummaryMarginalCalories = 0;
		$historySummarySedentaryMinutes = 0;
		$historySummarySteps = 0;
        $historySummaryVeryActiveMinutes = 0;
        $historySummaryDistanceTotal = 0;
        $historySummaryDistanceTracker = 0;
        $historySummaryLoggedActivies = 0;
        $historySummaryVeryActivite = 0;
        $historySummaryModeratelyActive = 0;
        $historySummaryLightlyActive = 0;
        $historySummarySedentaryActive = 0;
        $historySummaryActiveMinutes = 0;
        $nuevaFecha = $hoy;
        for ($i = 0; $i < 30; $i++) {
            //primero se fija si está en la bd
            $query = "SELECT id_usuario_fitbit_actividad FROM usuario_fitbit_actividad WHERE user_id = '" . $user_id . "' AND fecha = DATE('" . $nuevaFecha . "')";
            //echo $query . "<br>";
            $sql = $cnx->query($query);
            $sql->read();
            if (!$sql->next()) {
                $urlActivity = sprintf(FITBIT_URL_ACTIVITY, $user_id, $nuevaFecha);
                $curlActivity = curl_init($urlActivity);
                curl_setopt($curlActivity, CURLOPT_HTTPHEADER, $authProfile);
                curl_setopt($curlActivity, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curlActivity, CURLOPT_SSL_VERIFYPEER, false);
                $resultActivity = curl_exec($curlActivity);
                curl_close($curlActivity);
                $resultsActivity = json_decode($resultActivity, true);
                //var_dump($resultActivity);
                if (isset($resultsActivity["summary"])) {
                    $historySummaryActiveScore = $resultsActivity["summary"]["activeScore"];
                    $historySummaryActivityCalories = $resultsActivity["summary"]["activityCalories"];
                    $historySummaryCaloriesBMR = $resultsActivity["summary"]["caloriesBMR"];
                    $historySummaryCaloriesOut = $resultsActivity["summary"]["caloriesOut"];
                    if (isset($resultsActivity["summary"]["elevation"])) {
                        $historySummaryElevation = $resultsActivity["summary"]["elevation"];
                    }
                    $historySummaryFairlyActiveMinutes = $resultsActivity["summary"]["fairlyActiveMinutes"];
                    if (isset($resultsActivity["summary"]["floors"])) {
                        $historySummaryFloors = $resultsActivity["summary"]["floors"];
                    }
                    $historySummaryLightlyActiveMinutes = $resultsActivity["summary"]["lightlyActiveMinutes"];
                    $historySummaryMarginalCalories = $resultsActivity["summary"]["marginalCalories"];
                    $historySummarySedentaryMinutes = $resultsActivity["summary"]["sedentaryMinutes"];
                    $historySummarySteps = $resultsActivity["summary"]["steps"];
                    $historySummaryVeryActiveMinutes = $resultsActivity["summary"]["veryActiveMinutes"];
                    if ($resultsActivity["summary"]["distances"] != null) {
                        foreach ($resultsActivity["summary"]["distances"] as $item) {
                            $itemActivity = $item["activity"];
                            $itemDistance = $item["distance"];
                            if ($itemActivity == CODE_ACTIVITY_TOTAL) {
                                $historySummaryDistanceTotal = $itemDistance;
                            } else if ($itemActivity == CODE_ACTIVITY_TRACKER) {
                                $historySummaryDistanceTracker = $itemDistance;
                            } else if ($itemActivity == CODE_ACTIVITY_LOGGEDACTIVITIES) {
                                $historySummaryLoggedActivies = $itemDistance;
                            } else if ($itemActivity == CODE_ACTIVITY_VERYACTIVE) {
                                $historySummaryVeryActivity = $itemDistance;
                            } else if ($itemActivity == CODE_ACTIVITY_MODERATELYACTIVE) {
                                $historySummaryModeratelyActive = $itemDistance;
                            } else if ($itemActivity == CODE_ACTIVITY_LIGHTLYACTIVE) {
                                $historySummaryLightlyActive = $itemDistance;
                            } else if ($itemActivity == CODE_ACTIVITY_SEDENTARYACTIVE) {
                                $historySummarySedentaryActive = $itemDistance;
                            }
                        }
                    }
                    $insert = "INSERT INTO usuario_fitbit_actividad (user_id, fecha, summary_active_score, summary_activity_calorie, summary_calorie_bmr, summary_calorie_out, summary_elevation, summary_fairly_active_minutes, summary_floors, summary_lightly_active_minutes, summary_marginal_calories, summary_sedentary_minutes, summary_steps, summary_very_active_minutes, summary_distance_total, summary_distance_tracker, summary_logged_activities, summary_very_activite, summary_moderately_active, summary_lightly_active, summary_sedentary_active, summary_active_minutes, usuario_registro) VALUES ('" . $user_id . "', DATE('" . $nuevaFecha . "'), " . $historySummaryActiveScore . ", " . $historySummaryActivityCalories . ", " . $historySummaryCaloriesBMR . ", " . $historySummaryCaloriesOut . ", " . $historySummaryElevation . ", " . $historySummaryFairlyActiveMinutes . ", " . $historySummaryFloors . ", " . $historySummaryLightlyActiveMinutes . ", " . $historySummaryMarginalCalories . ", " . $historySummarySedentaryMinutes . ", " . $historySummarySteps . ", " . $historySummaryVeryActiveMinutes . ", " . $historySummaryDistanceTotal . ", " . $historySummaryDistanceTracker . ", " . $historySummaryLoggedActivies . ", " . $historySummaryVeryActivite . ", " . $historySummaryModeratelyActive . ", " . $historySummaryLightlyActive . ", " . $historySummarySedentaryActive . ", " . $historySummaryActiveMinutes . ", '" . $usuario . "')";
                    //echo $insert . "<br>";
                    $cnx->insert($insert);
                }
            }
            $nuevaFecha = strtotime('-1 day', strtotime($nuevaFecha));
            $nuevaFecha = date('Y-m-d', $nuevaFecha);
        }
        //trae data de actividad desde la bd
        $htmlChartPaso = "";
        $htmlChartCaloria = "";
        $htmlChartActividad = "";
        $htmlChartDistancia = "";
        $fechaUnMesAtras = strtotime('-30 day', strtotime($hoy));
        $fechaUnMesAtras = date('Y-m-d', $fechaUnMesAtras);
        $query = "SELECT fecha, id_usuario_fitbit_actividad, summary_active_score, summary_activity_calorie, summary_calorie_bmr, summary_calorie_out, summary_elevation, summary_fairly_active_minutes, summary_floors, summary_lightly_active_minutes, summary_marginal_calories, summary_sedentary_minutes, summary_steps, summary_very_active_minutes, summary_distance_total, summary_distance_tracker, summary_logged_activities, summary_very_activite, summary_moderately_active, summary_lightly_active, summary_sedentary_active, summary_active_minutes FROM usuario_fitbit_actividad WHERE user_id = '" . $user_id . "' AND estado = 1 AND fecha BETWEEN DATE('" . $fechaUnMesAtras . "') AND DATE('" . $hoy . "') ORDER BY fecha ASC" ;
       // echo $query;
        $sql = $cnx->query($query);
        $sql->read();
        while($sql->next()) {
            $fecha = $sql->field('fecha');
            $actividadCaloritaBmr = $sql->field('summary_calorie_bmr');
            $actividadCaloritaOut = $sql->field('summary_calorie_out');
            $actividadBastanteActivo = $sql->field('summary_fairly_active_minutes');
            $actividadLigeramenteActivo = $sql->field('summary_lightly_active_minutes');
            $actividadSedentario = $sql->field('summary_sedentary_minutes');
            $actividadMuyActivo = $sql->field('summary_very_active_minutes');
            $actividadPasos = $sql->field('summary_steps');
            $actividadDistancia = $sql->field('summary_distance_total');
            $htmlChartPaso .= "{ y: '" . $fecha . "', item1: " . $actividadPasos . " },";
            $htmlChartCaloria .= "{ y: '" . $fecha . "', a: " . $actividadCaloritaBmr . ", b:" . $actividadCaloritaOut . " },";
            $htmlChartActividad .= "{ period: '" . $fecha . "', a: " . $actividadSedentario . ", b:" . $actividadLigeramenteActivo . ", c:" . $actividadBastanteActivo . ", d:" . $actividadMuyActivo . " },";
            $htmlChartDistancia .= "{ y: '" . $fecha . "', item1: " . $actividadDistancia . " },";
        }
        if ($htmlChartPaso != "") {
            $htmlChartPaso = substr($htmlChartPaso, 0, strlen($htmlChartPaso) - 1);
        }
        if ($htmlChartCaloria != "") {
            $htmlChartCaloria = substr($htmlChartCaloria, 0, strlen($htmlChartCaloria) - 1);
        }
        if ($htmlChartActividad != "") {
            $htmlChartActividad = substr($htmlChartActividad, 0, strlen($htmlChartActividad) - 1);
        }
        if ($htmlChartDistancia != "") {
            $htmlChartDistancia = substr($htmlChartDistancia, 0, strlen($htmlChartDistancia) - 1);
        }
        $cnx = null;
?>
                                <h1>Resultado consulta Fitbit : <?php echo $displayName; ?> </h1>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Nombre</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $fullName; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Edad</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $age; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Fecha de nacimiento</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $dateOfBirth; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>G&eacute;nero</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $gender; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Altura</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $height . " [" . $heightUnit . "]"; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Peso</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $weight . " [" . $weightUnit . "]"; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Regi&oacute;n horaria</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $timezone; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Localizaci&oacute;n</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $locale; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Registro desde</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $memberSince; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Inicio de semana</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $startDayOfWeek; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Distancia de paso al correr</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $strideLengthRunning . " [" . $distanceUnit . "]"; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>Distancia de paso al caminar</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $strideLengthWalking . " [" . $distanceUnit . "]"; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label>ID</label>
                                            </div>
                                            <div class="col-md-8 col-xs-8">
                                                <?php echo $encodedId; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                         <img src="<?php echo $avatar640; ?>" class="img-responsive thumbnail m-r-15" alt="" />
                                    </div>
                                </div>

                                <div class="row row-in">
                                    <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                                        <div class="col-in row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <i class="linea-icon linea-basic" data-icon="&#xe01b;"></i>
                                                <h5 class="text-muted vb">PROMEDIO DE PASOS DIARIOS</h5>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <h3 class="counter text-right m-t-15 text-megna"><?php echo $averageDailySteps; ?></h3>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-megna"        role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                        <span class="sr-only">0%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 row-in-br">
                                        <div class="col-in row">
                                            <div class="col-md-6 col-sm-6 col-xs-6"> 
                                                <i data-icon="E" class="linea-icon linea-basic"></i>
                                                <h5 class="text-muted vb">SCORE<br/> ACTIVO</h5>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <h3 class="counter text-right m-t-15 text-danger"><?php echo $summaryActiveScore; ?></h3>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                        <span class="sr-only">0%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 p-r-40">
                                        <h3 class="box-title">Minutos activos (<?php echo $activeMinutes ?>)</h3>
                                        <div class="progress progress-lg">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo $percentageActiveMinutes ?>%; color:black;" role="progressbar"><?php echo $summaryActiveMinutes ?></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 p-r-40">
                                        <h3 class="box-title">Pasos (<?php echo $steps ?>)</h3>
                                        <div class="progress progress-lg">
                                            <div class="progress-bar progress-bar-danger" style="width: <?php echo $percentageSteps ?>%; color:black;" role="progressbar"><?php echo $summarySteps ?></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 p-r-40">
                                        <h3 class="box-title">P&eacute;rdida Calor&iacute;as (<?php echo $caloriesOut ?>)</h3>
                                        <div class="progress progress-lg">
                                            <div class="progress-bar progress-bar-default" style="width: <?php echo $percentageCaloriesOut ?>%; color:black;" role="progressbar"><?php echo $summaryCaloriesOut ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 p-r-40">
                                        <h3 class="box-title">Pisos (<?php echo $floors ?>)</h3>
                                        <div class="progress progress-lg">
                                            <div class="progress-bar progress-bar-megna" style="width: <?php echo $percentageFloors ?>%; color:black;" role="progressbar"><?php echo $summaryfloors ?></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 p-r-40">
                                        <h3 class="box-title">Distancia (<?php echo $distance ?>)</h3>
                                        <div class="progress progress-lg">
                                            <div class="progress-bar progress-bar-purple" style="width: <?php echo $percentageDistance ?>%; color:black;" role="progressbar"><?php echo $summaryDistanceTotal ?></div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row row-in">
                                    <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                                        <div class="col-in row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <i class="linea-icon linea-basic" data-icon="&#xe01b;"></i>
                                                <h5 class="text-muted vb">MINUTOS DE SUEÑO</h5>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <h3 class="counter text-right m-t-15 text-megna"><?php echo $summaryTotalMinutesASleep; ?></h3>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-megna"        role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                        <span class="sr-only">0%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 row-in-br">
                                        <div class="col-in row">
                                            <div class="col-md-6 col-sm-6 col-xs-6"> 
                                                <i data-icon="E" class="linea-icon linea-basic"></i>
                                                <h5 class="text-muted vb">TIEMPO EN<br/>CAMA</h5>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <h3 class="counter text-right m-t-15 text-danger"><?php echo $summaryTimeInBed; ?></h3>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                        <span class="sr-only">0%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table ">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center">Duraci&oacute;n (m)</th>
                                                <th class="text-center">Eficiencia</th>
                                                <th class="text-center">Inicio</th>
                                                <th class="text-center">Fin</th>
                                                <th class="text-center">Sueño (m)</th>
                                                <th class="text-center">Despierto (m)</th>
                                                <th class="text-center">En cama (m)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php echo $htmlSuenioDetalle; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <hr />
                                <h4>Actividades del &Uacute;ltimo mes</h4>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-xs-6">
                                        <div class="white-box">
                                            <h3 class="box-title">Pasos</h3>
                                            <div id="chart-paso"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-xs-6">
                                        <div class="white-box">
                                            <h3 class="box-title">Distancia</h3>
                                            <div id="chart-distancia"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                        <div class="white-box">
                                            <h3 class="box-title">Calor&iacute;as</h3>
                                            <div id="chart-caloria"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                        <div class="white-box">
                                            <h3 class="box-title">Actividad en minutos</h3>
                                            <ul class="list-inline text-center m-t-40">
                                                <li>
                                                    <h5><i class="fa fa-circle m-r-5" style="color: #fb9678;"></i>Sedentario</h5> </li>
                                                <li>
                                                    <h5><i class="fa fa-circle m-r-5" style="color: #01c0c8;"></i>Ligero</h5> </li>
                                                <li>
                                                    <h5><i class="fa fa-circle m-r-5" style="color: #8698b7;"></i>Bastante activo</h5> </li>
                                                <li>
                                                    <h5><i class="fa fa-circle m-r-5" style="color: #99d683;"></i>Muy activo</h5> </li>
                                            </ul>
                                            <div id="chart-actividad"></div>
                                        </div>
                                    </div>
                                </div>
<?php
    }
?>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="consultar-fitbit" value="Acceder a Fitbit" class="btn btn-success" />
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
                $('#nav-fitbit').addClass('active');
            });

            $('#consultar-fitbit').click(function () {
                location.href = '<?php echo FITBIT_URL_OAUTH; ?>';
            });

        </script>
        <!--Morris JavaScript -->
        <script src="assets/plugins/bower_components/raphael/raphael-min.js"></script>
        <script src="assets/plugins/bower_components/morrisjs/morris.js"></script>
        <script type="text/javascript">
        <?php if ($htmlChartPaso != "") {?>
            var line = new Morris.Line({
                element: 'chart-paso',
                resize: true,
                data: [
                    <?php echo $htmlChartPaso; ?>
                ],
                xkey: 'y',
                ykeys: ['item1'],
                labels: ['Pasos'],
                gridLineColor: '#eef0f2',
                lineColors: ['#a3a4a9'],
                lineWidth: 1,
                hideHover: 'auto'
            });
        <?php } ?>
        <?php if ($htmlChartDistancia != "") {?>
            var line = new Morris.Line({
                element: 'chart-distancia',
                resize: true,
                data: [
                    <?php echo $htmlChartDistancia; ?>
                ],
                xkey: 'y',
                ykeys: ['item1'],
                labels: ['Distancia Km.'],
                gridLineColor: '#eef0f2',
                lineColors: ['#01c0c8'],
                lineWidth: 1,
                hideHover: 'auto'
            });
        <?php } ?>
        <?php if ($htmlChartCaloria != "") {?>
            Morris.Bar({
                element: 'chart-caloria',
                data: [
                    <?php echo $htmlChartCaloria; ?>
                ],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Calorías BMR', 'Calorías Out'],
                barColors:['#b8edf0', '#b4c1d7'],
                hideHover: 'auto',
                gridLineColor: '#eef0f2',
                resize: true
            });
        <?php } ?>
        <?php if ($htmlChartActividad != "") {?>
            Morris.Area({
                element: 'chart-actividad',
                data: [
                    <?php echo $htmlChartActividad; ?>
                ],
                lineColors: ['#fb9678', '#01c0c8', '#8698b7', '#99d683'],
                xkey: 'period',
                ykeys: ['a', 'b', 'c', 'd'],
                labels: ['Sedentario', 'Ligero', 'Bastante activo', 'Muy activo'],
                pointSize: 0,
                lineWidth: 0,
                resize:true,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'
            });
        <?php } ?>
        </script>
    </body>
</html>