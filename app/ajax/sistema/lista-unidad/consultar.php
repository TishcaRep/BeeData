<?php
global $tekodb;
if(empty($_POST['id'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $tekodb->get_row("SELECT id,Nombre,ApPaterno,ApMaterno,Cargo,Telefono,email,username FROM users INNER JOIN administrador ON id = fkusers_id WHERE id = {$_POST['id']} ");
if(empty($data)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar el momento')]);
} else {
	teko_json(['error' => FALSE, 'data' => $data]);
}
