<?php

define('ABSPATH', dirname(__DIR__));
define('APPPATH', dirname(__FILE__));

require_once ABSPATH . '/vendor/autoload.php';//Composer
require_once APPPATH."/lib/conekta-php-master/lib/Conekta.php";//Conekta

//Configurar conekta
\Conekta\Conekta::setApiKey("key_rxDZSLh4xejavUo8ANr1xQ");
\Conekta\Conekta::setApiVersion("2.0.0");

//Dependencias de clases
use Tipsy\Tipsy;
use Gettext\GettextTranslator;

//Tipsy
Tipsy::config(ABSPATH .'/config/config.ini');

//Variables Globales
global $tekodb, $tekoauth, $tekoconfig, $titulo, $site_url, $sidebar_menu;

$tekoconfig = Tipsy::config();

//i18n
$t = new GettextTranslator();
$t->setLanguage('en_US');
$t->loadDomain('messages', APPPATH.'/i18n');
$t->register();

//Funciones de TekoCore
require_once('teko-core/generales.php');
require_once('teko-core/mail.php');

//Install
if(empty($tekoconfig['site']['path'])){
    require_once('teko-core/assets/install/index.php');
    exit;
}

//Debug
if($tekoconfig['debug']['show_errors']){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

//Cargar configuración de la Base de datos
$db_user = $tekoconfig['db']['user'];
$db_pass = $tekoconfig['db']['pass'];
$db_name = $tekoconfig['db']['name'];
$db_host = $tekoconfig['db']['host'];
$tekodb = new ezSQL_mysqli($db_user, $db_pass, $db_name, $db_host, 'utf8');

//Url del sitio (automática)
$site_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$tekoconfig['site']['path'];
//Manual $site_url = $tekoconfig['sitio']['url'];

//DB driver para conexión a MySQL
try {
    $pdo = new PDO("mysql:dbname={$db_name};host={$db_host};charset=utf8", $db_user, $db_pass);
} catch (Exception $ex){
    require_once('teko-core/assets/nodb/index.php');
    exit;
}
//Revisar si existen las tablas necesarias para usuarios en la DB, si no, crearlas
$existe = $tekodb->get_col("SHOW TABLES LIKE 'users'");
if(!count($existe)){
    $sql = file_get_contents(ABSPATH . '/vendor/delight-im/auth/Database/MySQL.sql');
    $pdo->exec($sql);
}
//Configurar variable global de autentificación (Docs: https://github.com/delight-im/PHP-Auth)
$tekoauth = new \Delight\Auth\Auth($pdo);

//Funciones TekoAuth
require_once('teko-core/auth.php');

//Crear primer usuario
if(!count($existe)){
    tk_register('tekoadmin@tekoestudio.com','TekoAdmin18.#','superadmin','tekoadmin');
}

//Funciones Globales
require_once('funciones.php');

//Rutas
$rutas = Tipsy::router();
require_once('rutas.php');

//Header de acceso para ruta cualquier ruta /api/*
Tipsy::middleware(function($Request) {
    if ($Request->loc() == 'api') {
        header("Access-Control-Allow-Origin: *");
    }
});

//Validación de acceso
Tipsy::middleware(function($Request) {
    if ($Request->loc() == 'dashboard') {
    	if(!in_array($Request->path(),['dashboard/ingresar','dashboard/usuarios/recuperar-password'])){
    		if(tk_id() == 0){
    			teko_redirect(site_url('dashboard/ingresar'));
    		}
    	}
    }
	//Seguridad para los ajax
	if(strpos($Request->path(), 'ajax/sistema') !== FALSE && !in_array($Request->path(),['ajax/sistema/usuarios/ingresar','ajax/sistema/usuarios/recuperar-password'])){
		if(tk_id() == 0)
			teko_redirect(site_url('404'));
	}
});

//Inicializar App
Tipsy::run();
