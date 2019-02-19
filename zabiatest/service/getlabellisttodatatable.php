<?php
    require('../inc/configuracion.php');
    require('../inc/constante_label.php');

    /* =============================================================== */
    /* CONEXIÓNES REMOTAS
    ================================================================ */
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }

    $user = "";
    if (isset($_GET["u"])) {
        $user = $_GET["u"];
    }

    // DB table to use
    $table = 'vlabel';
    
    // Table's primary key
    $primaryKey = 'id_label';
    
    $columns = array(
        array( 'db' => 'id_label', 'dt' => 0 ),
        array( 'db' => 'correo', 'dt' => 1),
        array( 'db' => 'nombre_producto', 'dt' => 2 ),
        array( 'db' => 'fecha_registro', 'dt' => 3),
        array( 'db' => 'estatus', 'dt' => 4),
        array(
            'db' => 'estatus',
            'dt' => 5,
            'formatter' => function( $d, $row ) {
                    return (strtoupper($d) == ESTATUS_PENDIENTE) ? "<i class=\"glyphicon glyphicon-trash eliminar \" title=\"Eliminar etiqueta\"></i>" : "";
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
    
    if ($user == "") {
        echo json_encode(
            SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );
    } else {
        echo json_encode(
            SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, "id_usuariolabel=" . $user)
        );
    }
?>