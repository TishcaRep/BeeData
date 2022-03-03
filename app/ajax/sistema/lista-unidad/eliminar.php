<?php
global $tekodb;

if(empty($_POST['id']))
{
	return teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}

$dell = $tekodb->delete('administrador_unidad_negocio', ['fkusers_id' => $_POST['id']]);
$dell = $tekodb->delete('administrador', ['fkusers_id' => $_POST['id']]);
$dell = $tekodb->delete('usuarios_dependientes', ['fkiduser_son' => $_POST['id']]);
if(empty($dell)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al eliminar el administrador')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El administrador se ha eliminado correctamente')]);
}
