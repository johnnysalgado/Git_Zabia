        <script type="text/javascript">
            function mapearConfiguracionDT() {
                var numeroPagina = $('#numero-pagina').val();
                var palabraSearch = $('#palabra-search').val();
                var longitudPaginacion = $('#longitud-paginacion').val();
                var param = {
                    "numeroPagina": numeroPagina
                    , "palabraSearch": palabraSearch
                    , "longitudPaginacion": longitudPaginacion
                };
                var paramJSON = JSON.stringify(param);
                $.ajax({
                    type: 'POST',
                    url: 'service/setsessionpage.php',
                    data: paramJSON,
                    dataType: 'json',
                    error: function(errorResult) {
                        console.log('Ha ocurrido un error ' + errorResult.error());
                    },
                    success: function (result) {
                        console.log(result);
                    }
                });
            }
        </script>
