<?php 
if(!defined('ABSPATH')){
	die('TekoTipsy Security');
}
$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
$carpeta = trim($_SERVER['REQUEST_URI'], '/');
$request = !empty($_GET['__url']) ? $_GET['__url'] : '';
$path = trim(substr($carpeta, 0, strlen($carpeta)-strlen($request)), '/');
if($path != $carpeta){
    header('Location: ' . $url.'/'.$path, true);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <title>Instalación TekoAdmin</title>
    <link rel="shortcut icon" href="app/teko-core/assets/favicon.ico">
    <meta content="Teko Admin Instalador" name="description" />
    <meta content="Teko Estudio" name="author" />
    <!-- Materializecss compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import Materialize-Stepper CSS -->
    <link rel="stylesheet" href="app/teko-core/assets/install/css/materialize-stepper.min.css">
    <link href="app/teko-core/assets/install/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col s12">
                <img src="app/teko-core/assets/install/images/logo.svg" alt="Teko Estudio" class="logo">
                <div class="card">
                    <div class="card-content">
                        <form id="form-install">
                            <ul class="stepper linear horizontal" id="wizard-install">
                                <li class="step active">
                                <div class="step-title waves-effect waves-dark">GENERALES</div>
                                <div class="step-content">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="sitio[path]" type="text" class="validate" value="<?= (($path) == '') ? '/' : "/{$path}" ?>" required>
                                            <label>Carpeta de instalación</label>
                                            <span class="helper-text">Poner únicamente "/" si se instala en la carpeta principal</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="view[path]" type="text" class="validate" value="app/vistas" required>
                                            <label>Carpeta de vistas</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="view[layout]" type="text" class="validate" value="plantillas/sistema" required>
                                            <label>Plantilla principal</label>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col s12">
                                            <p>Mostrar errores</p>
                                            <div class="switch">
                                                <label>
                                                    No
                                                    <input type="checkbox" name="debug[show_errors]" checked>
                                                    <span class="lever"></span>
                                                    Si
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="step-actions">
                                        <button class="waves-effect waves-dark btn blue next-step">CONTINUAR</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                </li>
                                <li class="step">
                                <div class="step-title waves-effect waves-dark">BASE DE DATOS</div>
                                <div class="step-content">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="db[user]" type="text" class="validate" required>
                                            <label>Usuario</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="db[pass]" type="password" class="validate">
                                            <label>Contraseña</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="db[name]" type="text" class="validate" required>
                                            <label>Nombre</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="db[host]" type="text" value="localhost" class="validate" required>
                                            <label>Servidor</label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="step-actions">
                                        <button class="waves-effect waves-dark btn blue next-step" data-feedback="validarConexionDB">CONTINUAR</button>
                                        <button class="waves-effect waves-dark btn-flat previous-step">REGRESAR</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                </li>
                                <li class="step">
                                <div class="step-title waves-effect waves-dark">ADMINISTRACIÓN</div>
                                <div class="step-content">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="mail[from]" type="text" class="validate" value="Teko Estudio <sistemas@tekoestudio.com>" required>
                                            <label>Destinatario correos</label>
                                        </div>
                                    </div>
                                    <strong><i>Administrador</i></strong>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="admin[correo]" type="email" class="validate" required>
                                            <label>Correo electrónico</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="admin[usuario]" type="text" class="validate" required>
                                            <label>Usuario</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input id="password" name="admin[pass]" type="password" class="validate" required>
                                            <label>Contraseña</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input data-rule-equalto="#password"  type="password" class="validate" data-msg-equalto="Las contraseñas son diferentes" required>
                                            <label>Confirmar contraseña</label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="step-actions">
                                        <button class="waves-effect waves-dark btn blue" type="submit" >INSTALAR</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!-- Materializecss compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
    <!-- jQueryValidation Plugin (optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/localization/messages_es.min.js"></script>
    <!--Import Materialize-Stepper JavaScript -->
    <script src="app/teko-core/assets/install/js/materialize-stepper.min.js"></script>
    <script type="text/javascript" src="app/teko-core/assets/install/js/actions.js"></script>
</body>
</html>