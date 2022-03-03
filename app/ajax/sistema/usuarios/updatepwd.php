<?php
global $tekodb;
$data = $_POST['datos'];
$res = tk_change_password($data['password'],$data['id']);
if($res['error']){
	teko_json(['error' => TRUE, 'mensaje' => $res['mensaje'], 'res' => $res]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('Se ha actualizado la contraseña...'), 'url' => site_url('
dashboard')]);
}
