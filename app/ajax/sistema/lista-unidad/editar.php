<?php
global $tekodb;
if(empty($_POST['datos']))
{
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el mapa de experiencia de usuario')]);
}
$datos = $_POST['datos'];
$res = $tekodb->update('administrador',$datos,['fkusers_id' => $_POST['id']]);
if (!empty($_POST['password'])) {
		tk_change_password($_POST['password'],$_POST['id']);
}
if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el administrador')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El usuario se ha editado correctamente')]);
}
