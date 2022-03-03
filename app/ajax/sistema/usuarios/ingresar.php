<?php

$usuario = !empty($_POST['usuario']) ? $_POST['usuario']: '';
$pass = !empty($_POST['pass']) ? $_POST['pass'] : '';
$recordarme = !empty($_POST['recordarme']);

$res = tk_login($usuario, $pass, $recordarme);

if($res['error']){
	teko_json(['error' => TRUE, 'mensaje' => $res['mensaje'], 'res' => $res]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('Datos correctos, redireccionando a la pantalla principal...'), 'url' => site_url('
dashboard')]);
}
