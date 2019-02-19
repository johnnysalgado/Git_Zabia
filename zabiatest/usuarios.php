<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
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
                            <h4 class="page-title">Usuarios</h4>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="usuario-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Correo </th>
                                            <th> Nombre </th>
                                            <th> Apellido </th>
                                            <th> Afiliado </th>
                                            <th> Cuestionario </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
             $('body').on('click', 'td.td-cuestionario', function() {
                var id_usuario = $(this).closest('tr').attr('data-id');
                location.href = "usuario_cuestionario_ver.php?id=" + id_usuario;
            });

             $('body').on('click', 'td.td-adn-resultado', function() {
                var id_usuario = $(this).closest('tr').attr('data-id');
                location.href = "usuario_adn_resultado.php?id=" + id_usuario;
            });

            $(document).ready(function() {
                $('#usuario-table')
                .DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "20%" },
                        { "width": "20%" },
                        { "width": "20%" },
                        { "width": "20%" },
                        { "width": "10%" }
                    ],
                    "aoColumnDefs" : [ 
                        {"aTargets" : [0], "sClass": "text-center"},
                        {"aTargets" : [1], "sClass": ""},
                        {"aTargets" : [2], "sClass": ""},
                        {"aTargets" : [3], "sClass": "text-center"}, 
                        {"aTargets" : [4], "sClass": "text-center"}, 
                        {"aTargets" : [5], "sClass": "td-cuestionario text-center", "data": null, "defaultContent": "<i class=\"glyphicon glyphicon-list-alt\"></i>"}
                    ],
                    "createdRow": function (row, data, index) {
                        $(row).attr('data-id', data[0])
                    },
                    "lengthMenu": [[25, 50, 100, 500], [25, 50, 100, 500]],
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                        "url": "service/getuserlisttodatatable.php"
                    }
                });
                $('#nav-user').addClass('active');
            });
        </script>
    </body>
</html>