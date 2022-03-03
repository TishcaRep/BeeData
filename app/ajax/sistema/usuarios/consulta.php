<?php
global $tekodb;
if(empty($_POST['id'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $tekodb->get_row("SELECT * FROM vusuario WHERE id = {$_POST['id']}");
if(empty($data)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar el usuario')]);
} else {
	teko_json(['error' => FALSE, 'data' => $data]);
}
