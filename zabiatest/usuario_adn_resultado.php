<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    $user = "";
    $correo = "";
    
    if (isset($_GET["id"])) {
        $user = $_GET["id"];
        if ($user != "") {
            $user = str_replace("'", "", $user);
        }
        if ($user > 0) {
            $html = "";
            $cnx = new MySQL();
            $query = "SELECT a.tipo_resultado, a.pos, a.ref, a.alt, a.gt, a.coverage, a.a_reads, a.c_reads, a.g_reads, a.t_reads, a.mutation_frequency, a.ad FROM usuario_adn_resultado a INNER JOIN usuario b ON a.codigo_examen_adn = b.codigo_examen_adn WHERE a.estado = 1 AND b.id_usuario = " . $user . " ORDER BY a.id_usuario_adn_resultado";
            $sql = $cnx->query($query);
            $sql->read();
            while ($sql->next()) {
                $tipoResultado = $sql->field('tipo_resultado');
                $pos = $sql->field('pos');
                $ref = $sql->field('ref');
                $alt = $sql->field('alt');
                $coverage = $sql->field('coverage');
                $aReads = $sql->field('a_reads');
                $cReads = $sql->field('c_reads');
                $gReads = $sql->field('g_reads');
                $tReads = $sql->field('t_reads');
                $mutationFrequency = $sql->field('mutation_frequency');
                $ad = $sql->field('ad');
                $html .= '<tr>';
                $html .= '<td>' . $tipoResultado . '</td>';
                $html .= '<td>' . $pos . '</td>';
                $html .= '<td>' . $ref . '</td>';
                $html .= '<td>' . $alt . '</td>';
                $html .= '<td>' . $coverage . '</td>';
                $html .= '<td>' . $aReads . '</td>';
                $html .= '<td>' . $cReads . '</td>';
                $html .= '<td>' . $gReads . '</td>';
                $html .= '<td>' . $tReads . '</td>';
                $html .= '<td>' . $ad . '</td>';
                $html .= '<td>' . round($mutationFrequency, 2) . '%</td>';
                $html .= '</tr>';
            }
            //correo
            $query = "SELECT email FROM usuario WHERE id_usuario = " . $user;
            $sql = $cnx->query($query);
            $sql->read();
            if ($sql->next()) {
                $correo = $sql->field('email');
            }
            $cnx = null;
       } else {
            header("Location: usuarios.php");
            die();
        }
    } else {
            header("Location: usuarios.php");
            die();
    }
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
                            <h4 class="page-title">Resultado ADN de <?php echo $correo; ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="enfermedad-table" class="table table-striped display">
                                    <thead>
                                        <tr>
                                            <th> Tipo </th>
                                            <th> Pos </th>
                                            <th> Ref </th>
                                            <th> Alt </th>
                                            <th> Coverage </th>
                                            <th> A reads </th>
                                            <th> C reads </th>
                                            <th> G reads </th>
                                            <th> T reads </th>
                                            <th> AD </th>
                                            <th> Mutation Frequency </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $html; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <input type="button" id="volver" value="Volver" class="btn btn-default" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#nav-user').addClass('active');
            });

            $('#volver').click(function() {
                location.href = 'usuarios.php';
            });
        </script>
    </body>
</html>