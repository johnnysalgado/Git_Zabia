<?php
    require("inc/constante.php");
    require("inc/sesion.php");

    $usuario = $_SESSION["U"];

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
                            <h4 class="page-title">Lista de etiquetas subidas</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <input type="button" id="nueva-imagen" value="Subir imagen" class="btn btn-primary nueva-imagen" />
                        </div>
                    </div>
                    <br />
                    <input type="button" id="abrir-modal" value="abrir modal" data-toggle="modal" data-target="#eliminar-modal" style="display:none;"/>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="imagen-table" class="table table-striped display" style="cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th> ID </th>
                                            <th> Usuario </th>
                                            <th> Producto </th>
                                            <th> Fecha </th>
                                            <th> Estatus </th>
                                            <th> &nbsp; </th>
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
        <div class="modal fade" tabindex="-1" role="dialog" id="eliminar-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">¿Est&aacute; seguro de eliminar esta imagen?</h4>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="eliminar_label" id="eliminar-label" />
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="close-eliminar-imagen">Close</button>
                        <button type="button" class="btn btn-primary" id="eliminar-eliminar-imagen" >Eliminar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <script type="text/javascript">
             $('body').on('click', 'td.td-imagen', function() {
                var id_imagen = $(this).closest('tr').attr('data-id');
                location.href = "label_editar.php?id=" + id_imagen;
            });

             $('body').on('click', '.eliminar', function() {
                var id_imagen = $(this).closest('tr').attr('data-id');
                $("#eliminar-label").val(id_imagen);
                $('#abrir-modal').trigger('click');
            });

            $(document).ready(function() {
                $('#nav-label').addClass('active');
                $('#imagen-table')
                .DataTable({
                    "columns": [
                        { "width": "5%" },
                        { "width": "25%" },
                        { "width": "25%" },
                        { "width": "20%" },
                        { "width": "20%" },
                        { "width": "5%" }
                    ],
                    "aoColumnDefs" : [
                        {"aTargets" : [0], "sClass": "td-imagen text-center"},
                        {"aTargets" : [1], "sClass": "td-imagen"},
                        {"aTargets" : [2], "sClass": "td-imagen"},
                        {"aTargets" : [3], "sClass": "td-imagen"},
                        {"aTargets" : [4], "sClass": "td-imagen text-center"}, 
                        {"aTargets" : [5], "sClass": "td-eliminar text-center"}
                    ],
                    "createdRow": function (row, data, index) {
                        $(row).attr('data-id', data[0])
                    },
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                        "url": "service/getlabellisttodatatable.php"
                    }
                });
            });

            $('#eliminar-eliminar-imagen').click(function() {
                var param = { "id" : $('#eliminar-label').val(), "user" : "<?php echo $usuario; ?>"};
                var paramJSON = JSON.stringify(param);
                $.ajax({
                    type: 'POST',
                    url: 'service/deletelabel.php',
                    data: paramJSON,
                    dataType: 'json',
                    success: function (result) {
                        location.href = location.href;
                    }
                });
            });

            $('.nueva-imagen').click(function() {
                location.href = "label_crear.php";
            });
        </script>
    </body>
</html>