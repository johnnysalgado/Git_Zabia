<?php
    require('../inc/configuracion.php');
    require('../inc/constante_insumo.php');
    require('../inc/mysql.php');
    require('../inc/constante.php');
    require('../inc/dao_nutriente.php');

    $tipoNutriente = 0;
    $tipoClase = 0;
    $tipoCategoria = 0;
    $tipoFamilia = 0;
    $tipoSubfamilia = 0;
    $flagEsencial = 0;
    $estadoBuscar = 1;
    $parametro = "";
    $entra = false;

    if (isset($_GET["tipoNutriente"])) {
        $tipoNutriente = $_GET["tipoNutriente"];
        if ( $tipoNutriente == "" || !(is_numeric($tipoNutriente)) ) {
            $tipoNutriente = 0;
        }
        $entra = true;
    }
    
    if (isset($_GET["tipoClase"])) {
        $tipoClase = $_GET["tipoClase"];
        if ( $tipoClase == "" || !(is_numeric($tipoClase)) ) {
            $tipoClase = 0;
        }
        $entra = true;
    }

    if (isset($_GET["tipoCategoria"])) {
        $tipoCategoria = $_GET["tipoCategoria"];
        if ( $tipoCategoria == "" || !(is_numeric($tipoCategoria)) ) {
            $tipoCategoria = 0;
        }
        $entra = true;
    }

    if (isset($_GET["tipoFamilia"])) {
        $tipoFamilia = $_GET["tipoFamilia"];
        if ( $tipoFamilia == "" || !(is_numeric($tipoFamilia)) ) {
            $tipoFamilia = 0;
        }
        $entra = true;
    }

    if (isset($_GET["tipoSubfamilia"])) {
        $tipoSubfamilia = $_GET["tipoSubfamilia"];
        if ( $tipoSubfamilia == "" || !(is_numeric($tipoSubfamilia)) ) {
            $tipoSubfamilia = 0;
        }
        $entra = true;
    }

    if (isset($_GET["flagEsencial"])) {
        $flagEsencial = $_GET["flagEsencial"];
        $entra = true;
    }

    if (isset($_GET["estadoBuscar"])) {
        $estadoBuscar = $_GET["estadoBuscar"];
        $entra = true;
    }

    // DB table to use
    $table = 'vnutriente';
    
    // Table's primary key
    $primaryKey = 'id_nutriente';

    if ($entra) {
        $daoNutriente = new DaoNutriente();
        $arreglo = $daoNutriente->listarNutriente($tipoNutriente, $tipoClase, $tipoCategoria, $tipoFamilia, $tipoSubfamilia, $flagEsencial, $estadoBuscar);
        $parametro = "id_nutriente IN (";
        if (count($arreglo) > 0) {
            foreach($arreglo as $item) {
                $parametro .= $item['id_nutriente'] . ", ";
            }
            $parametro = substr($parametro, 0, strlen($parametro) - 2);
        } else {
            $parametro .= "-1";
        }
        $parametro .= ")";
        $daoNutriente = null;
    }

    $columns = array(
        array( 'db' => 'id_nutriente', 'dt' => 0 ),
        array( 'db' => 'nombre', 'dt' => 1 ),
        array( 'db' => 'nombre_ing', 'dt' => 2 ),
        array( 'db' => 'tipo_nutriente', 'dt' => 3 ),
        array( 'db' => 'tipo_clase', 'dt' => 4 ),
        array( 'db' => 'tipo_categoria', 'dt' => 5 ),
        array( 'db' => 'tipo_familia', 'dt' => 6 ),
        array( 'db' => 'tipo_subfamilia', 'dt' => 7 )
    );
 
    // SQL server connection information
    $sql_details = array(
        'user' => DB_USER,
        'pass' => DB_PASS,
        'db'   => DB_NAME,
        'host' => DB_HOST
    );
 
    require( '../inc/ssp.class.php' );
 
    if ($parametro == "") {
        echo json_encode(
            SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );
    } else {
        echo json_encode(
            SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $parametro)
        );
    }
?>