<?php
	session_start();

	if (!isset($_SESSION['U'])) {
		$urlRetorno = 'index.php';
		header("Location: $urlRetorno");
	} else {
		//echo 'sesión: ' . $_SESSION['U'];
	}
?>