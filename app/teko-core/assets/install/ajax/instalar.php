<?php

define('ABSPATH', realpath(__DIR__.'/../../../../../'));

if(file_exists(ABSPATH.'/config/config.ini')){
    die('TekoTipsy Security');
}

global $tekodb, $tekoauth, $tekoconfig;

require_once ABSPATH . '/vendor/autoload.php';

$tekoconfig = $_POST;

$user = $tekoconfig['admin'];

unset($tekoconfig['admin']);

//Modo booleano debug
$tekoconfig['debug']['show_errors'] = boolval(!empty($tekoconfig['debug']['show_errors']));

//Escribir en el archivo
use \Matomo\Ini\IniWriter;
$writer = new IniWriter();

$writer->writeToFile(ABSPATH.'/config/config.ini', $tekoconfig);

//Url del sitio (automática)
$site_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$tekoconfig['sitio']['path'];

//Crear Usuario
$db_user = $tekoconfig['db']['user'];
$db_pass = $tekoconfig['db']['pass'];
$db_name = $tekoconfig['db']['name'];
$db_host = $tekoconfig['db']['host'];
$tekodb = new ezSQL_mysqli($db_user, $db_pass, $db_name, $db_host, 'uft8');

//DB driver para conexión a MySQL
try {
    $pdo = new PDO("mysql:dbname={$db_name};host={$db_host};charset=utf8", $db_user, $db_pass);
} catch (Exception $ex){
    teko_json(['error' => TRUE, 'mensaje' => 'Ocurrió un error al conectarse con la base de datos']);
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
require_once(ABSPATH.'/app/teko-core/auth.php');

//Crear primer usuario
tk_register($user['correo'],$user['pass'],'superadmin',$user['usuario']);

//Asingar permisos a carpeta config
@chmod(ABSPATH.'/config', 0755);

teko_json(['error' => FALSE, 'mensaje' => 'TekoAdmin instalado correctamente', 'url' => $site_url.'/administracion']);

function teko_json($arr){
    header('Content-Type: application/json');
    die(json_encode($arr));
}