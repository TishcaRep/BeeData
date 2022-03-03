<?php
/*
if(empty($_POST['data'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
*/
$cpanelusr = 'lams';
$cpanelpass = 'L1a2m3s4#';
require_once(APPPATH.'/lib/xmlapi.php');
$xmlapi = new xmlapi('127.0.0.1');
$xmlapi->set_port( 2083 );
$xmlapi->password_auth($cpanelusr,$cpanelpass);
$xmlapi->set_debug(0);
$result = $xmlapi->api1_query($cpanelusr, 'SubDomain', 'addsubdomain', array('asdru','beedata.mx',0,0, '/public_html/beedata/pymes/test/teko'));
$databasename = 'beedata_asdru0';
$databaseuser = 'beedata_asdru0'; //¡Tener cuidado! Esto puede tener un máximo de 7 caracteres
$databasepass = 'dsafsghdgsfafghjdfgsf';
$createdb = $xmlapi->api1_query($cpanelusr, "Mysql", "adddb", array($databasename)); //crea la base de datos
$usr = $xmlapi->api1_query($cpanelusr, "Mysql", "adduser", array($databaseuser, $databasepass)); //crea el usuario
$addusr = $xmlapi->api1_query($cpanelusr, "Mysql", "adduserdb", array("".$cpanelusr."_".$databasename."", "".$cpanelusr."_".$databaseuser."", 'all')); //concede todos los privilegios al usuario que acabamos de crear
