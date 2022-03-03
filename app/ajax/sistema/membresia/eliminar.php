<?php
global $tekodb;

$id = !empty($_POST['id']) ? $_POST['id'] : 0;

$res = $tekodb->delete('planes_membresia', ['idPlanesMembresia' => $id]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al eliminar la membresia')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La membresia se ha eliminado correctamente')]);
}
