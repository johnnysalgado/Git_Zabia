<?php
$mimetype = mime_content_type($_FILES['file']['tmp_name']);
if(in_array($mimetype, array('image/png', 'image/jpeg')))
{
   $nombre = $_FILES["file"]["name"];
	$ext = end((explode(".", $nombre))); # extra () to prevent notice
	$nombre = explode('.',$nombre);
	
	$name = date('dmYHis').".".$ext;
    $ds          = DIRECTORY_SEPARATOR;  //1
	 
	$storeFolder = 'imagen/tip/';   //2

	$tempFile = $_FILES['file']['tmp_name'];          //3             
		  
	$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
	 
	$targetFile =  $targetPath. $name;  //5
 
	move_uploaded_file($tempFile, $targetFile); //6
	
	echo $name;

} else {
    echo 'error';
} 

?>