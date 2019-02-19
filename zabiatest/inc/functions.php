<?php

function checkSession() {
	if (!isset($_SESSION)) {
		session_start();
	}
}

function getPage(){
	$page = explode('/', $_SERVER['SCRIPT_NAME']);
	$page = str_replace('.php','',$page[count($page)-1]);
	if ( strpos($page, '-') > 0 ) {
		$tmp = explode('-', $page );
		$page = $tmp[0];
	}
	 return $page;
}

function getNavigatorAddEdit($navigator,$addEddit = true){
	$tmp = "";
	foreach($navigator as $key => $value){	
		$tmp .= "<a href=\"".$value."\">".$key."</a> / ";
	}
	return $tmp.= ($addEddit)?"Agregar":"Editar";
}

function getNavigator($navigator){
	$tmp = "";
	$i = 0;
	$tot = count($navigator) - 1;
	foreach($navigator as $key => $value){	
		$tmp .= ($i < $tot)?"<a href=\"".$value."\">".$key."</a> / ":$key;
		$i++;
	}
	return $tmp;
}

function checkOption($param, $option) {
	if ($param == $option) {
		echo " selected=\"selected\"";
	}
}

function checkFile($file_type,$file_name,$file_size,$file_temp)
{
	if(!empty($file_name)){
		if($file_type == 'application/pdf')
		{
			//echo 'El '.$file_name.', es correcto';
			
			$maxSize = 5000000;
			if(!empty($file_name)){
				if ($file_size > $maxSize) {
					return "error_size";
					//exit();
				}
				else {
					return 'Ok';
				}
			  }
		}	
		else
		{
			return 'error_type';
			//exit;	
		}
	}else{
		return 'error_file';
	}
	
}

function isPOST($param) {  
	return isset($_POST[$param]) ? true : false;
}
function isGET($param) {
	return isset($_GET[$param]) ? true : false;    
}

function getBoundaries($lat, $lng, $distancia = 1, $radioTierra = 6371)
{
    $return = array();
     
    // Los angulos para cada dirección
    $cardinalCoords = array('norte' => '0',
                            'sur'   => '180',
                            'este'  => '90',
                            'oeste' => '270');
    $rLat = deg2rad($lat);
    $rLng = deg2rad($lng);
    $rAngDist = $distancia/$radioTierra;
    foreach ($cardinalCoords as $name => $angle)
    {
        $rAngle = deg2rad($angle);
        $rLatB = asin(sin($rLat) * cos($rAngDist) + cos($rLat) * sin($rAngDist) * cos($rAngle));
        $rLonB = $rLng + atan2(sin($rAngle) * sin($rAngDist) * cos($rLat), cos($rAngDist) - sin($rLat) * sin($rLatB));
        $return[$name] = array('lat' => (float)rad2deg($rLatB), 
                               'lng' => (float)rad2deg($rLonB));
    }
    return array('min_lat' => $return['sur']['lat'],
                 'max_lat' => $return['norte']['lat'],
                 'min_lng' => $return['oeste']['lng'],
                 'max_lng' => $return['este']['lng']);
}

function dividirDiaTiempoFitbit($fechaCompleta) {
	return substr(str_replace("T", " ", $fechaCompleta), 0, strlen($fechaCompleta) - 4);
}

function save_base64_image($base64_image_string, $output_file_without_extension, $path_with_end_slash ) {
    $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
    $mime = $splited[0];
    $data = $splited[1];

    $mime_split_without_base64 = explode(';', $mime, 2);
    $mime_split = explode('/', $mime_split_without_base64[0], 2);
    if(count($mime_split)==2)
    {
        $extension=$mime_split[1];
        if($extension=='jpeg')$extension='jpg';
        $output_file_with_extension = $output_file_without_extension.'.'.$extension;
    }
    file_put_contents( $path_with_end_slash . $output_file_with_extension, base64_decode($data) );
    return $output_file_with_extension;
}

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

function obtieneNombreImagen($nombreImagen, $id) {
	$posParam = strrpos($nombreImagen, '?');
	if ($posParam > -1) {
		$mt = microtime(true);
		$mt =  $mt*1000;
		$ticks = (string)$mt * 10;
		$nombreImagen = $id . "_" . $ticks. ".jpg";
	} else {
		$nombreImagen = $id . "_" . $nombreImagen;
 	}
	return $nombreImagen;
}

function existeFolderCrea($ruta) {
	if (!file_exists($ruta)) {
		mkdir($ruta, 0777, true);
	}
}

function obtieneNombreImagenDesdeMime($mime, $id) {
	$mime_split = explode('/', $mime, 2);
	$extension = "";
    if(count($mime_split)==2)
    {
        $extension = $mime_split[1];
        if($extension=='jpeg') $extension='jpg';
    }
	$nombreImagen = $id . "." . $extension;
	return $nombreImagen;
}

