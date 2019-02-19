<?php
    require('../inc/configuracion.php');
    require('../inc/constante_insumo.php');
    require('../inc/mysql.php');

    $cnx = new MySQL();

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'vinsumo';
 
// Table's primary key
$primaryKey = 'id_insumo';

$nutriente = 0;
$beneficio = 0;
$tipoAlimento = 0;
$orac = 0;
$parametro = "";
$ids = [];
$entra = false;

$query = "SELECT DISTINCT a.id_insumo FROM insumo a LEFT OUTER JOIN insumo_nutriente b ON a.id_insumo = b.id_insumo LEFT OUTER JOIN nutriente_beneficio c ON b.id_nutriente = c.id_nutriente LEFT OUTER JOIN insumo_orac d ON a.id_insumo = d.id_insumo WHERE 1=1 ";

if (isset($_GET["nutriente"])) {
    $nutriente = $_GET["nutriente"];
    if ( $nutriente == "" || !(is_numeric($nutriente)) ) {
        $nutriente = 0;
    } else {
        $query .= "AND b.id_nutriente = " . $nutriente;
        $entra = true;
    }
}
 
if (isset($_GET["beneficio"])) {
    $beneficio = $_GET["beneficio"];
    if ( $beneficio == "" || !(is_numeric($beneficio)) ) {
        $beneficio = 0;
    } else {
        $query .= " AND c.id_beneficio = " . $beneficio;
        $entra = true;
    }
}

if (isset($_GET["tipoAlimento"])) {
    $tipoAlimento = $_GET["tipoAlimento"];
    if ( $tipoAlimento == "" || !(is_numeric($tipoAlimento)) ) {
        $tipoAlimento = 0;
    } else {
        $query .= " AND a.id_tipo_alimento = " . $tipoAlimento;
        $entra = true;
    }
}

if (isset($_GET["orac"])) {
    $orac = $_GET["orac"];
    if ( $orac == "" || $orac == 0 || !(is_numeric($orac)) ) {
        $orac = 0;
    } else {
        $query .= " AND d.promedio >= " . $orac;
        $entra = true;
    }
}

if ($entra) {
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $id = $sql->field('id_insumo');
        array_push($ids, $id);
    }
    $parametro = "id_insumo IN (";
    if (count($ids)) {
        foreach($ids as $id) {
            $parametro .= $id . ", ";
        }
        $parametro = substr($parametro, 0, strlen($parametro) - 2);
    } else {
        $parametro .= "-1";
    }
    $parametro .= ")";

/*    
    echo "nutriente: " . $nutriente . "<br/>";
    echo "beneficio: " . $beneficio . "<br/>";
    echo "tipoAlimento: " . $tipoAlimento . "<br/>";
    echo "orac: " . $orac . "<br/>";
    echo "cantidad: " . count($ids) . "<br/>";
    echo "query: " . $query . "<br/>";
    echo "parametro: " . $parametro . "<br/>";
    die();
  */  
}

$columns = array(
    array( 'db' => 'id_insumo', 'dt' => 0 ),
    array( 'db' => 'codigo_externo', 'dt' => 1 ),
    array( 'db' => 'tipo_alimento', 'dt' => 2 ),
    array( 'db' => 'nombre', 'dt' => 3 ),
    array( 'db' => 'nombre_ing', 'dt' => 4 ),
    array(
        'db' => 'estado',
        'dt' => 5,
            'formatter' => function( $d, $row ) {
                if ($d == '1') {
                    return "Si";
                } else {
                    return "No";
                }
            }
        ),
    array(
        'db' => 'imagen',
        'dt' => 6,
        'formatter' => function( $d, $row ) {
                if ($d != "") {
                    //return "<img src=\"" . INSUMO_IMAGE_SHORT_PATH . $d . "\" alt=\"\" class=\"img-responsive thumbnail m-r-15\" />";
                    $resultado = "";
                    $rutaImagen = "../" . INSUMO_IMAGE_SHORT_PATH . $d;
                    if (file_exists($rutaImagen)) {
                        $resultado = "<i class=\"glyphicon glyphicon-ok\"></i>";
                    }
                    return $resultado;
                } else {
                    return "";
                }
            }
        ),
    array(
        'db' => 'flag_superfood',
        'dt' => 7,
        'formatter' => function( $d, $row ) {
                return '';
            }
        ),
    array(
        'db' => 'flag_superfood',
        'dt' => 8,
        'formatter' => function( $d, $row ) {
                return '';
            }
        ),
    array(
        'db' => 'flag_superfood',
        'dt' => 9,
        'formatter' => function( $d, $row ) {
                return '';
            }
        )
);
 
// SQL server connection information
$sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASS,
    'db'   => DB_NAME,
    'host' => DB_HOST
);
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( '../inc/ssp.class.php' );
 
//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns ));
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