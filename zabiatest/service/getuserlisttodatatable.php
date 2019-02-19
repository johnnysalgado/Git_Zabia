<?php
    require('../inc/configuracion.php');
    require('../inc/constante_usuario.php');
    // DB table to use
    $table = 'vusuario';
    
    // Table's primary key
    $primaryKey = 'id_usuario';
    
    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array( 'db' => 'id_usuario', 'dt' => 0 ),
        array( 'db' => 'email', 'dt' => 1 ),
        array( 'db' => 'nombre', 'dt' => 2 ),
        array( 'db' => 'apellido', 'dt' => 3),
        array( 'db' => 'affiliates_name', 'dt' => 4 ),
        array(
            'db' => 'estado',
            'dt' => 5,
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

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );

?>