function grabarImagenDesdeFormulario($id, $nombreElementoForma, $rutaGrabar, $eliminarImagen) {
	$nombreImagen = "";
	if ($_FILES[$nombreElementoForma]["tmp_name"] != "") {
		if ($id == 0) {
			$mt = microtime(true);
			$mt =  $mt * 1000;
			$base = (string) $mt * 10;
		} else {
			$base = $id;
		}
		$nombreImagen = $base . "_" . basename($_FILES[$nombreElementoForma]["name"]);
		$target_file = $rutaGrabar . $nombreImagen;
		$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
		$check = getimagesize($_FILES[$nombreElementoForma]["tmp_name"]);
		if($check !== false) {
			move_uploaded_file($_FILES[$nombreElementoForma]["tmp_name"], $target_file);
		}
	} else if ($eliminarImagen == "1") {
		$nombreImagen = "";
	} else {
		$nombreImagen = $_POST["imagen"];
	}
	return $nombreImagen;
}

function getHtmlTipoRespuesta($cnx, $valorSeleccionado) {
    $query = "CALL USP_LIST_TIPO_RESPUESTA();";
    $sql = $cnx->query($query);
    $sql->read();
    $html = "";
    while ($sql->next()) {
        $tipoRespuesta = $sql->field("cod_tipo_respuesta");
        $descripcion = $sql->field('descripcion');
		$html .= '<option value="' . $tipoRespuesta . '"';
		if ($tipoRespuesta == $valorSeleccionado) $html .= ' selected="selected"';
		$html .= '>' . $descripcion . '</option>';
	}
	return $html;
}

function grabarImagenDesdeGoogleDrive($service, $fileId, $id, $rutaGrabar) {
	$content = $service->files->get($fileId, array("alt" => "media"));
	$nombreArchivo = "";
	if (isset($content->getHeader("Content-Type")[0])) {
		$nombreArchivo = obtieneNombreImagenDesdeMime($content->getHeader("Content-Type")[0], $id);
	} else {
		$nombreArchivo = $id . ".jpg"; 
	}
	$outHandle = fopen($rutaGrabar . $nombreArchivo, "w+");
	while (!$content->getBody()->eof()) {
			fwrite($outHandle, $content->getBody()->read(1024));
	}
	fclose($outHandle);
	return $nombreArchivo;
}

function grabarImagenDesdeURL($imagen, $id, $rutaGrabar) {
	$posSlash = strrpos($imagen, '/');
	$nombreImagen = substr($imagen, $posSlash + 1, strlen($imagen) - $posSlash + 1);
	if ($nombreImagen != '') {
		$imagen = str_replace("https://", "http://", $imagen);
		$contenido = getRemoteFile($imagen);
		if ($contenido) {
			$nombreImagen = obtieneNombreImagen($nombreImagen, $id);
			$rutaImagen = $rutaGrabar . $nombreImagen;
			file_put_contents($rutaImagen, $contenido);
		} else {
			$nombreImagen = "";
		}
	}
	return $nombreImagen;
}

function grabaExcelDesdeFormulario($nombreElementoForma, $rutaGrabar, &$mensajeError) {
	$rutaArchivo = "";
	if ($_FILES[$nombreElementoForma]["tmp_name"] != "") {
		$nombreArchivo = basename($_FILES[$nombreElementoForma]["name"]);
		$rutaArchivo = $rutaGrabar . $nombreArchivo;
		$fileType = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
		if ($fileType == 'xlsx') {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $rutaArchivo)) {
			} else {
				$mensajeError = "Hay error al subir el archivo.";
			}
		} else {
			$mensajeError =  "El archivo no es Excel > 2007.";
		}
	}
	return $rutaArchivo;
}

function existeImagenInsumoParaVistaUsuario($imagen, $basePath, $insumoPath, $iconoPath) {
	if ($imagen != "") {
		$rutaImagen = "../" . $insumoPath . $imagen;
		if (file_exists($rutaImagen)) {
			$imagen = $basePath . $insumoPath . $imagen;
		} else {
			$imagen = $iconoPath . "logo.jpeg";
		}
	} else {
		$imagen = $iconoPath . "logo.jpeg";
	}
	return $imagen;
}

