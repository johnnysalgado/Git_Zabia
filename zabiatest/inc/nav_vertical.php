            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                    <ul class="nav" id="side-menu">
                        <li class="nav-small-cap m-t-10">--- Maestras</li>
                        <li> 
                            <a href="javascript:void(0);" class="waves-effect" id="nav-recipe">
                                <i class="linea-icon linea-basic fa-fw fa fa-cutlery"></i> 
                                <span class="hide-menu"> Recetas <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="recetas.php">Lista</a> </li>
                            </ul>
                        </li>
                        <li> 
                            <a href="javascript:void(0);" class="waves-effect" id="nav-ingredient">
                                <i class="linea-icon linea-basic fa-fw fa fa-circle"></i> 
                                <span class="hide-menu"> Insumos <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="insumos.php?a=1">Lista de insumos</a> </li>
                                <li> <a href="insumo_por_orac.php">Insumos por ORAC</a> </li>
                                <li> <a href="subir_imagen_insumo.php">Subir im&aacute;genes de insumo</a> </li>
                                <li> <a href="subir_insumo_medida.php">Subir gramaje</a> </li>
                            </ul>
                        </li>
                        <li> 
                            <a href="javascript:void(0);" class="waves-effect" id="nav-health">
                                <i class="linea-icon linea-basic fa-fw fa fa-male"></i> 
                                <span class="hide-menu"> Salud <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="nutrientes.php?a=1">
                                <i class="linea-icon linea-basic fa-fw fa fa-houzz"></i> Lista de nutrientes</a> </li>
                                <li> <a href="enfermedades.php?a=1">
                                <i class="linea-icon linea-basic fa-fw fa fa-houzz"></i> Precondiciones</a> </li>
                                <li> <a href="intolerancia.php">
                                <i class="linea-icon linea-basic fa-fw fa fa-houzz"></i> Intolerancias y alergias</a> </li>
                                <li> <a href="trastorno_estomacal.php">
                                <i class="linea-icon linea-basic fa-fw fa fa-houzz"></i> Trastornos estomacales</a> </li>
                                <li> <a href="javascript:void(0)" class="waves-effect" id="nav-health-question">Cuestionario salud <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li> <a href="elegir_afiliado.php?url=<?php echo urlencode("pregunta.php?a=1&s=-1"); ?>">Preguntas</a> </li>
                                        <li> <a href="elegir_afiliado.php?url=<?php echo urlencode("seccion_cuestionario.php?a=1");?>">Secciones</a> </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li> 
                            <a href="javascript:void(0);" class="waves-effect" id="nav-place">
                                <i class="linea-icon linea-basic fa-fw fa fa-cubes"></i> 
                                <span class="hide-menu"> Places <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="restaurantes.php">Lista de restaurantes</a> </li>
                                <li> <a href="groceries.php">Lista de tiendas (grocery)</a> </li>
                                <li> <a href="biotiendas.php">Lista de bio tiendas</a> </li>
                                <li> <a href="terapiaalternativas.php">Lista de terapias alternativa</a> </li>
                                <li> <a href="subir_comercio.php">Subir comercios</a> </li>
                            </ul>
                        </li>
                        <li> 
                            <a href="javascript:void(0);" class="waves-effect" id="nav-flow">
                                <i class="linea-icon linea-basic fa-fw fa fa-wechat"></i> 
                                <span class="hide-menu"> Chat <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="flujochat.php">Configuraci&oacute;n flujo</a> </li>
                            </ul>
                        </li>
                        <li> 
                            <a href="javascript:void(0);" class="waves-effect" id="nav-user">
                                <i class="linea-icon linea-basic fa-fw fa fa-user"></i> 
                                <span class="hide-menu"> Usuarios <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="usuarios.php">Lista de usuarios</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect" id="nav-tip">
                                <i class="linea-icon linea-basic fa-fw fa fa-circle-o"></i> 
                                <span class="hide-menu"> Tips <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="tips.php">Lista</a> </li>
                                <li> <a href="subir_tip.php">Subir tips</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect" id="nav-tag">
                                <i class="linea-icon linea-basic fa-fw fa fa-hashtag "></i> 
                                <span class="hide-menu"> Tags <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="tags.php">Lista</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect" id="nav-quiz">
                                <i class="linea-icon linea-basic fa-fw fa fa-book "></i> 
                                <span class="hide-menu"> Quizzes <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="quiz.php">Lista</a> </li>
                                <li> <a href="subir_quiz.php">Subir quizzes</a> </li>
                            </ul>
                        </li>
                        <!--li>
                            <a href="javascript:void(0);" class="waves-effect" id="nav-label">
                                <i class="linea-icon linea-basic fa-fw fa fa-dashcube"></i> 
                                <span class="hide-menu"> Label <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="labels.php">Lista</a> </li>
                            </ul>
                        </li-->
                        <li>
                            <a href="javascript:void(0);" class="waves-effect" id="nav-informe">
                                <i class="linea-icon linea-basic fa-fw fa fa-newspaper-o "></i> 
                                <span class="hide-menu"> Informe <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="informesecciones.php">Secciones</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect" id="nav-tabla">
                                <i class="linea-icon linea-basic fa-fw fa fa-clone "></i> 
                                <span class="hide-menu"> Tablas <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="tipo_categoria_precondicion.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Categor&iacute;a de precondici&oacute;n</a> </li>
                                <li> <a href="tipo_genero.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> G&eacute;nero</a> </li>
                                <li> <a href="miembro_familiar.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Miembro familiar</a> </li>
                                <li> <a href="tipo_educacion.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Nivel de educaci&oacute;n</a> </li>
                                <li> <a href="tipo_ingreso_economico.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Nivel de ingreso econ&oacute;mico</a> </li>
                                <!--
                                <li> <a href="javascript:void(0)" class="waves-effect" id="nav-param">Par&aacute;metros <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li> <a href="param_mensaje_usuario.php?lang=es">Mensajes de usuario</a> </li>
                                        <li> <a href="param_mensaje_usuario.php?lang=en">Mensajes de usuario (Ingl&eacute;s)</a> </li>
                                        <li> <a href="param_correo.php">Configuraci&oacute;n de correo</a> </li>
                                    </ul>
                                </li>
                                -->
                                <li> <a href="paises.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Pa&iacute;s</a> </li>
                                <li> <a href="tipo_cocina.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Tipo de cocina</a> </li>
                                <li> <a href="tipo_dieta.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Tipo de dieta</a> </li>
                                <li> <a href="tipo_nutriente.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Tipo de nutriente</a> </li>
                                <li> <a href="tipo_alimento.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Tipo de alimentos</a> </li>
                                <li> <a href="tipo_empleo.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Tipo empleo</a> </li>
                                <li> <a href="tipo_plato.php"> <i class="linea-icon linea-basic fa-fw fa fa-genderless "></i> Tipo de plato</a> </li>
                                <li> <a href="javascript:void(0)" class="waves-effect" id="nav-table">Tablas planas <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li> <a href="tabla_nutritional_label.php"> <i class="linea-icon linea-basic fa-fw fa fa-building-o "></i> Calcular nutritional label</a> </li>
                                        <li> <a href="tabla_insumo_nutriente_precondicion.php"> <i class="linea-icon linea-basic fa-fw fa fa-building-o "></i> Crear tabla de insumo - nutriente - precondici&oacute;n</a> </li>
                                        <li> <a href="tabla_insumo_intolerancia.php"> <i class="linea-icon linea-basic fa-fw fa fa-building-o "></i> Tabla de insumo - intolerancia</a> </li>
                                        <li> <a href="tabla_insumo_tipo_alimento.php"> <i class="linea-icon linea-basic fa-fw fa fa-building-o "></i> Tabla de insumo - tipo de alimento</a> </li>
                                        <li> <a href="tabla_densidad_nutricional.php"> <i class="linea-icon linea-basic fa-fw fa fa-building-o "></i> Calcular densidad nutricional</a> </li>
                                        <li> <a href="tabla_plana_imagen.php"> <i class="linea-icon linea-basic fa-fw fa fa-building-o "></i> Actualizar im&aacute;genes de insumos</a> </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect" id="nav-cuestionario">
                                <i class="linea-icon linea-basic fa-fw fa fa-newspaper-o "></i> 
                                <span class="hide-menu"> Cuestionario de Perfil <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="cuestionario.php">Prueba</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect" id="nav-fitbit">
                                <i class="linea-icon linea-basic fa-fw fa fa-newspaper-o "></i> 
                                <span class="hide-menu"> Servicios externos <span class="fa arrow"></span> </span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li> <a href="callback_fitbit.php">Fitbit</a> </li>
                                <li> <a href="clima_api_conexion.php">Clima</a> </li>
                                <li> <a href="voz.php">Voz</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a href="logout.php" class="waves-effect">
                                <i class="icon-logout fa-fw"></i>
                                <span class="hide-menu"> Salir </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
