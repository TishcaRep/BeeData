<?php
global $tekodb;

$id = !empty($_POST['id']) ? $_POST['id'] : 0;

$res = $tekodb->delete('momento_verdad', ['idPregunta' => $id]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al eliminar la pregunta')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La pregunta se ha eliminado')]);
}