function obtenerHtmlListaPreguntaRespuesta($arregloPreguntaRespuesta, $arregloRespuestaAfecta) {
	$html = "";
	$seccion = "";
	$seccionx = "";
	$pregunta = "";
	$preguntax = "";
	$contadorPregunta = 0;
	$contadorRespuesta = 0;
	foreach ($arregloPreguntaRespuesta as $item) {
		$seccion = $item['seccion'];
		if ($seccion != $seccionx) {
			if ($contadorRespuesta > 0) {
				$html .= " </div> </div> </div> </div>";
			}
			$html .= "<div class=\"row\"><h4>$seccion</h4></div>"; 		
			$seccionx = $seccion;
			$contadorPregunta = 0;
			$contadorRespuesta = 0;
		}
		$idPregunta = $item['id_pregunta'];
		$pregunta = $item['pregunta'];
		if ($pregunta != $preguntax) {
			if ($contadorRespuesta > 0) {
				$html .= " </div> </div> </div> </div>";
			}
			$contadorRespuesta = 0;
			$preguntax = $pregunta;
			$contadorPregunta ++;
			$html .= "<br /> <div class=\"row\"> <div class=\"col-md-12\"> <div class=\"form-group\"> <label>$contadorPregunta. $pregunta</label> <div class=\"checkbox checkbox-success\">";
		}
		$contadorRespuesta ++;
		$idRespuesta = $item['id_respuesta']; 
		$respuesta = $item['respuesta']; 
		$html .= "<div class=\"col-md-3\"> <input type=\"checkbox\" name=\"respuesta_afecta[]\" value=\"$idPregunta" . "_" . "$idRespuesta\"";
		if (existeEnArregloPreguntaRespuesta($arregloRespuestaAfecta, $idPregunta, $idRespuesta)) {
			$html .= " checked=\"checked\"";
		}
		$html .= "/> <label>$respuesta</label> </div>";
	}
	if ($contadorRespuesta > 0) {
		$html .= " </div> </div> </div> </div>";
	}
	return $html;
}

function existeEnArregloPreguntaRespuesta($arregloRespuestaAfecta, $idPregunta, $idRespuesta) {
	$resultado = false;
	foreach($arregloRespuestaAfecta as $item) {
		if ($item['id_pregunta_afecta'] == $idPregunta && $item['id_respuesta_afecta'] == $idRespuesta) {
			$resultado = true;
			break;
		}
	}
	return $resultado;
}

function securePassword($user_pwd, $salt, $multi) {
	
	/*
		secure_password ( string $user_pwd, boolean/string $multi ) 
	
		*** Description: 
			This function verifies a password against a (database-) stored password's hash or
			returns $hash for a given password if $multi is set to either true or false
	
		*** Examples:
			// To check a password against its hash
			if(secure_password($user_password, $row['user_password'])) {
				login_function();
			} 
			// To create a password-hash
			$my_password = 'uber_sEcUrE_pass';
			$hash = secure_password($my_password, true);
			echo $hash;
	*/
	
	// If $multi is not boolean check password and return validation state true/false
	if($multi!==true && $multi!==false) {
		if (password_verify($user_pwd, $table_pwd = $multi)) {
			return true; // valid password
		} else {
			return false; // invalid password
		}
	// If $multi is boolean return $hash
	} else {
		// Set options for encryption and build unique random hash
		$crypt_options = ['cost' => 11, 'salt' => $salt];
		$hash = password_hash($user_pwd, PASSWORD_BCRYPT, $crypt_options);
		return $hash;
	}
	
}

function guidv4() {
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function obtenerSeccionEnlazada ($arregloSeccionEnlazada, $idPreguntaAfecta, $idRespuestaAfecta, $flagMostrarOmitir) {
    $seccionEnlazada = "";
    foreach($arregloSeccionEnlazada as $item) {
        if ( $item['id_pregunta_afecta'] == $idPreguntaAfecta && $item['id_respuesta_afecta'] == $idRespuestaAfecta && $item[$flagMostrarOmitir] == 1) {
            $idSeccionEnlazada = $item['id_seccion_cuestionario_afectada'];
            $seccionEnlazada .= "$idSeccionEnlazada, ";
        }
    }
    if ($seccionEnlazada != "") {
        $seccionEnlazada = substr($seccionEnlazada, 0, strlen($seccionEnlazada) - 2);
    }
    return $seccionEnlazada;
}

function obtenerPreguntaEnlazada ($arregloPreguntaEnlazada, $idPreguntaAfecta, $idRespuestaAfecta, $flagMostrarOmitir) {
    $preguntaEnlazada = "";
    foreach($arregloPreguntaEnlazada as $item) {
        if ( $item['id_pregunta_afecta'] == $idPreguntaAfecta && $item['id_respuesta_afecta'] == $idRespuestaAfecta && $item[$flagMostrarOmitir] == 1) {
            $idPreguntaEnlazada = $item['id_pregunta_afectada'];
            $preguntaEnlazada .= "$idPreguntaEnlazada, ";
        }
    }
    if ($preguntaEnlazada != "") {
        $preguntaEnlazada = substr($preguntaEnlazada, 0, strlen($preguntaEnlazada) - 2);
    }
    return $preguntaEnlazada;
}
?>