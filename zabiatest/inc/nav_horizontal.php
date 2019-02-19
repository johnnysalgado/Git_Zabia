            <nav class="navbar navbar-default navbar-static-top m-b-0">
                <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
                    <div class="top-left-part">
                        <a class="logo" href="principal.php"><img src="assets/plugins/images/logo_50.jpg" alt="home" /></a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right pull-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                                <img src="assets/plugins/images/users/user.jpg" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"><?php echo $_SESSION["U"] ?></b>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated flipInY">
                                <li><a href="contrasena.php"><i class="fa fa-asterisk"></i> Cambiar contrase&ntilde;a</a></li>
                                <li><a href="logout.php"><i class="fa fa-power-off"></i> Salir</a></li>
                            </ul>
                            <!-- /.dropdown-user -->
                        </li>
                    </ul>
                </div>
            </nav>
