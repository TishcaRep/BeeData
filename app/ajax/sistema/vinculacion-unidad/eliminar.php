<?php
global $tekodb;

if(empty($_POST['id']))
{
	return teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}

$dell = $tekodb->delete('administrador_unidad_negocio', ['idadmin_unidad' => $_POST['id']]);

if(empty($dell)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al eliminar la unidad de negocio')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La unidad de negocio se ha eliminado correctamente')]);
}
